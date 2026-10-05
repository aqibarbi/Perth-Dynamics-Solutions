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
      $pds_bed_raw  = (string) get_field( 'bedrooms', $pds_post->ID );
      $pds_bed_nums = array();
      // Range ("1-4", "1–4") counts both ends. Otherwise take the leading integer ("2", "2 Bed", "2 Bed + Laundry").
      if ( preg_match( '/(\d+)\s*(?:-|–|—|to)\s*(\d+)/u', $pds_bed_raw, $pds_bed_match ) ) {
        $pds_bed_nums = array( (int) $pds_bed_match[1], (int) $pds_bed_match[2] );
      } elseif ( preg_match( '/\d+/', $pds_bed_raw, $pds_bed_match ) ) {
        $pds_bed_nums = array( (int) $pds_bed_match[0] );
      }
      foreach ( $pds_bed_nums as $pds_bed_num ) {
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

    <!-- ==================== NEW: SECTION 1 + 2 (SEO additions) ==================== -->
    <section class="seo-section seo-intro">
      <div class="wrap">

        <!-- Section 1: What Is an Expander Home? -->
        <div class="seo-head reveal">
          <h2 class="section-h seo-h">What Is an Expander Home?</h2>
        </div>
        <div class="seo-pair reveal">
          <div class="seo-card seo-card--warm seo-copy">
            <span class="seo-card-ico" aria-hidden="true"><i class="fas fa-expand"></i></span>
            <p>Expander homes are prefab expandable homes built entirely in a factory, transported to site in a compact form, then unfolded into full-width living space on arrival. That's why expandable homes Perth buyers choose are so practical: the unit travels like a small cabin, so it can reach blocks where a full-size modular delivery would struggle, then opens out into a proper home with a kitchen, bathroom and bedrooms already fitted.</p>
          </div>
          <div class="seo-card seo-copy">
            <span class="seo-card-ico ico-navy" aria-hidden="true"><i class="fas fa-industry"></i></span>
            <p>Every Perth Dynamic Solutions expandable modular home is finished with plumbing, wiring and fixtures in place before it leaves our factory. On-site work is mostly positioning, unfolding and connecting services, which is why the unfold itself takes hours rather than weeks.</p>
          </div>
        </div>

        <!-- Section 2: Size guide -->
        <div class="seo-sizes">
          <div class="seo-head reveal">
            <h2 class="section-h seo-h">20ft, 30ft and 40ft Expandable Homes: Choosing Your Size</h2>
            <div class="seo-copy">
              <p class="seo-lead">Not sure which footprint suits your block? Here's how our current range compares. Each model's detail page includes its floor plan, a good starting point if you're searching for an expandable house plan.</p>
            </div>
          </div>

          <div class="size-table-wrap reveal" role="region" aria-label="Expandable home size comparison" tabindex="0">
            <table class="size-table">
              <thead>
                <tr>
                  <th scope="col">Size</th>
                  <th scope="col">Model</th>
                  <th scope="col">Type</th>
                  <th scope="col">Bedrooms</th>
                  <th scope="col">Floor area</th>
                  <th scope="col">From</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="st-size">20ft</td>
                  <td class="st-model"><a href="<?php echo esc_url( home_url( '/products/ember/' ) ); ?>">Ember</a></td>
                  <td><span class="type-pill type-half">Half Expander</span></td>
                  <td>1</td>
                  <td>26m²</td>
                  <td class="st-price">$26,000+GST</td>
                </tr>
                <tr>
                  <td class="st-size">20ft</td>
                  <td class="st-model"><a href="<?php echo esc_url( home_url( '/products/cove/' ) ); ?>">Cove</a></td>
                  <td><span class="type-pill type-half">Half Expander</span></td>
                  <td>1</td>
                  <td>25m²</td>
                  <td class="st-price">$30,000+GST</td>
                </tr>
                <tr>
                  <td class="st-size">20ft</td>
                  <td class="st-model"><a href="<?php echo esc_url( home_url( '/products/sable/' ) ); ?>">Sable</a></td>
                  <td><span class="type-pill type-full">Full Expander</span></td>
                  <td>1–4</td>
                  <td>36–40m²</td>
                  <td class="st-price">$26,000+GST</td>
                </tr>
                <tr>
                  <td class="st-size">30ft</td>
                  <td class="st-model"><a href="<?php echo esc_url( home_url( '/products/reva/' ) ); ?>">Reva</a></td>
                  <td><span class="type-pill type-full">Full Expander</span></td>
                  <td>1–3</td>
                  <td>56–60m²</td>
                  <td class="st-price">$40,000+GST</td>
                </tr>
                <tr>
                  <td class="st-size">40ft</td>
                  <td class="st-model"><a href="<?php echo esc_url( home_url( '/products/vasa/' ) ); ?>">Vasa</a></td>
                  <td><span class="type-pill type-full">Full Expander</span></td>
                  <td>2–4</td>
                  <td>74–80m²</td>
                  <td class="st-price">$48,000+GST</td>
                </tr>
              </tbody>
            </table>
          </div>

          <aside class="seo-note reveal">
            <span class="seo-note-ico" aria-hidden="true"><i class="fas fa-ruler-combined"></i></span>
            <p>Comparing 40ft expandable container homes? The Vasa is our largest, with up to four bedrooms and a full kitchen on a 40ft footprint.</p>
          </aside>
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


    <!-- ==================== NEW: SECTIONS 3-6 (SEO additions) ==================== -->
    <div class="seo-post">
      <div class="wrap">

        <!-- Section 3: What Can You Use an Expandable Home For? -->
        <section class="seo-block">
          <div class="seo-head reveal">
            <h2 class="section-h seo-h">What Can You Use an Expandable Home For?</h2>
            <div class="seo-copy">
              <p class="seo-lead">Expander homes are flexible by design. Perth customers use them as:</p>
            </div>
          </div>
          <ul class="seo-uses reveal" role="list">
            <li>
              <span class="use-ico" aria-hidden="true"><i class="fas fa-people-roof"></i></span>
              <div><strong>Granny flats and ancillary dwellings</strong> for family, guests or rental income</div>
            </li>
            <li>
              <span class="use-ico" aria-hidden="true"><i class="fas fa-laptop-house"></i></span>
              <div><strong>Spare rooms and home offices</strong> in the backyard</div>
            </li>
            <li>
              <span class="use-ico" aria-hidden="true"><i class="fas fa-house-chimney"></i></span>
              <div><strong>Compact permanent homes</strong> for full-time living</div>
            </li>
            <li>
              <span class="use-ico" aria-hidden="true"><i class="fas fa-campground"></i></span>
              <div><strong>Holiday, rural and worksite accommodation</strong> on larger blocks</div>
            </li>
          </ul>
          <div class="seo-copy seo-after-list">
            <p>Buyers searching for expandable tiny homes often want the small footprint of a tiny home without giving up a proper kitchen and bathroom. That's the gap an expander fills: it arrives compact, then opens into real living space.</p>
          </div>
        </section>

        <!-- Section 4 + 5: Finishes / Container comparison -->
        <div class="seo-duo">
          <section class="seo-card seo-card--warm seo-copy reveal">
            <span class="seo-card-ico" aria-hidden="true"><i class="fas fa-palette"></i></span>
            <h2 class="section-h seo-h">Choose Your Finishes and Add-Ons</h2>
            <p>Every expander can be finished your way. Choose cladding, flooring, cabinetry and fixtures from our range, then add the extras that make the build self-sufficient: a pitched roof, veranda, air conditioning and trailer options. Talk to our team about what suits your block and budget.</p>
          </section>
          <section class="seo-card seo-copy reveal">
            <span class="seo-card-ico ico-navy" aria-hidden="true"><i class="fas fa-scale-balanced"></i></span>
            <h2 class="section-h seo-h">Expander Homes vs Container Homes Perth</h2>
            <p>If you've been researching container homes Perth builders offer, you'll notice the appeal is the same: factory-built quality, fast delivery, and a lower cost than a traditional build. Like container houses Perth buyers compare online, expanders are built off-site and transported as a compact unit. The difference is that an expander unfolds on arrival, giving you a wider, more liveable footprint than a fixed-width container house. If a container expandable home is on your shortlist, this is the format to compare against.</p>
          </section>
        </div>

        <!-- Section 6: Council Approval and Site Requirements in WA -->
        <section class="seo-panel reveal">
          <div class="seo-head">
            <h2 class="section-h seo-h">Council Approval and Site Requirements in WA</h2>
          </div>
          <div class="seo-cols">
            <div class="seo-copy">
              <span class="seo-card-ico" aria-hidden="true"><i class="fas fa-landmark"></i></span>
              <p>Approval requirements depend on your property and how the unit will be used. In WA, ancillary dwellings (granny flats) are generally limited to 70 m² under the <a href="https://www.dplh.wa.gov.au/rcodes" target="_blank" rel="noopener noreferrer">Residential Design Codes</a>, so our Ember, Cove, Sable and Reva models sit within that limit, while the Vasa is better suited as a primary or larger dwelling. A building permit is required in most cases.</p>
            </div>
            <div class="seo-copy">
              <span class="seo-card-ico ico-navy" aria-hidden="true"><i class="fas fa-truck"></i></span>
              <p>On site, we plan access and crane work around your block, including narrow driveways, slopes, and tight corners. Share your dimensions, and we'll confirm what suits your site. Explore our <a href="<?php echo esc_url( home_url( '/class-1a/' ) ); ?>">Class 1A range</a> for permanent, building-code-compliant homes.</p>
            </div>
          </div>
        </section>

      </div>
    </div>

    <!-- ==================== NEW: SECTION 7 FAQ (SEO addition) ====================-->
    <?php
    $pds_faqs = array(
      array(
        'icon' => 'fa-tags',
        'q'    => 'How much do expandable homes cost in Perth?',
        'a'    => 'Perth Dynamic Solutions expander homes start from $26,000+GST for the Ember and Sable, up to $48,000+GST for the Vasa. Final cost depends on size, finishes, and site requirements.',
      ),
      array(
        'icon' => 'fa-clock',
        'q'    => 'How long does it take to get an expandable home?',
        'a'    => 'Lead time is typically 9–10 weeks from order to delivery on your block. Ask us about limited available stock.',
      ),
      array(
        'icon' => 'fa-landmark',
        'q'    => 'Do expandable homes need council approval?',
        'a'    => 'Usually a building permit is required, and planning approval depends on your property and use. Models within 70m² can qualify as ancillary dwellings under WA\'s Residential Design Codes. Our team can guide you through what your site needs.',
      ),
      array(
        'icon' => 'fa-maximize',
        'q'    => 'What\'s the difference between a Full Expander and a Half Expander?',
        'a'    => 'A Half Expander (Ember, Cove) is a compact 1-bedroom, 1-bathroom layout of around 25–26 m². A Full Expander (Sable, Reva, Vasa) unfolds to full width for larger, multi-bedroom living.',
      ),
      array(
        'icon' => 'fa-people-roof',
        'q'    => 'Can an expandable home be used as a granny flat?',
        'a'    => 'Yes. Our models up to 60m² fit within WA\'s 70m² ancillary dwelling limit, making them a popular granny flat option. See our <a href="' . esc_url( home_url( '/studio/' ) ) . '">Studio range</a> for other self-contained options.',
      ),
      array(
        'icon' => 'fa-coins',
        'q'    => 'Can you build a tiny home for $20,000?',
        'a'    => 'Only a very basic, self-built shell is likely to come in that low. A factory-finished home with plumbing, wiring, a kitchen, and a bathroom costs more. Our Studio range starts from $19,000, and our Ember expander from $26,000+GST, both fully built and ready to deliver.',
      ),
      array(
        'icon' => 'fa-hammer',
        'q'    => 'Who makes the best kit homes in Australia?',
        'a'    => '"Best" depends on your budget, timeline, and how much finishing work you want to take on. We build factory-finished expander homes rather than kits, so there\'s no assembly on your side. Whichever builder you compare, check what\'s included in the price, the lead time, and the warranty.',
      ),
    );

    $pds_faq_entities = array();
    foreach ( $pds_faqs as $pds_faq ) {
      $pds_faq_entities[] = array(
        '@type'          => 'Question',
        'name'           => $pds_faq['q'],
        'acceptedAnswer' => array(
          '@type' => 'Answer',
          'text'  => wp_strip_all_tags( $pds_faq['a'] ),
        ),
      );
    }
    $pds_faq_schema = array(
      '@context'   => 'https://schema.org',
      '@type'      => 'FAQPage',
      'mainEntity' => $pds_faq_entities,
    );

    // two columns, first column gets the extra item when the count is odd
    $pds_faq_cols = array_chunk( $pds_faqs, (int) ceil( count( $pds_faqs ) / 2 ) );
    $pds_faq_n    = 0;
    ?>
    <section id="expander-faq">
      <div class="wrap">
        <div class="faq-title">
          <h2>Expander Home FAQs</h2>
        </div>
        <div class="faq-cols">
          <?php foreach ( $pds_faq_cols as $pds_faq_col ) : ?>
          <div class="faq-col">
            <?php foreach ( $pds_faq_col as $pds_faq ) : $pds_faq_n++; ?>
            <div class="faq-item">
              <h3>
                <button type="button" class="faq-q" id="expander-faq-q-<?php echo (int) $pds_faq_n; ?>" aria-expanded="false" aria-controls="expander-faq-a-<?php echo (int) $pds_faq_n; ?>">
                  <span class="faq-icon-badge" aria-hidden="true"><i class="fas <?php echo esc_attr( $pds_faq['icon'] ); ?>"></i></span>
                  <span class="faq-q-text"><?php echo esc_html( $pds_faq['q'] ); ?></span>
                </button>
              </h3>
              <div class="faq-a" id="expander-faq-a-<?php echo (int) $pds_faq_n; ?>" role="region" aria-labelledby="expander-faq-q-<?php echo (int) $pds_faq_n; ?>">
                <p><?php echo wp_kses( $pds_faq['a'], array( 'a' => array( 'href' => array() ) ) ); ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <script type="application/ld+json"><?php echo wp_json_encode( $pds_faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG ); ?></script>
    <script>
      (function () {
        document.querySelectorAll("#expander-faq .faq-q").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var item = btn.closest(".faq-item");
            var open = item.classList.toggle("is-open");
            btn.setAttribute("aria-expanded", open ? "true" : "false");
          });
        });
      })();
    </script>

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