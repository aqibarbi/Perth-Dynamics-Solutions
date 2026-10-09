<?php
/**
 * 404 page.
 * Path: child-theme/404.php
 *
 * WordPress loads this file automatically for any URL that doesn't exist,
 * and sends the real 404 status itself. Don't redirect from here.
 *
 * All CSS is inside the <style> block below, scoped with the .nf- prefix,
 * so it can't affect other pages. It uses the site's :root tokens.
 */

get_header();

// Quick links: icon (Font Awesome), label, URL.
// Check these slugs match the live pages.
$nf_links = array(
	array( 'fa-maximize',           'Expanders',   home_url( '/expanders/' ) ),
	array( 'fa-box',                'Studio',      home_url( '/studio/' ) ),
	array( 'fa-house-circle-check', 'Class 1A',    home_url( '/class-1a/' ) ),
	array( 'fa-warehouse',          'Spaces',      home_url( '/spaces/' ) ),
	array( 'fa-toolbox',            'Accessories', home_url( '/accessorie/' ) ),
	array( 'fa-dollar-sign',        'Finance',     home_url( '/finance/' ) ),
);
?>

<style>
  .nf-page {
    /* distance from the top so the fixed header doesn't cover the content.
       Same value the other pages use for the breadcrumb bar. */
    --nf-header-offset: 156px;

    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    min-height: 70vh;
    padding: calc(var(--nf-header-offset) + 2rem) 1.5rem clamp(4rem, 8vw, 6rem);
    background: var(--grad-warm-soft);
  }

  /* soft blurred circles, same as the FAQ section */
  .nf-page::before {
    content: "";
    position: absolute;
    top: -120px;
    right: -120px;
    width: 380px;
    height: 380px;
    border-radius: 50%;
    background: var(--grad-orange-leaf);
    opacity: 0.14;
    filter: blur(60px);
    pointer-events: none;
  }

  .nf-page::after {
    content: "";
    position: absolute;
    bottom: -140px;
    left: -140px;
    width: 420px;
    height: 420px;
    border-radius: 50%;
    background: var(--grad-navy-deep);
    opacity: 0.08;
    filter: blur(70px);
    pointer-events: none;
  }

  .nf-inner {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 960px;
    margin: 0 auto;
    text-align: center;
  }

  /* big 404, decorative (the h1 below says the same thing in words) */
  .nf-code {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: clamp(6rem, 22vw, 11rem);
    line-height: 1;
    letter-spacing: -0.04em;
    background: var(--grad-orange-leaf);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
  }

  .nf-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    margin: 0.5rem 0 14px;
    font-family: 'Inter', Arial, sans-serif;
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: 0.13em;
    text-transform: uppercase;
    color: var(--leaf-dark);
  }

  .nf-eyebrow::before {
    content: "";
    width: 22px;
    height: 2px;
    border-radius: 2px;
    background: currentColor;
  }

  .nf-page h1 {
    margin: 0 0 1rem;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: clamp(1.75rem, 4.5vw, 2.75rem);
    line-height: 1.1;
    letter-spacing: -0.02em;
    color: var(--navy-deep);
  }

  .nf-lead {
    max-width: 520px;
    margin: 0 auto;
    font-family: 'Inter', Arial, sans-serif;
    font-size: 1.05rem;
    line-height: 1.65;
    color: var(--ink-soft);
  }

  /* buttons */
  .nf-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 14px;
    margin-top: 2rem;
  }

  .nf-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 26px;
    border-radius: 999px;
    border: 1.5px solid var(--navy);
    font-family: 'Inter', Arial, sans-serif;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.01em;
    text-decoration: none;
    background: var(--navy);
    color: #fff;
    transition: transform 0.25s var(--ease), box-shadow 0.25s var(--ease), background 0.25s var(--ease), color 0.25s var(--ease);
  }

  .nf-btn:hover {
    background: var(--navy-deep);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: var(--shadow-navy);
  }

  .nf-btn.ghost {
    background: transparent;
    color: var(--navy);
  }

  .nf-btn.ghost:hover {
    background: var(--navy);
    color: #fff;
  }

  .nf-btn:focus-visible,
  .nf-card:focus-visible {
    outline: 2.5px solid var(--orange-deep);
    outline-offset: 3px;
  }

  /* quick links */
  .nf-sub {
    margin: 3rem 0 1rem;
    font-family: 'Inter', Arial, sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--ink-soft);
  }

  .nf-links {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin: 0;
    padding: 0;
    list-style: none;
    text-align: left;
  }

  .nf-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 1rem 2.75rem 1rem 1.15rem;
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    background: rgba(255, 254, 251, 0.72);
    -webkit-backdrop-filter: blur(14px);
    backdrop-filter: blur(14px);
    box-shadow: var(--shadow-sm);
    font-family: 'Inter', Arial, sans-serif;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    color: var(--ink);
    transition: box-shadow 0.35s var(--ease), transform 0.35s var(--ease), border-color 0.35s var(--ease);
  }

  .nf-card:hover {
    transform: translateY(-2px);
    border-color: rgba(243, 153, 90, 0.35);
    box-shadow: var(--shadow-md);
    color: var(--ink);
  }

  .nf-card::after {
    content: "\2192";
    position: absolute;
    right: 1.15rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--ink-soft);
    transition: transform 0.35s var(--ease-spring);
  }

  .nf-card:hover::after {
    transform: translate(4px, -50%);
  }

  .nf-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: var(--grad-orange-leaf);
    box-shadow: var(--shadow-glow-orange);
    color: #fff;
    font-size: 0.9rem;
  }

  @media (max-width: 768px) {
    .nf-links {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 576px) {
    .nf-page {
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .nf-btn {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 480px) {
    .nf-links {
      grid-template-columns: 1fr;
    }
  }

  @media (prefers-reduced-motion: reduce) {
    .nf-btn,
    .nf-card,
    .nf-card::after {
      transition: none;
    }
  }
</style>

<main id="main">
  <section class="nf-page" aria-labelledby="nf-title">
    <div class="nf-inner">
      <p class="nf-code" aria-hidden="true">404</p>
      <div class="nf-eyebrow">Page not found</div>
      <h1 id="nf-title">We can't find that page.</h1>
      <p class="nf-lead">
        The link may be broken, or the page may have moved. Head back home or try one of our main pages below.
      </p>

      <div class="nf-actions">
        <a class="nf-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Home</a>
        <a class="nf-btn ghost" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Us</a>
      </div>

      <p class="nf-sub">Popular pages</p>
      <ul class="nf-links">
        <?php foreach ( $nf_links as $nf_link ) : ?>
          <li>
            <a class="nf-card" href="<?php echo esc_url( $nf_link[2] ); ?>">
              <span class="nf-icon" aria-hidden="true"><i class="fas <?php echo esc_attr( $nf_link[0] ); ?>"></i></span>
              <?php echo esc_html( $nf_link[1] ); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>
</main>

<?php get_footer(); ?>