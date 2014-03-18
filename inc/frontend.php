<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#submit_acumba').click(function(){
        var initialText=jQuery('#submit_acumba').prop('value');
        var datatosend={};
        jQuery("input[id*='acumba_']").each(function(index){
            if(jQuery(this).prop('type')=='checkbox'){
                if(jQuery(this).is(':checked')){
                    datatosend[jQuery(this).prop('name')] = true;
                }else{
                    datatosend[jQuery(this).prop('name')] = false;
                }
            }else{
                datatosend[jQuery(this).prop('name')]=jQuery(this).val();
            }
        });

        jQuery('#submit_acumba').prop('value',"Cargando...");

        datatosend['action']="send_acumbaform";
        console.log(JSON.stringify(datatosend));
        jQuery.ajax({type:"POST",url:ajaxurl, data:datatosend}).done(function(data) {
            console.log(data);
            if(data=="Te has suscrito correctamente."){
                jQuery('#acumba_info').css('color','green');
            } else {
                jQuery('#acumba_info').css('color','red');
            }
            jQuery('#acumba_info').html(data);
            jQuery('#submit_acumba').prop('value',initialText);
        });

    });
});


<?php
    echo "</script>";

    $widget_fields_get = get_option('acumba_widget_fields');
    $list = get_option('acumba_chosen_list');
    $ordered = get_option('acumba_ordered_fields');

    if($list != ''){
            $chosen_list=$list['acumba_chosen_list'];

            echo $before_widget;
            if (!empty($title)){
              echo $before_title . $title . $after_title;
            }else{
              echo "Suscríbete a nuestro newsletter";
            }

            if(empty($button)){
                $button="Suscríbete";
            }

            if ($subtitle) echo "<div id=\"acumba_info\" style=\"margin-button: 5px\">$subtitle</div>";
            echo '<form action="http://acumbamail.com/signup/'.$chosen_list.'/" method="POST">';

            foreach ($ordered as $key => $value) {
                if($widget_fields_get[$value]['type']=="email" || $widget_fields_get[$value]['type']=="char"){
                    echo '<p style="margin:5px 0 0 0;"><input type="text" class="widefat" id="acumba_'.$widget_fields_get[$value]['name'].'" name="'.$widget_fields_get[$value]['name'].'" placeholder="'.$widget_fields_get[$value]['name_given'].'" style="width:100%"></p>';
                } elseif ($widget_fields_get[$value]['type']=="boolean") {
                    echo '<p style="margin:5px 0 0 0;"><input type="checkbox" id="acumba_'.$widget_fields_get[$value]['name'].'" name="'.$widget_fields_get[$value]['name'].'"> '.$widget_fields_get[$value]['name_given'].'</p>';
                }/* elseif ($widget_fields_get[$widget_field]=="combobox") {

                }*/
            }

            echo "<p style=\"margin:5px 0 0 0;\"><input type=\"button\" value=\"$button\" id=\"submit_acumba\"></p>";
            echo $after_widget;
            echo "</form>";
    }else{
        echo $before_widget.'Configura el plugin de Acumbamail en la interfaz para visualizar el Widget'.$after_widget;
    }
?>
