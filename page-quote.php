<?php
/**
 * Template Name: Get a Quote
 * Description: Quote request page for Perth Dynamic Solutions.
 *              Form itself is handled by Fluent Forms Pro (shortcode id 5) —
 *              only the surrounding layout/design lives here.
 */

get_header();
?>
<?php
$prefill = null;
if ( ! empty( $_GET['model'] ) ) {
    $prefill = [
        'model'         => sanitize_text_field( wp_unslash( $_GET['model'] ) ),
        'layout'        => sanitize_text_field( wp_unslash( $_GET['layout'] ?? '' ) ),
        'price'         => sanitize_text_field( wp_unslash( $_GET['price'] ?? '' ) ),
        'shireApproval' => sanitize_text_field( wp_unslash( $_GET['shireApproval'] ?? '' ) ),
        'returnUrl'     => esc_url_raw( wp_unslash( $_GET['returnUrl'] ?? '' ) ),
        'finishes'      => [],
    ];
    $skip_keys = [ 'model', 'layout', 'price', 'shireApproval', 'returnUrl' ];
    foreach ( $_GET as $key => $val ) {
        if ( in_array( $key, $skip_keys, true ) ) continue;
        $prefill['finishes'][ sanitize_key( $key ) ] = sanitize_text_field( wp_unslash( $val ) );
    }
}
?>



<main class="pds-quote-page">
    <?php if ( $prefill ) : ?>
<script>window.pdsPrefill = <?php echo wp_json_encode( $prefill ); ?>;</script>
<?php endif; ?>

    <div class="breadcrumb-bar">
        <nav class="breadcrumb" aria-label="Breadcrumb">
          <ol>
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-house"></i>Home</a></li>
            <li aria-current="page">Get a Quote</li>
          </ol>
        </nav>
     </div>
    <!-- ============ HERO ============ -->
    <section class="pds-quote-hero">
        <div class="pds-container pds-quote-hero-inner">
            <div>
                <span class="pds-quote-eyebrow">Free &amp; No-Obligation</span>
                <h1>Get your free quote — <em>built for how you live.</em></h1>
                <p class="pds-hero-sub">
                    Tell us a little about your project and one of our team will put together
                    a clear, itemised quote for your portable home, studio, or extra space —
                    tailored to your site here in WA.
                </p>

                <div class="pds-hero-badges">
                    <span class="pds-hero-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Fast, personal response
                    </span>
                    <span class="pds-hero-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Built &amp; delivered across WA
                    </span>
                    <span class="pds-hero-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        No pushy sales calls
                    </span>
                </div>
            </div>

            <div class="pds-quote-hero-art">
                <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/07/IMG_4561.webp"
                     alt="Perth Dynamic Solutions portable home on site" loading="lazy" />
                <div class="pds-hero-float-card">
                    <span class="pds-icon-circle">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </span>
                    <div>
                        <strong>Locally built</strong>
                        <span>Designed &amp; assembled in Western Australia</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ PROCESS STRIP ============ -->
    <section class="pds-process">
        <div class="pds-container pds-process-grid">
            <div class="pds-process-card">
                <span class="pds-process-num">01</span>
                <h3>Tell us about your project</h3>
                <p>Fill in the form below with your site and product details — it only takes a couple of minutes.</p>
            </div>
            <div class="pds-process-card">
                <span class="pds-process-num">02</span>
                <h3>We put together your quote</h3>
                <p>Our team reviews your requirements and prepares a clear, itemised quote suited to your site.</p>
            </div>
            <div class="pds-process-card">
                <span class="pds-process-num">03</span>
                <h3>We're in touch, promptly</h3>
                <p>You'll hear back from a real person — no automated sales chasing, no hidden costs.</p>
            </div>
        </div>
    </section>

    <!-- ============ FORM SECTION ============ -->
    <section class="pds-quote-form-section">
        <div class="pds-container">
            <div class="pds-form-shell">

                <div class="pds-form-side">
                    <div>
                        <h2>Why request through Perth Dynamic Solutions?</h2>
                        <p>We keep it simple — one form, one point of contact, and a quote that actually reflects your site.</p>

                        <ul class="pds-form-side-list">
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                Itemised, transparent pricing — no hidden extras
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                Delivery &amp; siting guidance specific to WA councils
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                Flexible finance options available on request
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                Your details are never sold or shared
                            </li>
                        </ul>
                    </div>

                    <div class="pds-form-side-contact">
                        <p style="margin-bottom:0.4rem;">Prefer to talk it through?</p>
                        <a href="tel:+61 451 113 007">Call our team directly →</a>
                    </div>
                </div>

                <div class="pds-form-main">
                    <div class="pds-form-main-head">
                        
                        <h3>Request your quote</h3>
                        <p>Fields marked required only take a minute — the more detail you give us, the more accurate your quote.</p>
                    </div>

                    <?php
                    // Fluent Forms Pro — form ID 5
                    if ( shortcode_exists( 'fluentform' ) ) {
                        echo do_shortcode( '[fluentform id="5"]' );
                    } else {
                        echo '<div class="pds-form-fallback">Quote form is temporarily unavailable — please call us directly or try again shortly.</div>';
                    }
                    ?>
                </div>

            </div>
        </div>
    </section>

    <!-- ============ WHY PDS ============ -->
    <section class="pds-why">
        <div class="pds-container">
            <div class="pds-why-head">
                <h2>Straightforward, from first enquiry to move-in</h2>
                <p>We've built our process around removing the guesswork — here's what you can expect working with us.</p>
            </div>

            <div class="pds-why-grid">
                <div class="pds-why-card">
                    <span class="pds-why-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </span>
                    <h3>Prompt turnaround</h3>
                    <p>Detailed, itemised quotes without the back-and-forth.</p>
                </div>
                <div class="pds-why-card">
                    <span class="pds-why-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-6 9 6v11a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Z"/><path d="M9 22V12h6v10"/></svg>
                    </span>
                    <h3>Delivered ready to use</h3>
                    <p>Minimal on-site work — your space is ready fast.</p>
                </div>
                <div class="pds-why-card">
                    <span class="pds-why-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg>
                    </span>
                    <h3>Finance available</h3>
                    <p>Flexible options for eligible customers — ask us how.</p>
                </div>
                <div class="pds-why-card">
                    <span class="pds-why-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </span>
                    <h3>Built to last</h3>
                    <p>Quality construction backed by a solid structural warranty.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ CALL CTA ============ -->
    <section class="pds-call-cta">
        <div class="pds-container pds-call-cta-inner">
            <div>
                <h3>Prefer to speak with someone first?</h3>
                <p>Give our team a call — we're happy to talk through your options before you fill anything in.</p>
            </div>
            <a href="tel:+61 451 113 007" class="pds-call-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
                Call our team
            </a>
        </div>
    </section>

</main>

<?php
get_footer();