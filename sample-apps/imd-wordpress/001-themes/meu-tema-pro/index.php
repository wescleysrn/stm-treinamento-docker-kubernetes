<?php
get_header(); // Carrega o cabeçalho padrão (ou cria um básico se não houver)
?>

<main style="padding: 50px; text-align: center;">
    <h1>Bem-vindo ao Controle Total</h1>
    <p>Este é o início da construção do seu império de cursos.</p>
    
    <?php
    // O famoso "The Loop" básico
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_title('<h2>', '</h2>');
            the_content();
        endwhile;
    else :
        echo '<p>Nada encontrado.</p>';
    endif;
    ?>
</main>

<?php
get_footer(); // Carrega o rodapé
?>
