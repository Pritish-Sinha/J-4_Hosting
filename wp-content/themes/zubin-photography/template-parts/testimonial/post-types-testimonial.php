<?php
/**
 * The template for displaying testimonial items
 *
 * @package Zubin
 */
?>

<?php
$number = get_theme_mod( 'zubin_testimonial_number', 4 );

if ( ! $number ) {
	// If number is 0, then this section is disabled
	return;
}

$args = array(
	'ignore_sticky_posts' => 1 // ignore sticky posts
);

$post_list  = array();// list of valid post/page ids

$args['post_type'] = 'jetpack-testimonial';

for ( $i = 1; $i <= $number; $i++ ) {
	$zubin_post_id = '';

	$zubin_post_id =  get_theme_mod( 'zubin_testimonial_cpt_' . $i );

	if ( $zubin_post_id && '' !== $zubin_post_id ) {
		// Polylang Support.
		if ( class_exists( 'Polylang' ) ) {
			$zubin_post_id = pll_get_post( $zubin_post_id, pll_current_language() );
		}

		$post_list = array_merge( $post_list, array( $zubin_post_id ) );

	}
}

$args['post__in'] = $post_list;
$args['orderby'] = 'post__in';

$args['posts_per_page'] = $number;
$loop = new WP_Query( $args );

if ( $loop -> have_posts() ) :
	while ( $loop -> have_posts() ) :
		$loop -> the_post();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="hentry-inner">
				<?php
				$thumb = trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/no-thumb-700x525.jpg'; 

				if ( has_post_thumbnail() ) {
					$thumb = get_the_post_thumbnail_url( get_the_ID(), array( 700,525 ) );
				}
				?>
				<div class="post-thumbnail" style="background-image: url( '<?php echo esc_url( $thumb ); ?>' )">
					<a class="cover-link" href="<?php the_permalink(); ?>">
					</a>
				</div>

				<div class="entry-container">
					<div class="entry-summary">
						<div class="content-wrap">
							<?php the_content(); ?>
						</div>
					</div>

					<?php
     				$counter  = absint( $loop->current_post ) + 1;
					$position = get_post_meta( $post->ID, 'ect_testimonial_position', true );	
					?>

					<header class="entry-header">
						<h2 class="entry-title">
							<a href=<?php the_permalink(); ?>>
								<?php the_title(); ?>
							</a>
						</h2>

						<?php if( $position ) : ?>
							<div class="position" >
								<?php echo esc_html( $position ); ?>
							</div>
						<?php endif; ?>
					</header>
				</div><!-- .entry-container -->
			</div><!-- .hentry-inner -->
		</article>
	<?php
	endwhile;
	wp_reset_postdata();
endif;
