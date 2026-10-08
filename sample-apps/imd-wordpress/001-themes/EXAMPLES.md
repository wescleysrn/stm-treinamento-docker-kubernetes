# Exemplos Práticos de Extensão

## 🎯 Classic Theme - Exemplos Avançados

### 1. Template para Curso Individual (single-lp_course.php)

```php
<?php
/**
 * Template para exibir curso individual do LearnPress
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            $course = learn_press_get_course(get_the_ID());
            ?>
            
            <article id="course-<?php the_ID(); ?>" <?php post_class('course-single'); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
                    <div class="course-meta">
                        <?php if ($course) : ?>
                            <span class="course-students">
                                👥 <?php echo $course->get_users_enrolled(); ?> alunos
                            </span>
                            <span class="course-duration">
                                ⏱️ <?php echo $course->get_duration(); ?>
                            </span>
                            <span class="course-level">
                                📊 <?php echo $course->get_level(); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>

                <div class="course-content-area">
                    <div class="course-main">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="course-thumbnail">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="course-description">
                            <?php the_content(); ?>
                        </div>

                        <?php
                        // Curriculum
                        if ($course) :
                            learn_press_get_template('single-course/curriculum.php');
                        endif;
                        ?>
                    </div>

                    <aside class="course-sidebar">
                        <div class="course-price-box">
                            <?php if ($course) : ?>
                                <div class="price">
                                    <?php echo $course->get_price_html(); ?>
                                </div>
                                <?php learn_press_get_template('single-course/buttons.php'); ?>
                            <?php endif; ?>
                        </div>

                        <div class="course-info-box">
                            <h3>Informações do Curso</h3>
                            <ul>
                                <li>📚 <strong>Aulas:</strong> <?php echo $course->count_items('lp_lesson'); ?></li>
                                <li>📝 <strong>Quiz:</strong> <?php echo $course->count_items('lp_quiz'); ?></li>
                                <li>⏱️ <strong>Duração:</strong> <?php echo $course->get_duration(); ?></li>
                                <li>🎓 <strong>Nível:</strong> <?php echo $course->get_level(); ?></li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </article>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
```

### 2. Widget Customizado de Cursos Populares

```php
<?php
// Adicionar em functions.php ou criar arquivo inc/widgets.php

class IMD_Popular_Courses_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'imd_popular_courses',
            'IMD - Cursos Populares',
            array('description' => 'Exibe cursos mais populares')
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        
        $courses = new WP_Query(array(
            'post_type' => 'lp_course',
            'posts_per_page' => $number,
            'meta_key' => '_lp_students',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        ));
        
        if ($courses->have_posts()) :
            echo '<ul class="popular-courses-widget">';
            while ($courses->have_posts()) : $courses->the_post();
                ?>
                <li>
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail'); ?>
                        <?php endif; ?>
                        <span class="course-title"><?php the_title(); ?></span>
                    </a>
                </li>
                <?php
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        endif;
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Cursos Populares';
        $number = !empty($instance['number']) ? $instance['number'] : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Título:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>">Número de cursos:</label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" 
                   name="<?php echo $this->get_field_name('number'); ?>" type="number" 
                   step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
        return $instance;
    }
}

// Registrar widget
function imd_register_widgets() {
    register_widget('IMD_Popular_Courses_Widget');
}
add_action('widgets_init', 'imd_register_widgets');
```

### 3. Shortcode para Exibir Cursos por Categoria

