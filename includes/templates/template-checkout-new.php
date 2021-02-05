
<link rel="stylesheet" href="https://reservas.sidon.com.br/assets/css/sweetalert.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.css">

<?php
    global $wte_cart;
        global $post;
        $wp_travel_engine_setting = get_post_meta( $post->ID,'wp_travel_engine_setting',true ); 

$cart_items   = $wte_cart->getItems();  
    $global_settings = wp_travel_engine_get_settings(); 

    $default_payment_gateway = isset( $global_settings['default_gateway'] ) && ! empty( $global_settings['default_gateway'] ) ? $global_settings['default_gateway'] : 'booking_only'; 

        foreach( $cart_items as $key => $cart_item ) {
            $wp_travel_engine_setting = get_post_meta( $cart_item['trip_id'],'wp_travel_engine_setting',true ); 
            $duracao = $wp_travel_engine_setting['trip_duration']; 
                $adults = $cart_item['pax']['adult'];
                $qtd_pax = $adults;
                if (!empty($cart_item['pax']['child'])) {
                    $child = $cart_item['pax']['child'];
                    $qtd_pax = $adults+$child;
                } 
                if (!empty($cart_item['pax']['infant'])) {
                    $infant = $cart_item['pax']['infant'];
                    $qtd_pax = $adults+$child+$infant;
                }
        }
 
            $status_pagamento = 0;

