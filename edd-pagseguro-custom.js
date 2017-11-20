jQuery(document).ready(function( $ ) {

    console.log("Easy Digital Downloads - EDD PagSeguro custom gateway");

    /**
     * O botão que dispara a transação com o PagSeguro
     * está dentro do modal que é exibido para o usuário
     * apenas se o mesmo estiver logado.
     * 
     * O formulário está presente nas páginas internas de
     * cada ferramenta apenas para separar as informações
     * referentes a action do wp_ajax e ID do produto.
     */
    var buyButtonSelector    = '#proceed-to-payment, #proceed-to-download',
        purchaseFormSelector = '.edd-pagseguro-purchase-form',
        $buyButton    = $(buyButtonSelector),
        $purchaseForm = $(purchaseFormSelector);

    $buyButton.on('click', function(e) {
        e.preventDefault();

        var $this = $(this);
        // Desabilita o botão para evitar multiplos cliques
        $this.prop('disabled', true);

        // Exibe uma mensagem de "loading"
        // enquanto o usuário não é redirecionado
        $this.text('Processando...');

        // Prepara os dados para o POST via ajax
        var data = {
            action: $purchaseForm.attr('action'),
            download_id: $purchaseForm.children('input[name="download_id"]').val()
        };

        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            // O script deve estar devidamente registrado com
            // wp_localize_script() para apontar corretamente para a action
            url: edd_custom_scripts.ajaxurl,
            xhrFields: {
                withCredentials: true
            },
            success: function (response) {
                // Se o processamento retornar o código de transação
                // do PagSeguro, redirecionamos o usuário
                if ( response.hasOwnProperty('code') ) {

                    var redirect_uri = response.checkout_uri + '?code=' + response.code[0];

                    if ( redirect_uri.length > 0 )
                        window.location.href = redirect_uri;

                } else if ( response.hasOwnProperty('download_uri') ) {

                    window.location.href = response.download_uri;
                    // location.reload();

                } else if ( response.hasOwnProperty('error') ) {

                    console.log( response.message );

                }
            },
            error: function (exception) {
                console.log(exception);
            }
        }).fail(function (response) {
            if ( window.console && window.console.log ) {
                console.log(response);
            }
        }).done(function (response) {

        });

        return false;
    });

});