```php
<?php
// Adicionar em functions.php

function imd_courses_by_category_shortcode($atts) {
    $atts = shortcode_atts(array(
        'category' => '',
        'number' => 3,
        'columns' => 3
    ), $atts, 'imd_courses');
    
    $args = array(
        'post_type' => 'lp_course',
        'posts_per_page' => $atts['number']
    );
    
    if (!empty($atts['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'course_category',
                'field' => 'slug',
                'terms' => $atts['category']
            )
        );
    }
    
    $courses = new WP_Query($args);
    
    if (!$courses->have_posts()) {
        return '<p>Nenhum curso encontrado.</p>';
    }
    
    ob_start();
    ?>
    
    <div class="imd-courses-grid" style="display: grid; grid-template-columns: repeat(<?php echo esc_attr($atts['columns']); ?>, 1fr); gap: 2rem;">
        <?php while ($courses->have_posts()) : $courses->the_post(); 
            $course = learn_press_get_course(get_the_ID());
        ?>
            <div class="course-card">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('course-thumbnail'); ?>
                    </a>
                <?php endif; ?>
                
                <div class="course-content">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                    
                    <?php if ($course) : ?>
                        <div class="course-price">
                            <?php echo $course->get_price_html(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('imd_courses', 'imd_courses_by_category_shortcode');

// Uso: [imd_courses category="programacao" number="6" columns="3"]
```

## 🎨 Block Theme - Exemplos Avançados

### 1. Padrão de Bloco: Seção de Depoimentos

```php
<?php
// Adicionar em functions.php

function imd_blocks_testimonials_pattern() {
    register_block_pattern(
        'imd-academy-blocks/testimonials',
        array(
            'title' => __('Seção de Depoimentos', 'imd-academy-blocks'),
            'description' => __('Seção com depoimentos de alunos', 'imd-academy-blocks'),
            'categories' => array('imd-featured'),
            'content' => '<!-- wp:group {"align":"full","backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background">
    
    <!-- wp:heading {"textAlign":"center","fontSize":"x-large"} -->
    <h2 class="wp-block-heading has-text-align-center has-x-large-font-size">O Que Nossos Alunos Dizem</h2>
    <!-- /wp:heading -->
    
    <!-- wp:columns -->
    <div class="wp-block-columns">
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"backgroundColor":"base","className":"testimonial-card"} -->
            <div class="wp-block-group testimonial-card has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
                
                <!-- wp:paragraph -->
                <p>"Os cursos da IMD Academy transformaram minha carreira. Consegui uma promoção em apenas 6 meses!"</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {"style":{"typography":{"fontWeight":"700"}},"textColor":"primary"} -->
                <p class="has-primary-color has-text-color" style="font-weight:700">— Maria Silva, Desenvolvedora</p>
                <!-- /wp:paragraph -->
                
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"backgroundColor":"base","className":"testimonial-card"} -->
            <div class="wp-block-group testimonial-card has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
                
                <!-- wp:paragraph -->
                <p>"Conteúdo de altíssima qualidade e professores excepcionais. Recomendo muito!"</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {"style":{"typography":{"fontWeight":"700"}},"textColor":"primary"} -->
                <p class="has-primary-color has-text-color" style="font-weight:700">— João Santos, Designer</p>
                <!-- /wp:paragraph -->
                
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"backgroundColor":"base","className":"testimonial-card"} -->
            <div class="wp-block-group testimonial-card has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
                
                <!-- wp:paragraph -->
                <p>"Plataforma intuitiva e cursos bem estruturados. Aprendi muito mais do que esperava!"</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {"style":{"typography":{"fontWeight":"700"}},"textColor":"primary"} -->
                <p class="has-primary-color has-text-color" style="font-weight:700">— Ana Costa, Empreendedora</p>
                <!-- /wp:paragraph -->
                
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
        
    </div>
    <!-- /wp:columns -->
    
</div>
<!-- /wp:group -->',
        )
    );
}
add_action('init', 'imd_blocks_testimonials_pattern');
```

