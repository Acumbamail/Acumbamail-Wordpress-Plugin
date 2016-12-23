<?php
class WPAcumbamail_Widget extends WP_WIDGET{
    function wpacumbamail_widget(){
        parent::__construct('false', $name = __( 'Acumbamail Widget'), //tranlation enabled // accessing the perant constructer
            array( 'description' => __('Widget para la inclusión de un formulario en el blog'))
            ); // end of the constructor
    }

    function widget($args, $instance){
        extract($args);

        wp_enqueue_script('jquery');
        wp_enqueue_script('alt-checkbox-script', plugins_url( 'assets/alt-checkbox/jquery.alt-checkbox.js' , __FILE__ ));
        wp_enqueue_style( 'frontend', plugins_url( 'assets/frontend/style.css' , __FILE__ ));
        wp_enqueue_style( 'alt-checkbox.icon-font',  plugins_url( 'assets/alt-checkbox/jquery.alt-checkbox.icon-font.css' , __FILE__ ));
        wp_enqueue_style( 'alt-checkbox', plugins_url( 'assets/alt-checkbox/jquery.alt-checkbox.css' , __FILE__ ));

        $title = apply_filters('widget_title', $instance['title'] );
        $subtitle = $instance[ 'subtitle' ];
        $button = $instance[ 'button' ];
        $ok_redirect = $instance[ 'ok_redirect' ];
	$double_optin = $instance[ 'double_optin' ];
	$welcome_email = $instance[ 'welcome_email' ];
        $options = get_option('acumba_plugin_data');
        if($options != ''){
            $acumba_customer_id = $options['acumba_customer_id'];
            $acumba_auth_token = $options['acumba_auth_token'];
        }

        $api = new AcumbamailAPI($acumba_customer_id,$acumba_auth_token);

        require('inc/frontend.php');
    }

    function update($new_instance, $old_instance){
        $instance = array();
        $instance[ 'title' ] = strip_tags($new_instance[ 'title' ]);
        $instance[ 'subtitle' ] = strip_tags($new_instance[ 'subtitle' ]);
        $instance[ 'button' ] = strip_tags($new_instance[ 'button' ]);
        $instance[ 'ok_redirect' ] = strip_tags($new_instance[ 'ok_redirect' ]);
	$instance[ 'double_optin' ] = $instance[ 'double_optin' ] = strip_tags(isset($new_instance['double_optin']) && $new_instance['double_optin']  ? "1" : "0");
	$instance[ 'welcome_email' ] = $instance[ 'welcome_email' ] = strip_tags(isset($new_instance['welcome_email']) && $new_instance['welcome_email']  ? "1" : "0");
        return $instance;
    }

    function form($instance){
        if($instance) {
            $title = strip_tags( $instance['title'] );
            $subtitle = strip_tags( $instance['subtitle'] );
            $button = strip_tags( $instance['button'] );
            $ok_redirect = strip_tags( $instance['ok_redirect'] );
	    $double_optin = strip_tags(isset($instance['double_optin']) && $instance['double_optin']  ? "1" : "0");
	    $welcome_email = strip_tags(isset($instance['welcome_email']) && $instance['welcome_email']  ? "1" : "0");
        } else {
            $title = '';
            $subtitle = '';
            $button = '';
            $ok_redirect = '';
	    $double_optin = "0";
	    $welcome_email = "0";
        }

        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Título'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Subtítulo'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('button'); ?>"><?php _e('Botón (obligatorio)'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name('button'); ?>" type="text" value="<?php echo esc_attr($button); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('ok_redirect'); ?>"><?php _e('Al suscribir redirigir a'); ?></label>
                <input class="widefat" placeholder="http://" id="<?php echo $this->get_field_id('ok_redirect'); ?>" name="<?php echo $this->get_field_name('ok_redirect'); ?>" type="text" value="<?php echo esc_attr($ok_redirect); ?>" />
            </p>
	    <p>
                <label for="<?php echo $this->get_field_id('double_optin'); ?>"><?php _e('Activar doble opt-in'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('double_optin'); ?>" name="<?php echo $this->get_field_name('double_optin'); ?>" type="checkbox" <?php echo ($double_optin==1 ? 'checked' : '');?> />
            </p>
	    <p>
                <label for="<?php echo $this->get_field_id('welcome_email'); ?>"><?php _e('Activar email de bienvenida'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('welcome_email'); ?>" name="<?php echo $this->get_field_name('welcome_email'); ?>" type="checkbox" <?php echo ($welcome_email==1 ? 'checked' : '');?> />
            </p>
        <?php
    }
}

function register_acumba_widget(){
    register_widget('WPAcumbamail_Widget');
}

add_action('widgets_init','register_acumba_widget');
?>
