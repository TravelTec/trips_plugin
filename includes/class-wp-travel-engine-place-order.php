<?php
/**
 * Place order form.
 *
 * Responsible for creating shortcodes for place order form and mainatain it.
 *
 * @package    Wp_Travel_Engine
 * @subpackage Wp_Travel_Engine/includes
 * @author
 */
class Wp_Travel_Engine_Place_Order
{

	/**
	* Initialize the place order form shortcode.
	* @since 1.0.0
	*/
	function init()
	{
		add_shortcode( 'WP_TRAVEL_ENGINE_PLACE_ORDER', array( $this, 'wp_travel_engine_place_order_shortcodes_callback' ) );
		add_action( 'init', array( $this, 'place_order_form_validate') );
	}

	/**
	* Place order form shortcode callback function.
	* @since 1.0
	*/
	function wp_travel_engine_place_order_shortcodes_callback()
	{
		global $post;

		global $wte_cart;

		if ( is_admin() )
			return;

		ob_start();

		// Check if login is required for checkout.
		$settings = wp_travel_engine_get_settings();

        $require_login_to_checkout = isset( $settings['enable_checkout_customer_registration'] ) ? $settings['enable_checkout_customer_registration'] : 'no';

        if ( 'yes' === $require_login_to_checkout && ! is_user_logged_in() ) {
            return wte_get_template( 'account/form-login.php' );
		}

		if ( defined( 'WTE_USE_OLD_BOOKING_PROCESS' ) && WTE_USE_OLD_BOOKING_PROCESS ) :
			wte_get_template('order-form-template.php');
		else :
			if ( ! empty( $wte_cart->getItems() ) && is_array( $wte_cart->getItems() ) ) {
				wte_get_template('template-checkout-new.php');
			} else {
				return __('Desculpe, talvez você não selecionou o número de passageiros para essa viagem. Por favor, selecione e confirme sua reserva. Agradecemos desde já.','wp-travel-engine');
			}

		endif;

		$data = ob_get_clean();

		return $data;
	}

	/**
	* Place order form validation function.
	* @since 1.0.0
	*/
	function place_order_form_validate()
	{

		//
	}
}
