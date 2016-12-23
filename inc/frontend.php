<script type="text/javascript">

jQuery(document).ready(function(){
    var initialText=jQuery('#submit_acumba').val();
    jQuery('input').keypress(function(){
        jQuery('#submit_acumba').removeProp('disabled');
        jQuery('#submit_acumba').val(initialText);
    });
    jQuery('form#wordpress-form-acm').submit(function(){        
        var datatosend={};
        jQuery("input[id*='acumba_'],select[id*='acumba_'],textarea[id*='acumba_']").each(function(index){
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

        jQuery('#submit_acumba').val("Enviando...");
    
        datatosend['action']="send_acumbaform";
        console.log(JSON.stringify(datatosend));
        jQuery.ajax({type:"POST",url:ajaxurl, data:datatosend}).done(function(data) {
            console.log(data);
            if(data=="Te has suscrito correctamente."){
                jQuery('#acumba_response').css('color','green');
                jQuery('#submit_acumba').val('Completado');
	        jQuery('#submit_acumba').prop('disabled','true');
		var choose_redirect=jQuery('#ok_redirect_acumba').val();
		var double_optin = '<?php echo $double_optin; ?>';
		var welcome_email = '<?php echo $welcome_email; ?>';
		if (double_optin == "1"){
     		    jQuery('#acumba_response').html("Te hemos enviado un email para confirmar tu suscripción");
		}else{
     		    jQuery('#acumba_response').html(data);
		}		
                console.log("redirect a: "+choose_redirect)
		if (choose_redirect && choose_redirect.length>1){
		    window.location=jQuery('#ok_redirect_acumba').val()
		}
            } else {
                jQuery('#acumba_response').css('color','red');
                jQuery('#submit_acumba').val(initialText);
		jQuery('#acumba_response').html(data);
            }    
        });
        return false;
    });
});


<?php
    echo "</script>";

    $widget_fields_get = get_option('acumba_widget_fields');
    $list = get_option('acumba_chosen_list');
    $ordered = get_option('acumba_ordered_fields');
    if($list != ''){
            $chosen_list=$list['acumba_chosen_list'];
            if (empty($title)) $title = "Suscríbete a nuestro newsletter";
            if (empty($button)) $button = "Suscríbete";

            if(get_option('theme_style')=='y'){
                echo $before_widget;
                echo $before_title . $title . $after_title;
                echo '<div style="padding:5px 5% 5% 5%;">';
                if ($subtitle){ 
			echo "<div id=\"acumba_info\" style=\"margin-button: 5px;\">$subtitle</div>";
		}
                echo '<form id="wordpress-form-acm" action="http://acumbamail.com/signup/'.$chosen_list.'/" method="POST">';

                foreach ($ordered as $key => $value) {    
		    if ($widget_fields_get[$value]['type']=="boolean") {
			echo '<p style="margin:5px 0 0 0;"><input type="checkbox" style="width: 100%;" id="acumba_'.$widget_fields_get[$value]['name'].'" name="'.$widget_fields_get[$value]['name'].'"> '.$widget_fields_get[$value]['name_given'].'</p>';
                    } elseif ($widget_fields_get[$value]['type']=="combobox") {
			echo '<p style="margin:5px 0 0 0;">';
                        $name_options=explode(";",$widget_fields_get[$value]['name']);
                        $option_values=explode(",",$name_options[1]);
                        echo '<select name="'.$name_options[0].'" class="subscribe-input" id="acumba_'.$name_options[0].'">';
			foreach($option_values as $option_item) {
		            echo '<option value="'.$option_item.'">'.$option_item.'</option>';
			}
			echo '</select></p>';    
		    } else {
			echo '<p style="margin:5px 0 0 0;"><input type="text" class="widefat" id="acumba_'.$widget_fields_get[$value]['name'].'" name="'.$widget_fields_get[$value]['name'].'" placeholder="'.$widget_fields_get[$value]['name_given'].'" style="width:100%;" required></p>';            
		    }
                }
                if($ok_redirect){
                    echo "<p style=\"margin:8px 0 0 0;\"><input type=\"hidden\" value=\"$ok_redirect\" id=\"ok_redirect_acumba\" style=\"width: 100%;\"></p>";
	        }
		if($double_optin){
                    echo "<p style=\"margin:8px 0 0 0;\"><input type=\"hidden\" value=\"1\" name=\"double_optin\" id=\"acumba_double_optin\" style=\"width: 100%;\"></p>";
	        }
		if($welcome_email){
                    echo "<p style=\"margin:8px 0 0 0;\"><input type=\"hidden\" value=\"1\" name=\"welcome_email\" id=\"acumba_welcome_email\" style=\"width: 100%;\"></p>";
	        }
                echo "<p style=\"margin:8px 0 0 0;\"> <input type=\"submit\" value=\"$button\" id=\"submit_acumba\" style=\"width: 100%;\"></p><div id=\"acumba_response\"></div>";                
                echo '</form></div>';
                echo $after_widget;
            }else{
                echo '<section class="subscribe block"><div class="subscribe-pitch"><h3>'.$title.'</h3><p id="acumba_info">'.$subtitle.'<p></div><form id="wordpress-form-acm" action="http://acumbamail.com/signup/'.$chosen_list.'/" method="POST" class="subscribe-form">';

		foreach ($ordered as $key => $value) {    
		    if ($widget_fields_get[$value]['type']=="boolean") {
			echo '<span class="sep"><input type="checkbox" id="acumba_'.$widget_fields_get[$value]['name'].'" name="acumba_'.$widget_fields_get[$value]['name'].'"><label>'.$widget_fields_get[$value]['name_given'].'</label></span>';
                    } elseif ($widget_fields_get[$value]['type']=="combobox") {
                        $name_options=explode(";",$widget_fields_get[$value]['name']);
                        $option_values=explode(",",$name_options[1]);
                        echo '<select name="'.$name_options[0].'" class="subscribe-input" id="acumba_'.$name_options[0].'">';
			foreach($option_values as $option_item) {
		            echo '<option value="'.$option_item.'">'.$option_item.'</option>';
			}
			echo '</select>';    
		    } else {
			echo '<input type="text" name="'.$widget_fields_get[$value]['name'].'" class="subscribe-input" placeholder="'.$widget_fields_get[$value]['name_given'].'" id="acumba_'.$widget_fields_get[$value]['name'].'" required>';
		    }
                }
                if($ok_redirect){
                    echo "<input type=\"hidden\" value=\"$ok_redirect\" id=\"ok_redirect_acumba\" >";
	        }	
		if($double_optin){
                    echo "<input type=\"hidden\" value=\"1\" id=\"acumba_double_optin\" name=\"double_optin\">";
	        }
		if($welcome_email){
                    echo "<input type=\"hidden\" value=\"1\" id=\"acumba_welcome_email\" name=\"welcome_email\">";
	        }
                echo '<input type="submit" id="submit_acumba" class="subscribe-submit" value="'.$button.'"><div id="acumba_response"></div></form></section>';
            }
    }else{
        echo $before_widget.'Configura el plugin de Acumbamail en la interfaz para visualizar el Widget'.$after_widget;
    }
?>
