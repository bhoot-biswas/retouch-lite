<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Retouch_Lite
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<?php
	/**
	 * Functions hooked into retouch_lite_before_header
	 *
	 * @hooked retouch_lite_skip_links [10]
	 */
	do_action( 'retouch_lite_before_header' );
	?>

	<header id="masthead" class="site-header">

		<?php
		/**
		 * Functions hooked into retouch_lite_header
		 *
		 * @hooked retouch_lite_primary_navigation [10]
		 */
		do_action( 'retouch_lite_header' );
		?>

	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked into retouch_lite_before_content
	 *
	 * @hooked retouch_lite_post_hero [10]
	 */
	do_action( 'retouch_lite_before_content' );
	?>

	<div id="content" class="site-content">

		<div class="wrap">

		<?php do_action( 'retouch_lite_content_top' ); ?>
