<?php
/**
 * Template Tags Personalizadas
 * 
 * @package IMD_Academy_Classic
 */

if (!function_exists('imd_classic_posted_on')) :
    /**
     * Exibe informações de data do post
     */
    function imd_classic_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        
        $time_string = sprintf($time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date())
        );
        
        $posted_on = sprintf(
            esc_html_x('Publicado em %s', 'post date', 'imd-academy-classic'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );
        
        echo '<span class="posted-on">' . $posted_on . '</span>';
    }
endif;

if (!function_exists('imd_classic_posted_by')) :
    /**
     * Exibe informações do autor
     */
    function imd_classic_posted_by() {
        $byline = sprintf(
            esc_html_x('por %s', 'post author', 'imd-academy-classic'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );
        
        echo '<span class="byline"> ' . $byline . '</span>';
    }
endif;

if (!function_exists('imd_classic_entry_footer')) :
    /**
     * Exibe categorias e tags
     */
    function imd_classic_entry_footer() {
        // Categorias
        $categories_list = get_the_category_list(esc_html__(', ', 'imd-academy-classic'));
        if ($categories_list) {
            printf('<span class="cat-links">' . esc_html__('Categorias: %1$s', 'imd-academy-classic') . '</span>', $categories_list);
        }
        
        // Tags
        $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'imd-academy-classic'));
        if ($tags_list) {
            printf('<span class="tags-links">' . esc_html__('Tags: %1$s', 'imd-academy-classic') . '</span>', $tags_list);
        }
    }
endif;

if (!function_exists('imd_classic_post_thumbnail')) :
    /**
     * Exibe thumbnail do post
     */
    function imd_classic_post_thumbnail() {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }
        
        if (is_singular()) : ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php else : ?>
            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('post-thumbnail', array('alt' => the_title_attribute(array('echo' => false)))); ?>
            </a>
        <?php endif;
    }
endif;
