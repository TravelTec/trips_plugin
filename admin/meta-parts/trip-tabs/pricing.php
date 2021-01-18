<?php
/**
 * Admin pricing Tab content - Trip Meta
 * 
 * @package Wp_Travel_Engine/Admin/Meta_parts
 */
global $post;
// Get post ID.
if ( ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
    $post_id  = $_POST['post_id'];
    $next_tab = $_POST['next_tab']; 
} else {
    $post_id = $post->ID;
}
/**
 * Pricing Options Settings.
 */
$wte_trip_settings = get_post_meta( $post_id, 'wp_travel_engine_setting', true );  
// default options.
$default_pricing_options = 
    array( 
        'adult'  => 'Adulto', 
        'child'  => __( 'Criança', 'wp-travel-engine' ), 
        'infant' => __( 'Bebê', 'wp-travel-engine' ),
        'group'  => __( 'Grupo', 'wp-travel-engine' ),
    ) ;
    $saved_pricing_options = $default_pricing_options;
    ?>
    <div class="wpte-multi-pricing-wrap">
        <?php
        // Pricing Loop Start.
        foreach( $saved_pricing_options as $option => $label ) :

            $trip_prev_price = '';
            $trip_sale_price = '';
            $trip_sale_enable = false;
            $bind = '';
            $bind_sale = '';

            if ( 'adult' === $option && ! isset( $wte_trip_settings['multiple_pricing'] ) ) :

                $trip_prev_price = isset( $wte_trip_settings['trip_prev_price'] ) && ! empty( $wte_trip_settings['trip_prev_price'] ) ? $wte_trip_settings['trip_prev_price'] : '';
                $trip_sale_enable = isset( $wte_trip_settings['sale'] ) && '1' === $wte_trip_settings['sale'] ? true : false;
                $trip_sale_price = isset( $wte_trip_settings['trip_price'] ) && ! empty( $wte_trip_settings['trip_price'] ) ? $wte_trip_settings['trip_price'] : '';

            endif;

            if ( 'child' === $option && ! isset( $wte_trip_settings['multiple_pricing'] ) ) :

                $trip_prev_price = apply_filters( 'wte_apply_group_discount_default', $trip_prev_price );

            endif;

            if ($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['label'] == 'Adult') {
                $label_campo = 'Adulto';
            }else if ($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['label'] == 'Child') {
                $label_campo = 'Criança';
            }else if ($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['label'] == 'Infant') {
                $label_campo = 'Bebê';
            }else if ($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['label'] == 'Group') {
                $label_campo = 'Grupo';
            }

            $pricing_option_label = isset( $label_campo ) ?  $label_campo : ucfirst( $option );

            // $price =  $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price'];

            $pricing_option_type = isset( $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price_type'] ) ? $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price_type'] : 'per-person';

            $pricing_option_price = isset( $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price'] ) ? $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price'] : $trip_prev_price;

            $pricing_option_sale_price = isset( $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['sale_price'] ) ?  $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['sale_price'] : $trip_sale_price;

            $enable_sale_option = isset( $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['enable_sale'] ) && '1' === $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['enable_sale'] ? true : $trip_sale_enable;

            $sale_display = $enable_sale_option ? true : false;

            if ( 'adult' === $option ) :
                $bind = 'bind="wpte-trip-mn-price"';
                $bind_sale = 'bindSale="wpte-trip-mn-sale-price"';
                ?>
                    <input id="wpte-trip-default-pper" type="hidden" name="wp_travel_engine_setting[trip_price_per]" value="<?php echo esc_attr( $pricing_option_type ); ?>">
                    <input <?php echo esc_attr( $bind ); ?> type="hidden" name="wp_travel_engine_setting[trip_prev_price]" value="<?php echo esc_attr( $pricing_option_price ); ?>">
                    <input <?php echo esc_attr( $bind_sale ); ?> type="hidden" name="wp_travel_engine_setting[trip_price]" value="<?php echo esc_attr( $pricing_option_sale_price ); ?>">
                    <input 
                        type  = "checkbox"
                        style = "display:none"
                        id    = "wpte-trip-enb-sale-price"
                        name  = "wp_travel_engine_setting[sale]"
                        value = "1"
                        <?php checked( $enable_sale_option, true ); ?>
                    />
                <?php
            endif;
        ?>
                <div class="wpte-field wpte-multi-fields">
                    <div class="wpte-field wpte-floated">
                        <label for="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][label]" class="wpte-field-label">
                            <?php		
                                $mp_label = ucfirst( $option );
                                if ($mp_label == 'Adult') {
                                    $mp_label = 'Adulto';
                                }else if ($mp_label == 'Child') {
                                    $mp_label = 'Criança';
                                }else if ($mp_label == 'Infant') {
                                    $mp_label = 'Bebê';
                                }else if ($mp_label == 'Group') {
                                    $mp_label = 'Grupo';
                                }
                                echo esc_html( sprintf( __( 'Campo %1$s', 'wp-travel-engine' ), $mp_label ) );
                            ?>
                        </label>
                        <div class="wpte-floated">
                            <input required type="text" name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][label]" id="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][label]" 
                        value="<?php echo esc_attr( $pricing_option_label ); ?>"
                        placeholder="<?php _e( 'Nome da opção', 'wp-travel-engine' ); ?>" />
                        </div>
                        <span class="wpte-tooltip"><?php echo esc_html( sprintf( __( 'O nome do campo para %1$s. Esse valor será exibido na seleção do passageiro no formulário de reserva.', 'wp-travel-engine' ), $mp_label ) ); ?></span>
                    </div>
                    <div class="wpte-field wpte-number wpte-floated">
                        <label class="wpte-field-label">
                            <?php		
                                $mp_label = ucfirst( $option );
                                if ($mp_label == 'Adult') {
                                    $mp_label = 'Adulto';
                                }else if ($mp_label == 'Child') {
                                    $mp_label = 'Criança';
                                }else if ($mp_label == 'Infant') {
                                    $mp_label = 'Bebê';
                                }else if ($mp_label == 'Group') {
                                    $mp_label = 'Grupo';
                                }
                                echo esc_html( sprintf( __( '%1$s valor', 'wp-travel-engine' ), $mp_label ) );
                            ?>
                        </label>
                        <div class="wpte-floated">
                            <input type="text" class="price_trip" name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][price]" 
                                id="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][price]" 
                                <?php echo esc_attr( $bind ); ?>
                                value="<?php echo esc_attr( $pricing_option_price ); ?>"
                                placeholder="<?php _e( 'Valor regular', 'wp-travel-engine' ); ?>" style="width: 40%;margin-right: 13px"/>
                            <span> 
                                <select class="wpte-sublabel" name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][currency_code]" 
                                id="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][currency_code]" >
                                    <option value="0" <?=(empty($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code']) ? 'selected' : '')?>>Moeda</option>
                                    <option value="R$" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code'] == 'R$' ? 'selected' : '')?>>R$</option>
                                    <option value="USD" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code'] == 'USD' ? 'selected' : '')?>>USD</option>
                                    <option value="U$" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code'] == 'U$' ? 'selected' : '')?>>U$</option>
                                    <option value="EUR" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code'] == 'EUR' ? 'selected' : '')?>>EUR</option>
                                    <option value="CAN" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code'] == 'CAN' ? 'selected' : '')?>>CAN</option>
                                    <option value="AUS" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code'] == 'AUS' ? 'selected' : '')?>>AUS</option>
                                    <option value="NZ" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code'] == 'NZ' ? 'selected' : '')?>>NZ</option>
                                    <option value="GBP" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code'] == 'GBP' ? 'selected' : '')?>>GBP</option>
                                </select>
                            </span> 
                        </div>
                        <span class="wpte-tooltip"><?php echo esc_html( sprintf( __( 'Informe o valor para o passageiro do tipo %1$s. O valor informado será aplicado como base tarifária para %2$s.', 'wp-travel-engine' ), $mp_label, $mp_label ) ); ?></span>
                    </div>
                    <div class="wpte-onoff-block wpte-floated">
                        <a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $sale_display ? 'active' : ''; ?>">
                            <label for="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][enable_sale]" class="wpte-field-label"><?php echo esc_html( 'Habilitar preço em oferta' ); ?><span class="wpte-onoff-btn"></span></label>
                        </a>
                        <input 
                            type    = "checkbox"
                            class   = "wp-travel-engine-setting-enable-pricing-sale"
                            id      = "wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][enable_sale]"
                            name    = "wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][enable_sale]"
                            value   = "1"
                            <?php checked( $enable_sale_option, true ); ?>
                        />
                        <div <?php echo $sale_display ? 'style="display:block;"' : ''; ?> class="wpte-onoff-popup">
                            <div class="wpte-field wpte-number">
                                <div class="wpte-floated">
                                    <input class="price_trip" <?php echo esc_attr( $bind_sale ); ?> type="text" name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][sale_price]" 
                                        id="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][sale_price]" 
                                        value="<?php echo esc_attr( $pricing_option_sale_price ); ?>"
                                        placeholder="<?php _e( 'Valor de oferta', 'wp-travel-engine' ); ?>" style="width: 57%;margin-right: 13px"/>
                                    
                            <span> 
                                <select class="wpte-sublabel" name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][currency_code_sale]" 
                                id="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][currency_code_sale]" >
                                    <option value="0" <?=(empty($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code_sale']) ? 'selected' : '')?>>Moeda</option>
                                    <option value="R$" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code_sale'] == 'R$' ? 'selected' : '')?>>R$</option>
                                    <option value="USD" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code_sale'] == 'USD' ? 'selected' : '')?>>USD</option>
                                    <option value="U$" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code_sale'] == 'U$' ? 'selected' : '')?>>U$</option>
                                    <option value="EUR" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code_sale'] == 'EUR' ? 'selected' : '')?>>EUR</option>
                                    <option value="CAN" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code_sale'] == 'CAN' ? 'selected' : '')?>>CAN</option>
                                    <option value="AUS" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code_sale'] == 'AUS' ? 'selected' : '')?>>AUS</option>
                                    <option value="NZ" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code_sale'] == 'NZ' ? 'selected' : '')?>>NZ</option>
                                    <option value="GBP" <?=($wte_trip_settings['multiple_pricing'][esc_attr( $option )]['currency_code_sale'] == 'GBP' ? 'selected' : '')?>>GBP</option>
                                </select>
                            </span> 
                                </div>
                            </div>
                        </div>
                        <span class="wpte-tooltip"><?php //echo esc_html__( 'Enable sale price for this pricing option.', 'wp-travel-engine' ); ?></span>
                    </div>
                    <?php if ( 'group' !== $option ) : ?>
                        <div style="margin-top:20px;" class="wpte-field wpte-floated">
                            <label for="wpte-adult-price-pertype-sel" class="wpte-field-label"><?php echo esc_html__( 'Tipo de precificação', 'wp-travel-engine' ); ?></label>
                            <div class="wpte-floated">
                                <select id="wpte-adult-price-pertype-sel" name="wp_travel_engine_setting[multiple_pricing][<?php echo esc_attr( $option ); ?>][price_type]">
                                    <option <?php selected( $pricing_option_type, 'per-person' ); ?> value="per-person"><?php esc_html_e( 'Por pessoa', 'wp-travel-engine' ); ?></option>
                                    <option <?php selected( $pricing_option_type, 'per-group' ); ?> value="per-group"><?php esc_html_e( 'Por grupo', 'wp-travel-engine' ); ?></option>
                                </select>
                            </div>
                            <span class="wpte-tooltip"><?php echo esc_html__( 'Altere o tipo de preço para esta opção de preço. Selecionar "Por Grupo" tratará o preço no total, independentemente do número de viajantes.', 'wp-travel-engine' ); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php
                        /**
                         * Hook for pax limits and advanced options.
                         */
                        do_action( 'wte_after_pricing_option_setting_fields' );
                    ?>
                </div>
        <?php endforeach; ?>
    </div>
    <?php
        /**
         * Hook for Group Discount, Partial Payment addons Upsell Notes.
         */
        //do_action( 'wte_after_pricing_upsell_notes' );
    ?>

    <?php if ( $next_tab ) : ?>
        <div class="wpte-field wpte-submit">
            <input data-tab="pricing" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte-trip-tab-save-continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php _e( 'Salvar e continuar', 'wp-travel-engine' ); ?>">
        </div>
    <?php endif; ?>

    <script src="https://wp02.montenegroev.com.br/wp-content/plugins/wp-travel-engine/admin/js/jquery.mask.js"></script>
    <script type="text/javascript">
        jQuery('.price_trip').mask('000.000.000.000.000,00', {reverse: true});
    </script>
 
