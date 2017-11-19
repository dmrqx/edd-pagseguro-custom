<?php
/*
Plugin Name: EDD PagSeguro custom
Description: Add custom PagSeguro payment gateway for EDD
Author: Índice.in
Version: 1.0
Author URI: http://indice.in/
*/
if ( ! defined( 'ABSPATH' ) ) exit;

// Inicializa a classe para debug
// A opção deve ser ativada também nas configurações do EDD
if( class_exists( 'Easy_Digital_Downloads' ) ) {
    $GLOBALS['edd_logs'] = new EDD_Logging();
    // edd_debug_log( 'message' );
}


/**
 * Verifica se o Easy Digital Downloads está ativado e cria variáveis
 * de opção do WordPress para usarmos na inicialização deste plugin
 */
function edd_pagseguro_custom_activate() {

    $edd = 'easy-digital-downloads/easy-digital-downloads.php';

    if ( is_plugin_active( $edd ) ) {
        add_option( 'Activated_Plugin', 'edd_pagseguro_custom' );
    } else {
        add_option( 'Disable_Plugin', 'edd_pagseguro_custom' );
    }

}
register_activation_hook( __FILE__, 'edd_pagseguro_custom_activate' );


/**
 * Se a dependência estiver resolvida, cria o usuário de testes
 * Se não, desativa o plugin e exibe uma notificação no admin
 */
function edd_pagseguro_custom_load() {

    // Verifica a permissão do usuário e se nosso plugin está ativo
    if ( current_user_can('activate_plugins') && is_plugin_active( plugin_basename( __FILE__ ) ) ) {

        if ( get_option( 'Activated_Plugin' ) == 'edd_pagseguro_custom' ) {

            delete_option( 'Activated_Plugin' );
            edd_register_pagseguro_sandbox_user();

        } elseif ( get_option( 'Disable_Plugin' ) == 'edd_pagseguro_custom' ) {

            delete_option( 'Disable_Plugin' );
            edd_pagseguro_custom_deactivate();

        }

    }

}
add_action( 'admin_init', 'edd_pagseguro_custom_load' );


/**
 * Cria um usuário para ser utilizado
 * como Comprador de Teste no PagSeguro
 * na ativação do plugin
 */
function edd_register_pagseguro_sandbox_user() {

    $user_id = username_exists( 'comprador_pagseguro' );

    if ( !$user_id && email_exists( $user_email ) == false ) {

        $userdata = array(
            'user_login' => 'comprador_pagseguro',
            'user_email' => 'comprador_pagseguro@sandbox.pagseguro.com.br',
            'first_name' => 'Comprador',
            'last_name'  => 'de Teste',
            'user_pass'  => wp_generate_password( $length = 12, $include_standard_special_chars = false ),
        );

        $user_id = wp_insert_user( $userdata ) ;

        if ( ! is_wp_error( $user_id ) ) {
            # echo "User created : ". $user_id;
        }

    }

}


/**
 * Desabilita o plugin se as dependências não estiverem
 * resolvidas e exibe uma mensagem para o usuário
 */
function edd_pagseguro_custom_deactivate() {

    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    deactivate_plugins( plugin_basename( __FILE__ ) );
    
    // Esconde a notificação de "Plugin ativado"
    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

    // Exibe uma notificação de erro
    add_action( 'admin_notices', 'edd_pagseguro_custom_show_notice__error' );

}


/**
 * Exibe mensagem de erro no wp-admin
 */
function edd_pagseguro_custom_show_notice__error() {
    $class = 'notice notice-error';
    $message = __( '<strong>Ops!</strong> Você precisa instalar e ativar o plugin <a href="https://easydigitaldownloads.com/" target="_blank">Easy Digital Downloads</a> para habilitar a opção de pagamento com o PagSeguro.', 'edd-pagseguro-custom' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message ); 
}


/**
 * Cria um "wrapper" para a inicialização deste plugin
 * verificando a existência da classe principal do EDD
 */
