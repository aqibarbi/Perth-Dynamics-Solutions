<?php
/**
 * Template: single-products-class1a.php
 * Loaded via the single_template filter (see functions.php) whenever
 * `product` CPT post has term "class-1a" in `product_category` taxonomy.
 * Covers: The Vista, The Horizon, The Meridian.
 */

get_header(); // uses theme's existing site-header markup — remove this line + the custom <header> below if your theme header already covers nav/logo.

$post_id = get_the_ID();

/* ---------- dynamic breadcrumb (detects which category loaded this template) ---------- */
$parent_crumb = ['label' => 'Class 1A', 'url' => '/class-1a/']; // default fallback

$product_terms = get_the_terms($post_id, 'product_category');
if (!empty($product_terms) && !is_wp_error($product_terms)) {
    foreach ($product_terms as $term) {
        if ($term->slug === 'half-expander') {
            $parent_crumb = ['label' => 'Half Expander', 'url' => '/half-expander/'];
            break;
        }
        if ($term->slug === 'class-1a') {
            $parent_crumb = ['label' => 'Class 1A', 'url' => '/class-1a/'];
            break;
        }
    }
}

/* ---------- pull ACF with Vista-brochure fallbacks ---------- */
/* price/weekly_price/short_description/badge_tag come from group_pds_common_fields;
   bedrooms/floor_area/ceiling_height come from group_pds_house_fields — both already
   live on this CPT, so we read the real names directly instead of duplicating fields. */
$name          = get_the_title() ?: 'The Vista';
$tagline       = get_field('short_description') ?: 'A permanent, council-approved home built to Class 1A standard — engineered, insulated and finished to move straight into.';
$badge_tag     = get_field('badge_tag') ?: 'Class 1A · Permanent Dwelling';
$size_sqm      = get_field('floor_area') ?: '40sqm';
$ceiling_ht    = get_field('ceiling_height') ?: '2400mm';
$bed_options   = get_field('bedrooms') ?: '1 or 2 bed options';
$price_from    = (int) (get_field('price') ?: 45000);
$finance_weekly= get_field('weekly_price') ?: 189;
$shire_addon   = (int) (get_field('shire_addon_price') ?: 4500);
$bathrooms     = get_field('bathrooms') ?: 1; // house_fields group — layout repeater no longer carries per-layout baths, use product-level count

$gallery = get_field('gallery');
if (empty($gallery)) $gallery = []; // falls back to placeholder blocks in the markup below

/* `layouts[].image` is now a GALLERY (multiple floor-plan photos per bed layout,
   e.g. Vista's 1 Bed has 2). Resolve to plain url strings for JS — an array is
   either a single ACF image array OR a gallery (array of image arrays); tell
   them apart by checking whether the first element itself has a 'url' key. */
function c1a_layout_image_url($image) {
    $urls = c1a_layout_gallery_urls($image);
    return $urls[0] ?? null;
}
function c1a_layout_gallery_urls($image) {
    if (empty($image)) return [];
    if (is_string($image)) return [$image];
    $is_gallery = isset($image[0]) && is_array($image[0]);
    $items = $is_gallery ? $image : [$image];
    $urls = [];
    foreach ($items as $item) {
        // full/original size — used for the lightbox so it isn't capped at ~300px
        $u = is_array($item) ? ($item['url'] ?? ($item['sizes']['medium'] ?? null)) : $item;
        if ($u) $urls[] = $u;
    }
    return $urls;
}

/* layouts fallback — auto-generated from the "Bedrooms" text field (e.g. "1, 2 or 3 bed
   options" or "3 bedrooms") whenever the ACF `layouts` repeater is empty. This is what stops
   every product inheriting the same "1 Bed / 2 Bed" pills — each product now reads its OWN
   bed count from its own Bedrooms field. Fill in the `layouts` repeater per-product if you want
   real photos/notes/bullets per layout instead of these generic ones. */
$layouts = [];
if (have_rows('layouts', $post_id)) {
    while (have_rows('layouts', $post_id)) { the_row();
        $bullets = [];
        if (have_rows('bullets')) { while (have_rows('bullets')) { the_row(); $bullets[] = get_sub_field('bullet_text'); } }
        $layouts[] = [
            'label' => get_sub_field('label'), 'beds' => get_sub_field('beds'),
            'price' => get_sub_field('price'), 'area' => get_sub_field('area'), 'sleeps' => get_sub_field('sleeps'),
            'note' => get_sub_field('desc'), 'bullets' => $bullets, 'image' => get_sub_field('image'),
            'image_urls' => c1a_layout_gallery_urls(get_sub_field('image')),
            'image_url' => c1a_layout_image_url(get_sub_field('image')),
        ];
    }
}
if (empty($layouts)) {
    preg_match_all('/\d+/', $bed_options, $bed_matches);
    $bed_counts = !empty($bed_matches[0]) ? array_values(array_unique(array_map('intval', $bed_matches[0]))) : [1, 2];
    sort($bed_counts);
    foreach ($bed_counts as $n) {
        $layouts[] = [
            'label' => $n.' Bed',
            'beds' => $n, 'price' => null, 'area' => null, 'sleeps' => $n * 2,
            'note' => $n.'-bedroom configuration within the same '.$size_sqm.' footprint.',
            'bullets' => ['Full window façade to the living area','Stone benchtop kitchen','Complete bathroom with shower, vanity & toilet'],
            'image' => null, 'image_url' => null, 'image_urls' => [],
        ];
    }
}

