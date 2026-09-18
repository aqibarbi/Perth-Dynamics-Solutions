<?php
/*
Template Name: Contact Us
*/
get_header();
?>
<!-- ===== HERO ===== -->
    <div class="breadcrumb-bar">
        <nav class="breadcrumb" aria-label="Breadcrumb">
          <ol>
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-house"></i>Home</a></li>
            <li aria-current="page">Contact Us</li>
          </ol>
        </nav>
     </div>
<section class="pds-hero">
    <div class="pds-container">
        <div class="pds-hero-inner">
            <span class="pds-eyebrow">Get In Touch</span>
            <h1 class="pds-hero-title">Questions? <em>We've got answers.</em></h1>
            <p class="pds-hero-subtitle">
                Send us a message or call the team directly — we'll get back to you promptly with honest advice and a no-obligation quote.
            </p>
            <div class="pds-trust-row">
                <span>★ 5.0 Rated facebook Reviews</span>
                <span class="pds-trust-divider"></span>
                <span>100% WA Owned &amp; Operated</span>
            </div>
        </div>
    </div>
</section>

<!-- ===== CONTACT GRID: form + direct contact ===== -->
<section class="pds-contact-section">
    <div class="pds-container">
        <div class="pds-contact-grid">

            <!-- Form -->
            <div class="pds-form-card">
                <h2>Send a Message</h2>
                <p class="pds-form-lead">Fill in the form below and one of our team will be in touch promptly — no sales pressure, just honest advice.</p>

                <?php
                if ( shortcode_exists( 'fluentform' ) ) {
                    echo do_shortcode( '[fluentform id="6"]' );
                } else {
                    echo '<div class="pds-form-fallback">Contact form is temporarily unavailable — please call us directly or try again shortly.</div>';
                }
                ?>
            </div>

            <!-- Direct contact -->
            <div class="pds-sidebar">
                <div class="pds-info-card">
                    <span class="pds-eyebrow pds-eyebrow--small">Direct Contact</span>
                    <h3>Talk to the Team</h3>
                    <p class="pds-info-note">Prefer to speak directly? Reach us Monday–Friday, 8am–5pm.
                    <!-- NOTE: confirm exact hours before publishing, not listed on the homepage --></p>
                    <a href="tel:+61 451 113 007" class="pds-info-link">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        +61 451 113 007
                    </a>
                    <a href="mailto:hello@perthdynamicsolutions.com.au" class="pds-info-link">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        info@perthdynamicsolutions.com.au
                        <!-- NOTE: confirm real inbox address before publishing -->
                    </a>
                </div>

                <div class="pds-info-card">
                    <span class="pds-eyebrow pds-eyebrow--small">Our Range</span>
                    <h3>Not Sure What Fits?</h3>
                    <p class="pds-info-note">Browse Class 1A, Half Expanders, Expanders, Studios, Spaces or Accessories before you enquire.</p>
                    <a href="/#range" class="pds-info-link">
                        Explore the Range
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== WHY PDS: numbered list, matching homepage pattern exactly ===== -->
<section class="pds-why-section">
    <div class="pds-container">
        <div class="pds-why-grid">
            <div class="pds-why-header">
                <span class="pds-eyebrow">Why Perth Dynamic Solutions</span>
                <h2>Built smarter. Delivered better.</h2>
                <p>Traditional builds cost a fortune and drag on for months. We plan, build and deliver with WA conditions and WA timelines in mind.</p>
            </div>
            <div class="pds-why-list">
                <div class="pds-why-row">
                    <span class="pds-why-num">01</span>
                    <div>
                        <h4>Delivered ready to go</h4>
                        <p>Arrives complete and site-ready. No mess, no drawn-out delays.</p>
                    </div>
                </div>
                <div class="pds-why-row">
                    <span class="pds-why-num">02</span>
                    <div>
                        <h4>Compliant from the ground up</h4>
                        <p>Built to Australian standards, with all trade connections accounted for.</p>
                    </div>
                </div>
                <div class="pds-why-row">
                    <span class="pds-why-num">03</span>
                    <div>
                        <h4>Flexible by design</h4>
                        <p>Move it, rent it, or grow into it — your build isn't locked to one plan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== FAQ TEASER: top questions + link to full FAQs page ===== -->
<section class="pds-faq-section">
    <div class="pds-container">
        <div class="pds-faq-header">
            <span class="pds-eyebrow">FAQ</span>
            <h2>Common Questions</h2>
            <p>A few quick answers — for the full list, see our <a href="/faqs">FAQs page</a>.</p>
        </div>

        <div class="pds-faq-list">
            <div class="pds-faq-item">
                <h4>Does the price include delivery?</h4>
                <p>Delivery is not included in the price and is charged per km. Share your address and our staff can give you a quote including delivery.</p>
            </div>
            <div class="pds-faq-item">
                <h4>What is the turn around time?</h4>
                <p>If the unit is not in stock or has been made to order, please allow a 10–12 week turn around. In-stock items can be delivered immediately.</p>
            </div>
            <div class="pds-faq-item">
                <h4>Do they come with warranty?</h4>
                <p>Yes, our homes come with an industry-leading 24-month warranty. See our Warranty Agreement for details.</p>
            </div>
            <div class="pds-faq-item">
                <h4>Do I need council approval?</h4>
                <p>Your relocatable building may qualify as a caravan, which can remove the need for council approval — this varies by council, so it's best to check locally.</p>
            </div>
        </div>
    </div>
</section>







<?php get_footer(); ?>