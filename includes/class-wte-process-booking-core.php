<?php

/**

 * Process the booking flow in WP Travel Engine.

 *

 * @package WP_Travel_Engine

 * @since 2.2.8

 */

/**

 * Main Booking process handler class.

 */

class WTE_Process_Booking_Core {



	/**

	 * Handle the booking process after the booking request form is submitted from checkout.

	 *

	 * @return void

	 */

	public function process_booking() {

		// $curl = curl_init();

  //       curl_setopt_array($curl, array(
  //         CURLOPT_URL => "https://api.traveltec.com.br/serv/pagamento/listar_dados",
  //         CURLOPT_RETURNTRANSFER => true,
  //         CURLOPT_ENCODING => "",
  //         CURLOPT_MAXREDIRS => 10,
  //         CURLOPT_TIMEOUT => 30,
  //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //         CURLOPT_CUSTOMREQUEST => "POST",
  //         CURLOPT_HTTPHEADER => array(
  //           "cache-control: no-cache",
  //           "content-type: application/json",
  //           "postman-token: 82934c35-3bd1-7c2d-4c2e-53571acfe5fc",
  //           "url: wp01.montenegroev.com.br"
  //         ),
  //       ));

  //       $response = curl_exec($curl);
  //       $err = curl_error($curl);

  //       curl_close($curl);

  //       if ($err) {
  //         echo "cURL Error #:" . $err;
  //       } else {
  //           $dados = json_decode($response, true);
  //           $message = $dados['message']; 
  //           $status_pagamento = $message['status'];
  //       } 
		$status_pagamento = 0;

		if (

			! isset( $_POST['action'] )

			|| 'wp_travel_engine_new_booking_process_action' !== $_POST['action']

			|| ! isset( $_POST['wp_travel_engine_nw_bkg_submit'] )

			|| ! isset( $_POST['wp_travel_engine_new_booking_process_nonce'] )

			|| ! wp_verify_nonce( $_POST['wp_travel_engine_new_booking_process_nonce'], 'wp_travel_engine_new_booking_process_nonce_action' )

		) {

			return;

		}



		global $wte_cart;



		$cart_items = $wte_cart->getItems();

		$cart_total = $wte_cart->get_total();

		$valor_total = $cart_total['total'];

		foreach( $cart_items as $key => $cart_item ) {
          	$wp_travel_engine_setting = get_post_meta( $cart_item['trip_id'],'wp_travel_engine_setting',true ); 
			$currency_code = $wp_travel_engine_setting['multiple_pricing'][0]['adult']['currency_code'];
		}



		if ( empty( $cart_items ) ) {

			return;

		}



		foreach ( $cart_items as $key => $cart_item ) :



			$post = get_post( $cart_item['trip_id'] );

			$slug = $post->post_title;

			$pax  = isset( $cart_item['pax'] ) ? $cart_item['pax'] : array();



			$payment_mode = isset( $_POST['wp_travel_engine_payment_mode'] ) ? $_POST['wp_travel_engine_payment_mode'] : 'full_payment';

			$due          = 'partial' == $payment_mode ? wp_travel_engine_get_formated_price( $cart_total['total'] - $cart_total['total_partial'] ) : 0;

			$total_paid   = 'partial' == $payment_mode ? wp_travel_engine_get_formated_price( $cart_total['total_partial'] ) : wp_travel_engine_get_formated_price( $cart_total['total'] );



			$order_metas = array(

				'place_order' => array(

					'currency_code' => esc_attr( $currency_code ),

					'traveler' => esc_attr( array_sum( $pax ) ),

					'cost_formatted'     => esc_attr( $total_paid ).'0,00',

					'cost'     => esc_attr( $total_paid ),

					'due'      => esc_attr( $due ),

					'tid'      => esc_attr( $cart_item['trip_id'] ),

					'tname'    => esc_attr( $slug ),

					'datetime' => esc_attr( $cart_item['trip_date'] ),

					'booking'  => array(

						'fullname' => esc_attr( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['full_name'] ),

						'fname'   => esc_attr( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['fname'] ),

						'lname'   => esc_attr( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['lname'] ),

						'phone'   => esc_attr( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['phone'] ),

						'email'   => esc_attr( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['email'] ),

						'address' => isset( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['address'] ) ? esc_attr( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['address'] ) : '',

						'city'    => isset( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['city'] ) ? esc_attr( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['city'] ) : '',

						'country' => isset( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['country'] ) ? esc_attr( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['country'] ) : '',

						'survey'  => isset( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['survey'] ) ? esc_attr( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['survey'] ) : '',

					),

				),

			);

			$url = wp_travel_engine_get_booking_confirm_url();

			$dados_checkout_pag_seguro = "{\"dados\": \"<?xml version='1.0'?><checkout> <sender> <name>".esc_attr( $_POST['wp_travel_engine_booking_setting']['place_order']['booking']['full_name'] )."</name> <phone> <areaCode>99</areaCode> <number>999999999</number> </phone> <documents> <document> <type>CPF</type> <value>00000000000</value> </document> </documents> </sender> <currency>BRL</currency> <items> <item> <id>0001</id> <description>Roteiro ".esc_attr( $slug )."</description> <amount>".esc_attr( str_replace(",", ".", str_replace(".", "", $valor_total) ) ).".00"."</amount> <quantity>1</quantity> </item> </items> <redirectURL>".$url."</redirectURL> <extraAmount>0.00</extraAmount> <reference>REF".$booking_id."</reference> <shippingAddressRequired>false</shippingAddressRequired> <timeout>25</timeout> <maxAge>999999999</maxAge> <maxUses>999</maxUses> <receiver> <email>".$_POST['wp_travel_engine_booking_setting']['place_order']['booking']['email']."</email> </receiver> <acceptedPaymentMethods> <exclude> <paymentMethod> <group>ONLINE_DEBIT</group> </paymentMethod> </exclude> </acceptedPaymentMethods> <paymentMethodConfigs> <paymentMethodConfig> <paymentMethod> <group>BOLETO</group> </paymentMethod> <configs> <config> <key>DISCOUNT_PERCENT</key> <value>10.00</value> </config> </configs> </paymentMethodConfig> </paymentMethodConfigs> <paymentMethodConfigs> <paymentMethodConfig> <paymentMethod> <group>CREDIT_CARD</group> </paymentMethod> <configs> <config> <key>MAX_INSTALLMENTS_LIMIT</key> <value>3</value> </config> </configs> </paymentMethodConfig> </paymentMethodConfigs> <enableRecover>false</enableRecover></checkout>\"}";



			if ( isset( $order_metas ) && is_array( $order_metas ) ) :

				global $wpdb;



				$new_post = array(

					'post_status' => 'publish',

					'post_type'   => 'booking',

					'post_title'  => 'booking',

				);



				$booking_id = wp_insert_post( $new_post );



				if ( ! is_wp_error( $booking_id ) ) :

					/**

					 * @action_hook wte_created_user_booking

					 *

					 * @since 2.2.0

					 */

					do_action( 'wte_after_booking_created', $booking_id );

				endif;



				// Add booking id to the cart.

				$wte_cart->set_attribute( 'booking_id', $booking_id );



				$book_post = array(

					'ID'         => $booking_id,

					'post_title' => 'Reserva nº ' . $booking_id,

				);



				// Update the post into the database

				$updated     = wp_update_post( $book_post );

				$bid[]       = $booking_id;

				$order_metas = array_merge_recursive( $order_metas, $bid );



				/**

				 * @hook wte_booking_meta

				 *

				 * @since 3.0.7

				 */

				$order_metas = apply_filters( 'wte_before_booking_meta_save', $order_metas, $booking_id );



				$all_form_values   = $_POST;

				$additional_fields = array();



				update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_status', 'pending' );



				// adds new meta

				if ( isset( $all_form_values['wpte_checkout_paymnet_method'] ) && 'direct_bank_transfer' === $all_form_values['wpte_checkout_paymnet_method'] ) {

					update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_gateway', __( 'Direct Bank Transfer', 'wp-travel-engine' ) );

					update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_status', 'voucher-waiting' );

				}

		

				if ( isset( $all_form_values['wpte_checkout_paymnet_method'] ) && 'check_payments' === $all_form_values['wpte_checkout_paymnet_method'] ) {

					update_post_meta( $post_id, 'wp_travel_engine_booking_payment_gateway', __( 'Check Payment', 'wp-travel-engine' ) );

					update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_status', 'check-waiting' );

				}

				update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_method', $all_form_values['wpte_checkout_paymnet_method'] );



				$remove_keys = array(

					'wp_travel_engine_booking_setting',

					'action',

					'_wp_http_referer',

					'wpte_checkout_paymnet_method',

					'wp_travel_engine_nw_bkg_submit',

					'wp_travel_engine_new_booking_process_nonce',

					'wp_travel_engine_payment_mode',

				);

				foreach ( $remove_keys as $key ) {

					// Unset metas.

					if ( isset( $all_form_values[ $key ] ) ) {

						unset( $all_form_values[ $key ] );

					}

				}



				// Set additional fields.

				$additional_fields = $all_form_values;



				update_post_meta( $booking_id, 'wp_travel_engine_booking_status', 'booked' );



				if ( ! empty( $additional_fields ) ) {

					// Update booking with additional fields.

					$order_metas['additional_fields'] = $additional_fields;

				}



				// Update the post meta data.

				update_post_meta( $booking_id, 'wp_travel_engine_booking_setting', $order_metas );



				$order_confirmation = new Wp_Travel_Engine_Order_Confirmation();



				$order_confirmation->insert_customer( $order_metas );



				if ( false === $updated ) {

					_e( 'There was an error on update.', 'wp-travel-engine' );

				}



				if ( wp_travel_engine_use_old_booking_process() ) {

					$email_class      = 'Wp_Travel_Engine_Mail_Template';

					$wte_email_object = apply_filters( 'mail_template_class', $email_class );

					$wte_email_object = new $wte_email_object();

					$wte_email_object->mail_editor( $order_metas, $booking_id );

				} else {

					// Mail class.

					require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-emails.php';

					if ($status_pagamento == 1) {  

					}else{

						$wte_mailer = new WP_Travel_Engine_Emails();

						$wte_mailer->send_booking_emails( $order_metas, $booking_id );

					}

				}



				/**

				 * Hook to handle payment process

				 *

				 * @since 2.2.8

				 */

				do_action( 'wp_travel_engine_after_booking_process_completed', $booking_id );

				do_action( 'wp_travel_engine_booking_completed_with_post_parameter', $_POST );

			endif;



		endforeach;


		if ($status_pagamento == 1) {  

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.traveltec.com.br/serv/pagamento/checkout",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => $dados_checkout_pag_seguro,
			  CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache",
			    "content-type: application/json",
			    "email: raabe@montenegroev.com.br",
			    "postman-token: 5e509227-c93d-a7ca-2c81-fe4c06a04525",
			    "token: 28F7F80C3C9848E1AA4C88BDD5DE57B2",
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
				$code = $dados['message'];
			}

			$wte_confirm = 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code='.$code; 
			header("Location: ".$wte_confirm);

		}else{

			$wte_confirm = wp_travel_engine_get_booking_confirm_url();

			$wte_confirm = add_query_arg( 'booking_id', $booking_id, $wte_confirm );



			// Redirect to the traveller's information page.

			wp_safe_redirect( $wte_confirm );
		}



		exit;



	}



}

