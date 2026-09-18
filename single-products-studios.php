<?php


get_header();

$post_id = get_the_ID();

/* ---------- common fields (shared group_pds_common_fields / group_pds_house_fields) ---------- */
$name           = get_the_title() ?: 'Hazel';
$module_size    = get_field('module_size') ?: '20ft';
$tagline        = get_field('short_description') ?: "A self-contained {$module_size} studio cabin built for a tight footprint — living/sleeping zone, kitchenette, and a fully fitted bathroom, delivered fully built and ready to place.";
$badge_tag      = get_field('badge_tag') ?: "{$module_size} Studio Cabin · Relocatable";
$size_sqm       = get_field('floor_area') ?: '15m²';
$bed_options    = get_field('bedrooms') ?: '1 bed';
$price_from     = (int) (get_field('price') ?: 25900);
$finance_weekly = get_field('weekly_price') ?: 103;

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

/* ---------- layouts (bed-count pills) ----------
   Repeater `layouts`: label, beds, price, area, sleeps, desc, image.
   Falls back to auto-generated 1..N pills parsed from the `bedrooms` text
   field, same trick as class1a — so each product reads its OWN bed count
   instead of inheriting Hazel's hardcoded bed count. This is what answers
   "Pick a bedroom count" needing to be 1/2/3/4 depending on product. */
$layouts = [];
if (have_rows('layouts', $post_id)) {
    while (have_rows('layouts', $post_id)) { the_row();
        $layouts[] = [
            'label' => get_sub_field('label'),
            'beds'  => (int) get_sub_field('beds'),
            'price' => (int) get_sub_field('price'),
            'area'  => get_sub_field('area') ?: $size_sqm,
            'sleeps'=> (int) get_sub_field('sleeps'),
            'desc'  => get_sub_field('desc'),
            'bullets' => (function () {
                $b = [];
                if (have_rows('bullets')) { while (have_rows('bullets')) { the_row(); $b[] = get_sub_field('bullet_text'); } }
                return $b;
            })(),
            'plan'  => exp_img_url(get_sub_field('image'), 'large'),
        ];
    }
}
if (empty($layouts)) {
    preg_match_all('/\d+/', $bed_options, $bed_matches);
    $bed_counts = !empty($bed_matches[0]) ? array_values(array_unique(array_map('intval', $bed_matches[0]))) : [1, 2, 3];
    sort($bed_counts);
    $descs = [
        1 => 'One open studio space built around a main bedroom',
        2 => 'Main bedroom plus a second room for guests, kids, or a home office',
        3 => 'Three separate bedrooms, sized for a full family',
        4 => 'Four separate bedrooms across the same footprint',
    ];
    $layout_bullets = [
        1 => ['Kitchenette fitted with upper and lower cabinetry','Fully enclosed bathroom with shower, toilet and vanity','Open floor space left for your own bed and furniture','Full-height sliding entry door'],
        2 => ['Kitchenette fitted with upper and lower cabinetry','Fully enclosed bathroom with shower, toilet and vanity','Main sleeping area plus a second room for guests or a home office','Full-height sliding entry door'],
        3 => ['Kitchenette fitted with upper and lower cabinetry','Fully enclosed bathroom with shower, toilet and vanity','Three separate rooms, sized for a full family','Full-height sliding entry door'],
        4 => ['Kitchenette fitted with upper and lower cabinetry','Fully enclosed bathroom with shower, toilet and vanity','Four separate rooms across the same footprint','Full-height sliding entry door'],
    ];
    foreach ($bed_counts as $n) {
        $layouts[] = [
            'label' => $n.' Bedroom Layout',
            'beds'  => $n,
            'price' => $price_from + (($n - ($bed_counts[0] ?? 1)) * 4000),
            'area'  => $size_sqm,
            'sleeps'=> $n * 2,
            'desc'  => $descs[$n] ?? $n.'-bedroom configuration within the same '.$size_sqm.' footprint.',
            'bullets' => $layout_bullets[$n] ?? ['Kitchenette fitted with upper and lower cabinetry','Fully enclosed bathroom with shower, toilet and vanity','Full-height sliding entry door'],
            'plan'  => null,
        ];
    }
}

/* group variants by bed count — supports 2+ rows sharing the same `beds`
   value (e.g. "2 Bed Standard" / "2 Bed Deluxe"). Bed-tabs dedupe on this
   grouping instead of looping every $layouts row directly, which used to
   print one bed-tab per row (duplicate labels) and let LAYOUTS[beds]
   overwrite down to the last row only. */
