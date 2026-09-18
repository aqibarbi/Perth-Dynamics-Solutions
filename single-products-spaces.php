<?php
/**
 * Template: single-products-spaces.php
 */

get_header();

$post_id = get_the_ID();

/* ---------- common fields (group_pds_common_fields / group_spaces_extra_fields) ---------- */
$name           = get_the_title() ?: 'Studio Space';
$spaces_type    = get_field('spaces_type') ?: 'static';
$footprint      = get_field('footprint') ?: '20ft';
$tagline        = get_field('short_description') ?: "An open-plan shell in a {$footprint} footprint — no kitchen, no bathroom, no internal walls. Insulated and pre-wired, delivered ready to place.";
$badge_tag      = get_field('badge_tag') ?: "{$footprint} · " . ($spaces_type === 'expandable' ? 'Expandable' : 'Static');
$size_sqm       = get_field('floor_area') ?: '13.3m²';
$ceiling_height_field = get_field('ceiling_height') ?: '2.4 m';
$dimensions     = get_field('dimensions');
$price_from     = (int) (get_field('price') ?: 19900);
$finance_weekly = get_field('weekly_price') ?: 108;
$floor_plan_img = get_field('floor_plan');

$gallery = get_field('gallery');
if (empty($gallery)) $gallery = [];

function exp_file_url($file) {
    if (is_array($file)) return $file['url'] ?? '#';
    return $file ?: '#';
}
function exp_img_url($image, $size = 'medium') {
    if (empty($image)) return null;
    if (is_string($image)) return $image;
    return $image['sizes'][$size] ?? ($image['url'] ?? null);
}

/* ---------- ceiling options ----------
   1 row = fixed ceiling height (no toggle rendered).
   2+ rows = toggle, first row's price is the base $price_from. */
$ceiling_options = [];
if (have_rows('ceiling_options', $post_id)) {
    while (have_rows('ceiling_options', $post_id)) { the_row();
        $ceiling_options[] = [
            'label' => get_sub_field('label'),
            'delta' => (int) get_sub_field('delta'),
        ];
    }
}
if (empty($ceiling_options)) {
    $ceiling_options = [
        ['label' => '2.2m ceiling', 'delta' => 0],
        ['label' => '2.4m ceiling', 'delta' => 5000],
    ];
}

/* ---------- at-a-glance bullets ---------- */
$glance_items = [];
if (have_rows('glance_items', $post_id)) { while (have_rows('glance_items', $post_id)) { the_row(); $glance_items[] = get_sub_field('text'); } }
if (empty($glance_items)) {
    $glance_items = [
        'Premium steel frame with epoxy protective coating',
        'Insulated sandwich panel construction throughout',
        'Double-glazed aluminium windows with flyscreens',
        'Pre-wired to AS/NZS 3000/3001, main distribution board fitted',
        'Premium engineered timber-look flooring',
        'LED ceiling lighting throughout',
        'Full-height sliding glass entry door',
    ];
}

/* ---------- open-plan feature bullets (Layout section) ---------- */
$feature_bullets = [];
if (have_rows('feature_bullets', $post_id)) { while (have_rows('feature_bullets', $post_id)) { the_row(); $feature_bullets[] = get_sub_field('text'); } }
if (empty($feature_bullets)) {
    $feature_bullets = [
        'Blank-canvas interior — layout it your way',
        'Large windows, max daylight',
        'Full-height glass sliding entry',
        'Electrical pre-wired, fit-out ready',
    ];
}

/* ---------- interior overview gallery ---------- */
$overview_repeater = get_field('overview_gallery', $post_id);
if (!empty($overview_repeater)) {
    $overview_gallery = $overview_repeater;
} else {
    $gallery_labels_fallback = ['Interior — sliding door view', 'Interior — open plan view'];
    $overview_gallery = [];
    foreach (array_slice($gallery, 1, 2) as $gi => $img) {
        $overview_gallery[] = ['image' => $img, 'label' => $img['caption'] ?: ($gallery_labels_fallback[$gi] ?? '')];
    }
}

