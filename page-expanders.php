<?php
/**
 * Template Name: Expander
 * Renders Expander products dynamically from the "product" CPT,
 * filtered by the "expanders-product" product_category term.
 */

get_header();
?>
  <main>
    <!-- ==================== BREADCRUMB ==================== -->
    <!-- Breadcrumb -->
  <div class="breadcrumb-bar">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol>
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
        <li aria-current="page"><?php echo esc_html( get_the_title() ); ?></li>
      </ol>
    </nav>
  </div>

    <?php
    $pds_products = new WP_Query( array(
      'post_type'      => 'product',
      'posts_per_page' => -1,
      'orderby'        => 'meta_value_num',
      'meta_key'       => 'price',
      'order'          => 'ASC',
      'tax_query'      => array(
        array(
          'taxonomy' => 'product_category',
          'field'    => 'slug',
          'terms'    => 'expanders',
        ),
      ),
    ) );

    /* reorder so "Full Expander" cards show before "Half Expander" ones —
       the query above already sorts by price ASC, this just groups by
       expander_type on top of that (price order is kept inside each group) */
    function pds_expander_type_rank($type) {
        if ($type === 'full') return 0;
        if ($type === 'half') return 1;
        return 2; // anything else (empty/unknown type) goes last
    }
    usort($pds_products->posts, function ($a, $b) {
        $rank_a = pds_expander_type_rank(get_field('expander_type', $a->ID));
        $rank_b = pds_expander_type_rank(get_field('expander_type', $b->ID));
        return $rank_a - $rank_b;
    });

    $pds_model_count = $pds_products->found_posts;
    $pds_min_price   = null;
    $pds_min_bed     = null;
    $pds_max_bed     = null;

    foreach ( $pds_products->posts as $pds_post ) {
      $pds_price = (float) get_field( 'price', $pds_post->ID );
      if ( $pds_price > 0 && ( $pds_min_price === null || $pds_price < $pds_min_price ) ) {
        $pds_min_price = $pds_price;
      }
      $pds_bed_raw = get_field( 'bedrooms', $pds_post->ID );
      // Extract leading integer from bedroom value (handles "2", "2 Bed", "2 Bed + Laundry", etc.)
      if ( preg_match( '/\d+/', (string) $pds_bed_raw, $pds_bed_match ) ) {
        $pds_bed_num = (int) $pds_bed_match[0];
        if ( $pds_min_bed === null || $pds_bed_num < $pds_min_bed ) {
          $pds_min_bed = $pds_bed_num;
        }
        if ( $pds_max_bed === null || $pds_bed_num > $pds_max_bed ) {
          $pds_max_bed = $pds_bed_num;
        }
      }
    }
    ?>

    <!-- ==================== PAGE HERO ==================== -->
    <section class="page-hero">
      <div class="wrap">
        <div class="hero-inner">
          <div class="reveal">
            <div class="hero-eyebrow">The Expander Range</div>
            <h1>Folds flat for transport.<br /><em>Opens into a real home.</em></h1>
            <p class="lead">
              Factory-built expander homes engineered in Western Australia,
              trucked to your block compact, then unfolded on-site into
              full-width living. Choose your size, bedroom count and finish.
            </p>
          </div>
          <div class="hero-stats reveal reveal-d2">
            <div class="stat-pill">
              <span class="val"><?php echo (int) $pds_model_count; ?></span>
              <span class="lbl">Models available</span>
            </div>
            <div class="stat-pill">
              <span class="val"><?php echo (int) $pds_min_bed; ?>–<span><?php echo (int) $pds_max_bed; ?></span></span>
              <span class="lbl">Bedroom layouts</span>
            </div>
            <div class="stat-pill">
              <span class="val">From <span>$<?php echo number_format( (float) $pds_min_price ); ?></span></span>
              <span class="lbl">Starting price</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================== FILTER + PRODUCTS ==================== -->
    <section class="products-section">
      <div class="wrap">

        <!-- Filter bar -->
        <div class="filter-bar-wrap reveal">
          <div class="filter-bar">
            <div class="filter-tabs" role="group" aria-label="Filter by type">
              <button class="filter-tab active" data-filter="all">All</button>
              <button class="filter-tab" data-filter="full">Full Expanders</button>
              <button class="filter-tab" data-filter="half">Half Expanders</button>
            </div>
            <div class="filter-count">
              <span id="visibleCount"><?php echo (int) $pds_products->found_posts; ?></span> models shown
            </div>
          </div>
        </div>

        <!-- Product Grid -->
        <div class="product-grid" id="productGrid">
          <?php
          if ( $pds_products->have_posts() ) :
            $pds_i = 0;
            while ( $pds_products->have_posts() ) : $pds_products->the_post();
              $pds_i++;
              $price          = get_field( 'price' );
              $badge_tag      = get_field( 'badge_tag' );
              $card_image     = get_field( 'card_image' );
              $short_desc     = get_field( 'short_description' );
              $bedrooms       = get_field( 'bedrooms' );
              $bathrooms      = get_field( 'bathrooms' );
              $kitchen        = get_field( 'kitchen' );
              $floor_area     = get_field( 'floor_area' );
              $weekly_price   = get_field( 'weekly_price' );
              $expander_type  = get_field( 'expander_type' );      // full / half
              $type_label     = $expander_type === 'full' ? 'Full Expander' : ( $expander_type === 'half' ? 'Half Expander' : '' );
          ?>
          <article class="product-card reveal reveal-d<?php echo min( $pds_i, 3 ); ?>" data-type="<?php echo esc_attr( $expander_type ); ?>">
              <a href="<?php the_permalink(); ?>" class="card-stretched-link" aria-label="View <?php the_title_attribute(); ?> details"></a>
            <div class="card-img">
              <?php if ( $card_image ) : ?>
                <img src="<?php echo esc_url( $card_image ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
              <?php endif; ?>
              <?php if ( $badge_tag ) : ?>
                <span class="card-badge orange"><?php echo esc_html( $badge_tag ); ?></span>
              <?php endif; ?>
              <?php if ( $type_label ) : ?>
                <span class="card-type-tag"><?php echo esc_html( $type_label ); ?></span>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <h3><?php the_title(); ?></h3>
              <p class="tagline"><?php echo esc_html( $short_desc ); ?></p>
              <div class="card-specs">
                <div class="spec-cell">
                  <span class="sv"><?php echo esc_html( $bedrooms ); ?></span>
                  <span class="sk">Bedrooms</span>
                </div>
                <div class="spec-cell">
                  <span class="sv"><?php echo esc_html( $bathrooms ); ?></span>
                  <span class="sk">Bathrooms</span>
                </div>
                <div class="spec-cell">
                  <span class="sv"><?php echo esc_html( $kitchen ); ?></span>
                  <span class="sk">kitchen</span>
                </div>
                <div class="spec-cell">
                  <span class="sv"><?php echo esc_html( $floor_area ); ?></span>
                  <span class="sk">Floor area</span>
                </div>
                <?php if ( $weekly_price ) : ?>
                <div class="spec-cell">
                  <span class="sv">Make it yours</span>
                  <span class="sk">Just <?php echo esc_html( $weekly_price ); ?>/week</span>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="card-footer">
              <div class="card-price">
                <span class="from-lbl">From</span>
                <span class="amount">$<?php echo number_format( (float) $price ); ?>+GST</span>
              </div>
              <a href="<?php the_permalink(); ?>" class="card-cta">
                View Detail<span class="arrow">→</span>
              </a>
            </div>
          </article>
          <?php
            endwhile;
            wp_reset_postdata();
          else :
          ?>
            <p>No products found in this category yet.</p>
          <?php endif; ?>
        </div>
        <!-- /product-grid -->

        <!-- Compare strip -->
        <div class="compare-strip reveal">
          <div class="cs-text">
            <h3>Torn between two sizes?</h3>
            <p>
              Give us your block dimensions and bedroom needs and we'll
              point you to the right expander, no guesswork required.
            </p>
          </div>
          <div class="cs-actions">
            <a  class="btn"href="https://m.me/61586353940439">Book an appointment</a>
            <a href="tel:+61 451 113 007" class="btn white">Call Us</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================== FEATURES BENTO ==================== -->
    <section class="features-section">
      <div class="wrap">
        <div class="features-eyebrow reveal">
          <div class="section-eyebrow">The PDS Difference</div>
          <h2 class="section-h">Made in WA.<br />Made to move in.</h2>
        </div>

        <div class="bento-grid">
          <div class="bento-card wide reveal reveal-d1">
            <div>
              <div class="bento-icon orange">🔌</div>
              <h4>Move-in ready, not move-in someday</h4>
              <p>
                Plumbing, wiring and fixtures are all done at the factory
                before it ever leaves the yard. Unfold, connect, live.
              </p>
            </div>
            <div class="bento-visual">
              <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/07/40m-hafexpanders.jpg" alt="Expander home interior" loading="lazy" />
            </div>
          </div>

          <div class="bento-card dark reveal reveal-d2">
            <div class="bento-stat">
              <span class="big">9–10</span>
              <span class="lbl">Weeks lead time</span>
              <span class="lbl" ><b style="font-weight: 900; ">(Ask for limited available stock)</b></span>
            </div>
            <p style="text-align: center; margin-top: 12px; font-size: 13.5px">
              From order to unfolded, on your block.
            </p>
          </div>

          <div class="bento-card reveal reveal-d2">
            <div class="bento-icon navy">📐</div>
            <h4>Finished the way you want it</h4>
            <p>
              Pick cladding, flooring, cabinetry and fixtures from our
              range and make each expander genuinely yours.
            </p>
          </div>

          <div class="bento-card reveal reveal-d3">
            <div class="bento-icon orange">🚚</div>
            <h4>We get it on site, wherever you are</h4>
            <p>
              Narrow driveways, sloped blocks, tight corners — our crew
              plans the access and crane work so it lands where it should.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================== PROCESS ==================== -->
    <section class="process-section">
      <div class="wrap">
        <div class="features-eyebrow reveal">
          <div class="section-eyebrow">The Process</div>
          <h2 class="section-h" style="color: #fff">
            Order it. Unfold it. Live in it.
          </h2>
        </div>

        <div class="steps-row">
          <div class="step-item reveal reveal-d1">
            <div class="step-num">01</div>
            <h4>Select</h4>
            <p>
              Browse models, layouts and options to find the right fit for
              your needs and block.
            </p>
          </div>
          <div class="step-item reveal reveal-d2">
            <div class="step-num">02</div>
            <h4>Personalise</h4>
            <p>
              Finalise layout, accessories and site considerations with our
              team.
            </p>
          </div>
          <div class="step-item reveal reveal-d3">
            <div class="step-num">03</div>
            <h4>Confirm</h4>
            <p>
              Approve the final quote and place your order to lock in
              production.
            </p>
          </div>
          <div class="step-item reveal reveal-d4">
            <div class="step-num">04</div>
            <h4>Delivered &amp; done</h4>
            <p>
              Fully finished and delivered to site, ready for final
              connection.
            </p>
          </div>
        </div>
      </div>
    </section>


  </main>

