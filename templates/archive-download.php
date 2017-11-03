<?php get_header(); ?>

<?php
    if( class_exists( 'EDD_Download' ) ):

        if( have_posts() ): while( have_posts() ): the_post();
?>

            <div class="col-xs-12 col-sm-4 c-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <figure>
                        <img src="<?php the_post_thumbnail(); ?>">
                        <p class="o-thumbnail__tag business">Ferramenta</p>
                    </figure>
                    <h2 class="o-thumbnail__title"><?php the_title(); ?></h2>
                </a>
            </div>

<?php endwhile; else : ?>

            <p><?php _e( 'Não foram encontradas ferramentas.' ); ?></p>

<?php
         endif;
    endif;
?>

<?php get_footer(); ?>