/* ---------- one shell, many fits (use cases) ---------- */
$use_cases = [];
if (have_rows('use_cases', $post_id)) { while (have_rows('use_cases', $post_id)) { the_row();
    $use_cases[] = ['title' => get_sub_field('title'), 'description' => get_sub_field('description')];
} }
if (empty($use_cases)) {
    $use_cases = [
        ['title' => 'Home office', 'description' => 'A quiet, lockable workspace separate from the main house — finished and ready to occupy.'],
        ['title' => 'Music room', 'description' => 'Insulated walls and ceiling help contain sound — set up for practice, lessons, or home recording.'],
        ['title' => 'Workshop', 'description' => 'Pre-wired open floor for tools, benches, and projects that need their own dedicated space.'],
        ['title' => 'Art studio', 'description' => 'Bright, open floor for painting, sculpture, photography or any creative practice that needs room.'],
        ['title' => 'Home gym', 'description' => 'A solid, clear-span floor that takes racks, mats and equipment — no internal walls to plan around.'],
        ['title' => 'Guest room', 'description' => 'Self-contained accommodation for visitors without giving up a room inside the main house.'],
    ];
}

/* ---------- spec groups ---------- */
$spec_groups = [];
if (have_rows('spec_groups', $post_id)) {
    while (have_rows('spec_groups', $post_id)) { the_row();
        $rows = [];
        if (have_rows('rows')) { while (have_rows('rows')) { the_row(); $rows[] = [get_sub_field('spec_key'), get_sub_field('spec_value')]; } }
        $spec_groups[] = ['group'=>get_sub_field('group_name'),'rows'=>$rows];
    }
}
if (empty($spec_groups)) {
    $spec_groups = [
        ['group'=>'Dimensions & footprint', 'rows'=>[
            ['Floor area', $size_sqm],
            ['Footprint', $footprint . ($spaces_type === 'expandable' ? ' (site-expandable)' : ' (fixed size)')],
            ['Ceiling height', $ceiling_height_field],
            ['Dimensions', $dimensions ?: 'Get in touch for full internal/external measurements'],
            ['Layout', 'Open-plan throughout'],
        ]],
        ['group'=>'Frame & Structure', 'rows'=>[
            ['Beams & columns','Galvanised structural steel frame, built for transport and long-term load'],
            ['Purlins & keel','Galvanised steel purlins with matching floor chassis'],
            ['Floor','PVC vinyl or SPC hybrid finish on a fibre-cement subfloor — resists moisture, dampens sound'],
            ['Walls','Insulated sandwich-panel walls, bamboo fibreboard lining available as upgrade'],
        ]],
        ['group'=>'Insulation, Doors & Glazing', 'rows'=>[
            ['Roof','Insulated sandwich-panel roof'],
            ['Insulation','Insulated panels thermal performance year-round on its own'],
            ['Entrance door','Thermally broken aluminium sliding/swing door, toughened double glazing, UV tint'],
            ['Windows','Thermally broken aluminium awning windows, toughened double glazing, UV tint'],
        ]],
        ['group'=>'Electrical', 'rows'=>[
            ['Lighting','Downlights, surface or recessed, throughout'],
            ['Wiring','Certified copper cabling — main feed, dedicated AC circuit, general power'],
            ['Main switch','Heavy-duty isolator with dedicated lockout'],
            ['Circuit protection','RCD/MCB on every circuit'],
            ['Sockets','Double-pole powerpoints, built-in USB-A/USB-C'],
        ]],
    ];
}

/* ---------- downloads ---------- */
$downloads = [];
if (have_rows('downloads', $post_id)) { while (have_rows('downloads', $post_id)) { the_row(); $downloads[] = ['title'=>get_sub_field('title'),'note'=>get_sub_field('note'),'file'=>exp_file_url(get_sub_field('file'))]; } }
if (empty($downloads)) {
    $downloads = [
        ['title'=>$name.' Floor Plans', 'note'=>'PDF · Dimensions & technical drawings', 'file'=>exp_file_url($floor_plan_img)],
    ];
}
?>



