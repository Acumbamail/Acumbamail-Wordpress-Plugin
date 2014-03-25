<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

delete_option('acumba_plugin_data');
delete_option('acumba_chosen_list');
delete_option('acumba_widget_fields');
delete_option('acumba_ordered_fields');

?>
