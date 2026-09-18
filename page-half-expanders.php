<?php
/*
Template Name: Half Expanders
*/
get_header();

// Fetch all products in the "half-expanders" category, cheapest first
$half_expanders_query = new WP_Query( array(
	'post_type'      => 'product',
	'posts_per_page' => -1,
	'meta_key'       => 'price',
	'orderby'        => 'meta_value_num',
	'order'          => 'ASC',
	'tax_query'      => array(
		array(
			'taxonomy' => 'product_category',
			'field'    => 'slug',
			'terms'    => 'half-expander',
		),
	),
) );
?>

    <div class="breadcrumb">
      <div class="wrap">
        <div class="breadcrumb-inner">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
          <span class="sep">›</span>
          <span class="current">Half Expanders</span>
        </div>
      </div>
    </div>

    <!-- ==================== PAGE HERO ==================== -->
    <section class="page-hero">
      <div class="wrap">
        <div class="hero-inner">
          <div class="reveal">
            <div class="hero-eyebrow">DIY Class 1A Half Expander Cabins</div>
            <h1>Not enough space<br /><em>for a full expander?</em></h1>
            <p class="lead">
              These particular units are for properties that don't have
              enough space for a full expander. We designed half expanders
              so your granny flat vision can still come true — the same
              WA-engineered quality, in a smaller footprint.
            </p>
          </div>
          <div class="hero-stats reveal reveal-d2">
            <div class="stat-pill">
              <span class="val"><?php echo esc_html( $half_expanders_query->found_posts ); ?></span>
              <span class="lbl">Models available</span>
            </div>
            <div class="stat-pill">
              <span class="val">1–<span>2</span></span>
              <span class="lbl">Bedroom layouts</span>
            </div>
            <div class="stat-pill">
              <span class="val">From <span>$45,000+GST</span></span>
              <span class="lbl">Starting price</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================== PRODUCTS ==================== -->
    <section class="products-section">
      <div class="wrap">
        <!-- Product Grid -->
        <div class="product-grid grid-4" id="productGrid">
          <?php
          /* Capture each model's real permalink (keyed by title) as we loop the
             grid below, so the comparison table further down can reuse the same
             links instead of the old "#contact" placeholders. */
          $he_model_links = array();
          ?>
          <?php if ( $half_expanders_query->have_posts() ) : ?>
            <?php while ( $half_expanders_query->have_posts() ) : $half_expanders_query->the_post(); ?>
              <?php
              $he_model_links[ get_the_title() ] = get_permalink();
              $price          = get_field( 'price' );
              $badge_tag      = get_field( 'badge_tag' );
              $card_image     = get_field( 'card_image' );
              $short_desc     = get_field( 'short_description' );
              $bedrooms       = get_field( 'bedrooms' );
              $bathrooms      = get_field( 'bathrooms' );
              $kitchen        = get_field( 'kitchen' );
              $floor_area     = get_field( 'floor_area' );
              $ceiling_height = get_field( 'ceiling_height' );
              $weekly_price   = get_field( 'weekly_price' );
              ?>
              <article class="product-card reveal">
                  <a href="<?php the_permalink(); ?>" class="card-stretched-link" aria-label="View <?php the_title_attribute(); ?> details"></a>
                <div class="card-img">
                  <?php
                    $img_url = is_array( $card_image ) ? $card_image['url'] : $card_image;
                    $img_alt = is_array( $card_image ) ? ( $card_image['alt'] ?: get_the_title() ) : get_the_title();
                    ?>
                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" loading="lazy" />
                  <?php if ( $badge_tag ) : ?>
                    <span class="card-badge"><?php echo esc_html( $badge_tag ); ?></span>
                  <?php endif; ?>
                  <span class="card-type-tag">Half Expander</span>
                </div>
                <div class="card-body">
                  <h3><?php the_title(); ?></h3>
                  <?php if ( $short_desc ) : ?>
                    <p class="tagline"><?php echo esc_html( $short_desc ); ?></p>
                  <?php endif; ?>
                  <div class="card-specs">
                    <?php if ( $bedrooms ) : ?>
                      <div class="spec-cell">
                        <span class="sv"><?php echo esc_html( $bedrooms ); ?></span>
                        <span class="sk">Bedrooms</span>
                      </div>
                    <?php endif; ?>
                    <?php if ( $bathrooms ) : ?>
                      <div class="spec-cell">
                        <span class="sv"><?php echo esc_html( $bathrooms ); ?></span>
                        <span class="sk">Bathroom</span>
                      </div>
                    <?php endif; ?>
                    <?php if ( $kitchen ) : ?>
                      <div class="spec-cell">
                        <span class="sv"><?php echo esc_html( $kitchen ); ?></span>
                        <span class="sk">Kitchen</span>
                      </div>
                    <?php endif; ?>
                    <?php if ( $floor_area ) : ?>
                      <div class="spec-cell">
                        <span class="sv"><?php echo esc_html( $floor_area ); ?></span>
                        <span class="sk">Floor area</span>
                      </div>
                    <?php endif; ?>
                    <?php if ( $ceiling_height ) : ?>
                      <div class="spec-cell">
                        <span class="sv"><?php echo esc_html( $ceiling_height ); ?></span>
                        <span class="sk">Ceiling height</span>
                      </div>
                    <?php endif; ?>
                    <?php if ( $weekly_price ) : ?>
                      <div class="spec-cell">
                        <span class="sv">Make it yours</span>
                        <span class="sk">just <?php echo esc_html( $weekly_price ); ?>/week</span>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="card-footer">
                  <?php if ( $price ) : ?>
                    <div class="card-price">
                      <span class="from-lbl">From</span>
                      <span class="amount">$<?php echo number_format( (float) $price ); ?>+GST</span>
                    </div>
                  <?php endif; ?>
                  <a href="<?php the_permalink(); ?>" class="card-cta">
                    View Detail<span class="arrow">→</span>
                  </a>
                </div>
              </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          <?php endif; ?>
        </div>
        <!-- /product-grid -->

        <?php
        /* Helper: find a captured model link by loose title match
           (so "The Ancillary" matches whether the post is titled "Ancillary" or "The Ancillary"). */
        if ( ! function_exists( 'he_find_link' ) ) {
          function he_find_link( $links, $needle ) {
            foreach ( $links as $title => $url ) {
              if ( stripos( $title, $needle ) !== false ) return $url;
            }
            return '#contact';
          }
        }
        $cubby_link    = he_find_link( $he_model_links, 'Cubby' );
        $ancillary_link = he_find_link( $he_model_links, 'Ancillary' );
        $little_lot_link = he_find_link( $he_model_links, 'Little Lot' );
        ?>

        <!-- Comparison Table -->
        <div class="compare-table-section reveal">
          <div class="compare-table-wrap">
            <div class="compare-table-scroll">
              <table class="compare-table">
                <thead>
                  <tr>
                    <th scope="col"></th>
                    <th scope="col">Cubby &amp; Co<span class="th-badge">27sqm</span></th>
                    <th scope="col" class="col-popular">The Ancillary<span class="th-badge">40.5sqm · Most Popular</span></th>
                    <th scope="col">The Little Lot<span class="th-badge">53.1sqm</span></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">Bedrooms</th>
                    <td>1–2</td>
                    <td class="col-popular">1–2</td>
                    <td>1–2</td>
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
                    <td>27m²</td>
                    <td class="col-popular">40.5m²</td>
                    <td>53.1m²</td>
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
                    <td>Most room to move</td>
                  </tr>
                  <tr>
                    <th scope="row">Starting Price</th>
                    <td class="price-cell">$45,000+GST</td>
                    <td class="col-popular price-cell">$60,000+GST</td>
                    <td class="price-cell">$75,000+GST</td>
                  </tr>
                  <tr class="cta-row">
                    <th scope="row"></th>
                    <td><a href="<?php echo esc_url( $cubby_link ); ?>" class="compare-cta">View Detail<span class="arrow">→</span></a></td>
                    <td class="col-popular"><a href="<?php echo esc_url( $ancillary_link ); ?>" class="compare-cta">View Detail<span class="arrow">→</span></a></td>
                    <td><a href="<?php echo esc_url( $little_lot_link ); ?>" class="compare-cta">View Detail<span class="arrow">→</span></a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Compare strip -->
        <div class="compare-strip reveal">
          <div class="cs-text">
            <h3>Not sure which size fits your block?</h3>
            <p>
              Tell us your block dimensions and what you need the space
              for, and we'll point you to the right half expander model —
              no guesswork required.
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
          <div class="section-eyebrow">What's Included</div>
          <h2 class="section-h">Premium inclusions.<br />No hidden extras.</h2>
        </div>

        <div class="bento-grid">
          <div class="bento-card wide reveal reveal-d1">
            <div>
              <div class="bento-icon orange">🏠</div>
              <h4>Premium as standard, not an upgrade</h4>
              <p>
                7-star energy rating, double glazing, a full window façade
                and stone benchtops come standard on every half expander —
                plus a full roof, 2m verandah and eco decking.
              </p>
            </div>
            <div class="bento-visual">
              <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/08/70sqm-expander-livingroom.webp" alt="Half expander cabin interior" loading="lazy" />
            </div>
          </div>

          <div class="bento-card dark reveal reveal-d2">
            <div class="bento-stat">
              <span class="big">$4,500</span>
              <span class="lbl">Flat-fee shire approval assistance</span>
            </div>
            <p style="text-align: center; margin-top: 12px; font-size: 13.5px">
              One fixed, transparent fee — no hidden charges.
            </p>
          </div>

          <div class="bento-card reveal reveal-d1">
            <div class="bento-icon leaf">🌿</div>
            <h4>Council paperwork, handled</h4>
            <p>
              We work closely with our reliable council approval partner
              to guide your project through the shire application process,
              with an excellent success record across most WA councils.
            </p>
          </div>

          <div class="bento-card reveal reveal-d2">
            <div class="bento-icon navy">📐</div>
            <h4>NCC compliant, WA-tested</h4>
            <p>
              Double glazed windows and doors, AS3786-compliant smoke
              alarms, 40mm floor insulation and AS3000-standard
              electrics — every unit is built to the National
              Construction Code.
            </p>
          </div>

          <div class="bento-card reveal reveal-d3">
            <div class="bento-icon orange">🛠️</div>
            <h4>Six years of WA engineering experience</h4>
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
                <h4> Plan and Sign up</h4>
                <p>
                  We start the process with deposit and get you started on the approval pathway.
                </p>
              </div>
              <div class="step-item reveal reveal-d2">
                <div class="step-num">02</div>
                <h4>Owner builder's process</h4>
                <p>
                  Complete the Owner builders course refer to the Owner builders guide.
                </p>
              </div>
              <div class="step-item reveal reveal-d3">
                <div class="step-num">03</div>
                <h4>Council approval Submission</h4>
                <p>
                  we will organise Engineering, Soil Testing, BAL Assessments, Energy efficency and CDC by Building Certifier to get the builiding permit.
                </p>
              </div>
              <div class="step-item reveal reveal-d4">
                <div class="step-num">04</div>
                <h4>Building your Class 1A Primary or Ancillary dweling</h4>
                <p>
                  We select the finishes and colours of your choice.
                </p>
              </div>
              <div class="step-item reveal reveal-d5">
                <div class="step-num">05</div>
                <h4>Delivered and Installed</h4>
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
            you want to be — we'll match you to a half expander model and
            get back with a straight-up, no-obligation quote.
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
<?php get_footer(); ?>

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
