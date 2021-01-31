<?php

/**

 * Thank you page template.

 */

global $wte_cart;

	$validated_request = true;



	$wte_options               = get_option( 'wp_travel_engine_settings', true );

	$wp_travel_engine_thankyou = isset( $wte_options['pages']['wp_travel_engine_thank_you'] ) ? esc_attr( $wte_options['pages']['wp_travel_engine_thank_you'] ) : '';



if ( ! empty( $wp_travel_engine_thankyou ) && isset( $wte_options['travelers_information'] ) && 'no' === $wte_options['travelers_information'] ) :



	if ( ! isset( $_POST['wp-travel-engine-confirmation-submit'] ) ) {

		$validated_request = false;

	}



	if ( ! isset( $_POST['wp_travel_engine_placeorder_setting'] ) || empty( $_POST['wp_travel_engine_placeorder_setting'] ) ) {

		$validated_request = false;

	}



	if ( ! isset( $_POST['nonce'] ) || empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wp_travel_engine_final_confirmation_nonce' ) ) {

		$validated_request = false;

	}

endif;



if ( empty( $wte_cart->getItems() ) ) {

	$validated_request = false;

}

 

		$booking_id = $wte_cart->get_attribute( 'booking_id' );

		if ( ! empty( $booking_id ) ) {

			$_GET['booking_id'] = $booking_id;

		}

		$booking_id        = $_GET['booking_id'];

		$booking_post_type = get_post_type( $_GET['booking_id'] );



		if ( 'booking' === $booking_post_type ) :

			if ( isset( $_POST['wp_travel_engine_placeorder_setting'] ) ) :

				// Update hook for addons

				do_action( 'wp_travel_engine_before_traveller_information_save' );



					// Update travellers information to booking id.

					update_post_meta( $booking_id, 'wp_travel_engine_placeorder_setting', stripslashes_deep( $_POST['wp_travel_engine_placeorder_setting'] ) );



				// Update hook for addons

				do_action( 'wp_travel_engine_after_traveller_information_save' );

			endif;



			// Show thank you HTML.

			wte_get_template( 'template-booking-thank-you.php' );



		else :



			$thank_page_msg = __( 'Invalid booking id supplied.', 'wp-travel-engine' );



			echo 'Inválido id de reserva.';



		endif; 

