<?php
// Turn off Hello Elementor's (parent theme) three default stylesheets
add_filter( 'hello_elementor_enqueue_style', '__return_false' );        // reset.css
add_filter( 'hello_elementor_enqueue_theme_style', '__return_false' );  // theme.css
add_filter( 'hello_elementor_header_footer', '__return_false' );        // header-footer.css

// Register menu location
function pds_register_menus() {
    register_nav_menus( array(
        'primary' => __( 'Primary Header Menu' ),
    ) );
}
add_action( 'after_setup_theme', 'pds_register_menus' );

/**
 * Preload hero LCP image + preconnect Font Awesome CDN origin.
 * PageSpeed: LCP breakdown showed 1.65-1.69s "resource load delay" before
 * the hero image even starts downloading (stuck behind render-blocking CSS).
 * Preload gets the browser fetching it immediately.
 */
add_action( 'wp_head', function () {
    if ( is_front_page() ) {
        $poster = esc_url( get_stylesheet_directory_uri() . '/assets/img/hero-poster.webp' );
        echo '<link rel="preload" as="image" href="' . $poster . '" fetchpriority="high">' . "\n";
    }
    echo '<link rel="preconnect" href="https://kit.fontawesome.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://ka.fontawesome.com" crossorigin>' . "\n";
}, 1 );

/**
 * WP core's Font Library (Appearance > Fonts) auto-generates @font-face
 * rules for Inter/Poppins with font-display defaulting to "fallback" —
 * there's no Admin UI setting for this (WP_Font_Face core default).
 * "fallback" still has a ~100ms invisible-text block; "swap" has none.
 * PageSpeed flagged 1.65-1.77s here. No official filter exists for
 * Font-Library-sourced fonts, so patch the printed <head> output directly.
 */
add_action( 'wp_head', function () { ob_start(); }, 0 );
add_action( 'wp_head', function () {
    $head = ob_get_clean();
    echo preg_replace( '/font-display\s*:\s*fallback/i', 'font-display:swap', $head );
}, 9999 );

class PDS_Nav_Walker extends Walker_Nav_Menu {

    private $parent_ids = array();

    function __construct() {
        $locations = get_nav_menu_locations();
        if ( isset( $locations['primary'] ) ) {
            $menu = wp_get_nav_menu_object( $locations['primary'] );
            if ( $menu ) {
                $items = wp_get_nav_menu_items( $menu->term_id );
                if ( $items ) {
                    foreach ( $items as $item ) {
                        if ( $item->menu_item_parent && $item->menu_item_parent != 0 ) {
                            $this->parent_ids[ $item->menu_item_parent ] = true;
                        }
                    }
                }
            }
        }
    }

    function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '<ul class="sub-menu">';
    }

    function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '</ul>';
    }

    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $is_active = in_array( 'current-menu-item', $classes, true );

        if ( ! $is_active && is_singular() ) {
            $taxonomies = get_object_taxonomies( get_post_type() );
            $item_slug = basename( untrailingslashit( parse_url( $item->url, PHP_URL_PATH ) ) );
            foreach ( $taxonomies as $taxonomy ) {
                $terms = get_the_terms( get_the_ID(), $taxonomy );
                if ( $terms && ! is_wp_error( $terms ) ) {
                    $term_slugs = wp_list_pluck( $terms, 'slug' );
                    if ( in_array( $item_slug, $term_slugs, true ) || in_array( rtrim( $item_slug, 's' ), $term_slugs, true ) || in_array( $item_slug . 's', $term_slugs, true ) ) {
                        $is_active = true;
                        break;
                    }
                }
            }
        }

        $active        = $is_active ? ' active-link' : '';
        $has_children  = isset( $this->parent_ids[ $item->ID ] ) ? ' menu-item-has-children' : '';
        $li_classes    = 'menu-item' . $has_children;

        $output .= '<li class="' . esc_attr( $li_classes ) . '">';
        $output .= '<a href="' . esc_url( $item->url ) . '" class="nav-link' . $active . '">' . esc_html( $item->title ) . '</a>';
    }

    function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= '</li>';
    }
}