function edd_pagseguro_custom_init() {

    if( class_exists( 'Easy_Digital_Downloads' ) ) {

        /**
         * Adiciona o script do plugin
         */
        function edd_pagseguro_custom_scripts() {

            wp_enqueue_script('edd_pagseguro_custom', plugin_dir_url( __FILE__ ) . 'edd-pagseguro-custom.js', array('jquery'), null, true);
            # Localize admin-ajax url in main.js
            wp_localize_script( 'edd_pagseguro_custom', 'edd_custom_scripts', array( 'ajaxurl' => admin_url('admin-ajax.php')) );
        
        }
        add_action( 'wp_enqueue_scripts', 'edd_pagseguro_custom_scripts' );


        /**
         * Registra o PagSeguro como gateway de pagamento
         */
        function edd_register_pagseguro_gateway($gateways) {
            $gateways['pagseguro'] = array(
                'admin_label' => 'PagSeguro',
                'checkout_label' => __('PagSeguro', 'pw_edd'),
            );
            return $gateways;
        }
        add_filter('edd_payment_gateways', 'edd_register_pagseguro_gateway');


        /**
         * Registra a seção do PagSeguro
         */
        function edd_register_pagseguro_gateway_section( $gateway_sections ) {
            $gateway_sections['pagseguro'] = __( 'PagSeguro', 'easy-digital-downloads' );
            return $gateway_sections;
        }
        add_filter( 'edd_settings_sections_gateways', 'edd_register_pagseguro_gateway_section', 1, 1 );


        /**
         * Registra as configurações do PagSeguro
         */
        function edd_register_pagseguro_gateway_settings( $gateway_settings ) {

            $pagseguro_settings = array (
                'pagseguro_settings' => array(
                    'id'   => 'pagseguro_settings',
                    'name' => '<strong>' . __( 'PagSeguro Configurações', 'easy-digital-downloads' ) . '</strong>',
                    'type' => 'header',
                ),
                'pagseguro_email' => array(
                    'id'   => 'pagseguro_email',
                    'name' => __( 'E-mail', 'easy-digital-downloads' ),
                    'desc' => __( 'Informe o e-mail da sua conta no PagSeguro', 'easy-digital-downloads' ),
                    'type' => 'text',
                    'size' => 'regular',
                ),
            );

            $api_key_settings = array(
                'pagseguro_api_token' => array(
                    'id'   => 'pagseguro_api_token',
                    'name' => __( 'Token de acesso', 'easy-digital-downloads' ),
                    'desc' => __( 'Informe seu token de acesso no PagSeguro', 'easy-digital-downloads' ),
                    'type' => 'text',
                    'size' => 'regular'
                ),
                'pagseguro_sandbox_api_token' => array(
                    'id'   => 'pagseguro_sandbox_api_token',
                    'name' => __( 'Token de acesso Sandbox', 'easy-digital-downloads' ),
                    'desc' => __( 'Informe seu token de acesso no SandBox PagSeguro', 'easy-digital-downloads' ),
                    'type' => 'text',
                    'size' => 'regular'
                )
            );

            $pagseguro_settings = array_merge( $pagseguro_settings, $api_key_settings );
            $pagseguro_settings = apply_filters( 'edd_pagseguro_settings', $pagseguro_settings );
            $gateway_settings['pagseguro'] = $pagseguro_settings;

            return $gateway_settings;
        }
        add_filter( 'edd_settings_gateways',
        'edd_register_pagseguro_gateway_settings', 1, 1 );


        /**
         * Monta o array com as informações da compra
         * recebidas do formulário do produto no front-end
         */
        function edd_set_pagseguro_purchase() {

            // Verifica a variável $_POST
            if ( empty( $_POST ) ) return false;

            $download_id = $_POST['download_id'];

            // Verifica se existe um item para compra
            if( empty( $download_id ) || ! edd_get_download( $download_id ) ) return;

            // Monta o array
            $purchase_data = edd_build_straight_to_gateway_data( $download_id );
            $purchase_data['gateway'] = 'pagseguro';

            edd_set_purchase_session( $purchase_data );

            // Executa a função para efetuar a transação
            edd_send_to_gateway( $purchase_data['gateway'], $purchase_data );
            exit;
        }
        add_action ( 'wp_ajax_' . 'edd_set_pagseguro_purchase',
        'edd_set_pagseguro_purchase' );


        /**
         * Processa a compra com PagSeguro
         * @param array $purchase_data Dados da compra
         * @return void
         */
        function edd_process_pagseguro_purchase( $purchase_data ) {

            // Disponibiliza as opções configuradas no wp-admin
            global $edd_options;

            if( ! wp_verify_nonce( $purchase_data['gateway_nonce'], 'edd-gateway' ) ) {
                wp_die( __( 'Nonce verification has failed', 'easy-digital-downloads' ), __( 'Error', 'easy-digital-downloads' ), array( 'response' => 403 ) );
            }

            // Coleta dados para o pagamento
            $payment_data = array(
                'price'         => $purchase_data['price'],
                'date'          => $purchase_data['date'],
                'user_email'    => $purchase_data['user_email'],
                'purchase_key'  => $purchase_data['purchase_key'],
                'currency'      => edd_get_currency(),
                'downloads'     => $purchase_data['downloads'],
                'user_info'     => $purchase_data['user_info'],
                'cart_details'  => $purchase_data['cart_details'],
                'gateway'       => 'pagseguro',
                'status'        => 'pending'
            );

            // Registra o pagamento com status pendente
            $payment = edd_insert_payment( $payment_data );

            // Verifica o pagamento
            if ( ! $payment ) {

                // Registra o erro
                edd_record_gateway_error( __( 'Payment Error', 'easy-digital-downloads' ), sprintf( __( 'Payment creation failed before sending buyer to PagSeguro. Payment data: %s', 'easy-digital-downloads' ), json_encode( $payment_data ) ), $payment );

                // Problemas? Informe o usuário (ajax)
                $response = array(
                    "error" => true,
                    "message" => "Ocorreu uma falha durante a criação do pagamento.",
                );

                echo json_encode( $response );
                exit;

            } else {

                // Cria variáveis de sessão para restaurar o processo de compra em caso de abandono ou erro
                EDD()->session->set( 'edd_resume_payment', $payment );

                // Define a url de sucesso do retorno
                $return_url = add_query_arg( array(
                    'id-compra' => $payment,
                    'confirmacao' => 'pendente'
                ), trailingslashit( get_site_url() ) . get_post_type_object( 'download' )->rewrite['slug'] );

                // Configura os argumentos da transação
                $pagseguro_args = array(
                    'email' => edd_get_option( 'pagseguro_email', false ),
                    'token' => edd_get_pagseguro_token(),
                    'currency' => edd_get_currency(),
                    'shippingAddressRequired' => 'false',
                    'redirectURL' => $return_url,
                    'reference' => $payment . '__' . $purchase_data['purchase_key'],
                    'senderName' => $purchase_data['user_info']['first_name'] . ' ' . $purchase_data['user_info']['last_name'],
                    'senderEmail' => $purchase_data['user_email'],
                );

                // Inclui os itens desejados na transação
                $i = 1;
                if( is_array( $purchase_data['cart_details'] ) && ! empty( $purchase_data['cart_details'] ) ) {
                    foreach ( $purchase_data['cart_details'] as $item ) {

                        $item_amount = round( ( $item['subtotal'] / $item['quantity'] ) - ( $item['discount'] / $item['quantity'] ), 2 );

                        if( $item_amount <= 0 ) {
                            $item_amount = 0;
                        }

                        $pagseguro_args['itemId' . $i ]  = $item['id'];
                        $pagseguro_args['itemDescription' . $i ] = stripslashes_deep( html_entity_decode( edd_get_cart_item_name( $item ), ENT_COMPAT, 'UTF-8' ) );
                        $pagseguro_args['itemQuantity' . $i ]  = $item['quantity'];
                        $pagseguro_args['itemAmount' . $i ]    = number_format($item_amount,2);

                        $i++;

                    }
                }

                // Grava a uri de pagamento do PagSeguro
                $pagseguro_api_url = trailingslashit( edd_get_pagseguro_uri( 'transaction' ) );

                // Chama a API do PagSeguro para obter o transaction_id da compra
                edd_get_pagseguro_checkout( $pagseguro_api_url, $pagseguro_args );
                exit;
            }
        }
        add_action('edd_gateway_pagseguro', 'edd_process_pagseguro_purchase');


        /**
         * Executa a transação com o PagSeguro
         * @param string $curl_url Url do webservice do Pagseguro
         * @param array $fields Parâmetros da requisição
         * @return void
         */
        // function edd_get_pagseguro_checkout( $curl_url, $fields ) {
        function edd_get_pagseguro_checkout( $url, $args ) {

            // Guarda o ID da compra
            $reference = $args['reference'];
            $purchase_id = explode('__', $reference)[0];

            $headers = array( "Content-Type: application/x-www-form-urlencoded; charset=UTF-8" );
            
            $post = wp_remote_post( $url, array(
                'method' => 'POST',
                'timeout' => 60,
                'httpversion' => '1.0',
                'blocking' => true,
                'sslverify' => false,
                'headers' => $headers,
                'body' => $args,
                )
            );

            if ( is_wp_error( $post ) ) {

                $error_message = $post->get_error_message();
                $response = array(
                    "error"   => true,
                    "message" => $error_message,
                );

             } else {

                $body = wp_remote_retrieve_body( $post );
                $result = simplexml_load_string( $body );

                // Erro: Credenciais do PagSeguro estão incorretas
                if ( $body == 'Unauthorized' ) {

                    edd_debug_log( 'Erro na compra ID ' . $purchase_id );
                    edd_debug_log( 'Chamada Não Autorizada à API PagSeguro. Verifique os tokens de acesso.' );

                    $response = array(
                        "error"   => true,
                        "message" => 'Não autorizado.',
                    );
                }

                // Erro: Informações erradas foram enviadas para o PagSeguro
                // Verifique o log de debug
                if ( count( $result->error ) > 0 ) {

                    edd_debug_log( 'Erro na compra ID ' . $purchase_id );

                    foreach ( $result->error as $error ) {
                        edd_debug_log( '['.$error->code.'] ' . $error->message );
                    }

                    $response = array(
                        "error"   => true,
                        "message" => 'Retorno do PagSeguro apresentou erros.',
                    );
                }

                // Sucesso
                if ( count( $result->code ) > 0 ) {
                    $response = array(
                        "checkout_uri" => edd_get_pagseguro_uri( 'checkout' ),
                        "code"         => $result->code,
                    );
                }

            }

            if ( array_key_exists( 'error', $response ) ) {
                edd_delete_purchase( $purchase_id );
            }

            // Redireciona para o PagSeguro no front-end
            echo json_encode( $response );
            exit;

        }


        /**
         * Manipula as notificações POST
         * recebidas da api do PagSeguro
         */
        function edd_handle_pagseguro_notification() {

            // Verifica a variável $_POST
            if ( empty( $_POST ) ) return false;

            // Valor esperado para notificationCode strlen(notificationCode) == 39
            $notification_code = $_POST['notificationCode'];
            // Valor esperado para notificationType == 'transaction'
            $notification_type = $_POST['notificationType'];

            // Verifica se a notificação está correta
            if( isset( $notification_type ) && ( $notification_type == 'transaction' ) ) {

                // Monta a requisição sobre a notificação recebida
                $request  = trailingslashit( edd_get_pagseguro_uri( 'notification' ) );
                $request .= $notification_code;
                $request  = add_query_arg( array(
                    'email' => edd_get_option( 'pagseguro_email', false ),
                    'token' => edd_get_pagseguro_token(),
                ), $request );

                // Requere do PagSeguro as informações sobre a transação notificada
                $get = wp_remote_get( $request );

                if ( is_wp_error( $get ) ) {

                    $error_message = $get->get_error_message();
                    edd_debug_log( 'Erro no serviço de notificação: ' . $error_message );
                    exit;

                } else {

                    // Carrega o xml do corpo da resposta
                    $body = wp_remote_retrieve_body( $get );
                    $result = simplexml_load_string( $body );

                    // Erro: Credenciais do PagSeguro estão incorretas
                    if ( $body == 'Unauthorized' ) {

                        edd_debug_log( 'Chamada Não Autorizada à API de Notificação do PagSeguro.' );
                        exit;
                    }

                    // Erro: Informações erradas foram enviadas para o PagSeguro
                    // Verifique o log de debug
                    if ( count( $result->error ) > 0 ) {

                        edd_debug_log( 'Retorno do PagSeguro apresentou erros.');

                        foreach ( $result->error as $error ) {
                            edd_debug_log( '['.$error->code.'] ' . $error->message );
                        }
                        exit;
                    }

                    // Pega os dados da transação para atualizar o status de pagamento
                    $status = $result->status;
                    // Referência = 'id-do-pagamento__purchase-key'
                    $reference   = $result->reference;
                    $purchase_id = explode('__', $reference)[0];

                    edd_debug_log( 'Atualização de status. Compra ID ' . $purchase_id );

                    switch ( $status ) {
                        // Aguardando pagamento
                        case 1:
                            edd_update_payment_status($purchase_id, 'pending');
                            edd_debug_log( 'Pagamento pendente' );
                            break;
                        // Em análise
                        case 2:
                            edd_update_payment_status($purchase_id, 'preapproved');
                            edd_debug_log( 'Pagamento pré-aprovado' );
                            break;
                        // Paga
                        case 3:
                            edd_update_payment_status($purchase_id, 'complete');
                            edd_debug_log( 'Pagamento confirmado' );
                            break;
                        // Devolvida
                        case 5:
                            edd_update_payment_status($purchase_id, 'revoked');
                            edd_debug_log( 'Pagamento revogado' );
                            break;
                        // Devolvida
                        case 6:
                            edd_update_payment_status($purchase_id, 'refunded');
                            edd_debug_log( 'Pagamento devolvido' );
                            Cancelada;
                        // Devolvida
                        case 7:
                            edd_update_payment_status($purchase_id, 'failed');
                            edd_debug_log( 'Pagamento cancelado' );
                            break;
                    }
                    exit;
                }
            }
        }
        add_action('init', 'edd_handle_pagseguro_notification');


        /**
         * Retorna o token de acesso do PagSeguro
         * @return string
         */
        function edd_get_pagseguro_token() {

            // Verifica se o plugin está em modo teste
            if ( edd_is_test_mode() ) {

                // Sandbox PagSeguro
                $token = edd_get_option( 'pagseguro_sandbox_api_token', false );

            } else {

                $token = edd_get_option( 'pagseguro_api_token', false );

            }

            return $token;
        }


        /**
         * Retorna os dados de configuração para Modo de Teste ou Produção
         * @param string $type qual dado a retornar
         * @param bool $ssl_check é SSL?
         * @return string
         */
        function edd_get_pagseguro_uri( $type, $ssl_check = false ) {

            $protocol = 'http://';
            if ( is_ssl() || ! $ssl_check ) {
                $protocol = 'https://';
            }

            // Sandbox PagSeguro
            $sandbox = '';
            if ( edd_is_test_mode() ) {
                $sandbox = 'sandbox.';
            }

            if ( edd_is_test_mode() ) {

                switch ( $type ) {
                    case "transaction":
                        $uri = $protocol . 'ws.' . $sandbox . 'pagseguro.uol.com.br/v2/checkout';
                        break;
                    case "checkout":
                        $uri = $protocol . $sandbox . 'pagseguro.uol.com.br/v2/checkout/payment.html';
                        break;
                    case "notification":
                        $uri = $protocol . 'ws.' . $sandbox . 'pagseguro.uol.com.br/v3/transactions/notifications';
                        break;
                }

            }

            return $uri;

        }

    } else {

        edd_pagseguro_custom_deactivate();

    }

}
add_action( 'plugins_loaded', 'edd_pagseguro_custom_init' );