$layouts_by_beds = [];
foreach ($layouts as $l) { $layouts_by_beds[$l['beds']][] = $l; }
ksort($layouts_by_beds);
$bed_counts_ordered = array_keys($layouts_by_beds);
$l0 = $layouts_by_beds[$bed_counts_ordered[0]][0];

/* ---------- ceiling options ---------- */
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
        ['label' => '2.4m ceiling', 'delta' => 0],
    ];
}

/* ---------- at-a-glance bullets ---------- */
$glance_items = [];
if (have_rows('glance_items', $post_id)) { while (have_rows('glance_items', $post_id)) { the_row(); $glance_items[] = get_sub_field('text'); } }
if (empty($glance_items)) {
    $glance_items = [
        'Efficient floorplan that makes the most of a small footprint',
        'Compact kitchenette fitted before delivery',
        'Fully enclosed bathroom, shower, toilet and vanity included',
        'Lightweight steel-framed build for easy transport',
        'Fast turnaround once it is on site',
        'Designed to be relocated if your plans change',
        'Plumbing and wiring done before it leaves the factory',
        'WaterMark-certified fixtures throughout',
        'Built to AS/NZS 3000/3001',
    ];
}

/* ---------- feature blocks (kitchen / bathroom / living) ---------- */
$feature_blocks = [];
if (have_rows('feature_blocks', $post_id)) {
    while (have_rows('feature_blocks', $post_id)) { the_row();
        $bullets = [];
        if (have_rows('bullets')) { while (have_rows('bullets')) { the_row(); $bullets[] = get_sub_field('bullet_text'); } }
        $feature_blocks[] = [
            'id'=>get_sub_field('anchor_id'), 'index'=>get_sub_field('index_label'),
            'title'=>get_sub_field('title'), 'copy'=>get_sub_field('copy'),
            'bullets'=>$bullets, 'image'=>get_sub_field('image'),
        ];
    }
}
if (empty($feature_blocks)) {
    $feature_blocks = [
        ['id'=>'living','index'=>'01 · Living','title'=>'A well-planned living zone in a small footprint.',
         'copy'=>'Steel frame wrapped in insulated sandwich panel keeps the interior quiet and easy to regulate. Bright ceiling panels and timber-look flooring give the space a clean finish.',
         'bullets'=>['Steel frame with a primer and epoxy protective coating','Insulated sandwich panel walls and ceiling','Glass-wool insulation under the steel-clad roof','Light-coloured ceiling panels for a brighter interior','Timber-look flooring over an engineered subfloor','LED lighting fitted inside and out'],
         'image'=>null],
        ['id'=>'kitchen','index'=>'02 · Kitchen','title'=>'A compact kitchenette that still fits everything in.',
         'copy'=>'A straight-line layout makes the most of the available wall space, with upper and lower cabinetry, a durable benchtop, and a stainless sink fitted before delivery.',
         'bullets'=>['Straight-line kitchenette with upper and lower cabinetry','Durable benchtop with an integrated splashback','Stainless sink paired with mixer tapware','Soft-close cupboards and drawers','Overhead cabinets for extra storage','Window positioned for light and airflow'],
         'image'=>null],
        ['id'=>'bathroom','index'=>'03 · Bathroom','title'=>'A modern bathroom, largely pre-fitted.',
         'copy'=>'Laid out to make the most of a small footprint while keeping a clean, modern feel. Final plumbing connections are completed on site by a licensed tradesperson.',
         'bullets'=>['Ceramic toilet with a soft-close seat','Glass-enclosed shower with dual shower heads','Compact vanity with a durable benchtop and round basin','Wall-mounted mixer tap and integrated waste','Overhead shelf and mirrored storage','Opening window plus an exhaust fan for ventilation'],
         'image'=>null],
        ['id'=>'roof','index'=>'04 · Roof','title'=>'A sloped roof built to shed water and last.',
         'copy'=>'The pitched roofline drains into integrated gutters, adds an extra layer of insulation above the ceiling, and gives the cabin a sharper exterior line.',
         'bullets'=>['Sloped roofline for clean water runoff and a tidier exterior','Extra insulation layer above the ceiling for thermal and acoustic performance','Integrated gutters and downpipe','Weather-rated steel sheeting finish'],
         'image'=>null],
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
            ['External','L6000 × W2300 × H2530 mm'],
            ['Internal','L5774 × W2150 × H2300 mm'],
            ['Floor area', $size_sqm],
            ['Weight','1,900 kg'],
            ['Bedroom', $bed_options],
            ['Bathroom','1'],
        ]],
        ['group'=>'Frame & Structure', 'rows'=>[
            ['Beams & columns','Galvanised structural steel, rated for both road transport and final placement'],
            ['Purlins & keel','Galvanised steel purlins on a reinforced floor chassis'],
            ['Floor','Vinyl or SPC hybrid flooring over a dense fibre-cement subfloor, damp- and sound-resistant'],
            ['Walls','Insulated panel wall system, with bamboo fibreboard lining offered as an upgrade'],
        ]],
        ['group'=>'Insulation, Doors & Glazing', 'rows'=>[
            ['Roof','Insulated panel roof, steel sheeting on top, drainage built in'],
            ['Insulation','Insulated panels thermal performance year-round on its own'],
            ['Entrance door','Aluminium sliding or swing door, thermally broken, toughened glass with UV tint'],
            ['Windows','Aluminium awning windows, thermally broken, toughened glass with UV tint'],
        ]],
        ['group'=>'Electrical', 'rows'=>[
            ['Lighting','Downlights throughout, surface or recessed'],
            ['Wiring','Certified copper cable, sized separately for main feed, aircon, and general power'],
            ['Main switch','Heavy-duty isolator with its own lock-out'],
            ['Circuit protection','RCD/MCB protection on every circuit'],
            ['Sockets','Double-pole powerpoints, USB-A and USB-C built in'],
        ]],
        ['group'=>'Plumbing', 'rows'=>[
            ['Hot & cold water','Plumbed through to kitchen and bathroom, ending in brass threaded inlets'],
            ['Grey water','50mm PVC line running kitchen and bathroom out to one outlet'],
            ['Toilet waste','100mm PVC line, P-trap, exits through the wall'],
        ]],
    ];
}

