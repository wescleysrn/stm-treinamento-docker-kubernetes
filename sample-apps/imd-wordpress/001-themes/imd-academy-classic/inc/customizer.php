<?php
/**
 * Theme Customizer
 * 
 * @package IMD_Academy_Classic
 */

function imd_classic_customize_register($wp_customize) {
    
    // Seção Hero Banner
    $wp_customize->add_section('imd_hero_section', array(
        'title' => __('Hero Banner', 'imd-academy-classic'),
        'priority' => 30,
    ));
    
    // Hero Title
    $wp_customize->add_setting('hero_title', array(
        'default' => __('Transforme seu Futuro com Educação de Qualidade', 'imd-academy-classic'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_title', array(
        'label' => __('Título do Hero', 'imd-academy-classic'),
        'section' => 'imd_hero_section',
        'type' => 'text',
    ));
    
    // Hero Description
    $wp_customize->add_setting('hero_description', array(
        'default' => __('Aprenda com os melhores cursos online e desenvolva suas habilidades para alcançar seus objetivos profissionais.', 'imd-academy-classic'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('hero_description', array(
        'label' => __('Descrição do Hero', 'imd-academy-classic'),
        'section' => 'imd_hero_section',
        'type' => 'textarea',
    ));
    
    // Cores personalizadas
    $wp_customize->add_setting('primary_color', array(
        'default' => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label' => __('Cor Primária', 'imd-academy-classic'),
        'section' => 'colors',
    )));
    
    $wp_customize->add_setting('secondary_color', array(
        'default' => '#7c3aed',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
        'label' => __('Cor Secundária', 'imd-academy-classic'),
        'section' => 'colors',
    )));
}
add_action('customize_register', 'imd_classic_customize_register');

/**
 * CSS inline do customizer
 */
function imd_classic_customizer_css() {
    $primary_color = get_theme_mod('primary_color', '#2563eb');
    $secondary_color = get_theme_mod('secondary_color', '#7c3aed');
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr($primary_color); ?>;
            --secondary-color: <?php echo esc_attr($secondary_color); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'imd_classic_customizer_css');