/**
 * Altera o slug padrão do EDD
 */
function edd_pagseguro_custom_slug_rewrite( $args, $post_type ) {

    if ( $post_type === 'download' ) {
        $args['label'] = 'Ferramentas';
        $args['rewrite']['slug'] = 'ferramentas';
    }

    return $args;
}
add_filter( 'register_post_type_args',
'edd_pagseguro_custom_slug_rewrite', 10, 2 );


/**
 * Altera a função do EDD para retornar o ID da compra
 * caso o usuário já tenha comprado a ferramenta
 */
function edd_has_user_purchased_custom( $user_id, $downloads ) {

    if ( empty( $user_id ) ) return false;

    $user_purchases = edd_get_users_purchases( $user_id, -1, false, 'any' );

    $return = array();

    if ( ! is_array( $downloads ) ) {
        $downloads = array( $downloads );
    }

    if ( $user_purchases ) {

        foreach ( $user_purchases as $purchase ) {

            $payment         = new EDD_Payment( $purchase->ID );

            $purchased_files = $payment->cart_details;

            if ( is_array( $purchased_files ) ) {

                foreach ( $purchased_files as $download ) {

                    if ( in_array( $download['id'], $downloads ) ) {

                        $variable_prices = edd_has_variable_prices( $download['id'] );

                        if ( $variable_prices && ! is_null( $variable_price_id ) && $variable_price_id !== false ) {

                            if ( isset( $download['item_number']['options']['price_id'] ) && $variable_price_id == $download['item_number']['options']['price_id'] ) {

                                $return['status'] = $purchase->post_status;
                                $return['id']     = $purchase->ID;
                                break 2; // Get out to prevent this value being overwritten if the customer has purchased item twice

                            } else {

                                $return = false;

                            }

                        } else {

                            $return['status'] = $purchase->post_status;
                            $return['id']     = $purchase->ID;
                            break 2;  // Get out to prevent this value being overwritten if the customer has purchased item twice

                        }
                    }
                }
            }
        }
    }

    $return = apply_filters( 'edd_has_user_purchased', $return, $user_id, $downloads, $variable_price_id );

    return $return;
}


