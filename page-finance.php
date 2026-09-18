<?php
/**
 * Template Name: Finance Page
 * Description: Custom finance page — Credit One embedded quick-quote form + links.
 * File: page-finance.php — auto-applies to page with slug "finance",
 * or select manually via Page Attributes > Template in wp-admin.
 */

get_header();
?>

<section class="fin-page">


    <div class="fin-wrap">

        <section class="fin-hero">
            <div>
                <span class="fin-eyebrow">FINANCE</span>
                <h1>Own it sooner, <em>finance made simple.</em></h1>
                <p>Flexible finance on every model, arranged through Credit One — fast approvals and no-deposit options
                    for eligible customers.</p>
                <p class="fin-trust">★★★★★ <strong>3,000+</strong> five-star Google reviews for Credit One</p>
            </div>
            <div class="fin-spec">
                <div class="fin-spec-label">TYPICAL APPROVAL TIME</div>
                <p class="fin-spec-num">24<span>-48 hours</span></p>
                <p class="fin-spec-sub">Checking your quote doesn't affect your credit score.</p>
                <div class="fin-spec-divider"></div>
                <div class="fin-spec-row"><span>Lender panel</span><strong>40+</strong></div>
            </div>
        </section>

        <section class="fin-strip" aria-label="Finance benefits">
            <div class="fin-strip-item">
                <h2>Fast approval</h2>
                <p>Most decisions land within 24-48 hours.</p>
            </div>
            <div class="fin-strip-item">
                <h2>Built for your budget</h2>
                <p>Rates compared across Credit One's lender panel.</p>
            </div>
            <div class="fin-strip-item">
                <h2>Australia-wide</h2>
                <p>Same options wherever your site is — not limited to WA.</p>
            </div>
        </section>

        <section class="fin-panel" aria-label="Quick quote form">
            <div class="fin-panel-grid">
                <div class="fin-panel-copy">
                    <h2>Why apply through us</h2>
                    <p>Arranged directly with Credit One — quote and apply without leaving the page.</p>
                    <ul>
                        <li>Decision within 24-48 hours</li>
                        <li>No impact on your credit score to check</li>
                        <li>Rates compared across 40+ lenders</li>
                    </ul>
                </div>
                <div class="fin-form-shell">
                    <div id="coau-embedded-form"></div>
                    <script src="https://www.creditone.com.au/embedded/quick-quote/base/script.js" data-step="form"
                        data-dealer="Perth Dynamic Solutions" data-customstyle="no" data-customscript="no"
                        data-utmsource="dealer" data-utmmedium="referral" data-utmcampaign="dealer_application_form"
                        data-utmkeywords="quick-quote"></script>
                </div>
            </div>
        </section>

        <nav class="fin-links" aria-label="Quick finance links">
            <a class="fin-link-btn"
                href="https://www.creditone.com.au/quick-quote?dealer_name=perth-dynamic-solutions&utm_source=dealer&utm_medium=referral&utm_campaign=dealer_application_form&utm_keywords=quick-quote"
                target="_blank" rel="noopener">
                <span class="fin-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M14 3v5a1 1 0 0 0 1 1h5" />
                        <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z" />
                        <path d="M9 13h6M9 17h4" />
                    </svg>
                </span>
                <span>
                    <span class="label">Apply now</span>
                    <span class="title">Start your loan application →</span>
                </span>
            </a>
            <a class="fin-link-btn"
                href="https://www.creditone.com.au/dealer-loan-calculator/loan/perth-dynamic-solutions?utm_source=dealer&utm_medium=referral&utm_campaign=dealer_online_calculator"
                target="_blank" rel="noopener">
                <span class="fin-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="4" y="2" width="16" height="20" rx="2" />
                        <path d="M8 6h8M8 11h1M12 11h1M16 11h1M8 15h1M12 15h1M16 15h1M8 19h1M12 19h1" />
                    </svg>
                </span>
                <span>
                    <span class="label">Plan ahead</span>
                    <span class="title">Estimate your repayments →</span>
                </span>
            </a>
        </nav>

        <footer class="fin-print">
            <p>Finance is arranged by Credit One Equipment Finance (trading as Credit One), Australian Credit Licence
                Number 390376, ABN 83 135 940 813. Perth Dynamic Solutions is not the credit provider. Terms and
                approval subject to Credit One's lending criteria.</p>
        </footer>

    </div>
</section>

<?php get_footer(); ?>