add_action('wp_enqueue_scripts', function() {
    // wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri().'/style.css');
});
function pds_enqueue_footer_js() {
    wp_enqueue_script(
        'pds-footer',
        get_stylesheet_directory_uri() . '/footer.js',
        array(),
        '1.0.0',
        true // true = load in footer, not head
    );
}
add_action( 'wp_enqueue_scripts', 'pds_enqueue_footer_js' );
function pds_enqueue_footer_reveal_js() {
    wp_enqueue_script(
        'pds-footer-reveal',
        get_stylesheet_directory_uri() . '/footer-reveal.js',
        array(),
        '1.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'pds_enqueue_footer_reveal_js' );
/**
 * Enqueue quote form custom CSS/JS — only on the Get a Quote page
 */
function pds_quote_form_assets() {
    if ( ! is_page_template( 'page-quote.php' ) ) {
        return;
    }

    $css_path = get_stylesheet_directory() . '/assets/quote-form-custom.css';
    $js_path  = get_stylesheet_directory() . '/assets/quote-form-custom.js';

    wp_enqueue_style(
        'pds-quote-form-custom',
        get_stylesheet_directory_uri() . '/assets/quote-form-custom.css',
        array(),
        file_exists( $css_path ) ? filemtime( $css_path ) : false
    );

    wp_enqueue_script(
        'pds-quote-form-custom',
        get_stylesheet_directory_uri() . '/assets/quote-form-custom.js',
        array(),
        file_exists( $js_path ) ? filemtime( $js_path ) : false,
        true // load in footer
    );
}
add_action( 'wp_enqueue_scripts', 'pds_quote_form_assets' );

// Load correct CSS file for each custom page template
function pds_enqueue_page_styles() {

    // header.css — loads on every page since header.php is included everywhere
    $header_css_path = get_stylesheet_directory() . '/assets/css/header.css';
    if ( file_exists( $header_css_path ) ) {
        wp_enqueue_style(
            'pds-header',
            get_stylesheet_directory_uri() . '/assets/css/header.css',
            array(),
            filemtime( $header_css_path )
        );
    }

    // front-page.css — front-page.php loads automatically for the static front page,
    // get_page_template_slug() below won't catch it, so handle separately
    if ( is_front_page() ) {
        $front_css_path = get_stylesheet_directory() . '/assets/css/front-page.css';
        if ( file_exists( $front_css_path ) ) {
            wp_enqueue_style(
                'pds-front-page',
                get_stylesheet_directory_uri() . '/assets/css/front-page.css',
                array(),
                filemtime( $front_css_path )
            );
        }
    }

    // page template file => matching css file name
    $css_map = array(
        'faqs.php'                 => 'faqs.css',
        'page-studios.php'        => 'studios.css',
        'page-accessories.php'    => 'accessories.css',
        'page-class-1a.php'       => 'class-1a.css',
        'page-contact.php'        => 'contact.css',
        'page-expanders.php'      => 'expanders.css',
        'page-finance.php'        => 'finance.css',
        'page-half-expanders.php' => 'half-expanders.css',
        'page-quote.php'          => 'quote.css',
        'page-spaces.php'         => 'spaces.css',
    );

    // check which template current page is using
    $current_template = get_page_template_slug();

    // if current page is in our list, load its css
    if ( isset( $css_map[ $current_template ] ) ) {

        $css_file = $css_map[ $current_template ];
        $css_path = get_stylesheet_directory() . '/assets/css/' . $css_file;
        $css_url  = get_stylesheet_directory_uri() . '/assets/css/' . $css_file;

        // filemtime for cache-busting, so browser doesn't show stale css after an update
        if ( file_exists( $css_path ) ) {
            wp_enqueue_style(
                'pds-' . basename( $css_file, '.css' ),
                $css_url,
                array(),
                filemtime( $css_path )
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'pds_enqueue_page_styles' );

// Load correct CSS file for single product pages (single-products-*.php)
// Uses the SAME taxonomy term mapping as pds_product_single_template() below,
// so no guessing — css always matches whichever single-products-*.php file
// actually renders for that product.
function pds_enqueue_single_product_styles() {

    if ( ! is_singular( 'product' ) ) {
        return;
    }

    $post_id  = get_the_ID();
    $css_file = null;

    if ( has_term( 'class-1a', 'product_category', $post_id ) || has_term( 'half-expander', 'product_category', $post_id ) ) {
        $css_file = 'single-class1a.css';   // both route to single-products-class1a.php
    } elseif ( has_term( 'expanders', 'product_category', $post_id ) ) {
        $css_file = 'single-expander.css';
    } elseif ( has_term( 'cabins-pods', 'product_category', $post_id ) ) {
        $css_file = 'single-studios.css';   // cabins-pods routes to single-products-studios.php
    } elseif ( has_term( 'spaces', 'product_category', $post_id ) ) {
        $css_file = 'single-spaces.css';
    }

    if ( $css_file ) {
        $css_path = get_stylesheet_directory() . '/assets/css/' . $css_file;
        $css_url  = get_stylesheet_directory_uri() . '/assets/css/' . $css_file;

        if ( file_exists( $css_path ) ) {
            wp_enqueue_style(
                'pds-' . basename( $css_file, '.css' ),
                $css_url,
                array(),
                filemtime( $css_path )
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'pds_enqueue_single_product_styles' );



/**
 * ============================================================
 * STEP 1: Custom Post Type + Taxonomy for Products
 * Add this to your child theme's functions.php
 * (or paste into CPT UI plugin's "Import/Export" screen instead
 *  if you prefer the GUI — same result, either way works.)
 * ============================================================
 */

// Register "Product" Custom Post Type
function pds_register_product_cpt() {
	$labels = array(
		'name'                  => 'Products',
		'singular_name'         => 'Product',
		'menu_name'             => 'Products',
		'add_new'               => 'Add New Product',
		'add_new_item'          => 'Add New Product',
		'edit_item'             => 'Edit Product',
		'new_item'              => 'New Product',
		'view_item'             => 'View Product',
		'search_items'          => 'Search Products',
		'not_found'             => 'No products found',
		'not_found_in_trash'    => 'No products found in Trash',
		'all_items'             => 'All Products',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'products' ),
		'capability_type'    => 'post',
		'has_archive'        => false, // no built-in archive — the 3 category pages replace it
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-admin-home',
		'supports'           => array( 'title', 'thumbnail', 'excerpt' ),
		'show_in_rest'       => true, // needed for ACF / block editor compatibility
	);

	register_post_type( 'product', $args );
}
add_action( 'init', 'pds_register_product_cpt' );


/**
 * Add to functions.php (or a plugin). Registers ONE global options page:
 * "PDS Swatch Library". Fill swatch photos here ONCE, across all products.
 * Then import acf-export-2026-07-24-updated.json in ACF > Tools > Import.
 */
if ( function_exists('acf_add_options_page') ) {
    acf_add_options_page([
        'page_title' => 'PDS Swatch Library',
        'menu_title' => 'Swatch Library',
        'menu_slug'  => 'pds-swatch-library',
        'capability' => 'edit_posts',
        'redirect'   => false,
    ]);
}

// Register "Product Category" Taxonomy (class-1a / cabins-pods / expanders)
function pds_register_product_category_tax() {
	$labels = array(
		'name'          => 'Product Categories',
		'singular_name' => 'Product Category',
		'search_items'  => 'Search Categories',
		'all_items'     => 'All Categories',
		'edit_item'     => 'Edit Category',
		'update_item'   => 'Update Category',
		'add_new_item'  => 'Add New Category',
		'new_item_name' => 'New Category Name',
		'menu_name'     => 'Categories',
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true, // behaves like categories, not tags
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'product-category' ),
		'show_in_rest'      => true,
	);

	register_taxonomy( 'product_category', array( 'product' ), $args );
}
add_action( 'init', 'pds_register_product_category_tax' );


add_filter('fluentform/rendering_field_data_input_checkbox', function ($field) {

    $category_map = array(
        'expanders_products'      => 'expanders',
        'cabins_pods_products'    => 'cabins-pods',
        'class_1a_products'       => 'class-1a',
        'half_expanders_products' => 'half-expander',
        'accessories_products'    => 'accessories',
    );

    $field_name = $field['attributes']['name'] ?? '';

    if (!isset($category_map[$field_name])) {
        return $field;
    }

    $term_slug = $category_map[$field_name];

    $posts = get_posts(array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_category',
                'field'    => 'slug',
                'terms'    => $term_slug,
            ),
        ),
    ));

    $options = array();
    foreach ($posts as $post) {
        $image_url = get_field('card_image', $post->ID);
        $options[] = array(
            'label' => get_the_title($post->ID),
            'value' => $post->ID,
            'image' => $image_url ?: '',
        );
    }

    if (!empty($options)) {
        $field['settings']['advanced_options'] = $options;
    }

    return $field;
});

/**
 * ============================================================
 * STEP 3: Output full product data as JS for rich card rendering
 * (Moved out of the fluentform filter above — this must only be
 *  registered ONCE per page load, not once per checkbox field.)
 * ============================================================
 */
add_action('wp_footer', function () {

    // Only needed on the quote page (checkbox rich-card rendering) — skip everywhere else
    if ( ! is_page_template( 'page-quote.php' ) ) {
        return;
    }

    $posts = get_posts(array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ));

    $house_cats = array('class-1a', 'expanders', 'cabins-pods', 'half-expander');
    $data = array();

    foreach ($posts as $post) {
        $id    = $post->ID;
        $terms = wp_get_post_terms($id, 'product_category', array('fields' => 'slugs'));
        $cat   = $terms[0] ?? '';

        $specs = array();

        if (in_array($cat, $house_cats, true)) {
            $spec_fields = array(
                'bedrooms'       => 'Bedrooms',
                'bathrooms'      => 'Bathrooms',
                'kitchen'        => 'Kitchen',
                'floor_area'     => 'Floor area',
                'ceiling_height' => 'Ceiling height',
            );
            foreach ($spec_fields as $key => $label) {
                $val = get_field($key, $id);
                if ($val !== '' && $val !== null) {
                    $specs[] = array('value' => $val, 'label' => $label);
                }
            }
        } elseif ($cat === 'accessories') {
            $spec_list = get_field('spec_list', $id);
            if ($spec_list) {
                foreach (preg_split('/\r\n|\r|\n/', $spec_list) as $line) {
                    $line = trim($line);
                    if ($line !== '') {
                        $specs[] = array('value' => '', 'label' => $line);
                    }
                }
            }
        }

        $data[$id] = array(
            'title'   => get_the_title($id),
            'image'   => get_field('card_image', $id) ?: '',
            'badge'   => get_field('badge_tag', $id) ?: '',
            'desc'    => get_field('short_description', $id) ?: '',
            'price'   => get_field('price', $id) ?: '',
            'weekly'  => get_field('weekly_price', $id) ?: '',
            'specs'   => $specs,
            'link'    => get_permalink($id),
            'isHouse' => in_array($cat, $house_cats, true),
        );
    }

    echo '<script>window.pdsProductData = ' . wp_json_encode($data) . ';</script>' . "\n";
});


/**
 * ============================================================
 * STEP 4: Show product titles instead of product IDs in email
 * notifications (e.g. "The Wren, The Cove" instead of "333, 332")
 * ============================================================
 */
add_filter('fluentform/email_body', 'pds_replace_product_ids_with_titles', 10, 4);

function pds_replace_product_ids_with_titles($emailBody, $notification, $submittedData, $form) {

    // Only run for Form ID 5 (quote form)
    $target_form_id = 5;
    if ($form->id != $target_form_id) {
        return $emailBody;
    }

    // Same field names used in STEP 2's $category_map
    $product_field_names = array(
        'expanders_products',
        'cabins_pods_products',
        'class_1a_products',
        'half_expanders_products',
        'accessories_products',
    );

    foreach ($product_field_names as $product_field_name) {

        // If nothing submitted in this field, check next field
        if (empty($submittedData[$product_field_name])) {
            continue;
        }

        $selected_ids = $submittedData[$product_field_name];

        // Fluent Forms sometimes gives an array, sometimes a comma-separated string —
        // handle both cases
        if (!is_array($selected_ids)) {
            $selected_ids = array_map('trim', explode(',', $selected_ids));
        }

        // This is the exact string currently shown inside the email body
        // (e.g. "333, 332") — find and replace this
        $raw_ids_as_shown = implode(', ', $selected_ids);

        // Get the product title for each ID
        $product_titles = array();
        foreach ($selected_ids as $product_id) {
            $title = get_the_title($product_id);
            // if post is deleted or title not found, show ID instead
            $product_titles[] = $title ? $title : $product_id;
        }
        $titles_as_string = implode(', ', $product_titles);

        // Replace raw ID list with product names inside the email body
        // (including inside the all_data table)
        $emailBody = str_replace($raw_ids_as_shown, $titles_as_string, $emailBody);
    }

    return $emailBody;
}


/**
 * Route single `product` CPT posts to a category-specific template file
 * based on their `product_category` taxonomy term.
 */
add_filter( 'single_template', 'pds_product_single_template' );
function pds_product_single_template( $template ) {
    if ( is_singular( 'product' ) ) {
        $post_id = get_the_ID();
        if ( has_term( 'class-1a', 'product_category', $post_id ) ) {
            $custom = locate_template( 'single-products-class1a.php' );
            if ( $custom ) {
                return $custom;
            }
        }
        if ( has_term( 'half-expander', 'product_category', $post_id ) ) {
            $custom = locate_template( 'single-products-class1a.php' );
            if ( $custom ) {
                return $custom;
            }
        }
        if ( has_term( 'expanders', 'product_category', $post_id ) ) {
            $custom = locate_template( 'single-products-expander.php' );
            if ( $custom ) {
                return $custom;
            }
        }
        if ( has_term( 'cabins-pods', 'product_category', $post_id ) ) {
            $custom = locate_template( 'single-products-studios.php' );
            if ( $custom ) {
                return $custom;
            }
        }
        if ( has_term( 'spaces', 'product_category', $post_id ) ) {
            $custom = locate_template( 'single-products-spaces.php' );
            if ( $custom ) {
                return $custom;
            }
        }
    }
    return $template;
}