<div class="exp-page">

    <div class="breadcrumb-bar">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <ol>
                <li><a href="/"><i class="fas fa-house"></i>Home</a></li>
                <li><a href="/spaces">Spaces</a></li>
                <li aria-current="page">
                    <?php echo esc_html($name); ?>
                </li>
            </ol>
        </nav>
    </div>

    <section class="hero">
        <div class="hero-bg">
            <?php if (!empty($gallery[0])): ?>
            <img src="<?php echo esc_url($gallery[0]['sizes']['large'] ?? $gallery[0]['url']); ?>"
                alt="<?php echo esc_attr($name); ?> exterior">
            <?php else: ?>
            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1400&q=80"
                alt="<?php echo esc_attr($name); ?> exterior">
            <?php endif; ?>
        </div>
        <div class="hero-inner">
            <span class="hero-eyebrow reveal">
                <?php echo esc_html($badge_tag); ?>
            </span>
            <h1 class="hero-title reveal">
                <?php echo esc_html($name); ?>
            </h1>
            <p class="hero-tagline reveal">
                <?php echo esc_html($tagline); ?>
            </p>
            
        </div>
    </section>
     <section>
        <div class="hero-ticket reveal">
            <div class="hero-ticket-price">
                <div class="hero-price-label">Starting from</div>
                <div class="hero-price">$
                    <?php echo esc_html(number_format($price_from)); ?>
                </div>
            </div>
            <div class="hero-ticket-stats">
                <div class="hero-stat">
                    <div class="hero-stat-value">
                        <?php echo esc_html($size_sqm); ?>
                    </div>
                    <div class="hero-stat-label">Floor area</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">
                        <?php echo esc_html($footprint); ?>
                    </div>
                    <div class="hero-stat-label">Footprint</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">Open</div>
                    <div class="hero-stat-label">Floor plan</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">
                        <?php echo esc_html($spaces_type === 'expandable' ? 'Optional' : 'Static'); ?>
                    </div>
                    <div class="hero-stat-label">
                        <?php echo esc_html($spaces_type === 'expandable' ? 'Cladding' : 'Non-expanding'); ?>
                    </div>
                </div>
            </div>
        </div>
     </section>

    <nav class="section-nav" id="sectionNav" aria-label="Product sections">
        <div class="section-nav-inner">
            <a class="section-nav-link is-active" href="#overview" data-target="overview">Overview</a>
            <a class="section-nav-link" href="#layout" data-target="layout">Floor plan</a>
            <a class="section-nav-link" href="#glance" data-target="glance">At a glance</a>
            <a class="section-nav-link" href="#usecases" data-target="usecases">Use cases</a>
            <a class="section-nav-link" href="#specs" data-target="specs">Specifications</a>
            <a class="section-nav-link" href="#downloads" data-target="downloads">Plans</a>
        </div>
    </nav>

    <section class="section" id="overview">
        <div class="container">
            <div class="eyebrow reveal">Overview</div>
            <div class="intro-head reveal">
                <h2>A clean, open shell — yours to make your own.</h2>
                <p>Arrives fully built —
                    <?php echo esc_html($size_sqm); ?> of bright, uninterrupted floor space ready for a home office,
                    studio or workshop fit-out. Insulated, lined, lit and pre-wired, with no internal walls, plumbing
                    or fixed-purpose rooms in the way.
                </p>
            </div>
            <div class="gallery-grid reveal" id="overview-gallery">
                <?php if (!empty($overview_gallery)): foreach ($overview_gallery as $row):
                $img = $row['image'];
                if (empty($img)) continue;
                $full_url = $img['sizes']['large'] ?? $img['url'];
                $thumb_url = $img['sizes']['medium'] ?? $img['url'];
                $label = $row['label'];
            ?>
                <div class="gallery-item" data-lightbox-src="<?php echo esc_url($full_url); ?>"
                    data-lightbox-caption="<?php echo esc_attr($label); ?>">
                    <div class="ph-photo">
                        <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($label); ?>">
                        <?php if ($label): ?><span class="gallery-caption">
                            <?php echo esc_html($label); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; else:
                $placeholder_labels = ['Interior — sliding door view', 'Interior — open plan view'];
                foreach ($placeholder_labels as $label): ?>
                <div class="gallery-item">
                    <div class="ph-photo">Photo —
                        <?php echo esc_html(strtolower($label)); ?>.jpg
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </section>

    <section class="configurator section" id="layout">
        <div class="container">
            <div class="eyebrow reveal">Floor plan</div>
            <div class="config-head reveal">
                <h2>One open room. <?php echo esc_html($size_sqm); ?> to do whatever you want with.</h2>
            </div>
            <div class="layout-card reveal">
                <div class="layout-plan">
                    <img src="<?php echo esc_url(exp_file_url($floor_plan_img) !== '#' ? exp_file_url($floor_plan_img) : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=700&q=80'); ?>"
                        alt="<?php echo esc_attr($name); ?> floor plan">
                    <div class="layout-plan-cap">Floor plan</div>
                </div>
                <div>
                    <div class="layout-name">Open-plan layout — zero internal walls</div>
                    <?php if (!empty($feature_bullets)): ?>
                    <ul class="layout-bullets">
                        <?php foreach ($feature_bullets as $b): ?>
                        <li>
                            <?php echo esc_html($b); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <div class="layout-stats">
                        <div class="hero-stat">
                            <div class="layout-stat-value">
                                <?php echo esc_html($size_sqm); ?>
                            </div>
                            <div class="layout-stat-label">Floor area</div>
                        </div>
                        <div class="hero-stat">
                            <div class="layout-stat-value">
                                <?php echo esc_html($dimensions); ?>
                            </div>
                            <div class="layout-stat-label"><?php echo esc_html($spaces_type === 'expandable' ? 'External footprint' : 'External footprint'); ?></div>
                        </div>
                        <div class="hero-stat">
                            <div class="layout-stat-value">
                                <?php echo esc_html($ceiling_height_field); ?>
                            </div>
                            <div class="layout-stat-label">Ceiling height</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="glance">
        <div class="container">
            <div class="eyebrow reveal">At a glance</div>
            <div class="intro-head reveal" style="margin-bottom:30px;">
                <h2>Standard inclusions, not upsells.</h2>
            </div>
            <div class="glance-grid">
                <ul class="glance-list reveal">
                    <?php foreach ($glance_items as $gi): ?>
                    <li class="glance-item"><span class="glance-check"><svg viewBox="0 0 24 24">
                                <polyline points="4 12 10 18 20 6" />
                            </svg></span>
                        <?php echo esc_html($gi); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="ceiling-card reveal">
                    <h4>Ceiling height</h4>
                    <div class="ceiling-options" id="ceilingOptions">
                        <?php foreach ($ceiling_options as $i => $co): ?>
                        <button class="ceiling-option <?php echo $i===0?'is-active':''; ?>"
                            data-delta="<?php echo esc_attr($co['delta']); ?>"
                            data-label="<?php echo esc_attr($co['label']); ?>">
                            <?php echo esc_html($co['label']); ?> <span class="delta">
                                <?php echo $co['delta']>0 ? '+$'.esc_html(number_format($co['delta'])) : 'Included'; ?>
                            </span>
                        </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="buy-box">
                        <div class="buy-box-config" id="buyConfig">
                            <?php echo esc_html($ceiling_options[0]['label']); ?>
                        </div>
                        <div class="buy-box-price" id="buyPrice">$
                            <?php echo esc_html(number_format($price_from)); ?>
                        </div>
                        <div class="buy-box-finance">or from <strong>$
                                <?php echo esc_html($finance_weekly); ?>
                            </strong>/week with finance · <a href="#">Learn more</a></div>
                        <div class="buy-box-actions">
                            <a href="https://m.me/61586353940439" class="btn btn-primary" id="cta-quote-buybox">Book an appointment</a>
                            <a href="#" class="btn btn-outline-navy">Finance options</a>
                        </div>
                    </div>
                    <div class="trust-row">
                        <span class="trust-item">2-years warranty</span>
                        <span class="trust-item">Pre-built & delivered</span>
                        <span class="trust-item">AS/NZS compliant</span>
                        <span class="trust-item">Fast lead times</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="usecases">
        <div class="container">
            <div class="eyebrow reveal">Built for work, made for craft</div>
            <div class="intro-head reveal" style="margin-bottom:30px;">
                <h2>One shell, many fits.</h2>
                <p>An insulated, pre-wired, open-plan space — no kitchen or bathroom to design around. A few of the
                    briefs we build for most often:</p>
            </div>
            <div class="usecase-list reveal">
                <?php foreach ($use_cases as $i => $uc): ?>
                <div class="usecase-row">
                    <div class="usecase-num"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></div>
                    <div class="usecase-body">
                        <h4>
                            <?php echo esc_html($uc['title']); ?>
                        </h4>
                        <p>
                            <?php echo esc_html($uc['description']); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" id="specs">
        <div class="container">
            <div class="eyebrow reveal">Specifications</div>
            <div class="intro-head reveal" style="margin-bottom:10px;">
                <h2>Specs and materials.</h2>
                <p>Measurements, weight, and material notes for checking site fit before you order.</p>
            </div>
            <div class="spec-groups reveal">
                <?php foreach ($spec_groups as $i => $g): ?>
                <details class="spec-group" <?php echo $i===0?'open':''; ?>>
                    <summary>
                        <h4>
                            <?php echo esc_html($g['group']); ?>
                        </h4>
                    </summary>
                    <dl class="spec-table">
                        <?php foreach ($g['rows'] as $row): ?>
                        <div class="spec-row">
                            <dt>
                                <?php echo esc_html($row[0]); ?>
                            </dt>
                            <dd>
                                <?php echo esc_html($row[1]); ?>
                            </dd>
                        </div>
                        <?php endforeach; ?>
                    </dl>
                </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" id="downloads" style="padding-top:0;">
        <div class="container">
            <div class="eyebrow reveal">Downloads</div>
            <div class="intro-head reveal" style="margin-bottom:0;">
                <h2>Brochure and floor plans.</h2>
                <p>Grab the dimensions, layout options, and drawings you'll need for council applications and site
                    planning.</p>
            </div>
            <div class="download-cards reveal">
                <?php foreach ($downloads as $dl): ?>
                <a class="download-card" href="<?php echo esc_url($dl['file'] ?: '#'); ?>">
                    <span class="download-icon"><svg viewBox="0 0 24 24">
                            <path d="M6 2h9l5 5v15H6z" />
                            <path d="M14 2v6h6" />
                        </svg></span>
                    <div>
                        <div class="download-title">
                            <?php echo esc_html($dl['title']); ?>
                        </div>
                        <div class="download-sub">
                            <?php echo esc_html($dl['note']); ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" style="padding-top:0;">
        <div class="cta-banner reveal">
            <div class="eyebrow" style="justify-content:center;color:var(--orange);">Get started today</div>
            <h2>Get
                <?php echo esc_html($name); ?> on your block.
            </h2>
            <p>Send us a few details about your site and we'll match you to the right size and layout, plus a quote with
                nothing attached.</p>
            <div class="cta-actions">
                <a href="https://m.me/61586353940439" class="btn btn-primary" id="cta-quote-bottom">Book an appointment</a>

            </div>

        </div>
    </section>

    <div class="sticky-bar" id="stickyBar">
        <div class="sticky-bar-inner container">
            <div>
                <div class="sticky-bar-info" id="stickyConfig">
                    <?php echo esc_html($ceiling_options[0]['label']); ?>
                </div>
                <div class="sticky-bar-price" id="stickyPrice">$
                    <?php echo esc_html(number_format($price_from)); ?>
                </div>
            </div>
            <a href="https://m.me/61586353940439" class="btn btn-primary" id="cta-quote-sticky">Book an appointment</a>
        </div>
    </div>

    <div class="pds-lightbox" id="pds-lightbox">
        <button type="button" class="pds-lightbox-close" id="pds-lightbox-close" aria-label="Close">×</button>
        <button type="button" class="pds-lightbox-prev" id="pds-lightbox-prev" aria-label="Previous">‹</button>
        <div class="pds-lightbox-content">
            <img src="" alt="" id="pds-lightbox-img">
            <div class="pds-lightbox-caption" id="pds-lightbox-caption"></div>
        </div>
        <button type="button" class="pds-lightbox-next" id="pds-lightbox-next" aria-label="Next">›</button>
    </div>

