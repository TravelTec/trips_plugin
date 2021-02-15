<?php
	$wrapper_classes = apply_filters( 'wpte_bf_outer_wrapper_classes', '' );
		global $post;
		$wp_travel_engine_setting = get_post_meta( $post->ID,'wp_travel_engine_setting',true ); 

		$contador = (count($wp_travel_engine_setting['multiple_pricing'])-1);

		
		for ($i=0; $i < count($wp_travel_engine_setting['multiple_pricing']); $i++) { 
			$valores[] = array('adulto' => (empty($wp_travel_engine_setting['multiple_pricing'][$i]['adult']['sale_price']) ? $wp_travel_engine_setting['multiple_pricing'][$i]['adult']['price'] : $wp_travel_engine_setting['multiple_pricing'][$i]['adult']['sale_price']), 'crianca' => (empty($wp_travel_engine_setting['multiple_pricing'][$i]['child']['sale_price']) ? $wp_travel_engine_setting['multiple_pricing'][$i]['child']['price'] : $wp_travel_engine_setting['multiple_pricing'][$i]['child']['sale_price']), 'bebe' => (empty($wp_travel_engine_setting['multiple_pricing'][$i]['infant']['sale_price']) ? $wp_travel_engine_setting['multiple_pricing'][$i]['infant']['price'] : $wp_travel_engine_setting['multiple_pricing'][$i]['infant']['sale_price']), 'grupo' => (empty($wp_travel_engine_setting['multiple_pricing'][$i]['group']['sale_price']) ? $wp_travel_engine_setting['multiple_pricing'][$i]['group']['price'] : $wp_travel_engine_setting['multiple_pricing'][$i]['group']['sale_price']), 'inicio' => implode("-", array_reverse(explode("/", $wp_travel_engine_setting['multiple_pricing'][$i]['adult']['inicio']))), 'termino' => implode("-", array_reverse(explode("/", $wp_travel_engine_setting['multiple_pricing'][$i]['adult']['termino']))));
		}
		usort($valores, function($d1, $d2){
		    $t1 = strtotime($d1['inicio']);
		    $t2 = strtotime($d2['inicio']);
		    if ($t1 === $t2) return 0;
		    return ($t1 < $t2) ? -1 : 1;
		});  

		for ($i=0; $i < count($valores); $i++) {   

			$inicio = explode("/", implode("/", array_reverse(explode("-", $valores[$i]['inicio']))));
			$termino = explode("/", implode("/", array_reverse(explode("-", $valores[$i]['termino']))));

			$mes_inicio = ($inicio[1] == '01' ? 0 : preg_replace("@0+@","",($inicio[1]-1)));
			$mes_final = ($termino[1] == '01' ? 0 : preg_replace("@0+@","",($termino[1]-1)));

			$dia_inicio = ($inicio[0] < '10' ? preg_replace("@0+@","",($inicio[0])) : $inicio[0]);
			$dia_final = ($termino[0] < '10' ? preg_replace("@0+@","",($termino[0])) : $termino[0]);


			$datas .= '{ "start": "'.implode("/", array_reverse(explode("-", $valores[$i]['inicio']))).'", "end": "'.implode("/", array_reverse(explode("-", $valores[$i]['termino']))).'", "adulto": "'.$valores[$i]['adulto'].'", "crianca": "'.$valores[$i]['crianca'].'", "bebe": "'.$valores[$i]['bebe'].'", "grupo": "'.$valores[$i]['grupo'].'" }'.($i == $contador ? '' : ','); 
			//$datas[] = array('start' => 'new Date('.$inicio[2].', '.$mes_inicio.', '.$dia_inicio.')', 'end' => 'new Date('.$termino[2].', '.$mes_final.', '.$dia_final.')');

		}
		//$valores_datas = str_replace("\"", "'", json_encode($datas)); 
		$valores_datas = '['.$datas.']';