### 2. Template de Curso Individual (templates/single-lp_course.html)

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","align":"full","layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull">
    
    <!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
    <div class="wp-block-group align-wide">
        
        <!-- wp:post-title {"level":1,"fontSize":"xx-large"} /-->
        
        <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group">
            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">👥 150 alunos</p>
            <!-- /wp:paragraph -->
            
            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">⏱️ 10 horas</p>
            <!-- /wp:paragraph -->
            
            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">📊 Intermediário</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
        
        <!-- wp:columns {"align":"wide"} -->
        <div class="wp-block-columns align-wide">
            
            <!-- wp:column {"width":"66.66%"} -->
            <div class="wp-block-column" style="flex-basis:66.66%">
                
                <!-- wp:post-featured-image {"aspectRatio":"16/9"} /-->
                
                <!-- wp:heading -->
                <h2 class="wp-block-heading">Sobre o Curso</h2>
                <!-- /wp:heading -->
                
                <!-- wp:post-content /-->
                
                <!-- wp:heading -->
                <h2 class="wp-block-heading">Conteúdo do Curso</h2>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph -->
                <p>O currículo será exibido aqui pelo LearnPress...</p>
                <!-- /wp:paragraph -->
                
            </div>
            <!-- /wp:column -->
            
            <!-- wp:column {"width":"33.33%"} -->
            <div class="wp-block-column" style="flex-basis:33.33%">
                
                <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"backgroundColor":"light","layout":{"type":"default"}} -->
                <div class="wp-block-group has-light-background-color has-background" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
                    
                    <!-- wp:heading {"level":3} -->
                    <h3 class="wp-block-heading">Informações</h3>
                    <!-- /wp:heading -->
                    
                    <!-- wp:list -->
                    <ul class="wp-block-list">
                        <li>📚 15 Aulas</li>
                        <li>📝 3 Quiz</li>
                        <li>⏱️ 10 horas</li>
                        <li>🎓 Certificado</li>
                        <li>♾️ Acesso vitalício</li>
                    </ul>
                    <!-- /wp:list -->
                    
                    <!-- wp:buttons -->
                    <div class="wp-block-buttons">
                        <!-- wp:button {"width":100} -->
                        <div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button">Inscrever-se Agora</a></div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                    
                </div>
                <!-- /wp:group -->
                
            </div>
            <!-- /wp:column -->
            
        </div>
        <!-- /wp:columns -->
        
    </div>
    <!-- /wp:group -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 3. Bloco Customizado via JavaScript

```javascript
// Criar arquivo: src/blocks/course-highlight/index.js

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl } from '@wordpress/components';

registerBlockType('imd/course-highlight', {
    title: 'Destaque de Curso',
    icon: 'welcome-learn-more',
    category: 'widgets',
    attributes: {
        courseId: {
            type: 'string',
            default: ''
        },
        layout: {
            type: 'string',
            default: 'horizontal'
        }
    },
    
    edit: ({ attributes, setAttributes }) => {
        const { courseId, layout } = attributes;
        const blockProps = useBlockProps();
        
        return (
            <>
                <InspectorControls>
                    <PanelBody title="Configurações do Curso">
                        <TextControl
                            label="ID do Curso"
                            value={courseId}
                            onChange={(value) => setAttributes({ courseId: value })}
                        />
                        <SelectControl
                            label="Layout"
                            value={layout}
                            options={[
                                { label: 'Horizontal', value: 'horizontal' },
                                { label: 'Vertical', value: 'vertical' }
                            ]}
                            onChange={(value) => setAttributes({ layout: value })}
                        />
                    </PanelBody>
                </InspectorControls>
                
                <div {...blockProps}>
                    <div style={{ 
                        border: '2px dashed #ccc', 
                        padding: '20px', 
                        textAlign: 'center' 
                    }}>
                        <p>Destaque de Curso</p>
                        <p>ID: {courseId || 'Nenhum curso selecionado'}</p>
                        <p>Layout: {layout}</p>
                    </div>
                </div>
            </>
        );
    },
    
    save: () => {
        return null; // Renderização dinâmica via PHP
    }
});
```

## 🎓 Dicas Finais

### Classic Theme
1. **Use child themes** para customizações maiores
2. **Hooks do WordPress** são seus amigos
3. **Template hierarchy** é fundamental
4. **Enqueue scripts/styles** corretamente

### Block Theme
1. **theme.json** é o coração do design
2. **Padrões de blocos** economizam tempo
3. **Templates HTML** são reutilizáveis
4. **Editor de site** permite customização visual

Ambos os themes são pontos de partida sólidos para aprender WordPress profundamente! 🚀
