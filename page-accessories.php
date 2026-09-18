<?php
/**
 * Template Name: Accessories Page
 * Renders Accessory products dynamically from the "product" CPT,
 * filtered by the "accessories" product_category term — same workflow
 */

get_header();?>
<!-- Breadcrumb -->
  <div class="breadcrumb-bar">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
        <li aria-current="page"><?php echo esc_html( get_the_title() ); ?></li>
      </ol>
    </nav>
  </div>

  <main>
    <!-- Hero -->
    <section class="acc-hero">
      <div class="wrap">
        <span class="eyebrow reveal is-visible">Accessories</span>
        <h1 class="reveal is-visible">
          The gear that makes your home actually work.
        </h1>
        <p class="lede reveal is-visible reveal-delay-1">
          Every Perth Dynamic Solutions build can be paired with the
          essentials — moved, cooled and supplied with water — so your home is
          liveable the day it lands on your block.
        </p>
        <?php
        $pds_acc_query = new WP_Query( array(
          'post_type'      => 'product',
          'posts_per_page' => -1,
          'tax_query'      => array(
            array(
              'taxonomy' => 'product_category',
              'field'    => 'slug',
              'terms'    => 'accessories',
            ),
          ),
        ) );
        ?>
        <div class="hero-count">
          <div class="stat reveal is-visible reveal-delay-2">
            <strong><?php echo (int) $pds_acc_query->found_posts; ?></strong>
            <span>Accessory ranges</span>
          </div>
          <div class="stat reveal is-visible reveal-delay-2">
            <strong>WA-wide</strong>
            <span>Delivery &amp; install</span>
          </div>
          <div class="stat reveal is-visible reveal-delay-3">
            <strong>From $3,000</strong>
            <span>Add-on pricing</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Features -->
    <section class="features">
      <div class="wrap">
        <?php
        if ( $pds_acc_query->have_posts() ) :
          $pds_fi = 0;
          while ( $pds_acc_query->have_posts() ) : $pds_acc_query->the_post();
            $pds_fi++;
            $flip_class    = ( $pds_fi % 2 === 0 ) ? ' flip' : '';
            $card_image    = get_field( 'card_image' );
            $badge_tag     = get_field( 'badge_tag' );      // used as the small image tag, e.g. "Trailers"
            $index_label   = get_field( 'index_label' );    // e.g. "Move it"
            $short_desc    = get_field( 'short_description' );
            $price         = get_field( 'price' );
            $weekly_price  = get_field( 'weekly_price' );
        ?>
        <div class="feature-row<?php echo esc_attr( $flip_class ); ?>">
          <div class="visual reveal">
            <?php if ( $badge_tag ) : ?>
              <span class="tag"><?php echo esc_html( $badge_tag ); ?></span>
            <?php endif; ?>
            <?php if ( $card_image ) : ?>
              <img src="<?php echo esc_url( $card_image ); ?>" alt="<?php the_title_attribute(); ?>" />
            <?php endif; ?>
          </div>
          <div class="content reveal reveal-delay-1">
            <?php if ( $index_label ) : ?>
              <span class="index"><?php echo esc_html( $index_label ); ?></span>
            <?php endif; ?>
            <h2><?php the_title(); ?></h2>
            <p class="desc"><?php echo esc_html( $short_desc ); ?></p>

            <?php
            $spec_list_raw = get_field( 'spec_list' ); // plain textarea, one bullet per line
            $spec_lines    = $spec_list_raw ? array_filter( array_map( 'trim', explode( "\n", $spec_list_raw ) ) ) : array();
            ?>
            <?php if ( ! empty( $spec_lines ) ) : ?>
            <ul class="spec-list">
              <?php foreach ( $spec_lines as $bullet ) : ?>
                <li><?php echo esc_html( $bullet ); ?></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <div class="price-row">
              <span class="from">From</span>
              <span class="amount">$<?php echo number_format( (float) $price ); ?></span>
              <?php if ( $weekly_price ) : ?>
                <span class="finance">or from <b>$<?php echo esc_html( $weekly_price ); ?></b>/week</span>
              <?php endif; ?>
            </div>

            
            </a>
          </div>
        </div>

        <?php endwhile;
          wp_reset_postdata();
        else : ?>
          <p>No accessories found yet — add one via Products → Add New, and tick the "Accessories" category.</p>
        <?php endif; ?>
      </div>
    </section>

    <!-- Final CTA -->
    <section id="contact" class="wrap">
      <div class="final-cta reveal">
        <h2>Fit your home out properly, in one call.</h2>
        <p>
          Tell us your model and block and we'll put together a trailer, A/C
          and water package that suits your build and budget.
        </p>
        <div class="cta-chips">
          <span class="chip">WA-wide delivery</span>
          <span class="chip">Fast lead times</span>
        </div>
        <div class="cta-actions">
          <a href="tel:+61 451 113 007" class="btn btn-primary">Call us now <span class="arrow">&rarr;</span></a>
          <a  class="btn btn-ghost"href="https://m.me/61586353940439">Book an appointment</a>
        </div>
      </div>
    </section>
  </main>

<script>


    // Scroll reveal
    const revealEls = document.querySelectorAll(".reveal:not(.is-visible)");
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15, rootMargin: "0px 0px -60px 0px" },
    );
    revealEls.forEach((el) => io.observe(el));
  </script>

<?php get_footer(); ?>