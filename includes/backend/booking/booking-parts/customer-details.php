<?php

/**

 * Customer Details parts.

 */

$trip_type = (isset($config["trip_type"]) ? $config["trip_type"] : '');
$nome_cliente = (isset($config["nome_cliente"]) ? $config["nome_cliente"] : '');
$token_cliente = (isset($config["token_cliente"]) ? $config["token_cliente"] : '');
$e_mail_cliente = (isset($config["e_mail_cliente"]) ? $config["e_mail_cliente"] : '');

?>

<input type="hidden" id="url_roteiro" name="" value="<?php echo esc_url( get_the_permalink( $trip_id ) ); ?>&ac=<?=$token_cliente?>">
<input type="hidden" id="nome_cliente" name="" value="<?= $nome_cliente ?>">
<input type="hidden" id="token_cliente" name="" value="<?= $token_cliente ?>">
<input type="hidden" id="e_mail_cliente" name="" value="<?= $e_mail_cliente ?>">
<input type="hidden" id="trip_type" name="" value="<?= $trip_type ?>">

<div class="wpte-block wpte-col3">

    <div class="wpte-title-wrap">

        <h4 class="wpte-title"><?php _e( 'Cliente', 'wp-travel-engine' ); ?></h4>

        

            <?php if ($trip_type == 1) { ?>
                <div class="wpte-button-wrap ">

                <a onclick="send_message_proposta()" class="wpte-btn-transparent wpte-btn-sm" style="cursor: pointer">  

                    <i class="fas fa-paper-plane"></i>

                    <?php _e( 'Enviar proposta', 'wp-travel-engine' ); ?>

                </a>
            </div>

            <?php }else{ ?>
                <div class="wpte-button-wrap wpte-edit-bkng">
                <a href="#" class="wpte-btn-transparent wpte-btn-sm">  

                    <i class="fas fa-pencil-alt"></i>

                    <?php _e( 'Editar', 'wp-travel-engine' ); ?>

                </a>
            </div>

            <?php } ?> 

    </div>

    <div class="wpte-block-content">

        <ul class="wpte-list">

            <?php if (isset($booking_metas['place_order']['booking'])) : ?>

                <li>

                    <b><?php _e('ID', 'wp-travel-engine'); ?></b>

                    <?php

                        $cid = get_page_by_title($booking_metas['place_order']['booking']['email'], OBJECT, 'customer');

                    ?>

                    <span><a target="_blank" href="<?php echo esc_url(get_edit_post_link($cid->ID, 'display')); ?>"><?php echo esc_attr($cid->ID); ?></a></span>

                </li>

            <?php

            endif;

            if (isset($billing_options['booking']) && ! empty($billing_options['booking']) ) :

                foreach( $billing_options['booking'] as $key => $value ) :

                    $booking_key = 'booking_' . $key;

                    if ('fname' === $key) {

                        $booking_key = 'booking_first_name';

                    } elseif ('lname' === $key) {

                        $booking_key = 'booking_last_name';

                    }

                    if ('survey' === $key) {

                        continue;

                    }

                    $data_label = wp_travel_engine_get_booking_field_label_by_name($booking_key);

                    ?>

                    <li>

                        <b><?php echo esc_html( $data_label ); ?></b>

                        <span><?php echo isset( $value ) ? esc_attr( $value ):'' ;?></span>

                    </li>

                    <?php

                endforeach;

                else :

                    esc_html_e('Detalhes do cliente não encontrados. Clique em "Editar" para preencher os detalhes.', 'wp-travel-engine');

                endif;

                ?>

        <?php

        if (!empty($additional_fields) ) {

            foreach ($additional_fields as $key => $value) {

                $data_label = wp_travel_engine_get_booking_field_label_by_name($key);

                if (!in_array($key, $exclude_add_info_key_array)) {

                    ?>

                <li>

                    <b><?php echo esc_html($data_label); ?></b>

                    <span><?php echo isset($value) ? esc_attr($value):'' ;?></span>

                </li>

            <?php

                }

            }

        }

        ?>

        </ul>

    </div>

    <div style="display:none;" class="wpte-block-content-edit edit-customer-info">

        <ul class="wpte-list">

            <?php

            if (isset($billing_options['booking']) && !empty($billing_options['booking'])) :

                foreach( $billing_options['booking'] as $key => $value ) :

                    $booking_key = 'booking_' . $key;

                    if ('fname' === $key) {

                        $booking_key = 'booking_first_name';

                    } elseif ('lname' === $key) {

                        $booking_key = 'booking_last_name';

                    }

                    if ('survey' === $key) {

                        continue;

                    }

                    $data_label = wp_travel_engine_get_booking_field_label_by_name( $booking_key );

                ?>

                    <li>

                        <b><?php echo esc_attr( $data_label ); ?></b>

                        <span>

                        <?php

                            // Switch type.

                            switch ( $key ) {

                                case 'email':

                                ?>

                                    <div class="wpte-field wpte-email">

                                    <input type="email" name="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]" value="<?php echo isset( $value ) ? esc_attr( $value ):'' ;?>" >

                                    </div>

                                <?php

                                break;

                                case 'country':

                                    ?>

                                    <div class="wpte-field wpte-select">

                                        <select class="wpte-enhanced-select" name="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]">

                                        <?php

                                            $wte             = new Wp_Travel_Engine_Functions();

                                            $country_options = $wte->wp_travel_engine_country_list();

                                            foreach( $country_options as $key => $country ) {

                                                $selected = selected( $value, $country, false );

                                                echo "<option " . $selected . " value='{$country}'>{$country}</option>";

                                            }

                                        ?>

                                    </select>

                                    </div>

                                    <?php

                                break;



                                default:

                                ?>

                                <div class="wpte-field wpte-text">

                                    <input type="text" name="wp_travel_engine_booking_setting[place_order][booking][<?php echo esc_attr( $key ); ?>]" value="<?php echo isset( $value ) ? esc_attr( $value ):'' ;?>" >

                                </div>

                                <?php

                                break;

                            }

                        ?>

                        </span>

                    </li>

                <?php

            endforeach;

            else :

                $checkout_fields   = WTE_Default_Form_Fields::booking();

                $checkout_fields   = apply_filters( 'wp_travel_engine_booking_fields_display', $checkout_fields );



                // Include the form class - framework.

                include_once WP_TRAVEL_ENGINE_ABSPATH . '/includes/lib/wte-form-framework/class-wte-form.php';



                // form fields initialize.

                $form_fields      = new WP_Travel_Engine_Form_Field();



                $checkout_fields  = array_map( function( $field ) {

                    $field['wrapper_class'] = 'wpte-field wpte-floated';

                    return $field;

                }, $checkout_fields );



                // Render form fields.

                $form_fields->init( $checkout_fields )->render();

            endif;

            if (!empty($additional_fields)) {

                foreach ($additional_fields as $key => $value) {

                    $data_label = wp_travel_engine_get_booking_field_label_by_name($key);

                    if (!in_array($key, $exclude_add_info_key_array)) {

                        ?>

                        <li>

                            <b><?php echo esc_html($data_label); ?></b>

                            <span>

                            <div class="wpte-field wpte-text">

                                <input type="text" name="wp_travel_engine_booking_setting[additional_fields][<?php echo esc_attr($key); ?>]" value="<?php echo isset($value) ? esc_attr($value):'' ; ?>" >

                            </div>

                                </span>

                        </li>

                    <?php

                    }

                }

            }

            ?>

        </ul>

    </div>

</div> <!-- .wpte-block -->

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> 

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

<script type="text/javascript">
    function send_message_proposta(){
        var token_cliente = jQuery("#token_cliente").val();
        var email_cliente = jQuery("#e_mail_cliente").val();
        var nome_cliente = jQuery("#nome_cliente").val();
        var url_roteiro = jQuery("#url_roteiro").val();
        var trip_type = jQuery("#trip_type").val();
        var trip_name = "<?=$trip_name?>";

        var form_data = {};

        form_data["token_cliente"] = token_cliente;
        form_data["email_cliente"] = email_cliente;
        form_data["nome_cliente"] = nome_cliente;
        form_data["trip_name"] = trip_name;
        form_data["url_roteiro"] = url_roteiro;
        form_data["trip_type"] = trip_type;
        form_data["action"] = "wte_enquiry_send_mail";

        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: form_data,
            type: "post",
            dataType: "json", 
            success: function (data) {
                return Swal.fire({

                    title: "Envio realizado",

                    text: "Sua mensagem foi enviada com sucesso.",

                    icon: "success",

                }).then((result) => {

                    // Reload the Page

                    location.reload();

                });
            },
        });
    }
</script>

<?php

