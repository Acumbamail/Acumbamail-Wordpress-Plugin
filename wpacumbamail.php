<?php
/*
 *  Plugin Name: Acumbamail
 *  Plugin URI:
 *  Description: Plugin para Wordpress destinado a la creación de Widgets para la suscripción de usuarios a listas
 *  Version: 1.0.1
 *  Author: Acumbamail
 *  Author URI: http://acumbamail.com
 *  License: GPLv2
*/

/*
 * ARCHIVO: wpacumbamail.php
 * Añade un enlace a nuestro plugin en la administración de WordPress
*/

require('acumbamail.class.php');
require('wpacumbamail_widget.php');

function wpacumbamail(){
    /*
     * Usa la función add_menu_page para añadir el enlace en el menú de administración
     * add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
     */

    add_menu_page(
        'Gestiona tus suscripciones en Acumbamail',
        'Acumbamail',
        'manage_options',
        'wpacumbamail',
        'wpacumbamail_options_page',
        plugin_dir_url(dirname(__FILE__) . '/wpacumbamail.php').'img/acumba_logo.png'
    );
}

add_action('admin_menu', 'wpacumbamail');

//Setup of AJAX Calls for Signup Form in FrontEnd
add_action('wp_head', 'add_ajax_library');
add_action('wp_ajax_send_acumbaform', 'send_api_acumbamail');
add_action('wp_ajax_nopriv_send_acumbaform', 'send_api_acumbamail');

function wpacumbamail_options_page(){
    /*
     * Función que muestra el código HTML para la página de ajustes en la administración
     * y realiza operaciones para el guardado de variables de ajustes
    */
    if(!current_user_can('manage_options')){
        wp_die('You do not have sufficient permissions to access this page');
    }

    $options = array();
    $list = array();

    if(isset($_POST['acumbamail_form_submitted'])){
        $hidden_field = esc_html($_POST['acumbamail_form_submitted']);

        if($hidden_field == 'Y'){
            if($_POST['acumba_customer_id']!='' && $_POST['acumba_auth_token']!='') {
                $options['acumba_customer_id'] = $_POST['acumba_customer_id'];
                $options['acumba_auth_token'] = $_POST['acumba_auth_token'];
                $options['last_updated'] = time();

                update_option('acumba_plugin_data', $options);
                delete_option('acumba_chosen_list');
                delete_option('acumba_widget_fields');
                delete_option('acumba_ordered_fields');
                echo '<div class="updated">Cambios actualizados correctamente</div>';
            }else{
                delete_option('acumba_plugin_data');
                delete_option('acumba_chosen_list');
                delete_option('acumba_widget_fields');
                delete_option('acumba_ordered_fields');
                add_action('widgets_init', 'unregister_wp_widget');
                echo '<div class="updated">Cuenta eliminada correctamente</div>';
            }
        }
    }

    $widget_fields=get_option('acumba_widget_fields');
    $ordered=get_option('acumba_ordered_fields');
    //TODO
    if(isset($_POST['changed_order'])){
        $ordered=array();
        $hidden_field = $_POST['changed_order'];

        unset($_POST['changed_order']);
        if($hidden_field == 'Y'){
            foreach ($_POST as $key=>$value) {
                if(strpos($key,'_given') !== false){
                    $index=strtok($key, '_');
                    $widget_fields[$index]['name_given']=$value;
                }else{
                    $ordered[$value]=$key;
                }
            }
            update_option('acumba_widget_fields', $widget_fields);
            update_option('acumba_ordered_fields', $ordered);
        }
    }

    $options = get_option('acumba_plugin_data');
    if($options != ''){
        $acumba_customer_id = $options['acumba_customer_id'];
        $acumba_auth_token = $options['acumba_auth_token'];
        if(isset($_POST['chosen_list'])){
            $hidden_field = esc_html($_POST['chosen_list']);

            if($hidden_field != ''){
                $list['acumba_chosen_list'] = $_POST['chosen_list'];

                update_option('acumba_chosen_list', $list);
                echo '<div class="updated">Cambios actualizados correctamente</div>';
            }
        }

        $list = get_option('acumba_chosen_list');
        if($list != ''){
            $chosen_list=$list['acumba_chosen_list'];
        }

        $api = new AcumbamailAPI($acumba_customer_id,$acumba_auth_token);

        $response_lists = $api->getLists();

        if($response_lists!='' && $chosen_list!=''){
            $mergetags = $api->getFields($chosen_list);
            if( (sizeof($mergetags)>sizeof($widget_fields)) || isset($_POST['chosen_list']) ){
                if(!isset($_POST['chosen_list'])) echo '<div class="updated">Actualizados los campos del formulario</div>';

                $widget_fields = array();
                $ordered = array();
                $i = 0;
                foreach (array_keys($mergetags) as $mergetag) {
                    $widget_fields[$mergetag] = array(
                        "name" => $mergetag,
                        "type" => $mergetags[$mergetag],
                        "name_given" => $mergetag,
                    );

                    $ordered[$i++] = $mergetag;
                }

                update_option('acumba_widget_fields', $widget_fields);
                update_option('acumba_ordered_fields', $ordered);
            }
        }
    }
    wp_enqueue_script('jquery');
    require('inc/admin_page.php');
}

function load_scripts($hook) {
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-ui-mouse');
    wp_enqueue_script('jquery-ui-sortable');
}
add_action('admin_enqueue_scripts', 'load_scripts');

function add_ajax_library() {

    $html = '<script type="text/javascript">';
        $html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
    $html .= '</script>';

    echo $html;

}

function send_api_acumbamail() {
    if($_POST!=''){
        $options = get_option('acumba_plugin_data');
        $list = get_option('acumba_chosen_list');

        if($options != ''){
            $acumba_customer_id = $options['acumba_customer_id'];
            $acumba_auth_token = $options['acumba_auth_token'];
        }

        if($list != ''){
            $chosen_list=$list['acumba_chosen_list'];
        }

        $api = new AcumbamailAPI($acumba_customer_id,$acumba_auth_token);
        $response = $api->addSubscriber($chosen_list,$_POST);

        if(isset($response['error'])){
            print_r($response['error']);
        }elseif (isset($response['subscriber_id'])){
            echo "Te has suscrito correctamente.";
        }else{
            echo "Ha ocurrido un error. Revisa todos los campos o comprueba tu conexión a internet.";
        }
    }else{
            echo "Ha ocurrido un error. Revisa todos los campos o comprueba tu conexión a internet.";
    }
    die();
}

?>
