<?php

function tema_dominio_setup() {
    // 1. Suporte básico
    add_theme_support( 'title-tag' ); // Título da aba do navegador dinâmico
    add_theme_support( 'post-thumbnails' ); // Imagens destacadas
    add_theme_support( 'custom-logo' );

    // 2. Suporte ao WooCommerce (Crucial para não quebrar o layout)
    add_theme_support( 'woocommerce' );
    
    // Habilita galeria moderna do Woo
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // 3. Suporte ao LearnPress (Geralmente automático, mas bom preparar hooks)
    // O LearnPress usa o sistema de templates do WP, então focaremos na hierarquia.
}
// O hook 'after_setup_theme' roda assim que o tema é carregado
add_action( 'after_setup_theme', 'tema_dominio_setup' );

/**
 * Enfileirar Scripts e Estilos (O jeito certo de carregar CSS/JS)
 */
function tema_dominio_scripts() {
    // Carrega o CSS principal (o que compilaremos no futuro)
    wp_enqueue_style( 'tema-dominio-style', get_stylesheet_uri() );
    
    // Aqui no futuro carregaremos o /dist/css/main.css
}
add_action( 'wp_enqueue_scripts', 'tema_dominio_scripts' );
