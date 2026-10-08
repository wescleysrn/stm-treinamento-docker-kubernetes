<?php
/**
 * Block styles.
 *
 * @package imd-learn-theme
 * @since 1.0.0
 */

/**
 * Register block styles
 *
 * @since 1.0.0
 *
 * @return void
 */
function imd_learn_theme_register_block_styles() {

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/button',
		array(
			'name'  => 'imd-learn-theme-flat-button',
			'label' => __( 'Flat button', 'imd-learn-theme' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/list',
		array(
			'name'  => 'imd-learn-theme-list-underline',
			'label' => __( 'Underlined list items', 'imd-learn-theme' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/group',
		array(
			'name'  => 'imd-learn-theme-box-shadow',
			'label' => __( 'Box shadow', 'imd-learn-theme' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/column',
		array(
			'name'  => 'imd-learn-theme-box-shadow',
			'label' => __( 'Box shadow', 'imd-learn-theme' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/columns',
		array(
			'name'  => 'imd-learn-theme-box-shadow',
			'label' => __( 'Box shadow', 'imd-learn-theme' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/details',
		array(
			'name'  => 'imd-learn-theme-plus',
			'label' => __( 'Plus & minus', 'imd-learn-theme' ),
		)
	);
}
add_action( 'init', 'imd_learn_theme_register_block_styles' );

/**
 * This is an example of how to unregister a core block style.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 * @see https://github.com/WordPress/gutenberg/pull/37580
 *
 * @since 1.0.0
 *
 * @return void
 */
function imd_learn_theme_unregister_block_style() {
	wp_enqueue_script(
		'imd-learn-theme-unregister',
		get_stylesheet_directory_uri() . '/assets/js/unregister.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		IMD_LEARN_THEME_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'imd_learn_theme_unregister_block_style' );
