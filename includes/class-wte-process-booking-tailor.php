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

class WTE_Process_Booking_Tailor {


	public function __construct() {

        

        $this->process_booking_tailor();

    

    }


	/**

	 * Handle the booking process after the booking request form is submitted from checkout.

	 *

	 * @return void

	 */

	public function process_booking_tailor() {

		$token_cliente = $_POST['token_cliente'];
		$email_cliente = $_POST['email_cliente'];
		$nome_cliente = $_POST['nome_cliente'];
		$trip_id = $_POST['trip_id'];  
		

		$post = get_post( $trip_id );

	}

} 

new WTE_Process_Booking_Tailor();

?>