/**
 * Purchase Tool Link Shortcode
 */
function edd_purchase_tool_shortcode_custom( $atts, $content = null ) {

    $modal_trigger_button = '<button type="button" class="o-buyButton__btn" data-toggle="modal" data-target="#purchase-modal">
    <span class="o-buyButton">Comprar ' . edd_price( get_the_ID(), false ) . '</span>
    </button>';

    $download_button = '<a href="{{download_link}}" role="button" class="o-buyButton__btn"><span class="o-buyButton download"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="22" viewBox="0 0 18 22"><g fill="none" fill-rule="evenodd" stroke="#fff" stroke-width="2"><path d="M17 18v3H1v-3M9 13.93V.944M15 10l-6 5-6-5"/></g></svg>Baixar</span></a>';

    $pending_payment_button = '<button type="button" disabled class="o-buyButton__btn"><span class="o-pendingButton">Processando o pagamento<br><span class="o-pendingButton__subtitle">O download estará disponível em breve</span></span></button>';

        global $post;

        $post_id = is_object( $post ) ? $post->ID : 0;

        $atts = shortcode_atts( array(
            'id'    => $post_id,
        ),
        $atts, 'purchase_tool' );
        

        if( ! empty( $atts['id'] ) ) {

            $payment = edd_has_user_purchased_custom( get_current_user_id(), $atts['id'] );

            if ( $payment ):

                $payment_key = edd_get_payment_key( $payment['id'] );

                if ( $payment['status'] == 'pending' ) :
                    return $pending_payment_button;

                else :

                    $user_email  = edd_get_payment_user_email( get_current_user_id() );
                    $download_link = esc_url( edd_get_download_file_url( $payment_key, $user_email, 1, $atts['id'] ) );

                    return str_replace( '{{download_link}}', $download_link, $download_button);
                endif;

            else:

                return $modal_trigger_button;

            endif;

        } else { return 'error'; }

}
add_shortcode( 'purchase_tool', 'edd_purchase_tool_shortcode_custom' );