/* ---------- downloads ---------- */
$downloads = [];
if (have_rows('downloads', $post_id)) { while (have_rows('downloads', $post_id)) { the_row(); $downloads[] = ['title'=>get_sub_field('title'),'note'=>get_sub_field('note'),'file'=>exp_file_url(get_sub_field('file'))]; } }
if (empty($downloads)) {
    $floor_plan   = get_field('floor_plan');
    $downloads = [
        ['title'=>$name.' Floor Plans', 'note'=>'PDF · Dimensions & technical drawings', 'file'=>exp_file_url($floor_plan)],
    ];
}

/* ---------- accessories ---------- */
$accessories = [];
if (have_rows('accessories', $post_id)) { while (have_rows('accessories', $post_id)) { the_row(); $accessories[] = ['cat'=>get_sub_field('cat'),'name'=>get_sub_field('name'),'meta'=>get_sub_field('meta'),'price'=>get_sub_field('price'),'image'=>get_sub_field('image')]; } }
if (empty($accessories)) {
    $accessories = [
        ['cat'=>'Solar','name'=>'3kVA · 5kWh Off-Grid Solar Kit','meta'=>'Victron · single-dwelling · 1× 5kWh battery','price'=>'From $9,182','image'=>null],
        ['cat'=>'Water','name'=>'3,000 L Slimline Tank','meta'=>'WCP poly · 800 mm wide · 20 yr warranty','price'=>'From $2,729','image'=>null],
        ['cat'=>'Aircon','name'=>'2.5 kW Split System','meta'=>'Reverse-cycle · Wi-Fi · 1 bedroom / studio','price'=>'From $1,620','image'=>null],
    ];
}

