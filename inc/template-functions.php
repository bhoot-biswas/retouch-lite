<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Retouch_Lite
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function retouch_lite_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'retouch_lite_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function retouch_lite_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'retouch_lite_pingback_header' );

if ( ! function_exists( 'retouch_lite_display_comments' ) ) {
	/**
	 * Display comments
	 *
	 * @since  1.0.0
	 */
	function retouch_lite_display_comments() {
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
	}
}

if ( ! function_exists( 'retouch_lite_footer_widgets' ) ) {
	/**
	 * Display the footer widget regions.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function retouch_lite_footer_widgets() {
		$rows    = intval( apply_filters( 'retouch_lite_footer_widget_rows', 1 ) );
		$regions = intval( apply_filters( 'retouch_lite_footer_widget_columns', 4 ) );

		for ( $row = 1; $row <= $rows; $row++ ) :

			// Defines the number of active columns in this footer row.
			for ( $region = $regions; 0 < $region; $region-- ) {
				if ( is_active_sidebar( 'footer-' . strval( $region + $regions * ( $row - 1 ) ) ) ) {
					$columns = $region;
					break;
				}
			}

			if ( isset( $columns ) ) : ?>
				<div class=<?php echo '"footer-widgets row-' . esc_attr( strval( $row ) ) . ' columns-' . esc_attr( strval( $columns ) ) . ' fix"'; ?>>
					<div class="container">
						<div class="row">
							<?php
							for ( $column = 1; $column <= $columns; $column++ ) :
								$footer_n = $column + $regions * ( $row - 1 );

								if ( is_active_sidebar( 'footer-' . strval( $footer_n ) ) ) :
									?>

									<div class="block footer-widget-<?php echo esc_attr( strval( $column ) ); ?>">
										<?php dynamic_sidebar( 'footer-' . strval( $footer_n ) ); ?>
									</div>

									<?php

								endif;
							endfor;
							?>
						</div>
					</div>
				</div><!-- .footer-widgets -->
				<?php

				unset( $columns );
			endif;
		endfor;
	}
}

if ( ! function_exists( 'retouch_lite_credit' ) ) {
	/**
	 * Display the theme credit
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function retouch_lite_credit() {
		?>
		<div class="site-info">
			<div class="container">
				<div class="wrap">
					<div class="info">
						<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'retouch-lite' ) ); ?>">
							<?php
								/* translators: %s: CMS name, i.e. WordPress. */
								printf( esc_html__( 'Proudly powered by %s', 'retouch-lite' ), 'WordPress' );
							?>
						</a>
						<span class="sep"> | </span>
						<?php
							/* translators: 1: Theme name, 2: Theme author. */
							printf( esc_html__( 'Theme: %1$s by %2$s.', 'retouch-lite' ), 'retouch-lite', '<a href="http://underscores.me/">Underscores.me</a>' );
						?>
					</div>

					<?php retouch_lite_social_menu(); ?>
				</div>
			</div>
		</div><!-- .site-info -->
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_primary_navigation' ) ) {
	/**
	 * Display primary navigation
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function retouch_lite_primary_navigation() {
		?>
		<nav id="site-navigation" class="main-navigation navbar navbar-expand-lg navbar-light bg-white">
			<div class="container">
				<?php
				/**
				 * Functions hooked into retouch_lite_primary_navigation
				 *
				 * @hooked retouch_lite_navbar_brand [10]
				 * @hooked retouch_lite_navbar_toggler [20]
				 * @hooked retouch_lite_navbar_collapse [30]
				 */
				do_action( 'retouch_lite_primary_navigation' );
				?>
			</div>
		</nav><!-- #site-navigation -->
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_navbar_brand' ) ) {
	/**
	 * Display navbar brand
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function retouch_lite_navbar_brand() {
		?>
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
			endif;

			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php
			endif;
			?>
		</div><!-- .site-branding -->
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_navbar_toggler' ) ) {
	/**
	 * Display navbar toggler
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function retouch_lite_navbar_toggler() {
		?>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'retouch-lite' ); ?></span>
			<span class="navbar-toggler-icon"></span>
		</button>
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_navbar_collapse' ) ) {
	/**
	 * Display navbar_collapse
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function retouch_lite_navbar_collapse() {
		?>
		<div class="collapse navbar-collapse" id="navbar-collapse">
			<?php
			/**
			 * Functions hooked into retouch_lite_navbar_collapse
			 *
			 * @hooked retouch_lite_primary_menu [10]
			 */
			do_action( 'retouch_lite_navbar_collapse' );
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_primary_menu' ) ) {
	/**
	 * Display primary menu
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function retouch_lite_primary_menu() {
		wp_nav_menu( array(
			'theme_location' => 'menu-1',
			'container'      => false,
			'menu_id'        => '',
			'menu_class'     => 'navbar-nav',
			'fallback_cb'    => '',
		) );
	}
}

if ( ! function_exists( 'retouch_lite_skip_links' ) ) {
	/**
	 * Skip links
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function retouch_lite_skip_links() {
		?>
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'retouch-lite' ); ?></a>
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_page_header' ) ) {
	/**
	 * Display the page header
	 *
	 * @since 1.0.0
	 */
	function retouch_lite_page_header() {
		?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_page_content' ) ) {
	/**
	 * Display the post content
	 *
	 * @since 1.0.0
	 */
	function retouch_lite_page_content() {
		?>
		<div class="entry-content">
			<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'retouch-lite' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_page_footer' ) ) {
	/**
	 * Display the post footer
	 *
	 * @since 1.0.0
	 */
	function retouch_lite_page_footer() {
		if ( get_edit_post_link() ) :
			?>
			<footer class="entry-footer">
				<?php
					edit_post_link(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Edit <span class="screen-reader-text">%s</span>', 'retouch-lite' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							get_the_title()
						),
						'<span class="edit-link">',
						'</span>'
					);
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_post_hero' ) ) {
	/**
	 * Template part for displaying hero image on the single page.
	 *
	 * @since 1.0.0
	 */
	function retouch_lite_post_hero() {
		if ( ! is_singular() || post_password_required() || is_attachment() ) {
			return;
		}

		if ( 'post' !== get_post_type() && ! has_post_thumbnail() || 'post' === get_post_type() && ! retouch_lite_has_post_thumbnail() ) {
			return;
		}
		?>
		<div class="entry-hero" <?php retouch_lite_background_image(); ?>>
			<div class="entry-hero-wrapper">
				<?php
				retouch_lite_post_categories();

				the_title( '<h1 class="entry-title">', '</h1>' );
				?>
			</div><!-- .entry-hero-wrapper -->
		</div><!-- .entry-hero -->
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_post_header' ) ) {
	/**
	 * Display the post header with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function retouch_lite_post_header() {
		if ( ! is_single() || ! retouch_lite_has_post_thumbnail() ) :
		?>
		<header class="entry-header" <?php retouch_lite_background_image(); ?>>
			<div class="entry-header__wrap">
				<?php
				retouch_lite_post_categories();

				if ( is_singular() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif;
				?>
			</div>
		</header><!-- .entry-header -->
		<?php
		endif;
	}
}

if ( ! function_exists( 'retouch_lite_post_content' ) ) {
	/**
	 * Display the post content with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function retouch_lite_post_content() {
		?>
		<div class="entry-content">
			<?php
				the_content( sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'retouch-lite' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'retouch-lite' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_post_footer' ) ) {
	/**
	 * Display the post footer
	 *
	 * @since 1.0.0
	 */
	function retouch_lite_post_footer() {
		?>
		<footer class="entry-footer">
			<?php retouch_lite_entry_footer(); ?>
		</footer><!-- .entry-footer -->
		<?php
	}
}

if ( ! function_exists( 'retouch_lite_posts_navigation' ) ) {
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function retouch_lite_posts_navigation() {
		the_posts_navigation();
	}
}

if ( ! function_exists( 'retouch_lite_post_navigation' ) ) {
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function retouch_lite_post_navigation() {
		the_post_navigation();
	}
}

if ( ! function_exists( 'retouch_lite_get_sidebar' ) ) {
	/**
	 * Display sidebar
	 *
	 * @uses get_sidebar()
	 * @since 1.0.0
	 */
	function retouch_lite_get_sidebar() {
		get_sidebar();
	}
}
