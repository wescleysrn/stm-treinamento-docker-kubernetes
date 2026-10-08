<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package imd-learn-theme
 * @since 1.0.0
 */

/**
 * The theme version.
 *
 * @since 1.0.0
 */
define( 'IMD_LEARN_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

/**
 * Add theme support for block styles and editor style.
 *
 * @since 1.0.0
 *
 * @return void
 */
function imd_learn_theme_setup() {
	add_editor_style( './assets/css/style-shared.min.css' );

	/*
	 * Load additional block styles.
	 * See details on how to add more styles in the readme.txt.
	 */
	$styled_blocks = [ 'button', 'quote', 'navigation', 'search' ];
	foreach ( $styled_blocks as $block_name ) {
		$args = array(
			'handle' => "imd-learn-theme-$block_name",
			'src'    => get_theme_file_uri( "assets/css/blocks/$block_name.min.css" ),
			'path'   => get_theme_file_path( "assets/css/blocks/$block_name.min.css" ),
		);
		// Replace the "core" prefix if you are styling blocks from plugins.
		wp_enqueue_block_style( "core/$block_name", $args );
	}

}
add_action( 'after_setup_theme', 'imd_learn_theme_setup' );

/**
 * Enqueue the CSS files.
 *
 * @since 1.0.0
 *
 * @return void
 */
function imd_learn_theme_styles() {
	wp_enqueue_style(
		'imd-learn-theme-style',
		get_stylesheet_uri(),
		[],
		IMD_LEARN_THEME_VERSION
	);
	wp_enqueue_style(
		'imd-learn-theme-shared-styles',
		get_theme_file_uri( 'assets/css/style-shared.min.css' ),
		[],
		IMD_LEARN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'imd_learn_theme_styles' );

// Filters.
require_once get_theme_file_path( 'inc/filters.php' );

// Block variation example.
require_once get_theme_file_path( 'inc/register-block-variations.php' );

// Block style examples.
require_once get_theme_file_path( 'inc/register-block-styles.php' );

// Block pattern and block category examples.
require_once get_theme_file_path( 'inc/register-block-patterns.php' );
