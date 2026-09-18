<?php
/* Template Name: Home */

get_header(); ?>


<main>
    <section class="hero">
        <video class="hero-video" autoplay muted loop playsinline preload="metadata" aria-hidden="true" role="presentation" poster="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/hero-poster.webp' ); ?>">
            <source src="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/hero.webm" type="video/webm">
            <source src="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/hero.mp4" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="wrap hero-content">
            <div>
                <div class="eyebrow">
                    Western Australia's trusted build &amp; solutions team
                </div>
                <h1 class="hero-h">Built Off-Site.<br><em class="accent">Lived-In Fast.</em></h1>
                <p class="lead">
                    Expandable, modular homes — delivered Australia-wide.
                </p>
                <div class="hero-ctas">
                    <a class="btn" href="#range">Browse our range</a>
                    <a class="btn ghost" href="#contact">Contact us</a>
                </div>
                <div class="hero-pdf">
                    <i class="fa-solid fa-file-pdf " style="    color: var(--leaf);"></i>
                    <a href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/docs/Perth Dynamics Brochure 2025.pdf' ); ?>" 
                       download="Perth Dynamics Brochure 2025.pdf">
                        Download our full brochure (PDF)
                    </a>
                </div>
            </div>
        </div>
        <div class="scroll-hint" onclick="
        document.querySelector('#range').scrollIntoView({ behavior: 'smooth' })
      ">
            <span class="scroll-text">Explore More</span>
            <div class="mouse-icon">
                <div class="mouse-wheel"></div>
            </div>
        </div>
    </section>
    
    <div class="badge-strip-hero">
        <div class="track">
            <span class="badge-chip">
                <span class="ic"><img
                        src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/icons/icon-home.webp' ); ?>"
                        width="40" height="40" alt="home-icon" srcset="" /></span>
                <b>CUSTOM BUILT</b>
            </span>
            <span class="badge-chip">
                <span class="ic"><img
                        src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/icons/DELIVERY.webp' ); ?>" width="40" height="40" alt="delivery-icon"
                        srcset="" /></span>
                <b>NATIONWIDE DELIVERY</b>
            </span>
            <span class="badge-chip">
                <span class="ic"><img
                        src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/icons/dollar-icon.webp' ); ?>"
                        width="40" height="40" alt="dollar-icon" srcset="" /></span>
                <b>EASY FINANCE</b>
            </span>
            <span class="badge-chip">
                <span class="ic"><img
                        src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/icons/tick-icon.webp' ); ?>"
                        width="40" height="40" alt="tick-icon" srcset="" /></span>
                <b>WARRANTY COVER</b>
            </span>
        </div>
    </div>
    
    <section style="padding-block-end: 0;">
        <div class="wrap">
            <!-- FB Reels Slider: FRONT PAGE ONLY -->
            <div class="reels-wrap">
                <div class="sec-eyebrow">Watch Our Customers</div>
                <div class="reels-slider" id="reelsSlider">
    
                    <div class="reel-slide">
                        <div class="reel-embed" style="aspect-ratio: 854 / 480;"
                             data-video-mp4="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video1-small.mp4"
                             data-video-webm="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video1-small.webm"
                             data-poster="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video1-poster.webp">
                            <div class="reel-placeholder" style="background-image:url('<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video1-poster.webp'); background-size:cover; background-position:center;">
                                <button class="reel-play-btn" aria-label="Play video">
                                    <i class="fa-solid fa-play"></i>
                                </button>
                            </div>
                        </div>
                        <a class="reel-fb-link" href="https://www.facebook.com/share/v/17ee3VW62h/" target="_blank" rel="noopener">
                            <i class="fa-brands fa-facebook-f"></i> View on Facebook
                        </a>
                    </div>
                    <div class="reel-slide">
                        <div class="reel-embed portrait" style="aspect-ratio: 480 / 854;"
                             data-video-mp4="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video3-small.mp4"
                             data-video-webm="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video3-small.webm"
                             data-poster="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video3-poster.webp">
                            <div class="reel-placeholder" style="background-image:url('<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video3-poster.webp'); background-size:cover; background-position:center;">
                                <button class="reel-play-btn" aria-label="Play video">
                                    <i class="fa-solid fa-play"></i>
                                </button>
                            </div>
                        </div>
                        <a class="reel-fb-link" href="https://www.facebook.com/share/r/1dSZFUipPq/" target="_blank" rel="noopener">
                            <i class="fa-brands fa-facebook-f"></i> View on Facebook
                        </a>
                    </div>
    
                    <div class="reel-slide">
                        <div class="reel-embed" style="aspect-ratio: 854 / 480;"
                             data-video-mp4="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video2-small.mp4"
                             data-video-webm="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video2-small.webm"
                             data-poster="<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video2-poster.webp">
                            <div class="reel-placeholder" style="background-image:url('<?php echo get_stylesheet_directory_uri(); ?>/assets/video/video2-poster.webp'); background-size:cover; background-position:center;">
                                <button class="reel-play-btn" aria-label="Play video">
                                    <i class="fa-solid fa-play"></i>
                                </button>
                            </div>
                        </div>
                        <a class="reel-fb-link" href="https://www.facebook.com/share/v/1BYPpUSQVN/" target="_blank" rel="noopener">
                            <i class="fa-brands fa-facebook-f"></i> View on Facebook
                        </a>
                    </div>
    
                   
    
                    <!-- Copy a block above for each additional reel; change href=...%2Freel%2FREEL_ID_HERE%2F... and set aspect-ratio to match that video's own width/height -->
    
                </div>
    
                <div class="reels-nav">
                    <button class="reel-arrow" id="reelPrev" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="reel-arrow" id="reelNext" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    <section id="range">
        <div class="wrap">
            <div class="sec-head">
                <div class="sec-eyebrow">Our Range</div>
                <h2 class="sec-h">A solution for every property &amp; budget.</h2>
                <p class="sec-sub">
                    From small backyard builds to full-scale projects — every job is
                    planned, costed and delivered with the same WA-tested standard.
                </p>
            </div>
            <div class="range-grid">
                <div class="range-card">
                    <a href="/expande">
                        <div class="swatch" style="background: linear-gradient(135deg, #f6b97a, #f08a2e)">
                            <span class="tag">Most popular</span>
                            <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/09/Expanders.webp"
                                loading="lazy" alt="Expanders" srcset="" />
                        </div>
                        <div class="body">
                            <h3>Expanders</h3>
                            <p>Flexible, ready-to-place builds for growing households.</p>
                            <div class="price">From <b>$26,000</b></div>
                            <span class="explore">Explore range →</span>
                        </div>
                    </a>
                </div>
                <div class="range-card">
                    <a href="/studio">
                        <div class="swatch" style="background: linear-gradient(135deg, #9bc97a, #6fa84b)">
                            <span class="tag">Self-contained</span>
                            <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/09/cabinspods.webp"
                                loading="lazy" alt="Studios" srcset="" />
                        </div>
                        <div class="body">
                            <h3>Studios</h3>
                            <p>
                                Pre-built cabin and pod range — granny flats, guest quarters and
                                rental-ready backyard studios.
                            </p>
                            <div class="price">From <b>$19,000</b></div>
                            <span class="explore">Explore range →</span>
                        </div>
                    </a>
                </div>
                <div class="range-card">
                    <a href="/class-1a">
                        <div class="swatch" style="background: linear-gradient(135deg, #9aa9cf, #16243f)">
                            <span class="tag">Fully compliant</span>
                            <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/09/70sqm-expander.webp"
                                loading="lazy" alt="Class 1a" srcset="" />
                        </div>
                        <div class="body">
                            <h3>Class 1a</h3>
                            <p>
                                Permanent, building-code compliant homes built to Class 1a standards for full-time living.
                            </p>
                            <div class="price">From <b>$23,000</b></div>
                            <span class="explore">Explore range →</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="diy-banner" id="diy">
            <div class="wrap">
                <div class="diy-row">
                    <div class="diy-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 11L12 4L20 11" stroke="#fff" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M6 10V19C6 19.5523 6.44772 20 7 20H10M18 10V19C18 19.5523 17.5523 20 17 20H14"
                                stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9 15.5L11 17.5L15 13" stroke="#fff" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="diy-content">
                        <div class="sec-eyebrow">Budget-Friendly Option</div>
                        <h2>Council approvable units, built your way.</h2>
                        <p>
                            We also offer council approvable units at an affordable price
                            through a DIY process — full support along the way, sizes to
                            match any block.
                        </p>
                        <div class="diy-size-parent">
                            <div class="diy-sizes">
                                <span>40 m²</span>
                                <span>60 m²</span>
                                <span>70 m²</span>
                                <span>80 m²</span>
                            </div>
                            <div class="diy-sizes">
                                <span>6m*2.3m</span>
                                <span>9m*2.3m</span>
                                <span>11.8m*2.3m</span>
                            </div>
                            <div class="diy-sizes">
                                <span>6m*4.5m</span>
                                <span>9m*4.5m</span>
                                <span>11.8m*4.5m</span>
                            </div>
                        </div>
                        <div class="diy-btn">
                            <a class="btn" href="https://perthdynamicsolutions.com.au/class-1a/"> learn more Class 1a  →</a>
                            <a class="btn" href="https://perthdynamicsolutions.com.au/half-expander"> learn more Half expanders  →</a>
                        </div>
                </div>
                </div>
            </div>
        </section>
        
