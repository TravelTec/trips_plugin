<style type="text/css">
	.blog-lg-area-left{
		text-align: center !important;
	}
</style>
<?php

/**

 * Thank you page template after booking success.

 *

 * @package WP_Travel_Engine

 */

global $wte_cart;  

$booking_id = (empty($_GET['booking_id']) ? '1565' : $_GET['booking_id']);

$booking_metas               = get_post_meta($booking_id, 'wp_travel_engine_booking_setting', true); 



$cart_items          = $wte_cart->getItems();

$cart_totals         = $wte_cart->get_total();

$date_format         = get_option( 'date_format' );

$wte_settings        = get_option( 'wp_travel_engine_settings' );

$roteiro_setting        = get_post_meta( $booking_metas['place_order']['tid'], 'wp_travel_engine_setting', true );   

$extra_service_title = isset( $wte_settings['extra_service_title'] ) && ! empty( $wte_settings['extra_service_title'] ) ? $wte_settings['extra_service_title'] : __( 'Extra Services:', 'wp-travel-engine' );

$currency_code = $roteiro_setting['multiple_pricing'][0]['adult']['currency_code'];

if ( ! empty( $cart_items ) ) :



		$thankyou  = __( 'Thank you for booking the trip. Please check your email for confirmation.', 'wp-travel-engine' );

		$thankyou .= __( ' Below is your booking detail:', 'wp-travel-engine' );

		$thankyou .= '<br>';



	if ( isset( $wte_settings['confirmation_msg'] ) && $wte_settings['confirmation_msg'] != '' ) {

		$thankyou = $wte_settings['confirmation_msg'];

	}



		// Display thany-you message.

		echo wp_kses_post( $thankyou );

	?>
	<br>


		<div class="thank-you-container" style="background-color: #fff;padding: 30px 24px;width: 600px;margin: 11px auto;text-align: center">

			<h3 class="trip-details"><?php echo esc_html__( 'Resumo da viagem', 'wp-travel-engine' ); ?></h3>

			<div class="detail-container">
				<div class="detail-item">
					<h3 style="margin-bottom: 0;font-size: 22px">Informações da reserva nº <?php echo esc_html( $_GET['booking_id'] ); ?></h3>
				</div> 



				<?php foreach ( $cart_items as $key => $cart_item ) :



					?> 



					<div class="detail-item">

						<strong class="item-label"><?php esc_html_e( 'Roteiro:', 'wp-travel-engine' ); ?></strong>

						<span class="value"><?php echo esc_html( get_the_title( $cart_item['trip_id'] ) ); ?></span>

					</div>



					<?php

						/**

						 * wte_thankyou_after_trip_name hook

						 *

						 * @hooked wte_display_trip_code_thankyou - Trip Code Addon

						 */

						do_action( 'wte_thankyou_after_trip_name', $cart_item['trip_id'] );

					?> 



					<div class="detail-item">

						<strong class="item-label"><?php esc_html_e( 'Período da viagem:', 'wp-travel-engine' ); ?></strong>

						<?php  

							$entrada = date("d/m/Y", strtotime($cart_item['trip_date']));

			                $data_entrada = explode("/", $entrada);
			                for ($i=0; $i < count($roteiro_setting['multiple_pricing']); $i++) { 
			                    $data_saida = explode("/", $roteiro_setting['multiple_pricing'][$i]['adult']['inicio']);

			 
			                    if ($data_entrada[0] >= $data_saida[0] && $data_entrada[1] == $data_saida[1]) {
			                        $saida = $roteiro_setting['multiple_pricing'][$i]['adult']['termino'];
			                    }
			                }

						?>

						<span class="value"><?php echo date("d/m/Y", strtotime($cart_item['trip_date'])); ?> a <?php echo str_replace("-", "/", $saida); ?></span>

					</div>

				<div class="detail-item">
					<h3 style="margin-bottom: 0;font-size: 22px">Passageiros</h3>
				</div>
				<?php for ( $i=1; $i <= $booking_metas['place_order']['traveler']; $i++ ) { ?>

					<ul class="wpte-list" style="margin-bottom: 0;border-bottom: 1px solid #f2f2f2;padding-left: 0">


                                                        <li style="list-style: none"> 
                                                <?php 

                                                $personal_options = $booking_metas['additional_fields']['wp_travel_engine_placeorder_setting']['place_order'];

                                                foreach( $personal_options['travelers'] as $key => $value ) : 

                                                    $ti_key = 'traveller_' . $key;

                                                    

                                                    if ( 'fname' === $key ) {

                                                        $ti_key = 'traveller_first_name';

                                                    } elseif( 'lname' === $key ) {

                                                        $ti_key = 'traveller_last_name';

                                                    } elseif( 'passport' === $key ) {

                                                        $ti_key = 'traveller_passport_number';

                                                    }

                                                    $data_label = wp_travel_engine_get_traveler_info_field_label_by_name( $ti_key );

                                                    $data_value = isset( $value[$i] ) && ! empty( $value[$i] ) ? $value[$i] : false; 

 												   if ( 'fname' === $key ) {
 												   		$nome_passageiro[$i] = "<strong>Nome: </strong> ".esc_html( $data_value ).' ';
 												   } 
 												   if ( 'lname' === $key ) {
 												   		$sobrenome_passageiro[$i] =  esc_html( $data_value ).' ';
 												   }  

 												   if ('dob' === $key) {
 												   		$data_nasc = explode("/", $data_value);

 												   		$ano_nasc = $data_nasc[2];

 												   		if ((date("Y") - $ano_nasc) >= 12) {
 												   			$tipo_pax = 'Adulto';
 												   		}else if ((date("Y") - $ano_nasc) > 2 && (date("Y") - $ano_nasc) < 12) {
 												   			$tipo_pax = 'Criança';
 												   		}else {
 												   			$tipo_pax = 'Bebê';
 												   		}
 												   	}

                                                ?>
                                                            <span style="font-size: 13.7px;position: relative;top: 0px"><?php if ( 'fname' === $key ) { 
                                                            		echo $nome_passageiro[$i];
                                                            	} 
                                                            	if ( 'lname' === $key ) {
                                                            	echo $sobrenome_passageiro[1];  
                                                            } ?></span>
                                                            <?php if ('dob' === $key) { ?>
                                                            	<br><span style="font-size: 13.7px;position: relative;top: -6px"><strong>Tipo: </strong> <?php echo esc_html( $tipo_pax ); ?></span>
                                                            	<br><span style="font-size: 13.7px;position: relative;top: -12px"><strong>Nascimento: </strong> <?php echo esc_html( $data_value ); ?></span>
                                                            <?php } ?>
                                                            <?php if ('title' === $key) { ?>
                                                            	<br><span style="font-size: 13.7px;position: relative;top: -18px"><strong>Gênero: </strong> <?php echo esc_html( $data_value ); ?></span>
                                                            <?php } ?>

                                                <?php   

                                                endforeach; ?>

                                                        </li>

                                            </ul>
                                        <?php } ?>

                                        <div class="detail-item">
					<h3 style="margin-bottom: 0;font-size: 22px">Tarifas</h3>
				</div>

					<?php  

						foreach ( $cart_item['pax'] as $pax_key => $pax ) :

 

							if ($pax_key == 'adult') {
								$label = 'Adulto';
							}else if ($pax_key == 'child') {
								$label = 'Criança';
							}else{
								$label = 'Bebê';
							}




							$pax_label         = wte_get_pricing_label_by_key_invoices( $cart_item['trip_id'], $pax_key, $pax );

							$per_pricing_price = ($pax == 0 ? '0,00' : ( $cart_item['pax_cost'][ $pax_key ] / $pax ));

							?>

								<div class="detail-item">

									<strong class="item-label"><?php echo $label." (".(esc_html( $pax ))."):" ?></strong>

									<span class="value" style="text-align: right;"><?php echo $currency_code. ' '.number_format(floatval($per_pricing_price).'.00', 2, ',', '.'); ?></span>

								</div>



							<?php

					  endforeach; 



						if ( isset( $cart_item['trip_extras'] ) && ! empty( $cart_item['trip_extras'] ) ) :

							?>



						<div class="detail-item">

							<strong class="item-label"><?php echo esc_html( $extra_service_title ); ?></strong>

							<span class="value">

							<?php foreach ( $cart_item['trip_extras'] as $trip_extra ) : ?>

							<div>

								<?php

								$qty           = $trip_extra['qty'];

								$extra_service = $trip_extra['extra_service'];

								$price         = $trip_extra['price'];

								$cost          = $qty * $price;

								if ( 0 === $cost ) {

									continue;

								}

								$formattedCost = wp_travel_engine_get_formated_price_with_currency( $cost, null, true );

								$output        = "{$qty} X {$extra_service} = {$formattedCost}";

								echo esc_html( $output );

								?>

							</div>

						<?php endforeach; ?>

							</span>

						</div>



							<?php



					endif;



						$cart_discounts = $wte_cart->get_discounts();

						if ( ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) :

							$trip_total       = $wte_cart->get_total();

							$code             = isset( $wte_settings['currency_code'] ) ? $wte_settings['currency_code'] : 'USD';

							$calculated_total = isset( $trip_total['cart_total'] ) && ! empty( $trip_total['cart_total'] ) ? intval( $trip_total['cart_total'] ) : 0;

							foreach ( $cart_discounts as $discount_key => $discount_item ) {

								?>

					<div class="detail-item">

						<strong class="item-label">

								<?php esc_html_e( 'Coupon Discount :', 'wp-travel-engine' ); ?>

							<div><?php echo esc_attr( $discount_item['name'] ) . ' - '; ?><?php echo isset( $discount_item ['type'] ) && 'percentage' === $discount_item ['type'] ? '(' . esc_attr( $discount_item ['value'] ) . '%)' : '(' . wp_travel_engine_get_formated_price_with_currency( esc_attr( $discount_item['value'] ) ) . ')'; ?>

							</div>

						</strong>

						<span class="value">

								<?php

								if ( 'fixed' === $discount_item ['type'] ) {

									$new_tcost = $calculated_total - $discount_item ['value'];

									echo wp_travel_engine_get_formated_price_with_currency( $discount_item['value'], null, true );

								} elseif ( 'percentage' === $discount_item ['type'] ) {

									$discount_amount_actual = number_format( ( ( $calculated_total * $discount_item ['value'] ) / 100 ), '2', '.', '' );

									$new_tcost              = $calculated_total - $discount_amount_actual;

									echo wp_travel_engine_get_formated_price_with_currency( $discount_amount_actual, null, true );

								} else {

									$new_tcost = $calculated_total;

								}

							}

							?>

						</span>

					</div>

							<?php

					endif;



						if ( wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ) :



							$booking = get_post_meta( $_GET['booking_id'], 'wp_travel_engine_booking_setting', true );

							$due     = isset( $booking['place_order']['due'] ) ? $booking['place_order']['due'] : 0;

							$paid    = isset( $booking['place_order']['cost'] ) ? $booking['place_order']['cost'] : 0;



							if ( 0 < floatval( $due ) && $paid != floatval( $due + $paid ) ) :



								?>

							<div class="detail-item">

								<strong class="item-label"><?php esc_html_e( 'Total Paid:', 'wp-travel-engine' ); ?></strong>

								<span class="value"><?php echo esc_html( wp_travel_engine_get_formated_price_with_currency( $paid, null, true ) ); ?></span>

							</div>



							<div class="detail-item">

								<strong class="item-label"><?php esc_html_e( 'Due:', 'wp-travel-engine' ); ?></strong>

								<span class="value"><?php echo esc_html( wp_travel_engine_get_formated_price_with_currency( $due, null, true ) ); ?></span>

							</div>



								<?php



							endif;



					endif;



					endforeach;

				?>

				<div class="detail-item">

						<strong class="item-label"><?php esc_html_e( 'Taxas e encargos:', 'wp-travel-engine' ); ?></strong>

						<span class="value" style="text-align: right;">

							<?=$currency_code?> 0,00

						</span>

					</div>

					<div class="detail-item">

						<strong class="item-label"><?php esc_html_e( 'Total:', 'wp-travel-engine' ); ?></strong>

						<span class="value" style="text-align: right;">

							<?php

							if ( ! empty( $cart_discounts ) || sizeof( $cart_discounts ) !== 0 ) {

								echo esc_html( wp_travel_engine_get_formated_price_with_currency( $new_tcost, null, true ) );

							} else {

								echo '<strong>'.$currency_code. ' '.$cart_totals['cart_total'].',00</strong>';
 

							}

							?>

						</span>

					</div>



			</div>

			<?php

			if ( ! empty( $_GET['booking_id'] ) ) :

				$booking_id     = $_GET['booking_id'];

				$payment_method = get_post_meta( $booking_id, 'wp_travel_engine_booking_payment_method', true );



				$payment_method_actions = array(

					'direct_bank_transfer' => function() {

						$settings = get_option( 'wp_travel_engine_settings', array() );

						$instructions = isset( $settings['bank_transfer']['instruction'] ) ? $settings['bank_transfer']['instruction'] : '';

						?>

						<div class="wte-bank-transfer-instructions">

							<?php echo wp_kses_post( $instructions ); ?>

						</div>

						<h3 class="bank-details"><?php echo esc_html__( 'Bank Details:', 'wp-travel-engine' ); ?></h3>

							<?php

							$bank_details = isset( $settings['bank_transfer']['accounts'] ) && is_array( $settings['bank_transfer']['accounts'] ) ? $settings['bank_transfer']['accounts'] : array();

							foreach ( $bank_details as $bank_detail ) :

								$details = array(

									'bank_name'      => array(

										'label' => __( 'Bank:', 'wp-travel-engine' ),

										'value' => $bank_detail['bank_name'],

									),

									'account_name'   => array(

										'label' => __( 'Account Name:', 'wp-travel-engine' ),

										'value' => $bank_detail['account_name'],

									),

									'account_number' => array(

										'label' => __( 'Account Number:', 'wp-travel-engine' ),

										'value' => $bank_detail['account_number'],

									),

									'sort_code'      => array(

										'label' => __( 'Sort Code:', 'wp-travel-engine' ),

										'value' => $bank_detail['sort_code'],

									),

									'iban'           => array(

										'label' => __( 'IBAN:', 'wp-travel-engine' ),

										'value' => $bank_detail['iban'],

									),

									'swift'          => array(

										'label' => __( 'BIC/SWIFT:', 'wp-travel-engine' ),

										'value' => $bank_detail['swift'],

									),



								);



								?>

								<div class="detail-container">

									<?php

									foreach ( $details as $detail ) :

										?>

									<div class="detail-item">

										<strong class="item-label"><?php echo esc_html( $detail['label'] ); ?></strong>

										<span class="value"><?php echo esc_html( $detail['value'] ); ?></span>

									</div>

									<?php endforeach; ?>

								</div>

								<?php

							endforeach;

					},

					'check_payments'       => function() {

						$settings = get_option( 'wp_travel_engine_settings', array() );

						$instructions = isset( $settings['check_payment']['instruction'] ) ? $settings['check_payment']['instruction'] : '';

						?>

						<div class="wte-bank-transfer-instructions">

							<?php echo wp_kses_post( $instructions ); ?>

						</div>

						<?php

					},

				);



				if ( isset( $payment_method_actions[ $payment_method ] ) ) {

					$payment_method_actions[ $payment_method ]();

				}

			endif;

			?>

		</div>

	<?php



	else :



		$thank_page_msg = __( 'Desculpe, você pode não ter confirmado sua reserva. Preencha o formulário e confirme a sua reserva. Obrigado.', 'wp-travel-engine' );



		$thank_page_error = apply_filters( 'wp_travel_engine_thankyou_page_error_msg', $thank_page_msg );



		echo 'Desculpe, você pode não ter confirmado sua reserva. Preencha o formulário e confirme a sua reserva. Obrigado.';



endif;



	// Clear cart data.

	//$wte_cart->clear();

