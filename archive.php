<?php
/**
 * Blog archive template (category, tag, author, date).
 * Location: Hello Elementor child theme root.
 * Styles: assets/css/blog-archive.css
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// "Category: Blogs" -> "Blogs"
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

get_header();

global $wp_query;
$total    = (int) $wp_query->post_count;
$is_paged = is_paged();

// Shown in the hero when the category has no description. Edit freely.
$fallback_desc = 'Guides and updates on modular homes, granny flats and expandable builds in Western Australia.';
?>

<main id="primary" class="pds-blog">

	<div class="breadcrumb-bar">
		<nav class="breadcrumb" aria-label="Breadcrumb">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-house"></i>Home</a></li>
				<li aria-current="page"><?php echo esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></li>
			</ol>
		</nav>
	</div>

	<div class="pds-blog-hero">
		<div class="pds-blog-wrap">
			<h1 class="pds-blog-title"><?php echo esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>
			<?php
			if ( is_category() && '' === trim( wp_strip_all_tags( category_description() ) ) ) {
				echo '<div class="pds-blog-desc"><p>' . esc_html( $fallback_desc ) . '</p></div>';
			} else {
				the_archive_description( '<div class="pds-blog-desc">', '</div>' );
			}
			?>
		</div>
	</div>

	<section class="pds-blog-list" aria-label="Posts">
		<div class="pds-blog-wrap">

			<?php if ( have_posts() ) : ?>

				<div class="pds-blog-grid">
					<?php
					$i = 0;
					while ( have_posts() ) :
						the_post();

						// Card role: featured (page 1, first), then 7/5 pairs that swap each row.
						$is_featured = ( 0 === $i && ! $is_paged );
						$pos         = $is_paged ? $i : $i - 1; // position among non-featured cards
						$is_last     = ( $i === $total - 1 );
						$is_solo     = ( ! $is_featured && $is_last && 0 === $pos % 2 );
						$is_lg       = ( 0 === ( intdiv( $pos, 2 ) + ( $pos % 2 ) ) % 2 );
						$i++;

						$classes = array( 'pds-blog-card' );
						if ( $is_featured ) {
							$classes[] = 'is-featured';
						} elseif ( $is_solo ) {
							$classes[] = 'is-solo';
						} else {
							$classes[] = $is_lg ? 'is-lg' : 'is-sm';
						}

						$words   = $is_featured ? 42 : ( $is_solo ? 34 : ( $is_lg ? 26 : 20 ) );
						$size    = ( $is_featured || $is_solo || $is_lg ) ? 'large' : 'medium_large';
						$cats    = get_the_category();
						$excerpt = has_excerpt()
							? get_the_excerpt()
							: wp_trim_words( wp_strip_all_tags( strip_shortcodes( get_the_content() ) ), $words, '…' );
						?>

						<article <?php post_class( $classes ); ?>>

							<div class="pds-blog-card__media">
								<?php if ( $is_featured ) : ?>
									<span class="pds-blog-card__badge">Latest post</span>
								<?php endif; ?>
								<?php
								if ( has_post_thumbnail() ) {
									$img_args = array( 'alt' => '' );
									if ( $is_featured ) {
										$img_args['loading']       = 'eager';
										$img_args['fetchpriority'] = 'high';
									}
									the_post_thumbnail( $size, $img_args );
								} else {
									echo '<span class="pds-blog-card__ph" aria-hidden="true"></span>';
								}
								?>
							</div>

							<div class="pds-blog-card__body">
								<div class="pds-blog-card__meta">
									<?php if ( ! is_category() && ! empty( $cats ) ) : ?>
										<span class="pds-blog-card__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
									<?php endif; ?>
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								</div>

								<h2 class="pds-blog-card__title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>

								<?php if ( $excerpt ) : ?>
									<p class="pds-blog-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
								<?php endif; ?>

								<span class="pds-blog-card__more" aria-hidden="true">Read post</span>
							</div>

						</article>

					<?php endwhile; ?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => 'Newer',
						'next_text' => 'Older',
					)
				);
				?>

				<aside class="pds-cta" aria-label="Contact">
					<div class="pds-cta__copy">
						<p class="pds-cta__title">Thinking about a modular home?</p>
						<p class="pds-cta__text">Tell us about your block. We will help you pick a size and layout, and confirm lead time.</p>
					</div>
					<div class="pds-cta__actions">
						<a class="pds-btn pds-btn--primary" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Us</a>
						<a class="pds-btn pds-btn--ghost" href="https://m.me/61586353940439">Book an appointment</a>
					</div>
				</aside>

			<?php else : ?>

				<p class="pds-blog-empty">No posts here yet. Check back soon.</p>

			<?php endif; ?>

		</div>
	</section>

</main>

<?php get_footer(); ?>