<footer id="colophon" class="site-footer">
        <div class="container">
            <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
                <div class="footer-widgets">
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <?php if (is_active_sidebar('footer-' . $i)) : ?>
                            <?php dynamic_sidebar('footer-' . $i); ?>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php else : ?>
                <div class="footer-widgets">
                    <div class="footer-widget">
                        <h3><?php _e('Sobre a IMD Academy', 'imd-academy-classic'); ?></h3>
                        <p><?php _e('Plataforma líder em educação online, oferecendo cursos de qualidade para transformar carreiras e vidas.', 'imd-academy-classic'); ?></p>
                    </div>
                    
                    <div class="footer-widget">
                        <h3><?php _e('Links Rápidos', 'imd-academy-classic'); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url(get_post_type_archive_link('lp_course')); ?>"><?php _e('Todos os Cursos', 'imd-academy-classic'); ?></a></li>
                            <?php if (class_exists('WooCommerce')) : ?>
                                <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php _e('Loja', 'imd-academy-classic'); ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo esc_url(home_url('/blog')); ?>"><?php _e('Blog', 'imd-academy-classic'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/sobre')); ?>"><?php _e('Sobre Nós', 'imd-academy-classic'); ?></a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-widget">
                        <h3><?php _e('Suporte', 'imd-academy-classic'); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/faq')); ?>"><?php _e('FAQ', 'imd-academy-classic'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/contato')); ?>"><?php _e('Contato', 'imd-academy-classic'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/politica-privacidade')); ?>"><?php _e('Política de Privacidade', 'imd-academy-classic'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/termos-uso')); ?>"><?php _e('Termos de Uso', 'imd-academy-classic'); ?></a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-widget">
                        <h3><?php _e('Contato', 'imd-academy-classic'); ?></h3>
                        <p>
                            <?php _e('Email:', 'imd-academy-classic'); ?> contato@imdacademy.com<br>
                            <?php _e('Telefone:', 'imd-academy-classic'); ?> (11) 9999-9999<br>
                            <?php _e('Horário:', 'imd-academy-classic'); ?> Seg-Sex, 9h-18h
                        </p>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="site-info">
                <p>
                    &copy; <?php echo date('Y'); ?> 
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php bloginfo('name'); ?>
                    </a>
                    <?php _e('- Todos os direitos reservados', 'imd-academy-classic'); ?>
                </p>
            </div>
        </div>
    </footer>
</div>

<?php wp_footer(); ?>

</body>
</html>
