<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;


/**
 * Remove o usuário 'comprador_pagseguro',
 * criado na ativação do plugin
 */
require_once( ABSPATH.'wp-admin/includes/user.php' );

$user_name = 'comprador_pagseguro';
$user_id = username_exists( $user_name );
if ( $user_id ) {

    wp_delete_user( $user_id );

}