?>
<style type="text/css">
    ::-webkit-scrollbar {
    appearance: none !important;
    width: auto !important;
    height: 20px !important;
}
::-webkit-scrollbar-thumb {
    background-color: #bdbdbd !important;
}
</style>
<div class="wpte-bf-outer wpte-bf-checkout">
    <div class="wpte-bf-booking-steps">
        <?php
            $show_header_steps_checkout = apply_filters( 'wp_travel_engine_show_checkout_header_steps', true );

            if ( $show_header_steps_checkout ) {
                /**
                 * Action hook for header steps.
                 */
                do_action( 'wp_travel_engine_checkout_header_steps' );
            } 

        ?>
        <div class="wpte-bf-step-content-wrap">

            <div class="wpte-bf-book-summary" style="width: 27%">
                <?php
                    do_action( 'wte_booking_before_minicart' );
                    wte_get_template( 'checkout/mini-cart.php' );
                    do_action( 'wte_booking_after_minicart' );
                ?>
            </div><!-- .wpte-bf-book-summary --> 
                <div class="wpte-bf-checkout-form" style="width: 67%;padding-right: 30px;padding-left: 30px;background-color: #fff;border-radius: 5px;padding-top: 25px;margin-left: 31px;">
                    <?php do_action('wp_travel_engine_before_billing_form'); ?> 
                    <form id="wp-travel-engine-new-checkout-form" method="POST" name="wp_travel_engine_new_checkout_form" action="" enctype="multipart/form-data" class="" novalidate="">
                        <input type="hidden" name="action" value="wp_travel_engine_new_booking_process_action">
                        <?php
                            // Create booking process action nonce for security.
                            wp_nonce_field( 'wp_travel_engine_new_booking_process_nonce_action', 'wp_travel_engine_new_booking_process_nonce' );
                        ?>
                        <input type="hidden" name="_wp_http_referer" value="/?page_id=11" />
                        <h4 style="    font-size: 24px;font-weight: 600;"><i class="fa fa-user"></i> Dados dos hóspedes</h4>
                        <?php for ($i=0; $i < $qtd_pax; $i++) {  ?>
                        <?php

    /**

     * Traveller's Information Template.

     *

     * @package WP_Travel_Engine.

     */

    global $wte_cart;



        if (isset($_POST['wp_travel_engine_booking_setting']['place_order']['booking']['subscribe']) && $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['subscribe']=='1' )

        {

            $myvar = $_POST;

            $obj = new Wte_Mailchimp_Main;

            $new = $obj->wte_mailchimp_action($myvar);

        }

        if (isset($_POST['wp_travel_engine_booking_setting']['place_order']['booking']['mailerlite']) && $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['mailerlite']=='1' )

        {

            $myvar = $_POST;

            $obj = new Wte_Mailerlite_Main;

            $new = $obj->wte_mailerlite_action($myvar);

        }

        if (isset($_POST['wp_travel_engine_booking_setting']['place_order']['booking']['convertkit']) && $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['convertkit']=='1' )

        {

            $myvar = $_POST;

            $obj = new Wte_Convertkit_Main;

            $new = $obj->wte_convertkit_action($myvar);

        }

        $options = get_option('wp_travel_engine_settings', true);

        $wp_travel_engine_thankyou = isset($options['pages']['wp_travel_engine_thank_you']) ? esc_attr($options['pages']['wp_travel_engine_thank_you']) : '';



        $wp_travel_engine_thankyou = ! empty( $wp_travel_engine_thankyou ) ? get_permalink( $wp_travel_engine_thankyou ) : home_url( '/' );



        if ( isset( $_GET['booking_id'] ) && ! empty( $_GET['booking_id'] ) ) :

            $wp_travel_engine_thankyou = add_query_arg( 'booking_id', $_GET['booking_id'], $wp_travel_engine_thankyou );

        endif;

        if ( isset( $_GET['redirect_type'] ) && ! empty( $_GET['redirect_type'] ) ) :

            $wp_travel_engine_thankyou = add_query_arg( 'redirect_type', $_GET['redirect_type'], $wp_travel_engine_thankyou );

        endif;

        if ( isset( $_GET['wte_gateway'] ) && ! empty( $_GET['wte_gateway'] ) ) :

            $wp_travel_engine_thankyou = add_query_arg( 'wte_gateway', $_GET['wte_gateway'], $wp_travel_engine_thankyou );

        endif;

        if ( isset( $_GET['status'] ) && ! empty( $_GET['status'] ) ) :

            $wp_travel_engine_thankyou = add_query_arg( 'status', $_GET['status'], $wp_travel_engine_thankyou );

        endif;

        ?>

        <section>

        <?php

            if( isset( $_GET['wte_gateway'] ) && 'paypal' === $_GET['wte_gateway'] ) {

                do_action( 'wp_travel_engine_verify_paypal_ipn' );

            }



            $hide_traveller_info = isset( $options['travelers_information'] ) ? $options['travelers_information'] : 'yes'; 


            include_once WP_TRAVEL_ENGINE_ABSPATH . '/includes/lib/wte-form-framework/class-wte-form.php';



            $total_pax = 0;

            $cart_items = $wte_cart->getItems();



            foreach( $cart_items as $key => $item ) {

                $pax       = array_sum( $item['pax'] );

                $total_pax = absint( $total_pax + $pax );

            }



            $form_fields      = new WP_Travel_Engine_Form_Field();



            $traveller_fields   = WTE_Default_Form_Fields::traveller_information();

            $traveller_fields   = apply_filters( 'wp_travel_engine_traveller_info_fields_display', $traveller_fields );



            $emergency_contact_fields = WTE_Default_Form_Fields::emergency_contact();

            $emergency_contact_fields = apply_filters( 'wp_travel_engine_emergency_contact_fields_display', $emergency_contact_fields );



            $wp_travel_engine_settings_options = get_option( 'wp_travel_engine_settings', true );


            $i = 1;
            foreach( $cart_item['pax'] as $pax_label => $pax ) {
                if ( $pax == '0' ) continue;

                echo '<div class=" "> </div>';
                echo '<div class="row">';
 

                                    if ($pax_label == 'adult') {
                                        $pax_label_disp = ($pax > 1 ? 'Adultos' : 'Adulto'); 
                                        $onclick = '';
                                    }else if ($pax_label == 'child') {
                                        $pax_label_disp = ($pax > 1 ? 'Crianças' : 'Criança'); 
                                        $onclick = 'return validar_data_child('.$i.')';
                                    }else if ($pax_label == 'infant') {
                                        $pax_label_disp = ($pax > 1 ? 'Bebês' : 'Bebê'); 
                                        $onclick = 'return validar_data_infant('.$i.')';
                                    }else if ($pax_label == 'group') {
                                        $pax_label_disp = ($pax > 1 ? 'Grupos' : 'Grupo'); 
                                        $onclick = '';
                                    } 



                echo ' 

                 <div class="wpte-bf-field wpte-cf-text col-lg-1 text-center" style="padding: 9px;">
                 <i class="fa fa-user" style="font-size:22px"></i><br><label style="font-size:12px !important">'.$pax_label_disp.'</label>
                 </div>

                 <div class="wpte-bf-field wpte-cf-text col-lg-3">
                <label class="" for="wp_travel_engine_placeorder_setting[place_order][travelers][fname]['.$i.']">

                    Nome:
                    
                        <span class="required">*</span>

                    
                </label>
                <input type="text" id="wp_travel_engine_placeorder_setting[place_order][travelers][fname]['.$i.']" name="wp_travel_engine_placeorder_setting[place_order][travelers][fname]['.$i.']" value="" required="1" maxlength="50" class="">            </div>

                    <div class="wpte-bf-field wpte-cf-text col-lg-3">
                <label class="" for="wp_travel_engine_placeorder_setting[place_order][travelers][lname]['.$i.']">

                    Último sobrenome:
                    
                        <span class="required">*</span>

                    
                </label>
                <input type="text" id="wp_travel_engine_placeorder_setting[place_order][travelers][lname]['.$i.']" name="wp_travel_engine_placeorder_setting[place_order][travelers][lname]['.$i.']" value="" required="1" maxlength="50" class="">            </div>

                    <div class="wpte-bf-field wpte-cf-text col-lg-3">
                <label class="" for="wp_travel_engine_placeorder_setting[place_order][travelers][dob]['.$i.']">

                    Nascimento:
                    
                        <span class="required">*</span>

                    
                </label>
                <input type="text" id="wp_travel_engine_placeorder_setting[place_order][travelers][dob]['.$i.']" name="wp_travel_engine_placeorder_setting[place_order][travelers][dob]['.$i.']" required="1" maxlength="50" class="nasc nasc_pax_'.$i.'" onblur="'.$onclick.'">            </div>

                    <div class="wpte-bf-field wpte-cf-text col-lg-2">
                <label class="" for="wp_travel_engine_placeorder_setting[place_order][travelers][title]['.$i.']">

                    Gênero:
                    
                        <span class="required">*</span>

                    
                </label>
                <select id="wp_travel_engine_placeorder_setting[place_order][travelers][title]['.$i.']" name="wp_travel_engine_placeorder_setting[place_order][travelers][title]['.$i.']" class="" required="1"><option value="" selected="">Escolha</option><option value="Feminino">Feminino</option><option value="Masculino">Masculino</option></select>            </div>';

        

                echo '</div>';

                $i++;

            }

            $nonce = wp_create_nonce('wp_travel_engine_final_confirmation_nonce');

            ?>

            <input type="hidden" name="nonce" value="<?php echo $nonce;?>"> 
        </section> 

                    <?php } ?> 
                        <br><br>
                        <h4 style="    font-size: 24px;font-weight: 600;"><i class="fa fa-lock"></i> Dados para contato</h4>
                        <div class="row">
                            <div class="wpte-bf-field wpte-cf-text col-lg-6">
                                <label class="wpte-bf-label" for="telefone">
                                    Nome completo

                                    
                                </label>
                                <input
                                    type="text"
                                    id="full_name"
                                    name="wp_travel_engine_booking_setting[place_order][booking][full_name]"
                                    value=""
                                    required="1"
                                    maxlength="15"
                                    class=""
                                    data-msg="Informe seu nome completo"
                                    data-parsley-required-message="Informe seu nome completo"
                                />
                            </div>
                            <div class="wpte-bf-field wpte-cf-text col-lg-6">
                                <label class="wpte-bf-label" for="telefone">
                                    Celular

                                    
                                </label>
                                <input
                                    type="text"
                                    id="telefone"
                                    name="wp_travel_engine_booking_setting[place_order][booking][phone]"
                                    value=""
                                    required="1"
                                    maxlength="15"
                                    class=""
                                    data-msg="Informe seu telefone"
                                    data-parsley-required-message="Informe seu telefone"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="wpte-bf-field wpte-cf-email col-lg-6">
                                <label class="wpte-bf-label" for="wp_travel_engine_booking_setting[place_order][booking][email]">
                                    Email

                                    
                                </label>
                                <input
                                    type="email"
                                    id="wp_travel_engine_booking_setting[place_order][booking][email]"
                                    name="wp_travel_engine_booking_setting[place_order][booking][email]"
                                    value=""
                                    required="1"
                                    class=""
                                    data-msg="Informe seu E-mail"
                                    data-parsley-required-message="Informe seu E-mail"
                                />
                            </div>  
                            <div class="wpte-bf-field wpte-cf-email col-lg-6">
                                <label class="wpte-bf-label" for="wp_travel_engine_booking_setting[place_order][booking][conf_email]">
                                    Confirme seu email
 
                                </label>
                                <input
                                    type="email"
                                    id="wp_travel_engine_booking_setting[place_order][booking][conf_email]"
                                    name="wp_travel_engine_booking_setting[place_order][booking][conf_email]"
                                    value=""
                                    required="1"
                                    class=""
                                    data-msg="Informe seu E-mail"
                                    data-parsley-required-message="Informe seu E-mail"
                                />
                            </div>  
                        </div>

                        <div class="wp-travel-engine-info-field">
                            <label class="" for="wp-travel-engine-privacy-info"> </label>
                            <div class="wp-travel-engine-info-wrap"><span class="wp-travel-engine-info" id="wp-travel-engine-privacy-info"></span></div>
                        </div>

                        <div class="wpte-bf-field wpte-bf-submit" style="margin-top: -25px">
                            <!-- <input type="submit" name="wp_travel_engine_nw_bkg_submit" value="RESERVAR" style="width: 30%;float: right;" /> -->
                            <button type="submit" name="wp_travel_engine_nw_bkg_submit" id="button_reserva" value="RESERVAR" style="width: 30%;float: right;margin-bottom: 35px;">RESERVAR</button>
                        </div>
                        <br>
                    </form>

                    <?php do_action( 'wte_booking_after_checkout_form_close' ); ?>
                </div><!-- .wpte-bf-checkout-form --> 
        </div><!-- .wpte-bf-step-content-wrap -->
    </div><!-- .wpte-bf-booking-steps -->