</div><!-- /.exp-page -->

<script>
    (function () {
        /* ---------- data from PHP ---------- */
        const productData = <?php echo wp_json_encode([
            'name' => $name, 'priceFrom' => $price_from, 'financeWeekly' => $finance_weekly,
            'ceilingOptions' => $ceiling_options,
        ]); ?>;
        var state = { ceilingDelta: productData.ceilingOptions[0].delta, ceilingLabel: productData.ceilingOptions[0].label };

        /* ---------- reveal on scroll ---------- */
        var revealEls = document.querySelectorAll(".reveal");
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add("is-visible"); io.unobserve(e.target); } });
        }, { threshold: 0.12 });
        revealEls.forEach(function (el) { io.observe(el); });

        function money(n) { return "$" + n.toLocaleString("en-AU"); }

        function render() {
            var total = productData.priceFrom + state.ceilingDelta;
            var buyConfigEl = document.getElementById("buyConfig");
            var buyPriceEl = document.getElementById("buyPrice");
            var stickyConfigEl = document.getElementById("stickyConfig");
            var stickyPriceEl = document.getElementById("stickyPrice");
            if (buyConfigEl) buyConfigEl.textContent = state.ceilingLabel;
            if (buyPriceEl) buyPriceEl.textContent = money(total);
            if (stickyConfigEl) stickyConfigEl.textContent = state.ceilingLabel;
            if (stickyPriceEl) stickyPriceEl.textContent = money(total);
        }

        /* ---------- ceiling toggle (single-select, only rendered when 2+ options) ---------- */
        document.querySelectorAll(".ceiling-options > .ceiling-option").forEach(function (btn) {
            btn.addEventListener("click", function () {
                document.querySelectorAll(".ceiling-options > .ceiling-option").forEach(function (b) { b.classList.remove("is-active"); });
                btn.classList.add("is-active");
                state.ceilingDelta = parseInt(btn.dataset.delta, 10);
                state.ceilingLabel = btn.dataset.label;
                render();
            });
        });

        render();

        /* ---------- sticky bar visibility ---------- */
        var stickyBar = document.getElementById("stickyBar");
        var layoutSection = document.getElementById("layout");
        if (stickyBar && layoutSection) {
            var stickyIO = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) { stickyBar.classList.toggle("is-visible", !e.isIntersecting && e.boundingClientRect.top < 50); });
            }, { threshold: 0 });
            stickyIO.observe(layoutSection);
        }

        function goToQuote(e) {
            e.preventDefault();
        }
        ['cta-quote-buybox', 'cta-quote-bottom', 'cta-quote-sticky'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('click', goToQuote);
        });

        /* ---------- section nav active state + scroll ---------- */
        var navLinks = document.querySelectorAll(".section-nav-link");
        var navMap = {};
        navLinks.forEach(function (link) { navMap[link.dataset.target] = link; });
        var sections = ["overview", "layout", "glance", "usecases", "specs", "downloads"]
        .map(function (id) { return document.getElementById(id); }).filter(Boolean);

        var sectionIO = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    navLinks.forEach(function (l) { l.classList.remove("is-active"); });
                    if (navMap[e.target.id]) { navMap[e.target.id].classList.add("is-active"); }
                }
            });
        }, { rootMargin: "-40% 0px -50% 0px", threshold: 0 });
        sections.forEach(function (s) { sectionIO.observe(s); });

        navLinks.forEach(function (link) {
            link.addEventListener("click", function (ev) {
                ev.preventDefault();
                var target = document.getElementById(link.dataset.target);
                var navHeight = document.getElementById("sectionNav").offsetHeight;
                if (target) window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - navHeight - 130, behavior: "smooth" });
            });
        });

        /* Overview gallery lightbox */
        (function () {
            var items = Array.prototype.slice.call(document.querySelectorAll('#overview-gallery .gallery-item[data-lightbox-src]'));
            if (!items.length) { return; }

            var lightbox = document.getElementById('pds-lightbox');
            var imgEl = document.getElementById('pds-lightbox-img');
            var capEl = document.getElementById('pds-lightbox-caption');
            var idx = 0;

            function open(i) {
                idx = i;
                imgEl.src = items[idx].dataset.lightboxSrc;
                capEl.textContent = items[idx].dataset.lightboxCaption || '';
                lightbox.classList.add('is-open');
            }
            function close() { lightbox.classList.remove('is-open'); }
            function next() { open((idx + 1) % items.length); }
            function prev() { open((idx - 1 + items.length) % items.length); }

            items.forEach(function (item, i) {
                item.addEventListener('click', function () { open(i); });
            });

            document.getElementById('pds-lightbox-close').addEventListener('click', close);
            document.getElementById('pds-lightbox-next').addEventListener('click', next);
            document.getElementById('pds-lightbox-prev').addEventListener('click', prev);
            lightbox.addEventListener('click', function (e) { if (e.target === lightbox) { close(); } });
            document.addEventListener('keydown', function (e) {
                if (!lightbox.classList.contains('is-open')) { return; }
                if (e.key === 'Escape') { close(); }
                if (e.key === 'ArrowRight') { next(); }
                if (e.key === 'ArrowLeft') { prev(); }
            });
        })();
    })();
</script>

<?php get_footer(); ?>