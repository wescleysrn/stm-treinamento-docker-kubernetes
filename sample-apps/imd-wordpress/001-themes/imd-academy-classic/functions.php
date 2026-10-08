<?php
/**
 * IMD Academy Classic Functions
 * 
 * @package IMD_Academy_Classic
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function imd_classic_setup() {
    // Suporte a tradução
    load_theme_textdomain('imd-academy-classic', get_template_directory() . '/languages');
    
    // Suporte a título automático
    add_theme_support('title-tag');
    
    // Suporte a imagens destacadas
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 630, true);
    
    // Tamanhos de imagem personalizados
    add_image_size('course-thumbnail', 400, 250, true);
    add_image_size('product-thumbnail', 400, 400, true);
    
    // Suporte a HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style'
    ));
    
    // Suporte a logo customizado
    add_theme_support('custom-logo', array(
        'height' => 60,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true,
    ));
    
    // Suporte a refresh automático do customizer
    add_theme_support('customize-selective-refresh-widgets');
    
    // Suporte a RSS feed links
    add_theme_support('automatic-feed-links');
    
    // Registrar menus
    register_nav_menus(array(
        'primary' => __('Menu Principal', 'imd-academy-classic'),
        'footer' => __('Menu do Rodapé', 'imd-academy-classic'),
    ));
    
    // Suporte a WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'imd_classic_setup');

/**
 * Enfileirar scripts e estilos
 */
function imd_classic_scripts() {
    // Estilo principal
    wp_enqueue_style('imd-classic-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Script principal (se necessário)
    wp_enqueue_script('imd-classic-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.0', true);
    
    // Script de comentários
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'imd_classic_scripts');

/**
 * Registrar áreas de widgets
 */
function imd_classic_widgets_init() {
    // Sidebar principal
    register_sidebar(array(
        'name' => __('Sidebar Principal', 'imd-academy-classic'),
        'id' => 'sidebar-1',
        'description' => __('Aparece nas páginas com sidebar', 'imd-academy-classic'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    // Footer widgets
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name' => sprintf(__('Footer Widget %d', 'imd-academy-classic'), $i),
            'id' => 'footer-' . $i,
            'description' => sprintf(__('Widget area %d no rodapé', 'imd-academy-classic'), $i),
            'before_widget' => '<div class="footer-widget">',
            'after_widget' => '</div>',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
        ));
    }
}
add_action('widgets_init', 'imd_classic_widgets_init');

/**
 * Customizar comprimento do excerpt
 */
function imd_classic_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'imd_classic_excerpt_length');

/**
 * Customizar "read more" do excerpt
 */
function imd_classic_excerpt_more($more) {
    return '... <a href="' . get_permalink() . '" class="read-more">' . __('Leia mais', 'imd-academy-classic') . '</a>';
}
add_filter('excerpt_more', 'imd_classic_excerpt_more');

/**
 * Adicionar classes ao body
 */
function imd_classic_body_classes($classes) {
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }
    
    if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'has-sidebar';
    }
    
    return $classes;
}
add_filter('body_class', 'imd_classic_body_classes');

/**
 * Configurações do WooCommerce
 */
function imd_classic_woocommerce_setup() {
    // Remover ações padrão do WooCommerce se necessário
    // remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    // remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
}
add_action('after_setup_theme', 'imd_classic_woocommerce_setup');

/**
 * Modificar número de produtos por página
 */
function imd_classic_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'imd_classic_products_per_page', 20);

/**
 * Configurações do LearnPress
 */
function imd_classic_learnpress_setup() {
    // Adicionar suporte ao LearnPress
    add_theme_support('learnpress');
}
add_action('after_setup_theme', 'imd_classic_learnpress_setup');

/**
 * Modificar número de cursos por página
 */
function imd_classic_courses_per_page($limit) {
    return 9;
}
add_filter('learn_press_courses_per_page', 'imd_classic_courses_per_page');

/**
 * Função auxiliar para exibir cursos em destaque
 */
function imd_classic_get_featured_courses($number = 3) {
    $args = array(
        'post_type' => 'lp_course',
        'posts_per_page' => $number,
        'meta_query' => array(
            array(
                'key' => '_lp_featured',
                'value' => 'yes',
            ),
        ),
    );
    
    return new WP_Query($args);
}

/**
 * Função auxiliar para exibir produtos em destaque
 */
function imd_classic_get_featured_products($number = 4) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $number,
        'meta_query' => array(
            array(
                'key' => '_featured',
                'value' => 'yes',
            ),
        ),
    );
    
    return new WP_Query($args);
}

/**
 * Customizer additions
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Template tags personalizadas
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Walker de navegação personalizado (opcional)
 */
// require get_template_directory() . '/inc/class-walker-nav-menu.php';
