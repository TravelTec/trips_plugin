<?php

/**

 * Trip Info

 */

$trip_type = (isset($config["trip_type"]) ? $config["trip_type"] : '');
$nome_cliente = (isset($config["nome_cliente"]) ? $config["nome_cliente"] : '');
$token_cliente = (isset($config["token_cliente"]) ? $config["token_cliente"] : '');
$e_mail_cliente = (isset($config["e_mail_cliente"]) ? $config["e_mail_cliente"] : '');

?>

<div class="wpte-block wpte-col3">

    <div class="wpte-title-wrap">

        <h4 class="wpte-title"><?php _e( 'Viagem', 'wp-travel-engine' ); ?></h4>

        <div class="wpte-button-wrap wpte-edit-bkng">

            <a href="#" class="wpte-btn-transparent wpte-btn-sm">

                <i class="fas fa-pencil-alt"></i>

                <?php _e( 'Editar', 'wp-travel-engine' ); ?>

            </a>

        </div>

    </div>

    <div class="wpte-block-content">

        <ul class="wpte-list">

            <li>

                <b><?php _e( 'Roteiro', 'wp-travel-engine' ); ?></b>

                <span><a target="_blank" href="<?php echo esc_url( get_the_permalink( $trip_id ) ); ?>&ac=<?=$token_cliente?>" ><?php echo esc_html( $trip_name ); ?></a></span>

            </li>

            <?php

                /**

                 * wte_booking_after_trip_name hook

                 * 

                 * @hooked wte_display_trip_code_booking - Trip Code Addon

                 */

                do_action( 'wte_booking_after_trip_name', $trip_id ); 

            ?>

            <li>

                <b><?php _e( 'Data da viagem', 'wp-travel-engine' ); ?></b>

                <span><?php echo esc_html( wte_get_formated_date( $trip_start_date ) ); ?></span>

            </li>

            <li>

                <b><?php _e( 'Passageiros', 'wp-travel-engine' ); ?></b>

                <span><?php echo esc_html( $booked_travellers ); ?></span>

            </li>

            <li>

                <b><?php _e( 'Total pago', 'wp-travel-engine' ); ?></b>

                <span><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $total_paid ); ?></span>

            </li>

            <li>

                <b><?php _e( 'Valor restante', 'wp-travel-engine' ); ?></b>

                <span><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $remaining_payment ); ?></span>

            </li>

            <li>

                <b><?php _e( 'Total', 'wp-travel-engine' ); ?></b>

                <span><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $total_cost ); ?></span>

            </li>

            <!-- <li>

                <b><?php //_e( 'Trip ID', 'wp-travel-engine' ); ?></b>

                <span><?php //echo esc_html( $trip_id ); ?></span>

            </li> -->

        </ul>

    </div>

    <div style="display:none;" class="wpte-block-content-edit edit-trip-info">

        <ul class="wpte-list">

            <li>

                <b><?php _e( 'Roteiro', 'wp-travel-engine' ); ?></b>

                <span>

                <div class="wpte-field wpte-select">

                    <select class="wpte-enhanced-select" name="wp_travel_engine_booking_setting[place_order][tid]" id="wpte-booking-trip-id">

                        <?php 

                            $trips_options = wp_travel_engine_get_trips_array();

                            foreach( $trips_options as $key => $trip ) {

                                $selected = selected( $trip_id, $key, false );

                                echo "<option " . $selected . " value='{$key}'>{$trip}</option>";

                            }

                        ?>

                    </select>

                </div>

                </span>

            </li>

            <li>

                <b><?php _e( 'Data da viagem', 'wp-travel-engine' ); ?></b>

                <span>

                    <div class="wpte-field wpte-text">

                        <input type="text" class="wp-travel-engine-datetime hasDatepicker" id="wp_travel_engine_booking_setting[place_order][datetime]" name="wp_travel_engine_booking_setting[place_order][datetime]" value="<?php echo esc_attr( $trip_start_date ); ?>">

                    </div>

                </span>

            </li>

            <li>

                <b><?php _e( 'Passageiros', 'wp-travel-engine' ); ?></b>

                <span>

                <div class="wpte-field wpte-number">

                    <input type="number" min="1" step="1" id="wp_travel_engine_booking_setting[place_order][traveler]" name="wp_travel_engine_booking_setting[place_order][traveler]" value="<?php echo esc_attr( $booked_travellers ); ?>" class="">

                </div>

                </span>

            </li>

            <li>

                <b><?php _e( 'Total pago', 'wp-travel-engine' ); ?></b>

                <span>

                <div class="wpte-field wpte-number">

                    <input type="number" min="0" step="0.01" name="wp_travel_engine_booking_setting[place_order][cost]" value="<?php echo esc_attr( $total_paid ); ?>">

                </div>    

                </span>

            </li>

            <li>

                <b><?php _e( 'Valor restante', 'wp-travel-engine' ); ?></b>

                <span>

                    <div class="wpte-field wpte-number">

                        <input type="number" min="0" step="0.01" id="wp_travel_engine_booking_setting[place_order][due]" name="wp_travel_engine_booking_setting[place_order][due]" value="<?php echo esc_attr( $remaining_payment ); ?>" class="">

                    </div>

                </span>

            </li>

            <li>

                <b><?php _e( 'Total', 'wp-travel-engine' ); ?></b>

                <span>

                    <div class="wpte-field wpte-number">

                    <input type="number" min="0" step="0.01" id="wp_travel_engine_booking_setting[place_order][total]" name="wp_travel_engine_booking_setting[place_order][total]" value="<?php echo esc_attr( $total_cost ); ?>" class="">

                    </div>

                </span>

            </li>

        </ul>

    </div>

    <?php 

            if( ! empty( $booking_discounts ) && is_array( $booking_discounts ) ) {

                ?>

                    <div class="wpte-title-wrap">

                        <h4 class="wpte-title"><?php esc_html_e( 'Coupon Discounts', 'wp-travel-engine' ); ?></h4>

                    </div>

                    <div class="wpte-block-content">

                        <ul>

                            <?php

                                foreach( $booking_discounts as $key => $discount ) {

                                    $amount_str = 'percentage' === $discount['type'] ? $discount['value'] . '%' : wp_travel_engine_get_formated_price_with_currency_code_symbol( $discount['value'] );

                                    ?>

                                        <li>

                                            <b><?php _e( 'Valor atual:', 'wp-travel-engine' ); ?></b>

                                            <span>

                                                <?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $discount['actual_total'] ); ?>

                                            </span>

                                        </li>

                                        <li>

                                            <b><?php _e( 'Desconto:', 'wp-travel-engine' ); ?></b>

                                            <span>

                                                <?php echo $discount['name'] . '( ' . $amount_str . ' )' ?>

                                            </span>

                                        </li>

                                        <li>

                                            <b><?php _e( 'Valor do desconto:', 'wp-travel-engine' ); ?></b>

                                            <span>

                                                <?php echo $discount['amount']; ?>

                                            </span>

                                        </li>

                                    <?php

                                }

                            ?>

                        </ul>

                    </div>

                <?php

            }

        ?>

</div> <!-- .wpte-block -->

<?php