</div><!-- .wpte-bf-outer -->
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script src="/wp-content/plugins/trips_plugin/admin/js/jquery.mask.js"></script>
<script src="https://reservas.sidon.com.br/assets/js/sweetalert.js"></script>
<script src="https://reservas.sidon.com.br/assets/js/jquery.creditCardValidator.js"></script>
<script src="/wp-content/plugins/trips_plugin/admin/js/validarcartao2.js"></script>
<script type="text/javascript">
    jQuery( document ).ready(function() {
        jQuery('#telefone').mask('(00) 00000-0000');
        jQuery('.nasc').mask('00/00/0000');
        jQuery('#cep').mask('00000-000');
        jQuery('#cpf_cnpj_cartao').mask('000.000.000-00');
        jQuery('#validadeCartao').mask('00/00');
        jQuery('#numero_do_cartao').mask('0000 0000 0000 0000'); 
    });  

    function validar_data_child(id){
        var min_age_child = $("#min_age_child").val();
        var max_age_child = $("#max_age_child").val();

        var d = new Date(); 

        var year = d.getFullYear();

        var campo = $(".nasc_pax_"+id).val();
        var data_pax = campo.split("/");

        var valor = year-data_pax[2];
        if (valor >= min_age_child && valor <= max_age_child) {

        }else{
            return Swal.fire({
                title: "Ops!",
                text: "Data inválida para o hóspede do tipo criança. Para ser aceita, a criança precisa ter entre "+min_age_child+" e "+max_age_child+" anos.",
                icon: "error",
            });
        }
    }  

    function validar_data_infant(id){
        var min_age_infant = $("#min_age_infant").val();
        var max_age_infant = $("#max_age_infant").val();

        var d = new Date(); 

        var year = d.getFullYear();

        var campo = $(".nasc_pax_"+id).val();
        var data_pax = campo.split("/");

        var valor = year-data_pax[2];
        console.log(valor);
        if (valor >= min_age_infant && valor <= max_age_infant) {

        }else{
            return Swal.fire({
                title: "Ops!",
                text: "Data inválida para o hóspede do tipo bebê. Para ser aceito, o bebê precisa ter entre "+min_age_infant+" e "+max_age_infant+" anos.",
                icon: "error",
            });
        }
    }
</script>