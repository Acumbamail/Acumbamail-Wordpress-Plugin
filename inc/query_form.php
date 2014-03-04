<?php
    require( '../../../../wp-load.php' );

    $post=json_decode(file_get_contents("php://input"),true);
    if($post!=''){
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
        $response = $api->addSubscriber($chosen_list,$post);

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
?>
