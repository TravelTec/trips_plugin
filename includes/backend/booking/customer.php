<?php
global $post;
$wp_travel_engine_postmeta_settings = get_post_meta( $post->ID, 'wp_travel_engine_booking_setting', true );
?>
<div class="customer-info-meta">
	<div class="customer-gravatar">
	<?php echo get_avatar( esc_attr( $wp_travel_engine_postmeta_settings['place_order']['booking']['email'] ), 100 ); ?>
	</div>
	<div class="wpte-block">
			<div class="wpte-block-content">
				<ul class="wpte-list">
					<li>
						<b><?php _e( 'ID', 'wp-travel-engine' ); ?></b>
						<span>
							<?php echo esc_attr( $post->ID ); ?>
						</span>
					</li>
					<li>
						<b><?php _e( 'Nome', 'wp-travel-engine' ); ?></b>
						<span>
							<?php echo isset($wp_travel_engine_postmeta_settings['place_order']['booking']['fname']) ? esc_attr($wp_travel_engine_postmeta_settings['place_order']['booking']['fname']):'';?>
						</span>
					</li>
					<li>
						<b><?php _e( 'Sobrenome', 'wp-travel-engine' ); ?></b>
						<span>
							<?php echo isset($wp_travel_engine_postmeta_settings['place_order']['booking']['lname']) ? esc_attr($wp_travel_engine_postmeta_settings['place_order']['booking']['lname']):'';?>
						</span>
					</li>
					<li>
						<b><?php _e( 'Email', 'wp-travel-engine' ); ?></b>
						<span>
							<?php echo isset($wp_travel_engine_postmeta_settings['place_order']['booking']['email']) ? esc_attr($wp_travel_engine_postmeta_settings['place_order']['booking']['email']):'';?>
						</span>
					</li>
					<li>
						<b><?php _e( 'Endereço', 'wp-travel-engine' ); ?></b>
						<span>
							<?php echo isset($wp_travel_engine_postmeta_settings['place_order']['booking']['address']) ? esc_attr($wp_travel_engine_postmeta_settings['place_order']['booking']['address']):'';?>
						</span>
					</li>
					<li>
						<b><?php _e( 'Cidade', 'wp-travel-engine' ); ?></b>
						<span>
							<?php echo isset($wp_travel_engine_postmeta_settings['place_order']['booking']['city']) ? esc_attr($wp_travel_engine_postmeta_settings['place_order']['booking']['city']):'';?>
						</span>
					</li>
					<li>
						<b><?php _e( 'Estado', 'wp-travel-engine' ); ?></b>
						<span>
							<?php echo isset($wp_travel_engine_postmeta_settings['place_order']['booking']['country']) ? esc_attr($wp_travel_engine_postmeta_settings['place_order']['booking']['country']):'';?>
						</span>
					</li>
					<li>
						<b><?php _e( 'CEP', 'wp-travel-engine' ); ?></b>
						<span>
							<?php echo isset($wp_travel_engine_postmeta_settings['place_order']['booking']['postcode']) ? esc_attr($wp_travel_engine_postmeta_settings['place_order']['booking']['postcode']):'';?>
						</span>
					</li>
				</ul>
			</div>
		</div>
</div>