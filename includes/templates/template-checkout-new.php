<link rel="stylesheet" type="text/css" href="https://reservas.sidon.com.br/assets/css/cores.php">
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

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.traveltec.com.br/serv/pagamento/listar_dados",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 82934c35-3bd1-7c2d-4c2e-53571acfe5fc",
            "url: wp01.montenegroev.com.br"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
            $dados = json_decode($response, true);
            $message = $dados['message']; 
            $status_pagamento = $message['status'];
        } 
        echo '<input type="hidden" id="status_pagamento" value="'.str_replace("/", "", $message['status']).'">';

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

            $options                           = get_option('wp_travel_engine_settings', true);
            $wp_travel_engine_terms_conditions = isset( $options['pages']['wp_travel_engine_terms_and_conditions'] ) ? esc_attr( $options['pages']['wp_travel_engine_terms_and_conditions'] ) : '';

            if( function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ) {

                $privacy_policy_lbl = sprintf( __( 'Check the box to confirm you\'ve read and agree to our <a href="%1$s" id="terms-and-conditions" target="_blank"> Terms and Conditions</a> and <a href="%2$s" id="privacy-policy" target="_blank">Privacy Policy</a>.', 'wp-travel-engine'), esc_url( get_permalink( $wp_travel_engine_terms_conditions ) ), esc_url( get_privacy_policy_url()) );

                $checkout_default_fields['privacy_policy_info'] =  array(
                    'type'          => 'checkbox',
                    'options'       => array( '0' => $privacy_policy_lbl ),
                    'name'          => 'wp_travel_engine_booking_setting[terms_conditions]',
                    'wrapper_class' => 'wp-travel-engine-terms',
                    'id'            => 'wp_travel_engine_booking_setting[terms_conditions]',
                    'default'       => '',
                    'validations'   => array(
                        'required' => true,
                    ),
                    'option_attributes' => array(
                        'required' => true,
                        'data-msg' => __( 'Please make sure to check the privacy policy checkbox', 'wp-travel-engine' ),
                        'data-parsley-required-message' => __( 'Please make sure to check the privacy policy checkbox', 'wp-travel-engine' ),
                    ),
                    'priority' => 70,
                );

            }
            elseif ( current_user_can( 'edit_theme_options' ) ) {

                $privacy_policy_lbl = sprintf( __( '%1$sPrivacy Policy page not set or not published, please check Admin Dashboard > Settings > Privacy.%2$s', 'wp-travel-engine' ), '<p style="color:red;">', '</p>' );

                $checkout_default_fields['privacy_policy_info'] =  array(
                    'type'              => 'text_info',
                    // 'label'             => __( 'Privacy Policy', 'wp-travel-engine' ),
                    'id'                => 'wp-travel-engine-privacy-info',
                    'default'           => '',
                    'priority'          => 80,
                );

            }

            $checkout_fields   = WTE_Default_Form_Fields::booking();
            $checkout_fields   = apply_filters( 'wp_travel_engine_booking_fields_display', $checkout_fields );

            // $priority = array_column( $checkout_fields, 'priority' );
            // array_multisort( $priority, SORT_ASC, $checkout_fields );

        ?>
        <div class="wpte-bf-step-content-wrap">

            <div class="wpte-bf-book-summary" style="width: 27%">
                <?php
                    do_action( 'wte_booking_before_minicart' );
                    wte_get_template( 'checkout/mini-cart.php' );
                    do_action( 'wte_booking_after_minicart' );
                ?>
            </div><!-- .wpte-bf-book-summary -->
            <?php  if ( ! empty( $checkout_fields ) && is_array( $checkout_fields ) ) : ?>
                <div class="wpte-bf-checkout-form" style="width: 70%;padding-right: 30px;padding-left: 30px;background-color: #fff;border-radius: 5px;padding-top: 25px;margin-left: 31px;">
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



            if( 'yes' === $hide_traveller_info || '1' === $hide_traveller_info ) {

                if ( isset( $_POST ) ) {

                    $error_found = FALSE;



                    //  Some input field checking

                    if ( $error_found == FALSE ) {

                        //  Use the wp redirect function

                        wp_redirect( $wp_travel_engine_thankyou );

                    }

                    else {

                        //  Some errors were found, so let's output the header since we are staying on this page

                        if (isset($_GET['noheader']))

                            require_once(ABSPATH . 'wp-admin/admin-header.php');

                    }

                }

            }



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

                       <!--  <br>

                        <h4 style="    font-size: 24px;font-weight: 600;"><i class="fa fa-lock"></i> Dados para o pagamento</h4>
                        <br>
                        <div class="reservar" style="background-color: #eee;padding: 30px;">
                        <div class="dados-fieldset-container"> 
                            <div class="container-card">
                                <div class="container-front">
                                    <div class="row white-text" style="padding: 10px 17px">
                                        <div class="input-field col-lg-12">
                                            <label class="label" for="numero_do_cartao" style="font-size: 13px;font-weight: 400;padding-left: 0;">Numero do cartão:</label>
                                            <div class="card-content">
                                                <span class="input-group-addon" style="background-color: transparent;border: 1px solid transparent;">
                                                    <i class="fa fa-credit-card" style="    font-size: 28px;color: #fff;margin: -2px 0px 2px 8px;"></i>
                                                    <img id="iconeMaster" class="ocultarDisplay iconeCards" src="https://reservas.sidon.com.br/assets/images/icon-mastercard.png" style="max-width: 44px;margin-top: -3px;margin-bottom: 8px;margin-right: -12px;">
                                                    <img id="iconeVisa" class="ocultarDisplay iconeCards" src="https://reservas.sidon.com.br/assets/images/icon-visa.png" style="max-width: 44px;margin-top: -3px;margin-bottom: 8px;margin-right: -12px;">
                                                    <img id="iconeAmex" class="ocultarDisplay iconeCards" src="https://reservas.sidon.com.br/assets/images/icon-amex.png" style="max-width: 44px;margin-top: -3px;margin-bottom: 8px;margin-right: -12px;">
                                                </span>
                                                <input autocomplete="off" type="text" name="numero_do_cartao" value="" id="numero_do_cartao" placeholder="Digite o número do cartão" class="cardNumber validate form-control" required="" style="background-color: #fff;height: 35px;font-size: 13px" onfocusout="bandeiraCartao()">
                                                <input type="hidden" id="quant01" value="">
                                                <input type="hidden" id="bandeira_cartao" name="bandeira_cartao" value="">
                                                <input type="hidden" id="bandeiras_permitidas" name="bandeiras_permitidas" value="mastercard;visa;amex;">
                                            </div>
                                        </div>
                                        <div class="input-field col-lg-6">

                                            <label class="label active" for="validade" style="font-size: 13px;font-weight: 400;padding-left: 0;">Validade (mês/ano):</label>
                                            <input autocomplete="off" type="text" name="validade" placeholder="mm/aa" id="validadeCartao" required="" style="background-color: #fff;height: 35px;font-size: 13px" class="form-control" onfocusout="ValidarDadoValidadeCartao()">
                                        </div> 

                                        <div class="input-field col-lg-6">
                                            <label class="label active" for="cpf" style="font-size: 13px;font-weight: 400;padding-left: 0;">CPF:</label>
                                            <input autocomplete="off" type="text" name="cpf" id="cpf_cnpj_cartao" onclick="$('#cpf_cnpj_cartao').val('')" placeholder="000.000.000-00" required="" style="background-color: #fff;height: 35px;font-size: 13px" class="form-control">
                                        </div>
                                        <div class="input-field col-lg-6">
                                            <label class="label active" for="nome" style="font-size: 13px;font-weight: 400;padding-left: 0;">Nome:</label>
                                            <input autocomplete="off" type="text" name="nome" id="nome" placeholder="Digite o nome que está no cartão" required="" style="background-color: #fff;height: 35px;font-size: 13px" class="form-control">
                                        </div>

                                        <div class="input-field col-lg-6">
                                            <label class="label active" for="sobrenome" style="font-size: 13px;font-weight: 400;padding-left: 0;">Sobrenome:</label>
                                            <input autocomplete="off" type="text" name="sobrenome" id="sobrenome" placeholder="Digite o sobrenome que está no cartão" required="" style="background-color: #fff;height: 35px;font-size: 13px" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="container-back hide-on-med-and-down">
                                    <div class="divider-card"></div>
                                    <div class="input-content">
                                        <div class="col-lg-2" style="padding: 0"></div>
                                        <div class="input-field col-lg-10" style="padding-left: 0">
                                            <label class="label active" for="numero_de_seguranca" style="font-size: 13px;font-weight: 400;padding-left: 20px;">Nº de segurança:</label>
                                            <input autocomplete="off" type="text" name="numero_de_seguranca" onblur="ValidarDadoValidadeCartao()" placeholder="XXX" id="numeroSeg" required="" maxlength="4" style="background-color: #fff;height: 35px;font-size: 13px;margin-left: 20px;width: 89%;" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="margin_2_top divider">

                            </div>
                            <br>
                            <h4 class="margin_2_top">Dados da fatura</h4>

                            <div class="row">


                                <div class="input-field col-lg-5">
                                    <label class="wpte-bf-label" for="wp_travel_engine_booking_setting[place_order][booking][address]"> CEP</label>
                                    <input autocomplete="off" type="text" name="cep" id="cep" onfocusout="buscarCEP()" required="" style="background-color: #fff">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="input-field col-lg-7 hide-on-med-and-down"></div>

                                <div class="input-field col-lg-10">
                                    <label class="wpte-bf-label" for="wp_travel_engine_booking_setting[place_order][booking][address]"> Endereço</label>
                                    <input
                                        type="text"
                                        id="wp_travel_engine_booking_setting[place_order][booking][address]"
                                        name="wp_travel_engine_booking_setting[place_order][booking][address]"
                                        value=""
                                        required="1"
                                        maxlength="100"
                                        class="endereco"
                                        data-msg="Informe seu Endereço"
                                        data-parsley-required-message="Informe seu Endereço" style="background-color: #fff"
                                    />
                                </div>

                                <div class="input-field col-lg-2">
                                    <label class="wpte-bf-label" for="wp_travel_engine_booking_setting[place_order][booking][address]"> Nº</label>
                                    <input autocomplete="off" type="text" name="numero" id="numero" placeholder="" required="" style="background-color: #fff">
                                </div>
                            </div>
                            <br>
                            <div class="row">

                                <div class="input-field col-lg-4">
                                    <label class="wpte-bf-label" for="wp_travel_engine_booking_setting[place_order][booking][address]"> Complemento</label>
                                    <input autocomplete="off" type="text" name="complemento" id="complemento" placeholder="" style="background-color: #fff">
                                </div>

                                <div class="input-field col-lg-3">
                                    <label class="wpte-bf-label" for="wp_travel_engine_booking_setting[place_order][booking][address]"> Bairro</label>
                                    <input autocomplete="off" type="text" name="bairro" id="bairro" placeholder="" required="" style="background-color: #fff">
                                </div>

                                <div class="input-field col-lg-3">
                                    <label class="wpte-bf-label" for="wp_travel_engine_booking_setting[place_order][booking][city]">
                                Cidade</label>
                                    <input
                                type="text"
                                id="wp_travel_engine_booking_setting[place_order][booking][city]"
                                name="wp_travel_engine_booking_setting[place_order][booking][city]"
                                value=""
                                required="1"
                                class="cidade"
                                data-msg="Informe sua Cidade"
                                data-parsley-required-message="Informe sua Cidade" style="background-color: #fff"
                            />
                                </div>

                                <div class="input-field col-lg-2">
                                    <label class="wpte-bf-label" for="wp_travel_engine_booking_setting[place_order][booking][address]"> UF</label>
                                    <input autocomplete="off" type="text" name="uf" id="estado" placeholder="" required="" style="background-color: #fff" maxlength="2">
                                </div>

                            </div>

                        </div>
                    </div> -->
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
            <?php endif; ?>
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