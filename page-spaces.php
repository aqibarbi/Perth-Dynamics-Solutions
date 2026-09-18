<?php
/**
 * Template Name: Spaces
 */

get_header();
?>


    <main>
      <!-- Breadcrumb -->
          <div class="breadcrumb-bar">
            <nav class="breadcrumb" aria-label="Breadcrumb">
              <ol>
                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-house"></i> Home</a></li>
                <li aria-current="page"><?php echo esc_html( get_the_title() ); ?></li>
              </ol>
            </nav>
          </div>

      <section class="page-hero">
        <div class="wrap">
          <div class="hero-inner">
            <div class="reveal">
              <div class="hero-eyebrow">The Spaces Range</div>
              <h1>
                An open shell,<br /><em>built for what you need it to be.</em>
              </h1>
              <p class="lead">
                Insulated, lined and pre-wired shells with no kitchen, bathroom or internal walls — craned onto your block and handed over ready for your own fit-out.
              </p>
            </div>
            <div class="hero-stats reveal reveal-d2">
              <div class="stat-pill">
                <span class="val">2</span
                ><span class="lbl">Models available</span>
              </div>
              <div class="stat-pill">
                <span class="val">13 -<span>36m²</span></span
                ><span class="lbl">Floor area</span>
              </div>
              <div class="stat-pill">
                <span class="val">From <span>$16,000+GST</span></span
                ><span class="lbl">Starting price</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="products-section">
        <div class="wrap">
