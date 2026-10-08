<?php
/**
 * Header Template
 * 
 * @package IMD_Academy_Classic
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php _e('Pular para o conteúdo', 'imd-academy-classic'); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="header-container">
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()) :
                        the_custom_logo();
                    else : ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                    <?php endif; ?>
                </div>

                <nav id="site-navigation" class="main-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'container' => false,
                        'fallback_cb' => function() {
                            echo '<ul>';
                            echo '<li><a href="' . esc_url(home_url('/')) . '">Home</a></li>';
                            echo '<li><a href="' . esc_url(get_post_type_archive_link('lp_course')) . '">Cursos</a></li>';
                            if (class_exists('WooCommerce')) {
                                echo '<li><a href="' . esc_url(wc_get_page_permalink('shop')) . '">Loja</a></li>';
                            }
                            echo '<li><a href="' . esc_url(home_url('/blog')) . '">Blog</a></li>';
                            echo '<li><a href="' . esc_url(home_url('/contato')) . '">Contato</a></li>';
                            echo '</ul>';
                        }
                    ));
                    ?>
                </nav>
            </div>
        </div>
    </header>
</div>