<section class="why" id="why">
  <div class="wrap">
    <div>
      <div class="sec-eyebrow">Why Perth Dynamic Solutions</div>
      <h2 class="sec-h">Faster builds.<br>Better outcomes.</h2>
      <p class="sec-sub">
        Site builds blow budgets and timelines. Our process — plan,
        construct, deliver — built around WA conditions and WA schedules.
      </p>
      <div class="why-visual">
        <div class="why-img-card">
          <span class="why-tag">Craned onto site</span>
          <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/08/crane-lift.webp" width="1200" height="896" loading="lazy" alt="Crane lifting Perth Dynamic Solutions modular home onto site">
        </div>
        <div class="why-img-card">
          <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/08/crane-transport.webp" width="1200" height="896" loading="lazy" alt="Modular home unit in transit for delivery">
        </div>
      </div>
    </div>

    <div class="why-steps">
      <div class="why-step">
        <div class="num">01</div>
        <div>
          <h3>Turnkey delivery</h3>
          <p>Rolls onto site fully finished. Zero mess, zero waiting around.</p>
        </div>
      </div>
      <div class="why-step">
        <div class="num">02</div>
        <div>
          <h3>Standards-compliant by default</h3>
          <p>Meets Australian building codes, trades pre-wired and pre-plumbed.</p>
        </div>
      </div>
      <div class="why-step">
        <div class="num">03</div>
        <div>
          <h3>Built to adapt</h3>
          <p>Relocate, lease, or expand later — no lock-in to a single plan.</p>
        </div>
      </div>
    </div>
  </div>