/* inclusions fallback (from Class 1A brochure — Premium Expanders inclusions list) */
$inclusions = [];
if (have_rows('inclusions', $post_id)) { while (have_rows('inclusions', $post_id)) { the_row(); $inclusions[] = ['title'=>get_sub_field('title'),'copy'=>get_sub_field('copy')]; } }
if (empty($inclusions)) {
    $inclusions = [
        ['title'=>'7-star energy rating', 'copy'=>'Thermally efficient build to a 7-star equivalent rating.'],
        ['title'=>'Double glazing', 'copy'=>'Double-glazed windows throughout for comfort and noise control.'],
        ['title'=>'Full window façade', 'copy'=>'Floor-to-ceiling glazing across the main living space.'],
        ['title'=>'Stone benchtops', 'copy'=>'Kitchen fitted with a stone benchtop as standard.'],
        ['title'=>'Kitchen & bathroom', 'copy'=>'Kitchen and bathroom (with laundry) installed and ready to use.'],
        ['title'=>'Full roof & 2m verandah', 'copy'=>'Complete roof with a 2m verandah and eco decking.<br><b>Note: For additional cost</b>'],
        ['title'=>'Planning & engineering', 'copy'=>'Planning, drawing, soil test, engineering, energy report and CDC included.<br><b>Note: For additional cost</b>'],
    ];
}

/* deep dives — optional, only render if ACF has content (brochure gives no room-by-room detail for Class1A) */
$deep_dives = [];
if (have_rows('deep_dives', $post_id)) {
    while (have_rows('deep_dives', $post_id)) { the_row();
        $bullets = [];
        if (have_rows('bullets')) { while (have_rows('bullets')) { the_row(); $bullets[] = get_sub_field('bullet_text'); } }
        $deep_dives[] = ['label'=>get_sub_field('label'),'title'=>get_sub_field('title'),'copy'=>get_sub_field('copy'),'bullets'=>$bullets,'image'=>get_sub_field('image')];
    }
}

/* spec groups fallback (NCC Compliant section from brochure) */
/* $weight is optional — only shows in Dimensions & Area if an ACF value is set (no made-up default). */
$weight = get_field('weight');

