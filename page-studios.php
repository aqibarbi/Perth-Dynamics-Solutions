<?php
/**
 * Template Name: Studios
 * Renders Cabins & Pods products dynamically from the "product" CPT,
 * filtered by the "Studio" product_category term.
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
              <div class="hero-eyebrow">The Studio Range</div>
              <h1>
                One room, done right.<br /><em>Ready the day it lands.</em>
              </h1>
              <p class="lead">
                Self-contained studios built and fitted out off-site, craned straight onto your block. Bedroom, bathroom, kitchen — one footprint. Pick size and finish.
              </p>
            </div>
            <div class="hero-stats reveal reveal-d2">
              <div class="stat-pill">
                <span class="val">3</span
                ><span class="lbl">Models available</span>
              </div>
              <div class="stat-pill">
                <span class="val">13–<span>26</span> m²</span
                ><span class="lbl">Floor area</span>
              </div>
              <div class="stat-pill">
                <span class="val">From <span>$18,000+GST</span></span
                ><span class="lbl">Starting price</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="products-section">
        <div class="wrap">
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
              'terms'    => 'cabins-pods',
            ),
          ),
        ) );
        ?>

          <div class="filter-bar-wrap reveal">
            <div class="filter-bar">
              <div
                class="filter-tabs"
                role="group"
                aria-label="Filter by length"
              >
                <button class="filter-tab active" data-filter="all">All</button>
                <button class="filter-tab" data-filter="6m">6 m</button>
                <button class="filter-tab" data-filter="9m">9 m</button>
                <button class="filter-tab" data-filter="12m">12 m</button>
              </div>
              <div class="filter-count">
                <span id="visibleCount"><?php echo (int) $pds_products->found_posts; ?></span> models shown
              </div>
            </div>
          </div>

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
                $ceiling_height = get_field( 'ceiling_height' );
                $sub_type       = get_field( 'sub_type' );        // 6m / 9m / 12m
                $sub_type_label = get_field( 'sub_type_label' );  // "6 m" / "9 m" / "12 m"
            ?>
            <article class="product-card reveal reveal-d<?php echo min( $pds_i, 3 ); ?>" data-type="<?php echo esc_attr( $sub_type ); ?>">
                <a href="<?php the_permalink(); ?>" class="card-stretched-link" aria-label="View <?php the_title_attribute(); ?> details"></a>
              <div class="card-img">
                <?php if ( $card_image ) : ?>
                  <img src="<?php echo esc_url( $card_image ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
                <?php endif; ?>
                <?php if ( $badge_tag ) : ?>
                  <span class="card-badge orange"><?php echo esc_html( $badge_tag ); ?></span>
                <?php endif; ?>
                <?php if ( $sub_type_label ) : ?>
                  <span class="card-type-tag"><?php echo esc_html( $sub_type_label ); ?></span>
                <?php endif; ?>
              </div>
              <div class="card-body">
                <h3><?php the_title(); ?></h3>
                <p class="tagline"><?php echo esc_html( $short_desc ); ?></p>
                <div class="card-specs">
                  <div class="spec-cell">
                    <span class="sv"><?php echo esc_html( $bedrooms ); ?></span><span class="sk">Bedroom</span>
                  </div>
                  <div class="spec-cell">
                    <span class="sv"><?php echo esc_html( $bathrooms ); ?></span><span class="sk">Bathroom</span>
                  </div>
                  <div class="spec-cell">
                    <span class="sv"><?php echo esc_html( $kitchen ); ?></span><span class="sk">kitchen</span>
                  </div>
                  <div class="spec-cell">
                    <span class="sv"><?php echo esc_html( $floor_area ); ?></span><span class="sk">Floor area</span>
                  </div>
                    <div class="spec-cell">
                      <span class="sv"><?php echo esc_html( $ceiling_height ); ?></span>
                      <span class="sk">Ceiling height</span>
                    </div>
                  <?php if ( $weekly_price ) : ?>
                  <div class="spec-cell">
                    <span class="sv">Make it yours</span><span class="sk">just <?php echo esc_html( $weekly_price ); ?>/week</span>
                  </div>
                  <?php endif; ?>
                </div>
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
              <h3>Choosing between our 6, 9 or 12 m studio?</h3>
              <p>
                Give us your block size and how you'll use it, and we'll point
                you to the right length and layout.
              </p>
            </div>
            <div class="cs-actions">
              <a  class="btn"href="https://m.me/61586353940439">Book an appointment</a>
              <a href="tel:+61 451 113 007" class="btn white"
                >Talk to a specialist</a
              >
            </div>
          </div>
        </div>
      </section>

      <section class="features-section">
        <div class="wrap">
          <div class="features-eyebrow reveal">
            <div class="section-eyebrow">Why Studio</div>
            <h2 class="section-h">Set down.<br />Switch on. Settle in.</h2>
          </div>
          <div class="bento-grid">
            <div class="bento-card wide reveal reveal-d1">
              <div>
                <div class="bento-icon leaf">🏡</div>
                <h4>Complete before it leaves the yard</h4>
                <p>
                  Kitchen, bathroom and bedroom are fitted, plumbed and wired at
                  the factory. On site, it's a connection job — not a build.
                </p>
              </div>
              <div class="bento-visual">
                <img
                  src="<?php /* TODO: real wp-content/uploads URL missing — was bare filename "Cabins & Pods.png", 404s live. Paste correct Media Library URL here. */ echo esc_url( home_url( '/wp-content/uploads/2026/08/70sqm-expander-livingroom.webp' ) ); ?>"
                  alt="Cabin interior"
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
                From confirmed order to a Studio on your block.
              </p>
            </div>
            <div class="bento-card reveal reveal-d1">
              <div class="bento-icon orange">📐</div>
              <h4>Three sizes to match your block</h4>
              <p>
                Go 6 m, 9 m or 12 m, then choose from a range of cladding,
                roofing and interior finish packs.
              </p>
            </div>
            <div class="bento-card reveal reveal-d2">
              <div class="bento-icon navy">✅</div>
              <h4>Paperwork ready for council</h4>
              <p>
                Structural drawings, specs and compliance certificates come
                standard — ready to lodge with your local council.
              </p>
            </div>
            <div class="bento-card reveal reveal-d3">
              <div class="bento-icon leaf">🚚</div>
              <h4>Set anywhere across WA</h4>
              <p>
                Our team plans transport and placement, tight access and crane
                lifts included where the site calls for it.
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
              <h4>Pick a size</h4>
              <p>
                Compare the Serpentine, Canning, Preston, Warren and Margaret
                against your block and budget.
              </p>
            </div>
            <div class="step-item reveal reveal-d2">
              <div class="step-num">02</div>
              <h4>Choose finishes</h4>
              <p>
                Settle on cladding, interior finishes and any extras with our
                team.
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
                Delivered complete, set on site and handed over ready to use.
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
            <h2>Ready for<br />your own Studio?</h2>
            <p>
              Share your block size and how you'll use it — we'll match you to a
              model and follow up with a straight-up, no-obligation quote.
            </p>
            <div class="cta-chips">
              <span class="cta-chip">No obligation</span>
              <span class="cta-chip">Prompt response</span>
              <span class="cta-chip">Fast lead times</span>
              <span class="cta-chip">Delivered fully built</span>
            </div>
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

      const tabs = document.querySelectorAll(".filter-tab");
      const cards = document.querySelectorAll(".product-card");
      const counter = document.getElementById("visibleCount");

      function updateCount(filter) {
        let count = 0;
        cards.forEach((card) => {
          if (filter === "all" || card.dataset.type === filter) count++;
        });
        counter.textContent = count;
      }

      tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
          tabs.forEach((t) => t.classList.remove("active"));
          tab.classList.add("active");
          const filter = tab.dataset.filter;
          cards.forEach((card) => {
            const hide = filter !== "all" && card.dataset.type !== filter;
            if (hide) {
              card.style.opacity = "0";
              card.style.transform = "scale(0.96)";
              card.style.transition = "opacity 0.2s ease,transform 0.2s ease";
              setTimeout(() => card.setAttribute("data-hidden", "true"), 200);
            } else {
              card.setAttribute("data-hidden", "false");
              requestAnimationFrame(() => {
                card.style.transition = "opacity 0.3s ease,transform 0.3s ease";
                card.style.opacity = "1";
                card.style.transform = "translateY(0)";
              });
            }
          });
          updateCount(filter);
        });
      });

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