/* ---------- finishes — same global swatch library + finishes_* checkbox fields as class1a ---------- */
function exp_norm_swatch_key($s) {
    return strtolower(trim(preg_replace('/\s+/', ' ', (string) $s)));
}
$finish_swatch_photos = [];
if (have_rows('finish_swatch_media', 'option')) {
    while (have_rows('finish_swatch_media', 'option')) { the_row();
        $cat = get_sub_field('category'); $sw_name = get_sub_field('name'); $img = get_sub_field('image');
        if ($cat && $sw_name && $img) $finish_swatch_photos[exp_norm_swatch_key($cat).'|'.exp_norm_swatch_key($sw_name)] = exp_file_url($img);
    }
}
function exp_get_finishes($acf_field, $default_names, $cat, $photo_lookup) {
    $selected = get_field($acf_field);
    $names = !empty($selected) ? $selected : $default_names;
    $out = [];
    foreach ($names as $n) {
        $key = exp_norm_swatch_key($cat).'|'.exp_norm_swatch_key($n);
        $out[] = ['name' => $n, 'image' => $photo_lookup[$key] ?? null];
    }
    return $out;
}
$finishes = [
    'cladding'    => exp_get_finishes('finishes_cladding', ['Dark Grey Cladding','Light Grey Cladding','White Cladding','Wood Style Cladding'], 'cladding', $finish_swatch_photos),
    'ext_cladding_standard' => exp_get_finishes('finishes_ext_cladding_standard', ['Rustic Cedar','Arctic Grey','Stone Grey','Soft Linen','Dune','Sandstone','Natural Oak','Smoked Oak','Slate Brick','Chalk Brick'], 'ext_cladding_standard', $finish_swatch_photos),
    'steel_frame' => exp_get_finishes('finishes_steel_frame', ['Black Steel Framing','Dark Grey Steel Framing','White Steel Framing'], 'steel_frame', $finish_swatch_photos),
    'flooring'    => exp_get_finishes('finishes_flooring', ['Grey Timber Grain Flooring','Light Oak Timber Grain Flooring','Medium Oak Timber Grain Flooring','Dark Oak Timber Grain Flooring'], 'flooring', $finish_swatch_photos),
    'vinyl_flooring' => exp_get_finishes('finishes_vinyl_flooring', ['Light Yellow Wood Grain','Light Apricot Wood Grain','Grey-toned Wood Grain','Cream White Wood Grain','Chestnut Shell Wood Grain','Brown Wood Grain','Dark Wood Grain'], 'vinyl_flooring', $finish_swatch_photos),
    'spc_flooring' => exp_get_finishes('finishes_spc_flooring', ['SP101','SP102','SP103','SP105','SP106','SP107','SP108','SP109'], 'spc_flooring', $finish_swatch_photos),
    'benchtop'    => exp_get_finishes('finishes_benchtop', ['White Marble Stone','White Stone','White Speckle Stone','Light Grey Stone'], 'benchtop', $finish_swatch_photos),
    'cabinetry'   => exp_get_finishes('finishes_cabinetry', ['White','Light Grey','Apricot','Tan'], 'cabinetry', $finish_swatch_photos),
    'vanity'      => exp_get_finishes('finishes_vanity', ['White','Light Oak','Black'], 'vanity', $finish_swatch_photos),
];
$finish_labels = [
    'cladding'=>'Cladding (Luxe)', 'ext_cladding_standard'=>'Cladding (Standard)', 'steel_frame'=>'Steel Frame',
    'flooring'=>'Flooring (Luxe)', 'vinyl_flooring'=>'Vinyl Flooring', 'spc_flooring'=>'SPC Flooring (+$500)',
    'benchtop'=>'Benchtop', 'cabinetry'=>'Cabinetry', 'vanity'=>'Bathroom Vanity',
];

/* ---------- hero ticket stats ---------- */
$bed_low  = $bed_counts_ordered[0];
$bed_high = end($bed_counts_ordered);
$bed_range = ($bed_low == $bed_high) ? $bed_low : "$bed_low–$bed_high";
?>