</section>

    <section id="process">
        <div class="wrap">
            <div class="sec-head">
                <div class="sec-eyebrow">How It Works</div>
                <h2 class="sec-h">From quote to keys — fast.</h2>
            </div>
            <div class="process-grid">
                <div class="proc-card">
                    <div class="icon">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/steps/stepicon01.webp' ); ?>"
                            loading="lazy" alt="Select step icon" />
                    </div>
                    <h3>Select</h3>
                    <p>
                        Browse models, layouts, and options to find the right fit for your
                        needs.
                    </p>
                    <div class="step-top" style="background: linear-gradient(135deg, #f6b97a, #f08a2e)">
                        <span class="ribbon">STEP 1</span>
                    </div>
                </div>
                <div class="proc-card">
                    <div class="icon">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/steps/stepicon02.webp' ); ?>"
                            loading="lazy" alt="Personalise step icon" />
                    </div>
                    <h3>Personalise</h3>
                    <p>
                        Finalise layout, accessories, and site considerations with our team.
                    </p>
                    <div class="step-top" style="background: linear-gradient(135deg, #16243f, #0d1830)">
                        <span class="ribbon">STEP 2</span>
                    </div>
                </div>
                <div class="proc-card">
                    <div class="icon">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/steps/stepicon03.webp' ); ?>"
                            loading="lazy" alt="Confirm step icon" />
                    </div>
                    <h3>Confirm</h3>
                    <p>
                        Approve the final quote and place your order to lock in production.
                    </p>
                    <div class="step-top" style="background: linear-gradient(135deg, #9aa9cf, #16243f)">
                        <span class="ribbon">STEP 3</span>
                    </div>
                </div>
                <div class="proc-card">
                    <div class="icon">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/steps/stepicon04.webp' ); ?>"
                            loading="lazy" alt="Delivered and done step icon" />
                    </div>
                    <h3>Delivered &amp; done</h3>
                    <p>
                        Fully finished and delivered to site, ready for final connection.
                    </p>
                    <div class="step-top" style="background: linear-gradient(135deg, #9bc97a, #6fa84b)">
                        <span class="ribbon">STEP 4</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
