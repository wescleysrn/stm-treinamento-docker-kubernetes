<?php
/**
 * IMD Academy Blocks Functions
 * 
 * @package IMD_Academy_Blocks
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function imd_blocks_setup() {
    // Suporte a tradução
    load_theme_textdomain('imd-academy-blocks', get_template_directory() . '/languages');
    
    // Suporte a editor de blocos
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_editor_style('style.css');
    
    // Suporte a imagens destacadas
    add_theme_support('post-thumbnails');
    
    // Tamanhos de imagem personalizados
    add_image_size('course-thumbnail', 400, 250, true);
    add_image_size('product-thumbnail', 400, 400, true);
    
    // Suporte a logo customizado
    add_theme_support('custom-logo', array(
        'height' => 60,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true,
    ));
    
    // Suporte a WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Suporte a LearnPress
    add_theme_support('learnpress');
}
add_action('after_setup_theme', 'imd_blocks_setup');

/**
 * Enfileirar scripts e estilos
 */
function imd_blocks_scripts() {
    // Estilo principal
    wp_enqueue_style('imd-blocks-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
    
    // Script customizado (se necessário)
    if (file_exists(get_template_directory() . '/js/main.js')) {
        wp_enqueue_script('imd-blocks-script', get_template_directory_uri() . '/js/main.js', array(), wp_get_theme()->get('Version'), true);
    }
}
add_action('wp_enqueue_scripts', 'imd_blocks_scripts');

/**
 * Registrar padrões de blocos customizados
 */
function imd_blocks_register_block_patterns() {
    // Hero Banner Pattern
    register_block_pattern(
        'imd-academy-blocks/hero-banner',
        array(
            'title' => __('Hero Banner', 'imd-academy-blocks'),
            'description' => __('Banner hero com título, descrição e botões', 'imd-academy-blocks'),
            'categories' => array('featured'),
            'content' => '<!-- wp:group {"align":"full","backgroundColor":"primary","textColor":"base","className":"hero-banner-block"} -->
<div class="wp-block-group alignfull hero-banner-block has-base-color has-primary-background-color has-text-color has-background">
    <!-- wp:group {"layout":{"type":"constrained"}} -->
    <div class="wp-block-group">
        <!-- wp:heading {"level":1,"fontSize":"xx-large"} -->
        <h1 class="wp-block-heading has-xx-large-font-size">Transforme seu Futuro com Educação de Qualidade</h1>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph {"fontSize":"large"} -->
        <p class="has-large-font-size">Aprenda com os melhores cursos online e desenvolva suas habilidades para alcançar seus objetivos profissionais.</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:buttons -->
        <div class="wp-block-buttons">
            <!-- wp:button {"backgroundColor":"base","textColor":"primary"} -->
            <div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-base-background-color has-text-color has-background wp-element-button">Explorar Cursos</a></div>
            <!-- /wp:button -->
            
            <!-- wp:button {"backgroundColor":"transparent","textColor":"base","className":"is-style-outline"} -->
            <div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-base-color has-transparent-background-color has-text-color has-background wp-element-button">Saiba Mais</a></div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->',
        )
    );
    
    // Course Grid Pattern
    register_block_pattern(
        'imd-academy-blocks/course-grid',
        array(
            'title' => __('Grade de Cursos', 'imd-academy-blocks'),
            'description' => __('Grade responsiva de cursos em destaque', 'imd-academy-blocks'),
            'categories' => array('featured'),
            'content' => '<!-- wp:group {"align":"full","backgroundColor":"light"} -->
<div class="wp-block-group alignfull has-light-background-color has-background">
    <!-- wp:group {"layout":{"type":"constrained"}} -->
    <div class="wp-block-group">
        <!-- wp:heading {"textAlign":"center","fontSize":"x-large"} -->
        <h2 class="wp-block-heading has-text-align-center has-x-large-font-size">Cursos em Destaque</h2>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph {"align":"center"} -->
        <p class="has-text-align-center">Descubra os cursos mais populares e comece sua jornada de aprendizado hoje mesmo</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:query {"queryId":1,"query":{"perPage":3,"pages":0,"offset":0,"postType":"lp_course","order":"desc","orderBy":"date"}} -->
        <div class="wp-block-query">
            <!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
                <!-- wp:group {"className":"course-card"} -->
                <div class="wp-block-group course-card">
                    <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->
                    
                    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
                    <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
                        <!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->
                        <!-- wp:post-excerpt {"moreText":"Leia mais","excerptLength":15} /-->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            <!-- /wp:post-template -->
        </div>
        <!-- /wp:query -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->',
        )
    );
}
add_action('init', 'imd_blocks_register_block_patterns');

/**
 * Registrar categorias de padrões
 */
function imd_blocks_register_pattern_categories() {
    register_block_pattern_category(
        'imd-featured',
        array('label' => __('IMD Academy', 'imd-academy-blocks'))
    );
}
add_action('init', 'imd_blocks_register_pattern_categories');

/**
 * Modificar número de produtos por página (WooCommerce)
 */
function imd_blocks_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'imd_blocks_products_per_page', 20);

/**
 * Modificar número de cursos por página (LearnPress)
 */
function imd_blocks_courses_per_page($limit) {
    return 9;
}
add_filter('learn_press_courses_per_page', 'imd_blocks_courses_per_page');

/**
 * Adicionar classes customizadas ao body
 */
function imd_blocks_body_classes($classes) {
    if (class_exists('WooCommerce') && is_woocommerce()) {
        $classes[] = 'woocommerce-page';
    }
    
    if (class_exists('LearnPress') && learn_press_is_course()) {
        $classes[] = 'learn-press-page';
    }
    
    return $classes;
}
add_filter('body_class', 'imd_blocks_body_classes');