?>
<div class="wpte-bf-outer <?php echo esc_attr( $wrapper_classes ); ?>">
	<input type="hidden" id="datas" value='<?=$valores_datas?>' name=""> 
	<!-- Prices List -->
	<?php do_action( 'wte_before_price_info' ); ?>
	<div class="wpte-bf-price-wrap">
		<?php do_action( 'wte_before_price_info_title' ); ?>
		<div class="wpte-bf-ptitle"><?php _e( 'Valores', 'wp-travel-engine' ); ?></div>
		<?php do_action( 'wte_after_price_info_title' ); ?>

		<div class="wpte-bf-price" id="price_per_person">
		<?php if ( $is_sale_price_enabled ) : ?>
			<del>
				<?php echo $wp_travel_engine_setting['multiple_pricing'][0]['adult']['currency_code'].' '.$regular_price; ?>
			</del>
		<?php endif; ?>
			<ins>
				<?php echo $wp_travel_engine_setting['multiple_pricing'][0]['adult']['currency_code'].' '.$price; ?></b>
			</ins>
			<?php 
				$per_person_txt_out = 'per-person' === $price_per_text ? __( 'Por pessoa', 'wp-travel-engine' ) : __( 'Por grupo', 'wp-travel-engine' );
			?>
			<span class="wpte-bf-pqty"><?php echo apply_filters( 'wte_default_traveller_unit', $per_person_txt_out ); ?></span>
		</div> 
	</div>
	<?php do_action( 'wte_after_price_info' ); ?>
	<?php session_start(); ?>
	<?php $_SESSION['currency_code'] = $wp_travel_engine_setting['multiple_pricing'][0]['adult']['currency_code']; ?>
	<!-- ./ Prices List -->
 	<input type="hidden" id="currency_code" value="<?=$wp_travel_engine_setting['multiple_pricing'][0]['adult']['currency_code'];?>" name="">
	<!-- Booking Form -->
	<?php do_action( 'wte_before_tip_booking_form' ); ?>
	<form id="wpte-booking-form" method="POST" class="price-holder" autocomplete="off" action="<?php echo esc_url( get_permalink( $wte_placeholder ) ); ?>">
		<?php wp_nonce_field( 'wp_travel_engine_booking_nonce', 'nonce' ); ?>

		<!-- Booking steps -->
		<div class="wpte-bf-booking-steps">
			<div class="wpte-bf-step-wrap">
			<?php
			$first_el = 0;
			foreach ( $booking_steps as $index => $booking_step ) : 
				?>
				<button data-step-name="wpte-bf-step-<?php echo esc_attr( $index ); ?>" class="wpte-bf-step<?php echo esc_attr( 0 === $first_el ? ' active' : '' ); ?>">
					<?php 
						if (esc_html( $booking_step ) == 'Select a Date') {
							$booking_step = 'Selecione a data';
						}
						if (esc_html( $booking_step ) == 'Travellers') {
							$booking_step = 'Passageiros';
						}
						echo esc_html( $booking_step ); 
					?>
				</button>
				<span class="wpte-bf-step-arrow">
					<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M24.707 38.101L4.908 57.899c-4.686 4.686-4.686 12.284 0 16.971L185.607 256 4.908 437.13c-4.686 4.686-4.686 12.284 0 16.971L24.707 473.9c4.686 4.686 12.284 4.686 16.971 0l209.414-209.414c4.686-4.686 4.686-12.284 0-16.971L41.678 38.101c-4.687-4.687-12.285-4.687-16.971 0z"></path></svg>
				</span>
				<?php
				$first_el++;
			endforeach;
			?>
			</div>


			<?php do_action( 'wte_before_booking_steps_content' ); ?>
			<div class="wpte-bf-step-content-wrap">
				<!-- Calender -->
				<div class="wpte-bf-step-content active">
					<div class="wpte-bf-datepicker"></div>
				</div>
				<!-- ./ Calender -->

				<!-- Travellers -->
				<?php
				do_action( 'wte_before_travellers_booking_step' );

					$min_pax = isset( $post_meta['trip_minimum_pax'] ) && ! empty( $post_meta['trip_minimum_pax'] ) ? $post_meta['trip_minimum_pax'] : 1;

					$max_pax = isset( $post_meta['trip_maximum_pax'] ) && ! empty( $post_meta['trip_maximum_pax'] ) ? $post_meta['trip_maximum_pax'] : 99999999999999;
				?>
				<div class="wpte-bf-step-content wpte-bf-content-travellers" data-mintravellers="<?php echo esc_attr( $min_pax ); ?>" data-maxtravellers="<?php echo esc_attr( $max_pax ); ?>">
					<div class="wpte-bf-traveler-block-wrap">
						<div class="wpte-bf-block-title"><?php _e( 'Passageiros', 'wp-travel-engine' ); ?></div>
						<div class="wpte-bf-traveler-member"> 
							<?php do_action( 'wte_bf_travellers_input_fields' ); ?>
						</div>
					</div>
				</div>
				<?php do_action( 'wte_after_travellers_booking_step' ); ?>

				<div class="wte-bf-price-detail" style="display: none">
					<div class="wpte-bf-total-price">
						<span class="wpte-bf-total-txt"><?php _e( 'Total', 'wp-travel-engine' ); ?> :</span>
						<span class="wpte-bf-currency">
							<?php //echo wp_travel_engine_get_currency_code(); ?> 
							<?php echo $wp_travel_engine_setting['multiple_pricing'][0]['adult']['currency_code']; ?>
						</span>
						<span class="wpte-price">
							<?php echo wp_travel_engine_get_formated_price_separator( $price ); ?>
						</span>
					</div> 
					<div class="wpte-bf-btn-wrap">
						<input type="button" name=""
							value="<?php _e( 'Continuar', 'wp-travel-engine' ); ?>" class="wpte-bf-btn" />
					</div>
				</div>
			</div>
		</div>
		<?php
			$global_settings   = wp_travel_engine_get_settings();
			$hide_enquiry_form = isset( $global_settings['enquiry'] ) && $global_settings['enquiry'] != '' ? true : false;

		if ( ! $hide_enquiry_form ) :
			?>
				<div class="wpte-bf-help-block">
				<?php esc_html_e( 'Precisa de ajuda com a sua reserva?', 'wp-travel-engine' ); ?>
					<a href="#wte_enquiry_contact_form" id="wte-send-enquiry-message">
					<?php esc_html_e( 'Envie uma mensagem', 'wp-travel-engine' ); ?>
					</a>
				</div>
			<?php
			endif;
		?>
		<!-- ./ Travellers -->
		<?php do_action( 'wte_after_booking_steps_content' ); ?>

	</form>
	<?php do_action( 'wte_after_tip_booking_form' ); ?>
	<!-- ./ Booking Form -->
</div>
<?php