$spec_groups = [];
if (have_rows('spec_groups', $post_id)) {
    while (have_rows('spec_groups', $post_id)) { the_row();
        $rows = [];
        if (have_rows('rows')) { while (have_rows('rows')) { the_row(); $rows[] = [get_sub_field('spec_key'), get_sub_field('spec_value')]; } }
        $spec_groups[] = ['group'=>get_sub_field('group_name'),'rows'=>$rows];
    }
}
if (empty($spec_groups)) {
    $dim_rows = [['Floor area', $size_sqm], ['Ceiling height', $ceiling_ht], ['Bed configurations', $bed_options]];
    if (!empty($weight)) $dim_rows[] = ['Weight', $weight];

    $spec_groups = [
        ['group'=>'Dimensions & Area', 'rows'=>$dim_rows],
        ['group'=>'Frame & Structure', 'rows'=>[
            ['Frame','Engineered steel/timber frame construction, rated for permanent siting'],
            ['Floor','Structural floor system over engineered bearers and joists'],
            ['Walls','Insulated wall panel system to Class 1A standard'],
            ['Roof','Full pitched or skillion roof with 2m verandah and eco decking'],
        ]],
        ['group'=>'Insulation, Doors & Glazing', 'rows'=>[
            ['Windows & doors','Double glazed, smoke alarms AS3786 compliant'],
            ['Floor insulation','40mm boards, AS4859.1:2018 R1.75'],
            ['Wall & roof insulation','Insulated to a 7-star equivalent thermal rating, Insulated panels thermal performance year-round on its own
                '],
        ]],
        ['group'=>'Electrical', 'rows'=>[
            ['Wiring','Certified copper cabling to AS3000 Australian Standards'],
            ['Main switch','Main isolator switch with dedicated lock-out'],
            ['Circuit protection','RCD/MCB protection across every circuit'],
            ['Lighting','LED downlights pre-installed in every room'],
        ]],
        ['group'=>'Plumbing', 'rows'=>[
            ['Hot & cold water','Pre-plumbed to kitchen and bathroom, ready to connect on site'],
            ['Grey water','PVC line from kitchen and bathroom to a single outlet point'],
            ['Toilet waste','PVC line exiting through the wall with a P-trap configuration'],
        ]],
        ['group'=>'Approvals', 'rows'=>[
            ['Certification','CDC (Complying Development Certificate)'],
            ['Addinal add-on','Shire submission assistance via our approval partner — fixed fee, no hidden charges   Planning, drawing, soil test, engineering, energy report'],
        ]],
    ];
}

/* ACF File fields return an array (url, filename, etc.) by default — esc_url()
   needs a plain string, so normalize here instead of passing the array straight
   through (that was silently breaking every download link). */
function c1a_file_url($file) {
    if (is_array($file)) return $file['url'] ?? '#';
    return $file ?: '#';
}

$downloads = [];
if (have_rows('downloads', $post_id)) { while (have_rows('downloads', $post_id)) { the_row(); $downloads[] = ['title'=>get_sub_field('title'),'note'=>get_sub_field('note'),'file'=>c1a_file_url(get_sub_field('file'))]; } }
if (empty($downloads)) {
    /* fall back to the real house-fields brochure_pdf / floor_plan if the repeater is empty */
    $floor_plan   = get_field('floor_plan');
    $downloads = [
        ['title'=>'Floor Plans', 'note'=>'PDF · dimensioned layouts', 'file'=>c1a_file_url($floor_plan)],
    ];
}

$accessories = [];
if (have_rows('accessories', $post_id)) { while (have_rows('accessories', $post_id)) { the_row(); $accessories[] = ['cat'=>get_sub_field('cat'),'name'=>get_sub_field('name'),'meta'=>get_sub_field('meta'),'price'=>get_sub_field('price'),'image'=>get_sub_field('image')]; } }

/* ---------- finishes — swatches sourced from a GLOBAL swatch photo library ---------- */
/* finish_swatch_media now lives on the "PDS Swatch Library" OPTIONS PAGE (global,
   filled ONCE across the whole site) instead of a per-product repeater. Per product
   you only tick the finishes_* checkboxes below — the matching photo is looked up
   from the shared library by category+name. See acf-export-2026-07-24-updated.json
   and functions-snippet-swatch-library.php for the options-page setup. */
/* Matching is case/whitespace-insensitive — manual entry into the swatch library
   ("white" vs "White", double spaces, trailing spaces) used to silently break the
   image lookup. Normalize both sides before matching. */
function c1a_norm_swatch_key($s) {
    return strtolower(trim(preg_replace('/\s+/', ' ', (string) $s)));
}

$finish_swatch_photos = []; // key: normalized "category|swatch name" => image url
if (have_rows('finish_swatch_media', 'option')) {
    while (have_rows('finish_swatch_media', 'option')) { the_row();
        $cat = get_sub_field('category'); $sw_name = get_sub_field('name'); $img = get_sub_field('image');
        if ($cat && $sw_name && $img) $finish_swatch_photos[c1a_norm_swatch_key($cat).'|'.c1a_norm_swatch_key($sw_name)] = c1a_file_url($img);
    }
}

function c1a_get_finishes($acf_field, $default_names, $cat, $photo_lookup) {
    $selected = get_field($acf_field);
    $names = !empty($selected) ? $selected : $default_names; // fallback: show everything if field is empty/not filled yet
    $out = [];
    foreach ($names as $n) {
        $key = c1a_norm_swatch_key($cat).'|'.c1a_norm_swatch_key($n);
        $out[] = [
            'name' => $n,
            'image'=> $photo_lookup[$key] ?? null,
        ];
    }
    return $out;
}
$finishes = [
    'cladding'    => c1a_get_finishes('finishes_cladding', ['Dark Grey Cladding','Light Grey Cladding','White Cladding','Wood Style Cladding'], 'cladding', $finish_swatch_photos),
    'ext_cladding_standard' => c1a_get_finishes('finishes_ext_cladding_standard', ['Rustic Cedar','Arctic Grey','Stone Grey','Soft Linen','Dune','Sandstone','Natural Oak','Smoked Oak','Slate Brick','Chalk Brick'], 'ext_cladding_standard', $finish_swatch_photos),
    'steel_frame' => c1a_get_finishes('finishes_steel_frame', ['Black Steel Framing','Dark Grey Steel Framing','White Steel Framing'], 'steel_frame', $finish_swatch_photos),
    'flooring'    => c1a_get_finishes('finishes_flooring', ['Grey Timber Grain Flooring','Light Oak Timber Grain Flooring','Medium Oak Timber Grain Flooring','Dark Oak Timber Grain Flooring'], 'flooring', $finish_swatch_photos),
    'vinyl_flooring' => c1a_get_finishes('finishes_vinyl_flooring', ['Light Yellow Wood Grain','Light Apricot Wood Grain','Grey-toned Wood Grain','Cream White Wood Grain','Chestnut Shell Wood Grain','Brown Wood Grain','Dark Wood Grain'], 'vinyl_flooring', $finish_swatch_photos),
    'spc_flooring' => c1a_get_finishes('finishes_spc_flooring', ['SP101','SP102','SP103','SP105','SP106','SP107','SP108','SP109'], 'spc_flooring', $finish_swatch_photos),
    'benchtop'    => c1a_get_finishes('finishes_benchtop', ['White Marble Stone','White Stone','White Speckle Stone','Light Grey Stone'], 'benchtop', $finish_swatch_photos),
    'cabinetry'   => c1a_get_finishes('finishes_cabinetry', ['White','Light Grey','Apricot','Tan'], 'cabinetry', $finish_swatch_photos),
    'vanity'      => c1a_get_finishes('finishes_vanity', ['White','Light Oak','Black'], 'vanity', $finish_swatch_photos),
];
$finish_labels = [
    'cladding'=>'Cladding (Luxe)', 'ext_cladding_standard'=>'Cladding (Standard)', 'steel_frame'=>'Steel Frame',
    'flooring'=>'Flooring (Luxe)', 'vinyl_flooring'=>'Vinyl Flooring', 'spc_flooring'=>'SPC Flooring (+$500)',
    'benchtop'=>'Benchtop', 'cabinetry'=>'Cabinetry', 'vanity'=>'Bathroom Vanity',
];

/* bed range used by hero stats */
$bed_low = $layouts[0]['beds'] ?? 1;
$bed_high = !empty($layouts) ? end($layouts)['beds'] : 1;
$bed_range = ($bed_low == $bed_high) ? $bed_low : "{$bed_low}–{$bed_high}";
?>


<div class="c1a-page">

    <div class="top-strip">
        <div class="wrap">
            <span><strong>WA'S BEST RANGE</strong> of Class 1A homes</span>
            <span><strong>Council-approved</strong> permanent dwelling</span>
            <span><strong>Easy finance</strong> available</span>
        </div>
    </div>

    <div class="breadcrumb-bar">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <ol>
                <li><a href="/"><i class="fas fa-house"></i>Home</a></li>
                <li><a href="<?php echo esc_url($parent_crumb['url']); ?>"><?php echo esc_html($parent_crumb['label']); ?></a></li>
                <li aria-current="page">
                    <?php echo esc_html($name); ?>
                </li>
            </ol>
        </nav>
    </div>
    <section class="hero">
        <div class="hero-photo">
            <?php if (!empty($gallery[0])): ?>
            <img src="<?php echo esc_url($gallery[0]['sizes']['large'] ?? $gallery[0]['url']); ?>"
                alt="<?php echo esc_attr($name); ?> exterior">
            <?php else: ?>
            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1400&q=80"
                alt="<?php echo esc_attr($name); ?> exterior">
            <?php endif; ?>
        </div>
        <div class="hero-title-row">
            <span class="hero-badge">
                <?php echo esc_html($badge_tag); ?>
            </span>
            <h1>
                <?php echo esc_html($name); ?>
            </h1>
            <p class="hero-tagline">
                <?php echo esc_html($tagline); ?>
            </p>
            <div class="hero-ticket">
                <div class="hero-ticket-price">
                    <div class="hero-price-label">Starting from</div>
                    <div class="hero-price">$
                        <?php echo esc_html(number_format($price_from)); ?>
                    </div>
                </div>
                <div class="hero-ticket-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-value">
                            <?php echo esc_html($bed_range); ?>
                        </div>
                        <div class="hero-stat-label">Bedrooms</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">1</div>
                        <div class="hero-stat-label">Bathroom</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">1</div>
                        <div class="hero-stat-label">Kitchen</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">
                            <?php echo esc_html($size_sqm); ?>
                        </div>
                        <div class="hero-stat-label">Floor area</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="wrap hero-extras">
        <div class="extras-card">
            <div class="extras-row">
                <p class="hero-finance">Finance available from <b>$
                        <?php echo esc_html($finance_weekly); ?>/week
                    </b> for eligible buyers.</p>

                <div class="addon-select" id="addon-select">
                    <label class="addon-opt" id="shire-opt">
                        <input type="checkbox" id="shire-checkbox">
                        <span>
                            <b>Shire approval assistance</b>
                            <span>+$
                                <?php echo esc_html(number_format($shire_addon)); ?> — we handle Shire submission via our
                                approval partner
                            </span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="extras-row">
                <div class="trust-badges">
                    <span>2-year structural warranty</span>
                    <span>Engineered &amp; soil-tested</span>
                    <span>NCC &amp; CDC compliant</span>
                    <span>WA designed &amp; built</span>
                </div>

                <div class="live-summary" id="live-summary">
                    <span id="live-summary-text">
                        <?php echo esc_html($layouts[0]['label']); ?> — $<?php echo esc_html(number_format($price_from)); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <nav class="subnav">
        <div class="wrap">
            <a href="#overview">Overview</a>
            <a href="#layouts">Layouts</a>
            <a href="#inclusions">Inclusions</a>
            <?php if (!empty($deep_dives)): ?><a href="#deepdives">Details</a>
            <?php endif; ?>
            <a href="#specs">Specifications</a>
            <a href="#finishes">Finishes</a>
            <a href="#downloads">Plans</a>
        </div>
    </nav>

    <div class="wrap body-grid">
        <div class="content">

            <section id="overview">
                <div class="eyebrow">At a glance</div>
                <h2 class="section-title">A permanent home, engineered and approved.</h2>
                <p class="lede">Built to Class 1A standard for a fixed site — not a movable or trailer-based product.
                    Full planning, soil testing, engineering and CDC documentation is included, with optional Shire
                    submission assistance above.</p>
                <?php
                /* Overview gallery: ACF repeater 'overview_gallery' (image + label sub-fields)
                   takes priority if filled in. Falls back to main gallery field
                   (skip index 0, next 6) using each image's WP caption, or a fixed label order. */
                $overview_repeater = get_field('overview_gallery', $post_id);
                if (!empty($overview_repeater)) {
                    $overview_gallery = $overview_repeater;
                } else {
                    $overview_labels = ['Bathroom vanity', 'Full bathroom', 'Bedroom', 'Kitchen', 'Living area', 'Open-plan living/kitchen with doors'];
                    $overview_gallery = [];
                    foreach (array_slice($gallery, 1, 6) as $gi => $img) {
                        $overview_gallery[] = ['image' => $img, 'label' => $img['caption'] ?: ($overview_labels[$gi] ?? '')];
                    }
                }
                ?>
                <div class="gallery-grid" id="overview-gallery">
                    <?php if (!empty($overview_gallery)): foreach ($overview_gallery as $row):
                        $img = $row['image'];
                        if (empty($img)) continue;
                        $full_url = $img['sizes']['large'] ?? $img['url'];
                        $thumb_url = $img['sizes']['medium'] ?? $img['url'];
                        $label = $row['label'];
                    ?>
                    <div class="gallery-item" data-lightbox-src="<?php echo esc_url($full_url); ?>" data-lightbox-caption="<?php echo esc_attr($label); ?>">
                        <div class="ph-photo">
                            <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($label); ?>">
                            <?php if ($label): ?><span class="gallery-caption"><?php echo esc_html($label); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; else: foreach (range(1,6) as $i): ?>
                    <div class="gallery-item">
                        <div class="ph-photo">Photo — gallery-<?php echo $i; ?>.jpg</div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </section>

            <section id="layouts">
                <div class="eyebrow">Configurations</div>
                <h2 class="section-title">Choose your bedroom layout.</h2>
                <div class="layout-pills" id="layout-pills">
                    <?php foreach ($layouts as $i => $l): ?>
                    <button class="layout-pill <?php echo $i===0?'active':''; ?>" data-idx="<?php echo $i; ?>">
                        <?php echo esc_html($l['label']); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <div class="layout-preview" id="layout-preview">
                    <?php $l0 = $layouts[0]; $l0_urls = $l0['image_urls'] ?? []; ?>
                    <div class="ph-photo" id="layout-photo-main">
                        <?php if (!empty($l0_urls[0])): ?><img
                            src="<?php echo esc_url($l0_urls[0]); ?>" alt="">
                        <?php else: ?>Photo — floorplan-
                        <?php echo esc_html($l0['label']); ?>.jpg
                        <?php endif; ?>
                    </div>
                    <div class="meta">
                        <div class="layout-price-label">From</div>
                            <div class="layout-price" id="layoutPrice">$
                                <?php 
                                $layout_price_raw = $l0['price'] ?? $price_from;
                                $layout_price_num = is_numeric($layout_price_raw) ? floatval($layout_price_raw) : 0;
                                echo esc_html(number_format($layout_price_num)); 
                                ?>
                            </div>
                            <div class="layout-name" id="layoutName">
                                <?php echo esc_html($l0['label']); ?>
                            </div>
                            <p class="lede" id="layoutDesc" style="color:var(--ink-soft);margin-bottom:18px;font-size:0.94rem;">
                                <?php echo esc_html($l0['note']); ?>
                            </p>
                            <ul class="layout-bullets" id="layoutBullets">
                                <?php foreach ($l0['bullets'] as $b): ?>
                                <li>
                                    <?php echo esc_html($b); ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <div class="layout-stats">
                            <div class="hero-stat">
                                <div class="layout-stat-value">
                                    <?php echo esc_html($bathrooms); ?>
                                </div>
                                <div class="layout-stat-label">Bathroom</div>
                            </div>
                            <div class="hero-stat">
                                <div class="layout-stat-value" id="layoutSleeps">
                                    <?php echo esc_html($l0['sleeps']); ?>
                                </div>
                                <div class="layout-stat-label">Sleeps up to</div>
                            </div>
                            <div class="hero-stat">
                                <div class="layout-stat-value" id="layoutArea">
                                    <?php echo esc_html($l0['area'] ?? $size_sqm); ?>
                                </div>
                                <div class="layout-stat-label">Floor area</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="inclusions">
                <div class="eyebrow">What's included</div>
                <h2 class="section-title">Premium as standard.</h2>
                <div class="incl-grid">
                    <?php foreach ($inclusions as $i): ?>
                    <div class="incl-card">
                        <div class="ic"></div>
                        <h4>
                            <?php echo esc_html($i['title']); ?>
                        </h4>
                        <p>
                            <?php echo wp_kses_post($i['copy']); ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <?php if (!empty($deep_dives)): ?>
            <section id="deepdives">
                <div class="eyebrow">Built in detail</div>
                <h2 class="section-title">A closer look, room by room.</h2>
                <div style="margin-top:24px;">
                    <?php foreach ($deep_dives as $i => $dd): ?>
                    <div class="deepdive-block">
                        <div class="ph-photo">
                            <?php if (!empty($dd['image'])): ?><img
                                src="<?php echo esc_url($dd['image']['sizes']['medium'] ?? $dd['image']['url']); ?>"
                                alt="">
                            <?php else: ?>Photo —
                            <?php echo esc_html($dd['label']); ?>.jpg
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="deepdive-num">0
                                <?php echo $i+1; ?> ·
                                <?php echo esc_html($dd['label']); ?>
                            </div>
                            <h3>
                                <?php echo esc_html($dd['title']); ?>
                            </h3>
                            <p>
                                <?php echo esc_html($dd['copy']); ?>
                            </p>
                            <ul>
                                <?php foreach ($dd['bullets'] as $b): ?>
                                <li>
                                    <?php echo esc_html($b); ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <section id="specs">
                <div class="eyebrow">Specification</div>
                <h2 class="section-title">Specifications and materials.</h2>
                <p class="lede" style="margin-bottom:20px;">Everything you need to check fit, compliance and site
                    requirements.</p>
                <div>
                    <?php foreach ($spec_groups as $i => $g): ?>
                    <details class="spec-group" <?php echo $i===0?'open':''; ?>>
                        <summary>
                            <?php echo esc_html($g['group']); ?>
                        </summary>
                        <table class="spec-table">
                            <?php foreach ($g['rows'] as $row): ?>
                            <tr>
                                <td>
                                    <?php echo esc_html($row[0]); ?>
                                </td>
                                <td>
                                    <?php echo esc_html($row[1]); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </details>
                    <?php endforeach; ?>
                </div>
            </section>

            <section id="finishes">
                <div class="eyebrow">Customise</div>
                <h2 class="section-title">Pick your finishes.</h2>
                <p class="lede" style="margin-bottom:20px;">Select your cladding, frame, flooring, benchtop, cabinetry
                    and vanity — we'll carry your choices straight through to your quote.</p>
                <p style="font-weight: 800;color: var(--orange-accessible);">
                    Ask us for our brochure to see the many other cladding options available.
                </p>
                <div class="finish-tabs" id="finish-tabs">
                    <?php foreach (array_keys($finishes) as $i => $cat): ?>
                    <button class="finish-tab <?php echo $i===0?'active':''; ?>"
                        data-cat="<?php echo esc_attr($cat); ?>">
                        <?php echo esc_html($finish_labels[$cat]); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <div id="finish-panels">
                    <?php foreach ($finishes as $cat => $opts): ?>
                    <div class="swatch-panel <?php echo $cat===array_key_first($finishes)?'active':''; ?>"
                        data-cat="<?php echo esc_attr($cat); ?>">
                        <div class="swatch-grid">
                            <?php foreach ($opts as $opt):
                                $thumb_style = !empty($opt['image'])
                                    ? "background-image:url(".esc_url($opt['image']).");background-size:cover;background-position:center;"
                                    : ""; // no photo in the swatch library yet for this name — empty placeholder
                            ?>
                            <div class="swatch-item" data-cat="<?php echo esc_attr($cat); ?>"
                                data-name="<?php echo esc_attr($opt['name']); ?>"
                                data-image="<?php echo esc_attr($opt['image'] ?: ''); ?>">
                                <div class="swatch-thumb" style="<?php echo $thumb_style; ?>">
                                </div>
                                <div class="swatch-name">
                                    <?php echo esc_html($opt['name']); ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section id="downloads">
                <div class="eyebrow">Downloads</div>
                <h2 class="section-title">Brochure &amp; floor plans.</h2>
                <div class="dl-grid">
                    <?php foreach ($downloads as $dl): ?>
                    <a href="<?php echo esc_url($dl['file'] ?: '#'); ?>" class="dl-card">
                        <div class="dl-icon"></div>
                        <div><b>
                                <?php echo esc_html($dl['title']); ?>
                            </b><span>
                                <?php echo esc_html($dl['note']); ?>
                            </span></div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <?php if (!empty($accessories)): ?>
            <section id="accessories">
                <div class="eyebrow">Complete your build</div>
                <h2 class="section-title">Pair this with…</h2>
                <p class="lede" style="margin-bottom:20px;">Popular add-ons sized to suit this model.</p>
                <div class="acc-grid">
                    <?php foreach ($accessories as $a): ?>
                    <div class="acc-card">
                        <div class="ph-photo">
                            <?php if (!empty($a['image'])): ?><img
                                src="<?php echo esc_url($a['image']['sizes']['medium'] ?? $a['image']['url']); ?>"
                                alt="">
                            <?php else: ?>Photo —
                            <?php echo esc_html(strtolower($a['cat'])); ?>.jpg
                            <?php endif; ?>
                        </div>
                        <div class="acc-body">
                            <div class="acc-cat">
                                <?php echo esc_html($a['cat']); ?>
                            </div>
                            <h4>
                                <?php echo esc_html($a['name']); ?>
                            </h4>
                            <div class="acc-meta">
                                <?php echo esc_html($a['meta']); ?>
                            </div>
                            <div class="acc-price">
                                <?php echo esc_html($a['price']); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <section class="closing-cta">
                <h2>Bring
                    <?php echo esc_html($name); ?> home.
                </h2>
                <p>Tell us about your site and we'll recommend the right model, size and layout — with a prompt,
                    no-obligation quote.</p>
                <div class="cta-row">
                    <a  class="btn btn-primary" id="cta-quote-bottom" href="https://m.me/61586353940439">Book an appointment</a>
                    <a href="/contact-us" class="btn-ghost">Enquire now</a>
                </div>
            </section>

        </div>

        <div class="rail">
            <div class="rail-card">
                <div class="price-label">From</div>
                <div class="price" id="rail-price">$
                    <?php echo esc_html(number_format($price_from)); ?>
                </div>
                <p class="note">Finance available from $
                    <?php echo esc_html($finance_weekly); ?>/week for eligible buyers.
                </p>
                <a  class="btn btn-primary" id="cta-quote" href="https://m.me/61586353940439">Book an appointment</a>
                <a href="tel:0451113007" class="btn btn-ghost">Talk to a specialist</a>
                <ul class="rail-mini">
                    <li>WA engineered &amp; built</li>
                    <li>2-year structural warranty</li>
                    <li>Permanent, council-approved dwelling</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="mobile-cta">
        <div>
            <div style="font-size:11.5px;color:var(--ink-soft);">From</div>
            <div style="font-family:var(--font-display);font-weight:800;font-size:19px;color:var(--navy-deep);"
                id="mobile-price">$
                <?php echo esc_html(number_format($price_from)); ?>
            </div>
        </div>
        <a class="btn btn-primary" id="cta-quote-mobile"href="https://m.me/61586353940439">Book an appointment</a>
    </div>

    <div class="pds-lightbox" id="pds-lightbox">
        <button type="button" class="pds-lightbox-close" id="pds-lightbox-close" aria-label="Close">×</button>
        <button type="button" class="pds-lightbox-prev" id="pds-lightbox-prev" aria-label="Previous">‹</button>
        <div class="pds-lightbox-content">
            <img src="" alt="" id="pds-lightbox-img">
            <div class="pds-lightbox-caption" id="pds-lightbox-caption"></div>
        </div>
        <button type="button" class="pds-lightbox-next" id="pds-lightbox-next" aria-label="Next">›</button>
    </div>

</div><!-- /.c1a-page -->

<script>
    (function () {
        const productData = <?php echo wp_json_encode([
            'name' => $name, 'priceFrom' => $price_from, 'shireAddon' => $shire_addon,
            'layouts' => $layouts, 'finishLabels' => $finish_labels, 'sizeSqm' => $size_sqm,
        ]); ?>;

        const state = { layoutIdx: 0, shire: false };

        /* finishes: tab switching */
        const finishTabs = document.querySelectorAll('#finish-tabs .finish-tab');
        const finishPanels = document.querySelectorAll('#finish-panels .swatch-panel');
        finishTabs.forEach(btn => {
            btn.addEventListener('click', () => {
                finishTabs.forEach(b => b.classList.remove('active'));
                finishPanels.forEach(p => p.classList.remove('active'));
                btn.classList.add('active');
                document.querySelector(`.swatch-panel[data-cat="${btn.dataset.cat}"]`).classList.add('active');
            });
        });

        /* finishes: swatch selection */
        document.querySelectorAll('.swatch-item').forEach(item => {
            item.addEventListener('click', () => {
                const cat = item.dataset.cat;
                document.querySelectorAll(`.swatch-item[data-cat="${cat}"]`).forEach(s => s.classList.remove('selected'));
                item.classList.add('selected');
            });
        });

        /* layout pill switching */
        const pills = document.querySelectorAll('#layout-pills .layout-pill');
        pills.forEach(btn => {
            btn.addEventListener('click', () => {
                pills.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                state.layoutIdx = parseInt(btn.dataset.idx, 10);
                const l = productData.layouts[state.layoutIdx];
                const urls = l.image_urls || [];
                const photoHtml = urls[0]
                    ? `<img src="${urls[0]}" alt="">`
                    : `Photo — floorplan-${l.label}.jpg`;
                document.querySelector('#layout-preview .ph-photo').outerHTML = `<div class="ph-photo" id="layout-photo-main">${photoHtml}</div>`;

                document.getElementById('layoutPrice').textContent = '$' + Number(l.price || productData.priceFrom).toLocaleString();
                document.getElementById('layoutName').textContent = l.label;
                document.getElementById('layoutDesc').textContent = l.note;
                document.getElementById('layoutBullets').innerHTML = (l.bullets || []).map(b => `<li>${b}</li>`).join('');
                document.getElementById('layoutSleeps').textContent = l.sleeps;
                document.getElementById('layoutArea').textContent = l.area || productData.sizeSqm;

                updatePrice();
            });
        });

        /* Shire approval add-on toggle */
        const shireBox = document.getElementById('shire-checkbox');
        const shireOpt = document.getElementById('shire-opt');
        shireBox.addEventListener('change', () => {
            state.shire = shireBox.checked;
            shireOpt.classList.toggle('active', state.shire);
            updatePrice();
        });

        function updatePrice() {
            const base = Number(productData.priceFrom) || 0;
            const addon = Number(productData.shireAddon) || 0;
            const total = base + (state.shire ? addon : 0);
            const formatted = '$' + total.toLocaleString();
            document.getElementById('rail-price').textContent = formatted;
            document.getElementById('mobile-price').textContent = formatted;
            updateLiveSummary(total);
        }

        function updateLiveSummary(total) {
            const l = productData.layouts[state.layoutIdx];
            const parts = [l.label, '$' + total.toLocaleString()];
            if (state.shire) parts.splice(1, 0, 'Shire approval');
            document.getElementById('live-summary-text').textContent = parts.join(' — ');
        }

        /* ============================================================
           PRODUCT PAGE — goToQuote()
           Sends finish CATEGORY LABEL as 3rd part, so the quote-page
           sidebar can show "Cladding (Luxe): Dark Grey Cladding" instead
           of just the raw category key.
           ============================================================ */
        function goToQuote(e) {
            e.preventDefault();
        }
        document.getElementById('cta-quote').addEventListener('click', goToQuote);
        document.getElementById('cta-quote-mobile').addEventListener('click', goToQuote);
        document.getElementById('cta-quote-bottom').addEventListener('click', goToQuote);

        /* subnav active state on scroll */
        const subnavLinks = document.querySelectorAll('.subnav a');
        const subnavTargets = [...subnavLinks].map(a => document.querySelector(a.getAttribute('href'))).filter(Boolean);
        window.addEventListener('scroll', () => {
            let current = subnavTargets[0];
            subnavTargets.forEach(t => { if (window.scrollY + 220 >= t.offsetTop) current = t; });
            if (current) subnavLinks.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + current.id));
        }, { passive: true });

        /* Overview gallery + layout-preview lightbox (shared) */
        (function () {
            var lightbox = document.getElementById('pds-lightbox');
            var imgEl = document.getElementById('pds-lightbox-img');
            var capEl = document.getElementById('pds-lightbox-caption');
            var prevBtn = document.getElementById('pds-lightbox-prev');
            var nextBtn = document.getElementById('pds-lightbox-next');

            var items = [];        // current gallery items (array mode)
            var idx = 0;
            var navEnabled = true; // false when opened from layout-preview (single image, no arrows)

            function setNavVisible(visible) {
                navEnabled = visible;
                prevBtn.style.display = visible ? '' : 'none';
                nextBtn.style.display = visible ? '' : 'none';
            }

            function openFromList(list, i) {
                items = list;
                idx = i;
                setNavVisible(items.length > 1);
                imgEl.src = items[idx].src;
                capEl.textContent = items[idx].caption || '';
                lightbox.classList.add('is-open');
            }

            function openSingle(src, caption) {
                items = [];
                setNavVisible(false);
                imgEl.src = src;
                capEl.textContent = caption || '';
                lightbox.classList.add('is-open');
            }

            function close() { lightbox.classList.remove('is-open'); }
            function next() { if (navEnabled && items.length) openFromList(items, (idx + 1) % items.length); }
            function prev() { if (navEnabled && items.length) openFromList(items, (idx - 1 + items.length) % items.length); }

            /* Overview gallery — click opens with next/prev */
            var galleryEls = Array.prototype.slice.call(document.querySelectorAll('#overview-gallery .gallery-item[data-lightbox-src]'));
            var galleryItems = galleryEls.map(function (el) {
                return { src: el.dataset.lightboxSrc, caption: el.dataset.lightboxCaption || '' };
            });
            galleryEls.forEach(function (item, i) {
                item.addEventListener('click', function () { openFromList(galleryItems, i); });
            });

            /* Layout-preview image — click opens single image, no next/prev.
               Listener delegated on the container because the photo <div> gets
               replaced (outerHTML) on every pill click, so a direct listener
               on the <img> would die after the first switch. */
            var layoutPreview = document.getElementById('layout-preview');
            if (layoutPreview) {
                layoutPreview.addEventListener('click', function (e) {
                    var img = e.target.closest('#layout-photo-main img');
                    if (!img) return;
                    var l = productData.layouts[state.layoutIdx];
                    openSingle(img.src, l ? l.label : '');
                });
            }

            document.getElementById('pds-lightbox-close').addEventListener('click', close);
            nextBtn.addEventListener('click', next);
            prevBtn.addEventListener('click', prev);
            lightbox.addEventListener('click', function (e) { if (e.target === lightbox) { close(); } });
            document.addEventListener('keydown', function (e) {
                if (!lightbox.classList.contains('is-open')) { return; }
                if (e.key === 'Escape') { close(); }
                if (e.key === 'ArrowRight') { next(); }
                if (e.key === 'ArrowLeft') { prev(); }
            });
        })();

        /* keep summary/price in sync with whatever loaded first */
        updatePrice();
    })();
</script>

<?php get_footer(); // uses theme's existing footer ?>