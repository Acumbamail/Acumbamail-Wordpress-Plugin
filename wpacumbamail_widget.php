<?php
class WPAcumbamail_Widget extends WP_WIDGET{
    function wpacumbamail_widget(){
        parent::__construct('false', $name = __( 'Acumbamail Widget'), //tranlation enabled // accessing the perant constructer
            array( 'description' => __('Widget para la inclusión de un formulario en el blog'))
            ); // end of the construct
    }

    function widget($args, $instance){
        extract($args);

        wp_enqueue_script('jquery');

        $title = apply_filters('widget_title', $instance['title'] );
        $subtitle = $instance[ 'subtitle' ];
        $button = $instance[ 'button' ];

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
        return $instance;
    }

    function form($instance){
        if($instance) {
            $title = strip_tags( $instance['title'] );
            $subtitle = strip_tags( $instance['subtitle'] );
            $button = strip_tags( $instance['button'] );
        } else {
            $title = '';
            $subtitle = '';
            $button = '';
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
                <label for="<?php echo $this->get_field_id('button'); ?>"><?php _e('Botón'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name('button'); ?>" type="text" value="<?php echo esc_attr($button); ?>" />
            </p>
        <?php
    }
}

add_action('widgets_init', function(){register_widget('WPAcumbamail_Widget');});
?>
