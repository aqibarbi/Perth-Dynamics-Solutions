<?php
/**
 * Template Name: Class 1A Page
 * Renders DIY Class 1A products dynamically from the "product" CPT,
 * filtered by the "class-1a" product_category term.
 */

get_header();
?>


  <main>
    <!-- ==================== BREADCRUMB ==================== -->

    <div class="breadcrumb-bar">
        <nav class="breadcrumb" aria-label="Breadcrumb">
          <ol>
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-house"></i>Home</a></li>
            <li aria-current="page">Class 1A</li>
          </ol>
        </nav>
     </div>

    <!-- ==================== PAGE HERO ==================== -->
    <section class="page-hero">
      <div class="wrap">
        <div class="hero-inner">
          <div class="reveal">
            <div class="hero-eyebrow">DIY Class 1A Cabins</div>
            <h1>Build it yourself.<br /><em>We handle the paperwork.</em></h1>
            <p class="lead">
              Cost-effective, self-contained cabins and granny flats engineered in Western Australia. As your owner-builder partner, we handle the Shire approval process for you — giving you a WA-tested, council-ready build.
            </p>
            <div class="hero-pdf" style="color: #9b51e0; line-height:1.4;  margin-top:5px; font-weight: 700; font-size: 22px; ">
                    <i class="fa-solid fa-file-pdf"></i>
                    <a href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/docs/Brochure DIY Class 1A + Normal cabins.pdf' ); ?>" 
                       download="Perth Dynamics Brochure 2025.pdf">
                        Download our Class1A brochure (PDF)
                    </a>
                </div>
          </div>
          <?php
          // Fetch Class 1A products once — reused for hero stats + grid below.
          $c1a_products = new WP_Query( array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => array(
              array(
                'taxonomy' => 'product_category',
                'field'    => 'slug',
                'terms'    => 'class-1a',
              ),
            ),
          ) );

          $c1a_count     = $c1a_products->found_posts;
          $c1a_min_price = null; // formatted string as stored in ACF, e.g. "$45,000+GST"
          $c1a_min_num   = null; // numeric value used only for comparison

          if ( $c1a_products->have_posts() ) {
            // Sort low -> high by numeric price (strips $ , GST text etc. before comparing).
            usort( $c1a_products->posts, function( $a, $b ) {
              $pa = (float) preg_replace( '/[^0-9.]/', '', (string) get_field( 'price', $a->ID ) );
              $pb = (float) preg_replace( '/[^0-9.]/', '', (string) get_field( 'price', $b->ID ) );
              return $pb <=> $pa; // DESC: high to low
            } );

            foreach ( $c1a_products->posts as $c1a_p ) {
              $c1a_price_raw = get_field( 'price', $c1a_p->ID );
              $c1a_num       = (float) preg_replace( '/[^0-9.]/', '', (string) $c1a_price_raw );
              if ( $c1a_num > 0 && ( $c1a_min_num === null || $c1a_num < $c1a_min_num ) ) {
                $c1a_min_num   = $c1a_num;
                $c1a_min_price = $c1a_price_raw;
              }
            }
          }
          ?>
          <div class="hero-stats reveal reveal-d2">
            <div class="stat-pill">
              <span class="val"><?php echo esc_html( $c1a_count ); ?></span>
              <span class="lbl">Models available</span>
            </div>
            <div class="stat-pill">
              <span class="val">1–<span>3</span></span>
              <span class="lbl">Bedroom layouts</span>
            </div>
            <div class="stat-pill">
              <span class="val">From <span><?php echo $c1a_min_num ? '$' . esc_html( number_format( $c1a_min_num ) ) . '+GST' : '—'; ?></span></span>
              <span class="lbl">Starting price</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================== PRODUCTS ==================== -->
    <section class="products-section">
      <div class="wrap">
        <?php
        /* Capture each model's real permalink (keyed by title) as we loop the
           grid below, so the comparison table further down can reuse the same
           links instead of the old "#contact" placeholders. */
        $c1a_model_links = array();
        ?>

        <h2 class="screen-reader-text">Available Class 1A Models</h2>

        <!-- Filter bar -->
        <div class="filter-bar-wrap reveal">
          <div class="filter-bar">
            <div class="filter-tabs" role="group" aria-label="Filter by type">
              <button class="filter-tab active" data-filter="all">All</button>
              <button class="filter-tab" data-filter="expanders">Expanders</button>
              <button class="filter-tab" data-filter="studio">Studio</button>
            </div>
            <div class="filter-count">
              <span id="visibleCount"><?php echo (int) $c1a_count; ?></span> models shown
            </div>
          </div>
        </div>

        <!-- Product Grid -->
        <div class="product-grid" id="productGrid">
          <?php
          $pds_products = $c1a_products; // already fetched + price-sorted above

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
              $ceiling_height = get_field( 'ceiling_height' );
              $class1a_type   = get_field( 'class1a_type' );       // 'expanders' or 'cabins'
              $type_label     = $class1a_type === 'expanders' ? 'Expander' : ( $class1a_type === 'studio' ? 'Studio' : '' );

              // stash this model's permalink for reuse in the comparison table below
              $c1a_model_links[ get_the_title() ] = get_permalink();
          ?>
          <article class="product-card reveal reveal-d<?php echo min( $pds_i, 3 ); ?>" data-type="<?php echo esc_attr( $class1a_type ); ?>">
            <a href="<?php the_permalink(); ?>" class="card-stretched-link" aria-label="View <?php the_title_attribute(); ?> details"></a>
            <div class="card-img">
              <?php if ( $card_image ) : ?>
                <img src="<?php echo esc_url( $card_image ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
              <?php endif; ?>
              <?php if ( $badge_tag ) : ?>
                <span class="card-badge"><?php echo esc_html( $badge_tag ); ?></span>
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
                  <span class="sk">Bathroom</span>
                </div>
                <div class="spec-cell">
                  <span class="sv"><?php echo esc_html( $kitchen ); ?></span>
                  <span class="sk">Kitchen</span>
                </div>
                <div class="spec-cell">
                  <span class="sv"><?php echo esc_html( $floor_area ); ?></span>
                  <span class="sk">Floor area</span>
                </div>
                <div class="spec-cell">
                  <span class="sv"><?php echo esc_html( $ceiling_height ); ?></span>
                  <span class="sk">Ceiling height</span>
                </div>
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

        <?php
        /* Helper: find a captured model link by loose title match
           (so "The Vista" matches whether the post is titled "Vista" or "The Vista"). */
        if ( ! function_exists( 'c1a_find_link' ) ) {
          function c1a_find_link( $links, $needle ) {
            foreach ( $links as $title => $url ) {
              if ( stripos( $title, $needle ) !== false ) return $url;
            }
            return '#contact';
          }
        }
        $vista_link    = c1a_find_link( $c1a_model_links, 'Vista' );
        $horizon_link  = c1a_find_link( $c1a_model_links, 'Horizon' );
        $meridian_link = c1a_find_link( $c1a_model_links, 'Meridian' );
        ?>

        <!-- Comparison Table -->
        <div class="compare-table-section reveal">
          <div class="compare-table-wrap">
            <div class="compare-table-scroll">
              <table class="compare-table">
                <thead>
                  <tr>
                    <th scope="col"></th>
                    <th scope="col">The Vista<span class="th-badge">40sqm</span></th>
                    <th scope="col" class="col-popular">The Horizon<span class="th-badge">60sqm · Most Popular</span></th>
                    <th scope="col">The Meridian<span class="th-badge">70–80sqm</span></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">Bedrooms</th>
                    <td>1–2</td>
                    <td class="col-popular">1–3</td>
                    <td>3</td>
                  </tr>
                  <tr>
                    <th scope="row">Bathroom</th>
                    <td>1</td>
                    <td class="col-popular">1</td>
                    <td>1</td>
                  </tr>
                  <tr>
                    <th scope="row">Kitchen</th>
                    <td>1</td>
                    <td class="col-popular">1</td>
                    <td>1</td>
                  </tr>
                  <tr>
                    <th scope="row">Floor Area</th>
                    <td>40m²</td>
                    <td class="col-popular">60m²</td>
                    <td>70–80m²</td>
                  </tr>
                  <tr>
                    <th scope="row">Ceiling Height</th>
                    <td>2400mm</td>
                    <td class="col-popular">2400mm</td>
                    <td>2400mm</td>
                  </tr>
                  <tr>
                    <th scope="row">Best For</th>
                    <td>Compact &amp; entry-level</td>
                    <td class="col-popular">Growing needs</td>
                    <td>Full family layout</td>
                  </tr>
                  <tr>
                    <th scope="row">Starting Price</th>
                    <td class="price-cell">$45,000+GST</td>
                    <td class="col-popular price-cell">$60,000+GST</td>
                    <td class="price-cell">$75,000+GST</td>
                  </tr>
                  <tr class="cta-row">
                    <th scope="row"></th>
                    <td><a href="<?php echo esc_url( $vista_link ); ?>" class="compare-cta" aria-label="View Vista details">View Detail<span class="arrow">→</span></a></td>
                    <td class="col-popular"><a href="<?php echo esc_url( $horizon_link ); ?>" class="compare-cta" aria-label="View Horizon details">View Detail<span class="arrow">→</span></a></td>
                    <td><a href="<?php echo esc_url( $meridian_link ); ?>" class="compare-cta" aria-label="View Meridian details">View Detail<span class="arrow">→</span></a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Compare strip -->
        <div class="compare-strip reveal">
          <div class="cs-text">
            <h2>Not sure which size fits your block?</h2>
            <p>
              Tell us your block dimensions and what you need the space
              for, and we'll point you to the right Class 1A model —
              no guesswork required.
            </p>
          </div>
          <div class="cs-actions">
            <a  class="btn" href="https://m.me/61586353940439">Book an appointment</a>
            <a href="tel:+61 451 113 007" class="btn white">Call Us</a>
          </div>
        </div>
      </div>
    </section>
    <!-- ==================== FEATURES BENTO ==================== -->
    <section class="features-section">
      <div class="wrap">
        <div class="features-eyebrow reveal">
          <div class="section-eyebrow">What's Included</div>
          <h2 class="section-h">Premium inclusions.<br />No hidden extras.</h2>
        </div>

        <div class="bento-grid">
          <div class="bento-card wide reveal reveal-d1">
            <div>
              <div class="bento-icon orange">🏠</div>
              <h3>Premium as standard, not an upgrade</h3>
              <p>
                7-star energy rating, double glazing, a full window façade
                and stone benchtops come standard on every Class 1A model —
                plus a full roof, 2m verandah and eco decking.
              </p>
            </div>
            <div class="bento-visual">
              <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/08/70sqm-expander-livingroom.webp" alt="Class 1A cabin interior" loading="lazy" />
            </div>
          </div>

          <div class="bento-card dark reveal reveal-d2">
            <div class="bento-stat">
              <span class="big">$4,500</span>
              <span class="lbl">Flat-fee shire approval assistance</span>
                <span class="lbl"><b style="font-weight: 900; ">(Ask for limited available stock)</b></span>    
            </div>
            <p style="text-align: center; margin-top: 12px; font-size: 13.5px">
              One fixed, transparent fee — no hidden charges.
            </p>
          </div>

          <div class="bento-card reveal reveal-d1">
            <div class="bento-icon leaf">🌿</div>
            <h3>Council paperwork, handled</h3>
            <p>
              We work closely with our reliable council approval partner
              to guide your project through the shire application process,
              with an excellent success record across most WA councils.
            </p>
          </div>

          <div class="bento-card reveal reveal-d2">
            <div class="bento-icon navy">📐</div>
            <h3>NCC compliant, WA-tested</h3>
            <p>
              Double glazed windows and doors, AS3786-compliant smoke
              alarms, 40mm floor insulation and AS3000-standard
              electrics — every unit is built to the National
              Construction Code.
            </p>
          </div>

          <div class="bento-card reveal reveal-d3">
            <div class="bento-icon orange">🛠️</div>
            <h3>Six years of WA engineering experience</h3>
            <p>
              We design cost-effective houses and self-contained granny
              flats tailor-made to match your living requirements and
              budget, with professional consultants collaborating daily
              with the Shire.
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
            Design it. Plan it. Build it.
          </h2>
        </div>

        <div class="steps-row">
          <div class="step-item reveal reveal-d1">
            <div class="step-num">01</div>
            <h3>Plan and Sign up</h3>
            <p>
              We start the process with deposit and get you started on the approval pathway.
            </p>
          </div>
          <div class="step-item reveal reveal-d2">
            <div class="step-num">02</div>
            <h3>Owner builder's process</h3>
            <p>
              Complete the Owner builders course refer to the Owner builders guide.
            </p>
          </div>
          <div class="step-item reveal reveal-d3">
            <div class="step-num">03</div>
            <h3>Council approval Submission</h3>
            <p>
              we will organise Engineering, Soil Testing, BAL Assessments, Energy efficency and CDC by Building Certifier to get the builiding permit.
            </p>
          </div>
          <div class="step-item reveal reveal-d4">
            <div class="step-num">04</div>
            <h3>Building your Class 1A Primary or Ancillary dwelling</h3>
            <p>
              We select the finishes and colours of your choice.
            </p>
          </div>
          <div class="step-item reveal reveal-d5">
            <div class="step-num">05</div>
            <h3>Delivered and Installed</h3>
            <p>
              we Deliver and install you Class 1A home, now you can live or rent out with peace of mind.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================== FINAL CTA ==================== -->
    <section class="cta-section" id="contact">
      <div class="wrap">
        <div class="cta-box reveal">
          <div class="section-eyebrow" style="justify-content: center; color: var(--orange)">
            Get Started Today
          </div>
          <h2>Ready to<br />build your own?</h2>
          <p>
            Tell us your site details, household size and how hands-on
            you want to be — we'll match you to a Class 1A model and get
            back with a straight-up, no-obligation quote.
          </p>
          <div class="cta-btns">
            <a class="btn" href="https://m.me/61586353940439">Book an appointment</a>
            <a href="tel:+61 451 113 007" class="btn ghost" style="
                  border-color: rgba(255, 255, 255, 0.3);
                  color: var(--text-on-dark);
                ">Call Us</a>
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

    /* ---- Filter tabs (All / Expanders / Cabins) ---- */
    const tabs = document.querySelectorAll(".filter-tab");
    const cards = document.querySelectorAll(".product-card");
    const counter = document.getElementById("visibleCount");

    function updateCount() {
      const visible = document.querySelectorAll(
        ".product-card:not([data-hidden='true'])",
      ).length;
      if (counter) counter.textContent = visible;
    }

    tabs.forEach((tab) => {
      tab.addEventListener("click", () => {
        tabs.forEach((t) => t.classList.remove("active"));
        tab.classList.add("active");

        const filter = tab.dataset.filter;

        cards.forEach((card) => {
          const type = card.dataset.type;
          const hide = filter !== "all" && type !== filter;

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

    updateCount();

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