<div class="exp-page">

    <div class="breadcrumb-bar">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <ol>
                <li><a href="/"><i class="fas fa-house"></i>Home</a></li>
                <li><a href="/studio">Studios</a></li>
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
                        <?php echo esc_html($bed_range); ?>
                    </div>
                    <div class="hero-stat-label">Bedrooms</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">1</div>
                    <div class="hero-stat-label">Bathroom</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">1</div>
                    <div class="hero-stat-label">Kitchen</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">
                        <?php echo esc_html($size_sqm); ?>
                    </div>
                    <div class="hero-stat-label">Floor area</div>
                </div>
            </div>
        </div>
     </section>

    <nav class="section-nav" id="sectionNav" aria-label="Product sections">
        <div class="section-nav-inner">
            <a class="section-nav-link is-active" href="#overview" data-target="overview">Overview</a>
            <a class="section-nav-link" href="#configurator" data-target="configurator">Choose your layout</a>
            <a class="section-nav-link" href="#glance" data-target="glance">At a glance</a>
            <?php foreach ($feature_blocks as $i => $fb): if ($i === 0): ?>
            <a class="section-nav-link" href="#<?php echo esc_attr($fb['id']); ?>"
                data-target="<?php echo esc_attr($fb['id']); ?>">Features</a>
            <?php endif; endforeach; ?>
            <a class="section-nav-link" href="#finishes" data-target="finishes">Finishes</a>
            <a class="section-nav-link" href="#specs" data-target="specs">Specifications</a>
            <a class="section-nav-link" href="#downloads" data-target="downloads">Plans</a>
        </div>
    </nav>

    <section class="section" id="overview">
        <div class="container">
            <div class="eyebrow reveal">Overview</div>
            <div class="intro-head reveal">
                <h2>Compact by design, complete from day one.</h2>
                <p>Delivered as one <?php echo esc_html($module_size); ?> unit, fully built and ready to place, with
                    <?php echo esc_html($size_sqm); ?> of finished space — kitchenette and bathroom already fitted.
                </p>
            </div>
            <?php
        $overview_repeater = get_field('overview_gallery', $post_id); // ACF repeater: image + label, per-image, order/count-safe
        if (!empty($overview_repeater)) {
            $overview_gallery = $overview_repeater;
        } else {
            // fallback: old Gallery field, index-based generic labels
            $gallery_labels_fallback = ['Living area', 'Open-plan living/kitchen with doors', 'Kitchen', 'Bedroom', 'Bathroom', 'Bathroom vanity'];
            $overview_gallery = [];
            foreach (array_slice($gallery, 1, 6) as $gi => $img) {
                $overview_gallery[] = ['image' => $img, 'label' => $img['caption'] ?: ($gallery_labels_fallback[$gi] ?? '')];
            }
        }
        ?>
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
                $placeholder_labels = ['Living area', 'Open-plan living/kitchen with doors', 'Kitchen', 'Bedroom', 'Bathroom', 'Bathroom vanity'];
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

    <section class="configurator section" id="configurator">
        <div class="container">
            <div class="eyebrow reveal">Choose your layout</div>
            <div class="config-head reveal">
                <h2>Pick a bedroom count, we'll build to it.</h2>
            </div>
            <div class="layout-tabs reveal" role="tablist" aria-label="Layout">
                <?php $li = 0; foreach ($bed_counts_ordered as $n): ?>
                <?php foreach ($layouts_by_beds[$n] as $vi => $v): ?>
                <button class="layout-tab <?php echo $li===0?'is-active':''; ?>" role="tab"
                    aria-selected="<?php echo $li===0?'true':'false'; ?>"
                    data-beds="<?php echo esc_attr($n); ?>" data-variant="<?php echo $vi; ?>">
                    <?php echo esc_html($v['label']); ?>
                </button>
                <?php $li++; endforeach; ?>
                <?php endforeach; ?>
            </div>
            <div class="layout-card reveal">
                <div class="layout-plan">
                    <img id="layoutPlanImg"
                        src="<?php echo esc_url($l0['plan'] ?: 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=700&q=80'); ?>"
                        alt="<?php echo esc_attr($l0['beds']); ?> bedroom floor plan">
                    <div class="layout-plan-cap">Floor plan</div>
                </div>
                <div>
                    <div class="layout-price-label">From</div>
                    <div class="layout-price" id="layoutPrice">$
                        <?php echo esc_html(number_format($l0['price'])); ?>
                    </div>
                    <div class="layout-name" id="layoutName">
                        <?php echo esc_html($l0['label']); ?>
                    </div>
                    <p class="lede" id="layoutDesc" style="color:var(--ink-soft);margin-bottom:18px;font-size:0.94rem;">
                        <?php echo esc_html($l0['desc']); ?>
                    </p>
                    <?php if (!empty($l0['bullets'])): ?>
                    <ul class="layout-bullets" id="layoutBullets">
                        <?php foreach ($l0['bullets'] as $b): ?>
                        <li>
                            <?php echo esc_html($b); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <div class="layout-stats">
                        <div class="hero-stat">
                            <div class="layout-stat-value">1</div>
                            <div class="layout-stat-label">Bathroom</div>
                        </div>
                        <div class="hero-stat">
                            <div class="layout-stat-value" id="layoutSleeps">
                                <?php echo esc_html($l0['sleeps']); ?>
                            </div>
                            <div class="layout-stat-label">Sleeps up to</div>
                        </div>
                        <div class="hero-stat">
                            <div class="layout-stat-value" id="layoutArea">
                                <?php echo esc_html($l0['area']); ?>
                            </div>
                            <div class="layout-stat-label">Floor area</div>
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
                        <button class="ceiling-option" id="roofToggleBtn" type="button"
                            data-delta="2000" data-label="Sloped or Gable roof">
                            Sloped or Gable roof <span class="delta">+$2,000</span>
                        </button>
                        <div class="sloped">
                            <img  src="https://perthdynamicsolutions.com.au/wp-content/uploads/2026/08/Sloped-or-Gable-roof-.webp">
                        </div>
                    </div>

                    <div class="buy-box">
                        <div class="buy-box-config" id="buyConfig">
                            <?php echo esc_html($l0['beds']); ?> bed ·
                            <?php echo esc_html($ceiling_options[0]['label']); ?>
                        </div>
                        <div class="buy-box-price" id="buyPrice">$
                            <?php echo esc_html(number_format($l0['price'])); ?>
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
                        <span class="trust-item">2-years warranty included</span>
                        <span class="trust-item">Arrives fully built</span>
                        <span class="trust-item">WaterMark-certified fixtures</span>
                        <span class="trust-item">Meets AS/NZS standards</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php foreach ($feature_blocks as $i => $fb): ?>
    <section class="container" id="<?php echo esc_attr($fb['id']); ?>">
        <div class="feature-block reveal">
            <div class="feature-media">
                <?php if (!empty($fb['image'])): ?>
                <img src="<?php echo esc_url($fb['image']['sizes']['medium'] ?? $fb['image']['url']); ?>" alt="">
                <?php else: ?>
                <img src="https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=800&q=80" alt="">
                <?php endif; ?>
            </div>
            <div>
                <div class="feature-index">
                    <?php echo esc_html($fb['index']); ?>
                </div>
                <h3>
                    <?php echo esc_html($fb['title']); ?>
                </h3>
                <p class="lede">
                    <?php echo esc_html($fb['copy']); ?>
                </p>
                <ul class="feature-list">
                    <?php foreach ($fb['bullets'] as $b): ?>
                    <li>
                        <?php echo esc_html($b); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>
    <?php endforeach; ?>

    <section class="section finishes-section" id="finishes">
        <div class="container">
            <div class="eyebrow reveal">05 · Finishes</div>
            <div class="intro-head reveal" style="margin-bottom:10px;">
                <h2>Choose your finishes.</h2>
                <p>Cladding, frame, flooring, benchtop, cabinetry and vanity — pick each one below and we'll bundle your
                    choices into the quote, then check stock on our end.</p>
                <p style="font-weight: 800;color: var(--orange-accessible);">
                    Ask us for our brochure to see the many other cladding options available.
                </p>
            </div>

            <div class="finishes-grid">
                <div class="finish-config">
                    <div class="finish-tabs reveal" id="finish-tabs">
                        <?php foreach (array_keys($finishes) as $i => $cat): ?>
                        <button class="finish-tab <?php echo $i===0?'active':''; ?>"
                            data-cat="<?php echo esc_attr($cat); ?>">
                            <?php echo esc_html($finish_labels[$cat]); ?>
                        </button>
                        <?php endforeach; ?>
                    </div>

                    <div id="finish-panels" class="reveal">
                        <?php foreach ($finishes as $cat => $opts): ?>
                        <div class="swatch-panel <?php echo $cat===array_key_first($finishes)?'active':''; ?>"
                            data-cat="<?php echo esc_attr($cat); ?>">
                            <div class="swatch-grid">
                                <?php foreach ($opts as $opt):
                                $thumb_style = !empty($opt['image']) ? "background-image:url(".esc_url($opt['image']).")" : "";
                            ?>
                                <div class="swatch-item" data-cat="<?php echo esc_attr($cat); ?>"
                                    data-name="<?php echo esc_attr($opt['name']); ?>"
                                    data-image="<?php echo esc_attr($opt['image'] ?: ''); ?>">
                                    <div class="swatch-thumb" style="<?php echo $thumb_style; ?>">
                                    </div>
                                    <div class="swatch-name">
                                        <?php echo esc_html($opt['name']); ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="specs">
        <div class="container">
            <div class="eyebrow reveal">06 · Specifications</div>
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
            <div class="eyebrow reveal">07 · Downloads</div>
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

    <?php if (!empty($accessories)): ?>
    <!--<section class="section">-->
    <!--    <div class="container">-->
    <!--        <div class="eyebrow reveal">Complete your build</div>-->
    <!--        <div class="intro-head reveal" style="margin-bottom:10px;">-->
    <!--            <h2>Popular add-ons.</h2>-->
    <!--            <p>These come up most often with-->
    <!--                <?php echo esc_html($name); ?> buyers, each one sized to match this model.-->
    <!--            </p>-->
    <!--        </div>-->
    <!--        <div class="accessory-cards reveal">-->
    <!--            <?php foreach ($accessories as $a): ?>-->
    <!--            <div class="accessory-card">-->
    <!--                <div class="accessory-media">-->
    <!--                    <?php if (!empty($a['image'])): ?>-->
    <!--                    <img src="<?php echo esc_url($a['image']['sizes']['medium'] ?? $a['image']['url']); ?>" alt="">-->
    <!--                    <?php endif; ?>-->
    <!--                </div>-->
    <!--                <div class="accessory-body">-->
    <!--                    <div class="accessory-cat">-->
    <!--                        <?php echo esc_html($a['cat']); ?>-->
    <!--                    </div>-->
    <!--                    <div class="accessory-name">-->
    <!--                        <?php echo esc_html($a['name']); ?>-->
    <!--                    </div>-->
    <!--                    <div class="accessory-desc">-->
    <!--                        <?php echo esc_html($a['meta']); ?>-->
    <!--                    </div>-->
    <!--                    <div class="accessory-foot">-->
    <!--                        <div>-->
    <!--                            <div class="accessory-price-label">From</div>-->
    <!--                            <div class="accessory-price">-->
    <!--                                <?php echo esc_html($a['price']); ?>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <a href="#" class="btn btn-outline-navy"-->
    <!--                            style="padding:8px 16px;font-size:0.82rem;">View</a>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <?php endforeach; ?>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
    <?php endif; ?>

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
                <a href="/contact-us/" class="btn btn-ghost">Enquire now</a>
            </div>
        </div>
    </section>

    <div class="sticky-bar" id="stickyBar">
        <div class="sticky-bar-inner container">
            <div>
                <div class="sticky-bar-info" id="stickyConfig">
                    <?php echo esc_html($l0['beds']); ?> bed ·
                    <?php echo esc_html($ceiling_options[0]['label']); ?>
                </div>
                <div class="sticky-bar-price" id="stickyPrice">$
                    <?php echo esc_html(number_format($l0['price'])); ?>
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
            'name' => $name, 'financeWeekly' => $finance_weekly,
            'layoutsByBeds' => $layouts_by_beds, 'bedCounts' => array_values($bed_counts_ordered),
            'ceilingOptions' => $ceiling_options, 'finishLabels' => $finish_labels,
        ]); ?>;
        var LAYOUTS_BY_BEDS = productData.layoutsByBeds;
        var BED_COUNTS = productData.bedCounts;
        var state = { beds: BED_COUNTS[0], variantIndex: 0, ceilingDelta: productData.ceilingOptions[0].delta, ceilingLabel: productData.ceilingOptions[0].label, roofDelta: 0 };

        function currentLayout() { return LAYOUTS_BY_BEDS[state.beds][state.variantIndex]; }

        /* ---------- reveal on scroll ---------- */
        var revealEls = document.querySelectorAll(".reveal");
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add("is-visible"); io.unobserve(e.target); } });
        }, { threshold: 0.12 });
        revealEls.forEach(function (el) { io.observe(el); });

        function money(n) { return "$" + n.toLocaleString("en-AU"); }

        function renderBullets(list) {
            var ul = document.getElementById("layoutBullets");
            if (!ul) return;
            ul.innerHTML = "";
            (list || []).forEach(function (b) {
                var li = document.createElement("li");
                li.textContent = b;
                ul.appendChild(li);
            });
        }

        function render() {
            var l = currentLayout();
            var total = l.price + state.ceilingDelta + state.roofDelta;
            document.getElementById("layoutName").textContent = l.label;
            document.getElementById("layoutDesc").textContent = l.desc;
            renderBullets(l.bullets);
            document.getElementById("layoutPrice").textContent = money(l.price);
            document.getElementById("layoutArea").textContent = l.area;
            document.getElementById("layoutSleeps").textContent = l.sleeps;
            document.getElementById("layoutPlanImg").src = l.plan || document.getElementById("layoutPlanImg").src;
            document.getElementById("layoutPlanImg").alt = state.beds + " bedroom floor plan";

            var configLabel = state.beds + " bed · " + state.ceilingLabel + (state.roofDelta > 0 ? " · Sloped/Gable roof" : "");
            document.getElementById("buyConfig").textContent = configLabel;
            document.getElementById("buyPrice").textContent = money(total);
            document.getElementById("stickyConfig").textContent = configLabel;
            document.getElementById("stickyPrice").textContent = money(total);
        }

        /* ---------- layout tabs: one flat row, one button per variant ---------- */
        document.querySelectorAll(".layout-tab").forEach(function (btn) {
            btn.addEventListener("click", function () {
                document.querySelectorAll(".layout-tab").forEach(function (b) { b.classList.remove("is-active"); b.setAttribute("aria-selected", "false"); });
                btn.classList.add("is-active");
                btn.setAttribute("aria-selected", "true");
                state.beds = parseInt(btn.dataset.beds, 10);
                state.variantIndex = parseInt(btn.dataset.variant, 10);
                render();
            });
        });

        /* ---------- ceiling toggle (single-select among 2.2m / 2.4m) ---------- */
        document.querySelectorAll(".ceiling-options > .ceiling-option:not(#roofToggleBtn)").forEach(function (btn) {
            btn.addEventListener("click", function () {
                document.querySelectorAll(".ceiling-options > .ceiling-option:not(#roofToggleBtn)").forEach(function (b) { b.classList.remove("is-active"); });
                btn.classList.add("is-active");
                state.ceilingDelta = parseInt(btn.dataset.delta, 10);
                state.ceilingLabel = btn.dataset.label;
                render();
            });
        });

        /* ---------- roof toggle (independent on/off, adds/removes its own delta) ---------- */
        var roofToggleBtn = document.getElementById("roofToggleBtn");
        if (roofToggleBtn) {
            roofToggleBtn.addEventListener("click", function () {
                var isActive = roofToggleBtn.classList.toggle("is-active");
                state.roofDelta = isActive ? parseInt(roofToggleBtn.dataset.delta, 10) : 0;
                render();
            });
        }

        render();

        /* ---------- finishes: tab switching ---------- */
        document.querySelectorAll(".finish-tab").forEach(function (tab) {
            tab.addEventListener("click", function () {
                var cat = tab.dataset.cat;
                document.querySelectorAll(".finish-tab").forEach(function (t) { t.classList.remove("active"); });
                tab.classList.add("active");
                document.querySelectorAll(".swatch-panel").forEach(function (p) { p.classList.toggle("active", p.dataset.cat === cat); });
            });
        });

        /* ---------- finishes: swatch selection ---------- */
        document.querySelectorAll(".swatch-item").forEach(function (item) {
            item.addEventListener("click", function () {
                var panel = item.closest(".swatch-panel");
                var wasActive = item.classList.contains("active");
                if (panel) panel.querySelectorAll(".swatch-item").forEach(function (i) { i.classList.remove("active"); });
                if (wasActive) { return; }
                item.classList.add("active");
            });
        });

        /* ---------- sticky bar visibility ---------- */
        var stickyBar = document.getElementById("stickyBar");
        var configurator = document.getElementById("configurator");
        var stickyIO = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) { stickyBar.classList.toggle("is-visible", !e.isIntersecting && e.boundingClientRect.top < 50); });
        }, { threshold: 0 });
        stickyIO.observe(configurator);

        /* ============================================================
           exp_goToQuote() — mirrors class1a's goToQuote(). Nothing was
           wired to #cta-quote-finishes in the source HTML; this binds
           it (and every other quote CTA) to send beds/ceiling/finishes
           to /get-a-quote/.
           ============================================================ */
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
        var sections = ["overview", "configurator", "glance", <?php foreach($feature_blocks as $fb) echo "'".esc_js($fb['id'])."', "; ?> "finishes", "specs", "downloads"]
        .map(function (id) { return document.getElementById(id); }).filter(Boolean);

        var sectionIO = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    navLinks.forEach(function (l) { l.classList.remove("is-active"); });
                    var active = navMap[e.target.id] || navMap["<?php echo esc_js($feature_blocks[0]['id'] ?? 'kitchen'); ?>"];
                    if (active && e.target.id !== "overview" && e.target.id !== "configurator" && e.target.id !== "glance" && e.target.id !== "finishes" && e.target.id !== "specs" && e.target.id !== "downloads") { navMap["<?php echo esc_js($feature_blocks[0]['id'] ?? 'kitchen'); ?>"].classList.add("is-active"); }
                    else if (navMap[e.target.id]) { navMap[e.target.id].classList.add("is-active"); }
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