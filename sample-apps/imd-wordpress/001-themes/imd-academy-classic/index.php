<?php
/**
 * Template Principal
 * 
 * @package IMD_Academy_Classic
 */

get_header(); ?>

<main id="primary" class="site-main">
    
    <?php if (is_home() && !is_paged()) : ?>
        <!-- Hero Banner -->
        <section class="hero-banner">
            <div class="container">
                <div class="hero-content">
                    <h1><?php echo esc_html(get_theme_mod('hero_title', 'Transforme seu Futuro com Educação de Qualidade')); ?></h1>
                    <p><?php echo esc_html(get_theme_mod('hero_description', 'Aprenda com os melhores cursos online e desenvolva suas habilidades para alcançar seus objetivos profissionais.')); ?></p>
                    <a href="<?php echo esc_url(get_post_type_archive_link('lp_course')); ?>" class="btn"><?php _e('Explorar Cursos', 'imd-academy-classic'); ?></a>
                    <a href="#courses" class="btn btn-secondary"><?php _e('Saiba Mais', 'imd-academy-classic'); ?></a>
                </div>
            </div>
        </section>

        <!-- Cursos em Destaque -->
        <?php
        $featured_courses = imd_classic_get_featured_courses(3);
        if ($featured_courses->have_posts()) : ?>
            <section id="courses" class="courses-section">
                <div class="container">
                    <div class="section-header">
                        <h2><?php _e('Cursos em Destaque', 'imd-academy-classic'); ?></h2>
                        <p><?php _e('Descubra os cursos mais populares e comece sua jornada de aprendizado hoje mesmo', 'imd-academy-classic'); ?></p>
                    </div>
                    
                    <div class="courses-grid">
                        <?php while ($featured_courses->have_posts()) : $featured_courses->the_post(); ?>
                            <article class="course-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php the_post_thumbnail_url('course-thumbnail'); ?>" alt="<?php the_title_attribute(); ?>" class="course-thumbnail">
                                <?php else : ?>
                                    <div class="course-thumbnail"></div>
                                <?php endif; ?>
                                
                                <div class="course-content">
                                    <h3 class="course-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="course-meta">
                                        <?php
                                        $course = learn_press_get_course(get_the_ID());
                                        if ($course) :
                                            $students = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0;
                                            $lessons = $course->get_curriculum_items('lp_lesson') ? count($course->get_curriculum_items('lp_lesson')) : 0;
                                        ?>
                                            <span>📚 <?php echo esc_html($lessons); ?> <?php _e('Aulas', 'imd-academy-classic'); ?></span>
                                            <span>👥 <?php echo esc_html($students); ?> <?php _e('Alunos', 'imd-academy-classic'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                    
                                    <?php if ($course) : ?>
                                        <div class="course-price">
                                            <?php echo $course->get_price_html(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    
                    <div style="text-align: center; margin-top: 2rem;">
                        <a href="<?php echo esc_url(get_post_type_archive_link('lp_course')); ?>" class="btn"><?php _e('Ver Todos os Cursos', 'imd-academy-classic'); ?></a>
                    </div>
                </div>
            </section>
        <?php
        wp_reset_postdata();
        endif;
        ?>

        <!-- Produtos em Destaque (WooCommerce) -->
        <?php
        if (class_exists('WooCommerce')) :
            $featured_products = imd_classic_get_featured_products(4);
            if ($featured_products->have_posts()) : ?>
                <section class="products-section" style="background: var(--light-color);">
                    <div class="container">
                        <div class="section-header">
                            <h2><?php _e('Produtos em Destaque', 'imd-academy-classic'); ?></h2>
                            <p><?php _e('Materiais complementares e recursos para potencializar seu aprendizado', 'imd-academy-classic'); ?></p>
                        </div>
                        
                        <div class="products-grid">
                            <?php while ($featured_products->have_posts()) : $featured_products->the_post(); ?>
                                <article class="product-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <img src="<?php the_post_thumbnail_url('product-thumbnail'); ?>" alt="<?php the_title_attribute(); ?>" class="product-thumbnail">
                                    <?php else : ?>
                                        <div class="product-thumbnail"></div>
                                    <?php endif; ?>
                                    
                                    <div class="product-content">
                                        <h3 class="product-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        
                                        <p><?php echo wp_trim_words(get_the_excerpt(), 12); ?></p>
                                        
                                        <div class="product-price">
                                            <?php
                                            $product = wc_get_product(get_the_ID());
                                            echo $product->get_price_html();
                                            ?>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                        
                        <div style="text-align: center; margin-top: 2rem;">
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn"><?php _e('Ver Todos os Produtos', 'imd-academy-classic'); ?></a>
                        </div>
                    </div>
                </section>
            <?php
            wp_reset_postdata();
            endif;
        endif;
        ?>
    <?php endif; ?>

    <!-- Loop de Posts -->
    <div class="container">
        <div class="content-area" style="padding: 3rem 0;">
            <?php
            if (have_posts()) :
                if (is_home() && !is_front_page()) : ?>
                    <header>
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif;

                while (have_posts()) : the_post();
                    get_template_part('template-parts/content', get_post_type());
                endwhile;

                the_posts_navigation();
            else :
                get_template_part('template-parts/content', 'none');
            endif;
            ?>
        </div>
    </div>

</main>

<?php
get_footer();