<?php
        $pds_spaces = new WP_Query( array(
          'post_type'      => 'product',
          'posts_per_page' => -1,
          'orderby'        => 'meta_value_num',
          'meta_key'       => 'price',
          'order'          => 'ASC',
          'tax_query'      => array(
            array(
              'taxonomy' => 'product_category',
              'field'    => 'slug',
              'terms'    => 'spaces',
            ),
          ),
        ) );
        ?>

          <div class="filter-bar-wrap reveal">
            <div class="filter-bar">
              <div class="filter-count">
                <span id="visibleCount"><?php echo (int) $pds_spaces->found_posts; ?></span> shells shown
              </div>
            </div>
          </div>

          <div class="product-grid" id="productGrid">
            <?php
            if ( $pds_spaces->have_posts() ) :
              $pds_i = 0;
              while ( $pds_spaces->have_posts() ) : $pds_spaces->the_post();
                $pds_i++;
                $price          = get_field( 'price' );
                $badge_tag      = get_field( 'badge_tag' );
                $card_image     = get_field( 'card_image' );
                $short_desc     = get_field( 'short_description' );
                $floor_area     = get_field( 'floor_area' );
                $footprint      = get_field( 'footprint' );
                $ceiling_height = get_field( 'ceiling_height' );
                $dimensions = get_field( 'dimensions' );
                $weekly_price   = get_field( 'weekly_price' );
            ?>
            <article class="product-card reveal reveal-d<?php echo min( $pds_i, 3 ); ?>">
                <a href="<?php the_permalink(); ?>" class="card-stretched-link" aria-label="View <?php the_title_attribute(); ?> details"></a>
              <div class="card-img">
                <?php if ( $card_image ) : ?>
                  <img src="<?php echo esc_url( $card_image ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
                <?php endif; ?>
                <?php if ( $badge_tag ) : ?>
                  <span class="card-badge orange"><?php echo esc_html( $badge_tag ); ?></span>
                <?php endif; ?>
                
              </div>
              <div class="card-body">
                <h3><?php the_title(); ?></h3>
                <p class="tagline"><?php echo esc_html( $short_desc ); ?></p>
                <?php if ( $floor_area || $footprint ||  $ceiling_height || $weekly_price ) : ?>
                <div class="card-specs">
                  <?php if ( $floor_area ) : ?>
                  <div class="spec-cell">
                    <span class="sv"><?php echo esc_html( $floor_area ); ?></span><span class="sk">Floor area</span>
                  </div>
                  <?php endif; ?>
                  <?php if ( $footprint ) : ?>
                  <div class="spec-cell">
                    <span class="sv"><?php echo esc_html( $footprint ); ?></span><span class="sk">Footprint</span>
                  </div>
                  <?php endif; ?>
                  
                  <?php if ( $dimensions ) : ?>
                    <div class="spec-cell">
                      <span class="sv"><?php echo esc_html( $dimensions ); ?></span><span class="sk">Dimensions</span>
                    </div>
                    <?php endif; ?>
                  <?php if ( $ceiling_height ) : ?>
                  <div class="spec-cell">
                    <span class="sv"><?php echo esc_html( $ceiling_height ); ?></span><span class="sk">Ceiling height</span>
                  </div>
                  <?php endif; ?>
                  
                  <?php if ( $weekly_price ) : ?>
                  <div class="spec-cell">
                    <span class="sv">Make it yours</span><span class="sk">just <?php echo esc_html( $weekly_price ); ?>/week</span>
                  </div>
                  <?php endif; ?>
                </div>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <div class="card-price">
                  <span class="from-lbl">From</span><span class="amount">$<?php echo number_format( (float) $price ); ?>+GST</span>
                </div>
                <a href="<?php the_permalink(); ?>" class="card-cta"
                  >View Detail <span class="arrow">→</span></a
                >
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

          <div class="compare-strip reveal">
            <div class="cs-text">
              <h3>Not sure which shell footprint fits your block?</h3>
              <p>
                Tell us your block size and what you'll use the space for, and we'll point you to the right size and layout.
              </p>
            </div>
            <div class="cs-actions">
              <a  class="btn" href="https://m.me/61586353940439">Book an appointment</a>
              <a href="tel:+61 451 113 007" class="btn white"
                >Talk to a specialist</a
              >
            </div>
          </div>
        </div>
      </section>
      
      <section class="size-note-section">
        <div class="wrap">
          <div class="size-note-banner reveal">
            <div class="size-note-text">
              <i class="fas fa-ruler-combined"></i>
              <span>Need more room? Every shell below also comes in <strong>30ft</strong> and <strong>40ft</strong> footprints — same build quality, more floor area.</span>
            </div>
            <a href="/contact-us/" class="btn ghost size-note-cta">Ask about sizes</a>
          </div>
        </div>
      </section>

      <section class="features-section">
        <div class="wrap">
          <div class="features-eyebrow reveal">
            <div class="section-eyebrow">Why Spaces</div>
            <h2 class="section-h">Shell today.<br />Fit-out on your terms.</h2>
          </div>
          <div class="bento-grid">
            <div class="bento-card wide reveal reveal-d1">
              <div>
                <div class="bento-icon leaf">🧱</div>
                <h4>Structure done, finish is yours</h4>
                <p>
                  Walls, insulation and wiring are complete before it leaves the factory. On site, it's ready for your fit-out — not a ground-up build.
                </p>
              </div>
              <div class="bento-visual">
                <img
                  src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/08/spaces-expanders.webp"
                  alt="Open plan shell interior"
                  loading="lazy"
                />
              </div>
            </div>
            <div class="bento-card dark reveal reveal-d2">
              <div class="bento-stat">
              <span class="big">9–10</span>
              <span class="lbl">Weeks lead time</span>
              <span class="lbl" ><b style="font-weight: 900; ">(Ask for limited available stock)</b></span>
            </div>
              <p
                style="text-align: center; margin-top: 12px; font-size: 13.5px"
              >
                From confirmed order to a Space on your block.
              </p>
            </div>
            <div class="bento-card reveal reveal-d1">
              <div class="bento-icon orange">📐</div>
              <h4>Sizes to match your footprint</h4>
              <p>
                Choose the shell size that fits your block, then pick cladding, roofing and interior finish packs to suit.
              </p>
            </div>
            <div class="bento-card reveal reveal-d2">
              <div class="bento-icon navy">✅</div>
              <h4>Paperwork ready for council</h4>
              <p>
                Structural drawings, specs and compliance certificates come standard — ready to lodge with your local council.
              </p>
            </div>
            <div class="bento-card reveal reveal-d3">
              <div class="bento-icon leaf">🚚</div>
              <h4>Set anywhere across WA</h4>
              <p>
                Our team plans transport and placement, tight access and crane lifts included where the site calls for it.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section class="process-section">
        <div class="wrap">
          <div class="features-eyebrow reveal">
            <div class="section-eyebrow">The Process</div>
            <h2 class="section-h" style="color: #fff">
              Simple, start to finish.
            </h2>
          </div>
          <div class="steps-row">
            <div class="step-item reveal reveal-d1">
              <div class="step-num">01</div>
              <h4>Pick a shell size</h4>
              <p>
                Compare our shell footprints against your block and how you plan to use the space.
              </p>
            </div>
            <div class="step-item reveal reveal-d2">
              <div class="step-num">02</div>
              <h4>Choose finishes</h4>
              <p>
                Settle on cladding, interior finishes and any extras with our team.
              </p>
            </div>
            <div class="step-item reveal reveal-d3">
              <div class="step-num">03</div>
              <h4>Lock it in</h4>
              <p>Sign off the final quote and secure your build slot.</p>
            </div>
            <div class="step-item reveal reveal-d4">
              <div class="step-num">04</div>
              <h4>Craned in &amp; ready</h4>
              <p>
                Delivered complete, set on site and ready for your fit-out.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section class="cta-section" id="contact">
        <div class="wrap">
          <div class="cta-box reveal">
            <div
              class="section-eyebrow"
              style="justify-content: center; color: var(--orange)"
            >
              Get Started Today
            </div>
            <h2>Ready for<br />your own Space?</h2>
            <p>
              Share your block size and intended use — we'll match you to a shell size and follow up with a straight-up, no-obligation quote.
            </p>

            <div class="cta-btns">
              <a  class="btn" href="https://m.me/61586353940439">Book an appointment</a
              >
              <a
                href="tel:+61 451 113 007"
                class="btn ghost"
                style="
                  border-color: rgba(255, 255, 255, 0.3);
                  color: var(--text-on-dark);
                "
                >Call Us</a
              >
            </div>
          </div>
        </div>
      </section>
    </main>

<script>


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
      document
        .querySelectorAll(".reveal")
        .forEach((el) => observer.observe(el));

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