<?php get_header(); ?>

<?php
    if( class_exists( 'EDD_Download' ) ):

        if( have_posts() ): while( have_posts() ): the_post();
?>

            <!-- Start: Page header -->
            <section class="container-fluid">
                <div class="row c-post__header business">
                    <figure>
                        <img src="https://source.unsplash.com/collection/160236/1600x1100" class="o-objectFit">
                    </figure>
                    <div class="c-post__title">
                        <p class="o-post__tag business">Ferramenta</p>
                        <h2 class="o-post__title business">
                            <span><?php the_title(); ?></span>
                        </h2>
                    </div>
                </div>
            </section><!-- End: Page header -->

            <!-- Start: Page content -->
            <section class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
                        <div class="row">

                            <!-- Start: .c-post__info -->
                            <div class="c-post__info business">
                                <!-- Start: Social Media links -->
                                <div class="c-social hidden-xs">
                                    <a href="https://www.instagram.com/dedodemoca/" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31">
                                            <g fill="none" fill-rule="evenodd">
                                                <path fill="#FFF" d="M15.5 30.539c8.56 0 15.5-6.837 15.5-15.27S24.06 0 15.5 0C6.94 0 0 6.836 0 15.27c0 8.432 6.94 15.269 15.5 15.269z"/>
                                                <g fill="#000">
                                                    <path d="M15.497 5.007c-2.851 0-3.208.012-4.328.063-1.117.05-1.88.228-2.548.488a5.145 5.145 0 0 0-1.86 1.21 5.145 5.145 0 0 0-1.21 1.86c-.26.667-.437 1.43-.488 2.548-.05 1.12-.063 1.477-.063 4.327 0 2.851.012 3.209.063 4.328.051 1.117.229 1.88.488 2.548.268.69.627 1.276 1.21 1.86a5.146 5.146 0 0 0 1.86 1.21c.668.26 1.43.437 2.548.488 1.12.05 1.477.063 4.328.063 2.85 0 3.208-.012 4.327-.063 1.118-.051 1.88-.229 2.548-.488a5.146 5.146 0 0 0 1.86-1.21 5.146 5.146 0 0 0 1.21-1.86c.26-.668.437-1.43.488-2.548.051-1.12.063-1.477.063-4.328 0-2.85-.012-3.208-.063-4.327-.05-1.118-.228-1.88-.488-2.548a5.146 5.146 0 0 0-1.21-1.86 5.145 5.145 0 0 0-1.86-1.21c-.667-.26-1.43-.437-2.548-.488-1.12-.051-1.477-.063-4.327-.063zm0 1.89c2.802 0 3.134.012 4.241.062 1.024.047 1.58.218 1.95.362.49.19.839.417 1.206.785.368.367.595.717.785 1.207.144.37.315.925.362 1.949.05 1.107.061 1.439.061 4.241 0 2.803-.01 3.135-.061 4.242-.047 1.023-.218 1.58-.362 1.949-.19.49-.417.84-.785 1.207a3.252 3.252 0 0 1-1.207.785c-.37.144-.925.315-1.949.361-1.106.051-1.438.062-4.241.062s-3.135-.011-4.242-.062c-1.023-.046-1.58-.217-1.949-.36-.49-.191-.84-.419-1.207-.786a3.252 3.252 0 0 1-.785-1.207c-.144-.37-.315-.926-.362-1.95-.05-1.106-.06-1.438-.06-4.24 0-2.803.01-3.135.06-4.242.047-1.024.218-1.58.362-1.95.19-.49.418-.839.785-1.206a3.252 3.252 0 0 1 1.207-.785c.37-.144.926-.315 1.95-.362 1.106-.05 1.438-.061 4.24-.061z"/>
                                                    <path d="M15.497 19.002a3.499 3.499 0 1 1 0-6.998 3.499 3.499 0 0 1 0 6.998zm0-8.889a5.39 5.39 0 1 0 0 10.78 5.39 5.39 0 0 0 0-10.78zM22.36 9.9a1.26 1.26 0 1 1-2.52 0 1.26 1.26 0 0 1 2.52 0"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                    <a href="https://br.pinterest.com/dedodemoca/" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31">
                                            <g fill="none" fill-rule="evenodd">
                                                <path fill="#FFF" d="M15.5 31C24.06 31 31 24.06 31 15.5 31 6.94 24.06 0 15.5 0 6.94 0 0 6.94 0 15.5 0 24.06 6.94 31 15.5 31z"/>
                                                <path fill="#000" d="M11.06 23.332c-.032-.766-.007-1.686.193-2.52l1.432-5.979s-.356-.7-.356-1.736c0-1.626.956-2.84 2.147-2.84 1.012 0 1.5.75 1.5 1.647 0 1.003-.648 2.504-.982 3.894-.279 1.164.592 2.113 1.757 2.113 2.108 0 3.528-2.67 3.528-5.834 0-2.404-1.642-4.204-4.63-4.204-3.376 0-5.48 2.482-5.48 5.254 0 .956.286 1.63.734 2.152.206.24.235.337.16.612-.054.202-.176.688-.227.88-.074.279-.302.378-.557.276C8.724 16.42 8 14.742 8 12.854 8 9.737 10.667 6 15.954 6 20.203 6 23 9.031 23 12.285c0 4.305-2.428 7.521-6.005 7.521-1.202 0-2.332-.64-2.719-1.368 0 0-.646 2.528-.783 3.016-.236.846-.698 1.692-1.12 2.35 0 0-.562.335-1.002.13-.335-.157-.312-.602-.312-.602"/>
                                            </g>
                                        </svg>
                                    </a>
                                    <a href="https://www.youtube.com/user/dedodemocavideos" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31">
                                            <g fill="none" fill-rule="evenodd">
                                                <path fill="#FFF" d="M15.5 30.539c8.56 0 15.5-6.837 15.5-15.27S24.06 0 15.5 0C6.94 0 0 6.836 0 15.27c0 8.432 6.94 15.269 15.5 15.269z"/>
                                                <path fill="#000" d="M13.352 18.452v-6.204l5.489 3.102-5.489 3.102zm12.21-8.157a2.63 2.63 0 0 0-1.857-1.856C22.067 8 15.5 8 15.5 8s-6.567 0-8.205.439a2.63 2.63 0 0 0-1.856 1.856C5 11.933 5 15.35 5 15.35s0 3.417.439 5.055a2.63 2.63 0 0 0 1.856 1.856c1.638.439 8.205.439 8.205.439s6.567 0 8.205-.439a2.63 2.63 0 0 0 1.856-1.856C26 18.767 26 15.35 26 15.35s0-3.417-.439-5.055z"/>
                                            </g>
                                        </svg>
                                    </a>
                                    <a href="https://www.facebook.com/dedodemocapersonalchef" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31">
                                            <g fill="none" fill-rule="evenodd">
                                                <path fill="#FFF" d="M15.5 30.539c8.56 0 15.5-6.837 15.5-15.27S24.06 0 15.5 0C6.94 0 0 6.836 0 15.27c0 8.432 6.94 15.269 15.5 15.269z"/>
                                                <path fill="#000" d="M19.957 15.79l.419-3.2h-3.217v-2.043c0-.927.261-1.558 1.61-1.558l1.72-.001V6.126A23.364 23.364 0 0 0 17.982 6c-2.48 0-4.177 1.491-4.177 4.23v2.36H11v3.2h2.805V24h3.354v-8.21h2.798z"/>
                                            </g>
                                        </svg>
                                    </a>
                                </div><!-- End: Social Media links -->
                                <!-- Start: Buy button -->
                                <div class="col-sm-5 c-post__icons full">
                                    <?php echo do_shortcode( '[purchase_tool]' ); ?>
                                </div><!-- End: Buy button -->
                            </div><!-- End: .c-post__info -->

                            <!-- Start: .c-post__blog -->
                            <div class="c-post__blog full clearfix">
                                <!-- Start: Post the_content -->
                                <div class="col-sm-9">
                                    <?php the_content(); ?>
                                </div><!-- End: Post the_content -->
                                <!-- Start: Sidebar banner -->
                                <div class="col-sm-3">
                                    <div class="c-banner">
                                        <figure>
                                            <img src="imgs/banner-vertical.png">
                                        </figure>
                                    </div>
                                </div><!-- End: Sidebar banner -->
                            </div><!-- End: .c-post__blog -->

                        </div>
                    </div>
                </div>
            </section><!-- End: Page content -->

            <!-- Start: EDD PagSeguro purchase form -->
            <form id="edd-purchase-form-<?php the_ID(); ?>" class="edd-pagseguro-purchase-form" method="post" action="edd_set_pagseguro_purchase">
                <input type="hidden" name="download_id" value="<?php the_ID(); ?>">
            </form><!-- End: EDD PagSeguro purchase form -->