<script>


    /* ---- Scroll Reveal ---- */
    const revealEls = document.querySelectorAll(".reveal");
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) {
            e.target.classList.add("visible");
            observer.unobserve(e.target);
          }
        });
      },
      { threshold: 0.12 },
    );
    revealEls.forEach((el) => observer.observe(el));

    /* ---- Filter tabs ---- */
    const tabs = document.querySelectorAll(".filter-tab");
    const cards = document.querySelectorAll(".product-card");
    const counter = document.getElementById("visibleCount");

    function updateCount() {
      const visible = document.querySelectorAll(
        ".product-card:not([data-hidden='true'])",
      ).length;
      counter.textContent = visible;
    }

    tabs.forEach((tab) => {
      tab.addEventListener("click", () => {
        tabs.forEach((t) => t.classList.remove("active"));
        tab.classList.add("active");

        const filter = tab.dataset.filter;

        cards.forEach((card) => {
          const type = card.dataset.type;
          const hide = filter !== "all" && type !== filter;
          card.setAttribute("data-hidden", hide ? "true" : "false");

          if (hide) {
            card.style.opacity = "0";
            card.style.transform = "scale(0.96)";
            card.style.transition = "opacity 0.2s ease, transform 0.2s ease";
            setTimeout(() => card.setAttribute("data-hidden", "true"), 200);
          } else {
            card.setAttribute("data-hidden", "false");
            requestAnimationFrame(() => {
              card.style.transition =
                "opacity 0.3s ease, transform 0.3s ease";
              card.style.opacity = "1";
              card.style.transform = "translateY(0)";
            });
          }
        });

        updateCount();
      });
    });

    /* ---- Scrolled header class ---- */
    window.addEventListener(
      "scroll",
      () => {
        const hdr = document.querySelector("header.site");
        if (hdr) hdr.classList.toggle("scrolled", window.scrollY > 10);
      },
      { passive: true },
    );
  </script>

<?php get_footer(); ?>