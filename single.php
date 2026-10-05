<?php
/**
 * Single blog post template.
 * Location: Hello Elementor child theme root.
 * Styles: assets/css/blog-single.css
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$cats = get_the_category();
	$cat  = ! empty( $cats ) ? $cats[0] : null;

	$content = apply_filters( 'the_content', get_the_content() );
	$content = str_replace( ']]>', ']]&gt;', $content );


	// Give every H2 and H3 an anchor id and build the "On this page" list.
	// The list stops at the "FAQs" H2 so FAQ questions are not listed.
	$toc  = array();
	$used = array();
	$stop = false;

	$processed = preg_replace_callback(
		'/<h([23])([^>]*)>(.*?)<\/h\1>/is', // \1 = same level closing tag
		function ( $m ) use ( &$toc, &$used, &$stop ) {
			$level = $m[1]; // "2" or "3"
			$attrs = $m[2];
			$inner = $m[3];

			$text = html_entity_decode( wp_strip_all_tags( $inner ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
			$text = trim( preg_replace( '/[\s\x{00A0}]+/u', ' ', $text ) );

			if ( '' === $text ) {
				return $m[0];
			}

			if ( preg_match( '/\sid=["\']([^"\']+)["\']/i', $attrs, $idm ) ) {
				$id   = $idm[1];
				$html = $m[0];
			} else {
				$root = sanitize_title( $text );
				$root = '' !== $root ? $root : 'section';
				$id   = $root;
				$n    = 2;
				while ( in_array( $id, $used, true ) ) {
					$id = $root . '-' . $n;
					$n++;
				}
				$html = '<h' . $level . $attrs . ' id="' . esc_attr( $id ) . '">' . $inner . '</h' . $level . '>';
			}

			$used[] = $id;

			if ( ! $stop ) {
				$toc[] = array(
					'id'    => $id,
					'text'  => $text,
					'level' => $level,
				);
				// Only the FAQs H2 stops the list
				if ( '2' === $level && preg_match( '/^faqs?$/i', $text ) ) {
					$stop = true;
				}
			}

			return $html;
		},
		$content
	);

	if ( null !== $processed ) {
		$content = $processed;
	}

	// Show "Updated" only when edited at least a day after publishing.
	$published_ts = get_post_time( 'U', true );
	$modified_ts  = get_post_modified_time( 'U', true );
	$show_updated = ( $modified_ts - $published_ts ) > DAY_IN_SECONDS;

	$has_cover = has_post_thumbnail();
	?>

	<main id="primary" class="pds-post">

		<div class="breadcrumb-bar">
			<nav class="breadcrumb" aria-label="Breadcrumb">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-house"></i>Home</a></li>
					<?php if ( $cat ) : ?>
						<li><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
					<?php endif; ?>
					<li aria-current="page"><?php the_title(); ?></li>
				</ol>
			</nav>
		</div>

		<div class="pds-post-hero">
			<div class="pds-post-wrap">
				<h1 class="pds-post-title"><?php the_title(); ?></h1>

				<p class="pds-post-meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<?php if ( $show_updated ) : ?>
						<span>Updated <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time></span>
					<?php endif; ?>
				</p>
			</div>
		</div>

		<div class="pds-post-body">
			<div class="pds-post-wrap">

				<article <?php post_class( 'pds-post-article' ); ?>>

					<?php if ( $has_cover ) : ?>
						<figure class="pds-post-cover">
							<?php
							the_post_thumbnail(
								'full',
								array(
									'loading'       => 'eager',
									'fetchpriority' => 'high',
									'sizes'         => '(max-width: 768px) 100vw, 736px',
								)
							);
							?>
						</figure>
					<?php endif; ?>

					<?php if ( count( $toc ) >= 3 ) : ?>
						<nav class="pds-toc" aria-labelledby="pds-toc-title">
							<p class="pds-toc__title" id="pds-toc-title">On this page</p>
							<ol>
								<?php foreach ( $toc as $item ) : ?>
                                	<li class="<?php echo '3' === $item['level'] ? 'pds-toc__h3' : 'pds-toc__h2'; ?>"><a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a></li>
                                <?php endforeach; ?>
							</ol>
						</nav>
					<?php endif; ?>

					<div class="pds-post-content">
						<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core the_content output ?>
					</div>

					<div class="pds-share">
						<span class="pds-share__label">Share this article</span>
						<button type="button" class="pds-share__btn" data-url="<?php echo esc_url( get_permalink() ); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>" aria-live="polite">Share</button>
					</div>

					<aside class="pds-cta" aria-label="Contact">
						<div class="pds-cta__copy">
							<p class="pds-cta__title">Thinking about a modular home?</p>
							<p class="pds-cta__text">Tell us about your block. We will help you pick a size and layout, and confirm lead time.</p>
						</div>
						<div class="pds-cta__actions">
							<a class="pds-post-btn pds-post-btn--primary" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Us</a>
							<a class="pds-post-btn pds-post-btn--ghost" href="https://m.me/61586353940439">Book an appointment</a>
						</div>
					</aside>

					<?php
					// Comment form + list (only when comments are open or exist).
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>

				</article>

			</div>
		</div>

		<?php
		// Related posts: same category first, latest posts as fallback.
		$related_args = array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);
		if ( $cat ) {
			$related_args['cat'] = $cat->term_id;
		}
		$related = new WP_Query( $related_args );
		if ( ! $related->have_posts() && $cat ) {
			unset( $related_args['cat'] );
			$related = new WP_Query( $related_args );
		}
		?>

		<?php if ( $related->have_posts() ) : ?>
			<section class="pds-related" aria-labelledby="pds-related-title">
				<div class="pds-post-wrap">
					<h2 class="pds-related__heading" id="pds-related-title">Related articles</h2>
					<div class="pds-related__grid">
						<?php
						while ( $related->have_posts() ) :
							$related->the_post();
							?>
							<article class="pds-related__item">
								<a class="pds-related__card" href="<?php the_permalink(); ?>">
									<span class="pds-related__media">
										<?php
										if ( has_post_thumbnail() ) {
											the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) );
										}
										?>
									</span>
									<span class="pds-related__body">
										<time class="pds-related__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
										<h3 class="pds-related__title"><?php the_title(); ?></h3>
									</span>
								</a>
							</article>
						<?php endwhile; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>

	<script>
	(function () {
		var btn = document.querySelector('.pds-share__btn');
		if (!btn) { return; }
		btn.addEventListener('click', function () {
			var url = btn.getAttribute('data-url'),
				title = btn.getAttribute('data-title'),
				label = btn.textContent;
			function done() {
				btn.textContent = 'Link copied';
				setTimeout(function () { btn.textContent = label; }, 2000);
			}
			if (navigator.share) {
				navigator.share({ title: title, url: url }).catch(function () {});
			} else if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url).then(done);
			} else {
				var t = document.createElement('textarea');
				t.value = url;
				document.body.appendChild(t);
				t.select();
				document.execCommand('copy');
				document.body.removeChild(t);
				done();
			}
		});
	})();
	</script>

	</main>

<?php endwhile; ?>

<?php get_footer(); ?>