<section class="reviews" id="reviews">
    <div class="wrap">
        <div class="sec-head">
            <div class="sec-eyebrow">Customer Reviews</div>

            <h2 class="sec-h">What people are saying about us.</h2>
        </div>

        <!-- Text reviews: show on ALL pages -->

        <div class="review-grid">
            <div class="review-card">
                <a href="https://www.facebook.com/share/p/18k8S7jfcy/" target="-blank">
                    <div class="stars">★★★★★</div>

                    <p>
                        "I did my research and looked at a few container homes in Perth and
                        the owner Hassan was super helpful. The homes he sells are of higher
                        quality than most and at a cheaper price. Hassan was super helpful
                        and the process from start to finish was so easy. I can't recommend
                        him enough. Thanks for all your help mate 👍"
                    </p>

                    <div class="reviewer">Jayke Wright. <span>Facebook</span></div>
                </a>
            </div>

            <div class="review-card">
                <a href="https://www.facebook.com/share/p/1DA5t8GUBG/" target="-blank">
                    <div class="stars">★★★★★</div>

                    <p>
                        "Hassan has been amazing to deal with, very quick turnaround and
                        able to answer all our questions. Best value and fast delivery,
                        can't wait to get it set up!"
                    </p>

                    <div class="reviewer">Jarred Hunter <span>Facebook</span></div>
                </a>
            </div>

            <div class="review-card">
                <a href="https://www.facebook.com/share/p/19a4EWhPSX/" target="-blank">
                    <div class="stars">★★★★★</div>

                    <p>"Easy to deal with, quick turnaround and great quality home!"</p>

                    <div class="reviewer">Shauni Humphries. <span>Facebook</span></div>
                </a>
            </div>

            <div class="review-card">
                <a href="https://www.facebook.com/share/p/1DCMemkyrF/" target="-blank">
                    <div class="stars">★★★★★</div>

                    <p>"Had no issue, fast responses and awesome communication!"</p>

                    <div class="reviewer">Hero Brown. <span>Facebook</span></div>
                </a>
            </div>

            <div class="review-card">
                <a href="https://www.facebook.com/share/p/1Dmo8XccdH/" target="-blank">
                    <div class="stars">★★★★★</div>

                    <p>
                        "Excellent quality, Great communication And honest guy who knows
                        what he is doing"
                    </p>

                    <div class="reviewer">Sean Neim . <span>Facebook</span></div>
                </a>
            </div>
        </div>
    </div>
