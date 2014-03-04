<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('tbody tr').each(function(){
        jQuery(this).find("input[type=hidden]").prop('value',jQuery('tbody tr').index(jQuery(this)))
    });
    jQuery('tbody').sortable({
        update:  function (event, ui) {
            jQuery('tbody tr').each(function(){
                jQuery(this).find("input[type=hidden]").prop('value',jQuery('tbody tr').index(jQuery(this)))
            });
        }});
});
</script>
<div class="wrap">
        <h2>Acumbamail para Wordpress</h2>

        <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <!-- main content -->
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                <h3><span>Configuración de Cuenta</span></h3>
                                <div class="inside">
                                    <form method="POST" action="" name="acumbamail_config_form">
                                        <!--Comprobamos si el formulario ha sido enviado ya-->
                                        <input type="hidden" name="acumbamail_form_submitted" value="Y">

                                        <p><label for="acumba_customer_id">Customer ID</label><br>
                                        <input name="acumba_customer_id" id="acumba_customer_id" type="text" value="<? if(isset($acumba_customer_id)){echo $acumba_customer_id;}?>" class="regular-text" /></p>
                                        <p><label for="acumba_auth_token">Auth Token</label><br>
                                        <input name="acumba_auth_token" id="acumba_auth_token" type="text" value="<? if(isset($acumba_auth_token)){echo $acumba_auth_token;}?>" class="regular-text"></p>

                                </div> <!-- .inside -->
                            </div> <!-- .postbox -->
                            <p><input class="button-primary" type="submit" value="<?php _e( 'Guardar Ajustes' ); ?>" /></p>
                            </form>

                            <?php if($response_lists){ ?>
                            <div class="postbox">
                                <h3><span>Tus Listas</span>

                                <form style="display: inline; margin-left: 10px;" method="POST" action="">
                                    <input type="hidden" name="acumbamail_list_chosen" value="Y">
                                    <select name="chosen_list">
                                        <?php
                                            foreach (array_keys($response_lists) as $list) {
                                                echo "<option value=\"$list\" " . ( ($list == $chosen_list) ? "selected" : "") . ">".$response_lists[$list]['name']."</option>";
                                            }
                                        ?>
                                    </select>
                                    <input class="button-primary" type="submit" value="<?php _e( 'Escoger lista' ); ?>" />
                                </form>
                                </h3>
                            </div> <!-- .postbox -->

                        <?php if(isset($chosen_list)){?>
                            <form method="POST" action="">
                            <div class="postbox">
                                <h3>Variables Personalizadas <span style="font-size: 0.8em">(Arrastra los campos para cambiar el orden del formulario)</span></h3>
                                <div class="inside">
                                    <table class="widefat" id="merge_tags">
                                        <tr class="alternate">
                                            <th class="row-title">Nombre</th>
                                            <th class="row-title">Tipo</th>
                                            <th class="row-title">Nombre en el formulario</th>
                                        </tr>
                                        <tbody>
                                        <input type="hidden" name="changed_order" value="Y">
                                        <?php

                                        foreach ($ordered as $key => $value) {
                                                echo "<tr style='cursor:move'>";
                                                echo '<input type="hidden" name="'.$widget_fields[$value]['name'].'" value="'.$i++.'">';
                                                echo '<td class="row-title"><label for="tablecell">'.$widget_fields[$value]['name'].'</label></td>
                                                      <td>'.$widget_fields[$value]['type'].'</td>
                                                      <td><input type="text" name="'.$widget_fields[$value]['name'].'_given" value="'.$widget_fields[$value]['name_given'].'"></td>
                                                      </tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div><!-- .inside -->
                            </div> <!-- .postbox -->
                            <p><input class="button-primary" type="submit" value="<?php _e( 'Guardar Cambios' ); ?>" /></p>
                            </form>
                        <?php } ?>
                <?php } else { ?>
                            <div class="postbox">
                                <h3><span>Información</span></h3>
                                <div class="inside">
                                    Para la utilización del plugin funcione necesitas configurar los datos de tu cuenta.
                                </div>
                            </div> <!-- .postbox -->
                <? } ?>
                        </div> <!-- .meta-box-sortables .ui-sortable -->
                    </div> <!-- post-body-content -->

                    <!-- sidebar -->
                    <div id="postbox-container-1" class="postbox-container">

                        <div class="meta-box-sortables">

                            <div class="postbox">

                                <h3><span>¿Cómo funciona el plugin?</span></h3>
                                <div class="inside">
                                    <ol>
                                        <li>Introduce los datos de la API de Acumbamail correspondiente a tu usuario. Los podrás encontrar <a href="http://acumbamail.com/apidoc">aquí</a></li>
                                        <li>Elige la lista para la que quieres el widget de suscriptores.</li>
                                        <li>Configura el título, subtítulo y el texto del botón que quieres que aparezca en el widget</li>
                                    </ol>
                                </div>

                            </div> <!-- .postbox -->

                            <div class="postbox">

                                <h3><span>Sobre Acumbamail</span></h3>
                                <div class="inside">
                                    <p>Nuestro equipo está formado por profesionales que proceden del sector del marketing online, la informática y el diseño.
                                    Tenemos años de experiencia detrás para garantizar un servicio de calidad. Estaremos encantados de atenderte cuando lo necesites.</p>
                                </div>

                            </div> <!-- .postbox -->

                        </div> <!-- .meta-box-sortables -->

                    </div> <!-- #postbox-container-1 .postbox-container -->

                </div>
                <br class="clear">
            </div>
    </div>

    <script type="text/javascript">
    alert($('#post-body'));
    </script>
