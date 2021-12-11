<?php
/*
 * This is the child theme for Zubin theme.
 *
 * (Please see https://developer.wordpress.org/themes/advanced-topics/child-themes/#how-to-create-a-child-theme)
 */
function zubin_photography_enqueue_styles() {
    // Include parent theme CSS.
    wp_enqueue_style( 'zubin-style', get_template_directory_uri() . '/style.css', null, date( 'Ymd-Gis', filemtime( get_template_directory() . '/style.css' ) ) );
    
    // Include child theme CSS.
    wp_enqueue_style( 'zubin-photography-style', get_stylesheet_directory_uri() . '/style.css', array( 'zubin-style' ), date( 'Ymd-Gis', filemtime( get_stylesheet_directory() . '/style.css' ) ) );

	// Load the rtl.
	if ( is_rtl() ) {
		wp_enqueue_style( 'zubin-rtl', get_template_directory_uri() . '/rtl.css', array( 'zubin-style' ), $version );
	}

	// Enqueue child block styles after parent block style.
	wp_enqueue_style( 'zubin-photography-block-style', get_stylesheet_directory_uri() . '/assets/css/child-blocks.css', array( 'zubin-block-style' ), date( 'Ymd-Gis', filemtime( get_stylesheet_directory() . '/assets/css/child-blocks.css' ) ) );
}
add_action( 'wp_enqueue_scripts', 'zubin_photography_enqueue_styles' );

/**
 * Add languages and child theme editor styles
 */
function zubin_photography_editor_style() {
	load_child_theme_textdomain( 'zubin-photography', get_stylesheet_directory() . '/languages' );

	add_editor_style( array(
			'assets/css/child-editor-style.css',
			zubin_fonts_url(),
			get_theme_file_uri( 'assets/css/font-awesome/css/font-awesome.css' ),
		)
	);
}
add_action( 'after_setup_theme', 'zubin_photography_editor_style', 11 );

/**
 * Enqueue editor styles for Gutenberg
 */
function zubin_photography_block_editor_styles() {
	// Enqueue child block editor style after parent editor block css.
	wp_enqueue_style( 'zubin-photography-block-editor-style', get_stylesheet_directory_uri() . '/assets/css/child-editor-blocks.css', array( 'zubin-block-editor-style' ), date( 'Ymd-Gis', filemtime( get_stylesheet_directory() . '/assets/css/child-editor-blocks.css' ) ) );
}
add_action( 'enqueue_block_editor_assets', 'zubin_photography_block_editor_styles', 11 );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function zubin_photography_body_classes( $classes ) {
	// Added color scheme to body class.
	$classes['theme_scheme']                 = 'theme-scheme-photography';
	$classes['zubin_menu_layout']            = 'header-boxed';
	$classes['zubin_primary_menu_alignment'] = 'menu-align-right';

	$enable_sticky_playlist = get_theme_mod( 'zubin_sticky_playlist_visibility', 'disabled' );

	if ( zubin_check_section( $enable_sticky_playlist ) ) {
		$classes[] = 'sticky-playlist-enabled';
	}

	return $classes;
}
add_filter( 'body_class', 'zubin_photography_body_classes', 11 );

/**
 * Change default header text color
 */
function zubin_photography_header_default_color( $args ) {
	$args['default-image'] =  get_theme_file_uri( 'assets/images/header-image.jpg' );

	return $args;
}
add_filter( 'zubin_custom_header_args', 'zubin_photography_header_default_color' );

/**
 * Override google font to source of parent
 */
function zubin_fonts_url() {
	/** 
	 * Translators: If there are characters in your language that are not
	 * supported by Open Sans, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$open_sans = _x( 'on', 'Open Sans: on or off', 'zubin-photography' );

	if ( 'on' === $open_sans ) {
		return esc_url( '//fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,400italic,700italic' );
	}
}

/**
 * Override Parent Header Media Text
 */
function zubin_header_media_text() {

	if ( ! zubin_has_header_media_text() ) {
		// Bail early if header media text is disabled on front page
		return false;
	}

	$header_media_logo = get_theme_mod( 'zubin_header_media_logo' );

	$before_subtitle = get_theme_mod( 'zubin_header_media_before_subtitle' );

	$after_subtitle = get_theme_mod( 'zubin_header_media_after_subtitle');
	?>
	<div class="custom-header-content content-position-left text-align-left">

		<div class="entry-container-wrapper">
			<div class="entry-container">
			<?php
			$enable_homepage_logo = get_theme_mod( 'zubin_header_media_logo_option', 'homepage' );
			?>

			<div class="entry-header">
				<?php if( is_front_page() && $before_subtitle ) : ?>
					<div class="sub-title">
						<span>
							<?php echo esc_html( $before_subtitle ); ?>
						</span>
					</div>
				<?php endif; ?>

				<?php
				if ( zubin_check_section( $enable_homepage_logo ) && $header_media_logo ) {  ?>
					<div class="site-header-logo">
						<img src="<?php echo esc_url( $header_media_logo ); ?>" title="<?php echo esc_attr( home_url( '/' ) ); ?>" />
					</div><!-- .site-header-logo -->
				<?php } ?>

				<?php
				if ( is_singular() && ! is_page() ) {
					zubin_header_title( '<h1 class="entry-title">', '</h1>' );
				} else {
					zubin_header_title( '<h2 class="entry-title">', '</h2>' );
				}
				?>

				<?php if( is_front_page() && $after_subtitle ) : ?>
					<div class="sub-title">
						<span>
							<?php echo esc_html( $after_subtitle ); ?>
						</span>
					</div>
				<?php endif; ?>
			</div>

			<?php zubin_header_description(); ?>

			</div> <!-- .entry-container -->
		</div> <!-- .entry-container-wrapper -->
	</div><!-- .custom-header-content -->
	<?php
} // zubin_header_media_text.