</section>

    <section class="finance">
        <div class="wrap">
            <div>
                <h2>Own your modular home from $120 a week.</h2>
                <p>
                    Flexible finance available on every model, with fast approvals and
                    no-deposit options for eligible customers.
                </p>
            </div>
            <a class="btn" href="https://perthdynamicsolutions.com.au/finance/">Explore finance options</a>
        </div>
    </section>

    <section class="acc">
    <div class="wrap">
        <div class="sec-head">
            <div class="sec-eyebrow">Complete The Build</div>
            <h2 class="sec-h">Everything you need, in one place.</h2>
            <p class="sec-sub">
                <!--Solar, aircon, water tanks-->
                Pitched Roof , Veranda and trailers — we supply everything to make
                your build fully self-sufficient.
            </p>
        </div>
        <div class="acc-slider">
            <div class="acc-track" id="accTrack">
                
                <div class="acc-card">
                    <a href="https://perthdynamicsolutions.com.au/accessorie/" >
                        <div class="ic">
                            <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/07/Trailers.webp" loading="lazy" alt="Trailers" />
                        </div>
                        Trailers
                    </a>
                </div>
                <div class="acc-card">
                    <a href="https://perthdynamicsolutions.com.au/accessorie/" >
                    <div class="ic">
                        <img src="	https://perthdynamicsolutions.com.au/wp-content/uploads/2026/08/70sqm-expander.webp" loading="lazy" alt="Aircon" />
                    </div>
                    Veranda
                    </a>
                </div>
                <div class="acc-card">
                    <a href="https://perthdynamicsolutions.com.au/accessorie/" >
                    <div class="ic">
                        <img src="	https://perthdynamicsolutions.com.au/wp-content/uploads/2026/08/pitched-roof.webp" loading="lazy" alt="Aircon" />
                    </div>
                    Pitched Roof
                    </a>
                </div>
                <!--<div class="acc-card">-->
                <!--    <a href="https://perthdynamicsolutions.com.au/accessorie/" >-->
                <!--    <div class="ic">-->
                <!--        <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/07/Ac.webp" loading="lazy" alt="Aircon" />-->
                <!--    </div>-->
                <!--    Aircon-->
                <!--    </a>-->
                <!--</div>-->
                <!--<div class="acc-card">-->
                <!--    <a href="https://perthdynamicsolutions.com.au/accessorie/" >-->
                <!--    <div class="ic">-->
                <!--        <img src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/07/water-filter.webp" loading="lazy" alt=" Water-system" />-->
                <!--    </div>-->
                <!--    Water-->
                <!--    </a>-->
                <!--</div>-->
            </div>
            <button class="acc-nav prev" id="accPrev" aria-label="Previous">‹</button>
            <button class="acc-nav next" id="accNext" aria-label="Next">›</button>
        </div>
        <div class="acc-dots" id="accDots"></div>
    </div>
</section>
</main>