<?php
        endwhile; endif;

    endif;
?>


<!-- Start: Modal -->
<div class="modal fade c-filter__modal card" id="purchase-modal" tabindex="-1" role="dialog">
    <div class="modal-content">
        <div class="modal-body">
            <div class="c-profile">
                <div class="c-profile__card">

                <?php if ( is_user_logged_in() ) : ?>
                    <!-- Start: Modal content - Logged in -->
                    <div class="c-profile__title">
                        <img src="https://source.unsplash.com/collection/787231/1600x1100" class="o-objectFit">
                    </div>
                    <h2 class="o-profile__name">Eric Clapton</h2>
                    <p class="o-profile__email">slowhand@fender.com</p>
                    <p class="o-error__text">Você será redirecionado agora ao PagSeguro para concluir sua compra!</p>
                    <button type="button" id="proceed-to-payment" class="o-buyButton sm c-profile__content">Ok, entendi!</button>
                    <!-- End: Modal content - Logged in -->

                    <?php else : ?>

                    <!-- Start: Modal content - Not logged in -->
                    <div class="c-profile__cardHeader">
                        <img src="https://source.unsplash.com/collection/330689/1600x1100" class="o-objectFit">
                    </div>
                    <p class="o-error__text">Faça login para acessar todas as funcionalidades do portal!</p>
                    <a href="#" class="o-input__facebook c-profile__facebook"><span><svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31"><path fill="#FFF" fill-rule="evenodd" d="M19.957 15.79l.419-3.2h-3.217v-2.043c0-.927.261-1.558 1.61-1.558l1.72-.001V6.126A23.364 23.364 0 0 0 17.982 6c-2.48 0-4.177 1.491-4.177 4.23v2.36H11v3.2h2.805V24h3.354v-8.21h2.798z"/></svg></span>Entrar usando o Facebook</a>

                    <form class="c-profile__form">
                        <label class="o-input__label">Email</label>
                        <input type="email" name="" placeholder="Email" class="c-input__login">
                        <label class="o-input__label">Senha</label>
                        <input type="password" name="" placeholder="Senha" class="c-input__login">
                        <div class="c-profile__submit">
                            <input type="submit" value="Entrar" class="c-input__send login">
                        </div>
                        <p class="o-input__terms"><a href="#">Esqueci minha senha</a></p>
                    </form>

                    <hr class="c-profile__line">

                    <a href="sign-up.html" class="o-buyButton sm">Criar conta</a>
                    <!-- End: Modal content - Not logged in -->

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div><!-- End: Modal -->


<?php get_footer(); ?>