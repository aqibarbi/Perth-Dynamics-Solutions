<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php wp_title('—', true, 'right'); ?>
        <?php bloginfo('name'); ?>
    </title>
    <script src="https://kit.fontawesome.com/443a3bd244.js" crossorigin="anonymous"></script>
    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <header class="site" id="siteHeader">
                <div class="badge-strip" aria-hidden="true">
                    <div class="track" id="marqueeTrack">
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-home.webp" width="36" height="36" alt="Home-icone" /><b>WA'S BEST RANGE OF PORTABLE HOMES</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-shipping.webp" width="36" height="36" alt="Shipping-icone" /><b>9–10 WEEKS LEAD TIME</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/dollar-icon.webp" width="36" height="36" alt="Dollar-icone" /><b>EASY FINANCE</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/tick-icon.webp" width="36" height="36" alt="Trick-icone" /><b>WARRANTY COVER</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-home.webp" width="36" height="36" alt="Home-icone" /><b>WA'S BEST RANGE OF PORTABLE HOMES</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-shipping.webp" width="36" height="36" alt="Shipping-icone" /><b>9–10 WEEKS LEAD TIME</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/dollar-icon.webp" width="36" height="36" alt="Dollar-icone" /><b>EASY FINANCE</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/tick-icon.webp" width="36" height="36" alt="Trick-icone" /><b>WARRANTY COVER</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-home.webp" width="36" height="36" alt="Home-icone" /><b>WA'S BEST RANGE OF PORTABLE HOMES</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-shipping.webp" width="36" height="36" alt="Shipping-icone" /><b>9–10 WEEKS LEAD TIME</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/dollar-icon.webp" width="36" height="36" alt="Dollar-icone" /><b>EASY FINANCE</b></span>
                        <span><img class="top-marquee-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/tick-icon.webp" width="36" height="36" alt="Trick-icone" /><b>WARRANTY COVER</b></span>
                    </div>
                </div>
        <div class="container">
            <div class="nav-row">
                <a class="brand" href="<?php echo home_url('/'); ?>">
                    <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/09/logo-1-e1788860766967.webp"
                        alt="Perth Dynamic Solutions logo" />
                    <div class="brand-name">
                        <div class="name">PERTH DYNAMIC<br />SOLUTIONS</div>
                        <div class="tag">Tried &amp; tested by WA</div>
                    </div>
                </a>
                <nav class="links">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '<ul class="menu-list">%3$s</ul>',
                        'walker'         => new PDS_Nav_Walker(),
                        'fallback_cb'    => false,
                    ) );
                    ?>
                    
                </nav>
                <div class="nav-cta">
                    <a href="https://www.facebook.com/share/1EYShnVevn/?mibextid=wwXIfr" target="_blank" rel="noopener" aria-label="Facebook"><b>facebook</b> </a>
                    <a class="btn" href="https://m.me/61586353940439">Book an appointment</a>
                    <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu" aria-expanded="false">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="mobile-panel" id="mobilePanel">
            <nav >
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '<ul class="menu-list">%3$s</ul>',
                        'walker'         => new PDS_Nav_Walker(),
                        'fallback_cb'    => false,
                    ) );
                    ?>
                    
                </nav>
            
        </div>
    </header>

    <!-- Scroll to top -->
    <div class="progress-circle-wrap" id="progressWrap" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <div class="progress-ring" id="progressRing">
            <div class="progress-inner">↑</div>
        </div>
    </div>
    
    <script>
    window.addEventListener("scroll", () => {
        const scrollTop = window.scrollY;
        const docHeight =
            document.documentElement.scrollHeight - window.innerHeight;
        const progress = (scrollTop / docHeight) * 100;
        const wrap = document.getElementById("progressWrap");

        document.getElementById("progressRing").style.background =
            `conic-gradient(#ff7a18 ${progress}%, #eee ${progress}%)`;

        if (scrollTop > 50) {
            wrap.classList.add("show");
        } else {
            wrap.classList.remove("show");
        }
    });
    

        // mobile menu toggle
        const headerEl = document.getElementById("siteHeader");
        const menuToggle = document.getElementById("menuToggle");
        menuToggle.addEventListener("click", () => {
            const isOpen = headerEl.classList.toggle("open");
            menuToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
        });
        document
            .getElementById("mobilePanel")
            .querySelectorAll("a")
            .forEach((a) => {
                a.addEventListener("click", () => {
                    headerEl.classList.remove("open");
                    menuToggle.setAttribute("aria-expanded", "false");
                });
            });
    </script>