<script>
    const accTrack = document.getElementById("accTrack");
    const accPrev = document.getElementById("accPrev");
    const accNext = document.getElementById("accNext");
    const accDotsWrap = document.getElementById("accDots");
    const accCards = Array.from(accTrack.querySelectorAll(".acc-card"));
    let accIndex = 0;

    accCards.forEach((_, i) => {
        const dot = document.createElement("button");
        dot.className = "acc-dot" + (i === 0 ? " active" : "");
        dot.setAttribute("aria-label", "Go to slide " + (i + 1));
        dot.addEventListener("click", () => {
            accGoTo(i);
            restartAutoplay();
        });
        accDotsWrap.appendChild(dot);
    });
    const accDots = Array.from(accDotsWrap.querySelectorAll(".acc-dot"));

    function accUpdateDots() {
        accDots.forEach((d, i) => d.classList.toggle("active", i === accIndex));
    }
    function accMaxScroll() {
        return accTrack.scrollWidth - accTrack.clientWidth;
    }
    function accGoTo(i) {
        accIndex = (i + accCards.length) % accCards.length;
        const target = Math.min(
            accCards[accIndex].offsetLeft - accTrack.offsetLeft,
            accMaxScroll(),
        );
        accTrack.scrollTo({ left: Math.max(target, 0), behavior: "smooth" });
        accUpdateDots();
    }
    function accGoNext() {
        const maxScroll = accMaxScroll();
        if (accTrack.scrollLeft >= maxScroll - 2) {
            accIndex = 0;
            accTrack.scrollTo({ left: 0, behavior: "smooth" });
            accUpdateDots();
        } else {
            accGoTo(accIndex + 1);
        }
    }
    function accGoPrev() {
        if (accTrack.scrollLeft <= 2) {
            accIndex = accCards.length - 1;
            accTrack.scrollTo({ left: accMaxScroll(), behavior: "smooth" });
            accUpdateDots();
        } else {
            accGoTo(accIndex - 1);
        }
    }

    accPrev.addEventListener("click", () => {
        accGoPrev();
        restartAutoplay();
    });
    accNext.addEventListener("click", () => {
        accGoNext();
        restartAutoplay();
    });

    let accAutoplay = setInterval(accGoNext, 3000);
    function restartAutoplay() {
        clearInterval(accAutoplay);
        accAutoplay = setInterval(accGoNext, 3000);
    }
    const accSlider = document.querySelector(".acc-slider");
    accSlider.addEventListener("mouseenter", () => clearInterval(accAutoplay));
    accSlider.addEventListener("mouseleave", () => restartAutoplay());
    accSlider.addEventListener("touchstart", () => clearInterval(accAutoplay), {
        passive: true,
    });
    accSlider.addEventListener("touchend", () => restartAutoplay());

    accTrack.addEventListener(
        "wheel",
        (e) => {
            const delta =
                Math.abs(e.deltaY) > Math.abs(e.deltaX) ? e.deltaY : e.deltaX;
            const atStart = accTrack.scrollLeft <= 0;
            const atEnd = accTrack.scrollLeft >= accMaxScroll() - 1;

            if ((delta < 0 && atStart) || (delta > 0 && atEnd)) {
                return;
            }

            e.preventDefault();
            accTrack.scrollLeft += delta;
            clearInterval(accAutoplay);
            restartAutoplay();
        },
        { passive: false },
    );

    (function () {
            const prefersReduced = window.matchMedia(
                "(prefers-reduced-motion: reduce)",
            ).matches;
            const targets = document.querySelectorAll(
                [
                    ".sec-head",
                    ".range-card",
                    ".proc-card",
                    ".review-card",
                    ".acc-card",
                    ".why-step",
                    ".why-visual",
                    ".finance .wrap > div",
                    ".diy-banner .wrap > div",
                    ".foot-contact-box",
                ].join(","),
            );

            if (prefersReduced || !("IntersectionObserver" in window)) {
                return;
            }

            targets.forEach((el, i) => {
                el.classList.add("reveal");
                el.style.transitionDelay = (i % 6) * 70 + "ms";
            });

            const io = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("is-visible");
                            io.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.15, rootMargin: "0px 0px -40px 0px" },
            );

            targets.forEach((el) => io.observe(el));
        })();

    document.addEventListener('DOMContentLoaded', function () {

        var slider = document.getElementById('reelsSlider');

        if (!slider) return;

        // Click-to-load: video only injected when user taps play
        slider.querySelectorAll('.reel-embed').forEach(function (embed) {

            var placeholder = embed.querySelector('.reel-placeholder');

            if (!placeholder) return;

            placeholder.addEventListener('click', function () {

                var mp4 = embed.getAttribute('data-video-mp4');
                var webm = embed.getAttribute('data-video-webm');

                if (!mp4) return;

                var video = document.createElement('video');
                video.controls = true;
                video.autoplay = true;
                video.playsInline = true;

                if (webm) {
                    var sourceWebm = document.createElement('source');
                    sourceWebm.src = webm;
                    sourceWebm.type = 'video/webm';
                    video.appendChild(sourceWebm);
                }

                var sourceMp4 = document.createElement('source');
                sourceMp4.src = mp4;
                sourceMp4.type = 'video/mp4';
                video.appendChild(sourceMp4);

                embed.innerHTML = '';
                embed.appendChild(video);

            }, { once: true });

        });

        // Arrow navigation — show one slide at a time
        var slides = Array.prototype.slice.call(slider.querySelectorAll('.reel-slide'));
        var prevBtn = document.getElementById('reelPrev');
        var nextBtn = document.getElementById('reelNext');
        var currentIndex = 0;

        function showSlide(index) {

            if (!slides.length) return;

            var leavingSlide = slides[currentIndex];
            if (leavingSlide) {
                var leavingVideo = leavingSlide.querySelector('video');
                if (leavingVideo) leavingVideo.pause();
            }

            currentIndex = (index + slides.length) % slides.length;

            slides.forEach(function (slide, i) {
                slide.classList.toggle('is-active', i === currentIndex);
            });

            var enteringSlide = slides[currentIndex];
            if (enteringSlide) {
                var enteringVideo = enteringSlide.querySelector('video');
                if (enteringVideo) enteringVideo.play().catch(function () {});
            }

        }

        showSlide(0);

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                showSlide(currentIndex - 1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                showSlide(currentIndex + 1);
            });
        }

    });
</script>


<?php get_footer(); ?>