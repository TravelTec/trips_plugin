<?php
/**
 * Admin general Tab content - Trip Meta
 *
 *  @package Wp_Travel_Engine/Admin/Meta_parts
 */
// Get global post.
global $post;

// Get settings meta.
$wp_travel_engine_setting = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

// Get DB values.
$trip_duration        = isset( $wp_travel_engine_setting['trip_duration'] ) ? $wp_travel_engine_setting['trip_duration'] : false;
$trip_duration_nights = isset( $wp_travel_engine_setting['trip_duration_nights'] ) ? $wp_travel_engine_setting['trip_duration_nights'] : false;
$trip_duration_unit   = $trip_duration ? 'days' : '';

// cut-off
$enable_cut_off    = isset( $wp_travel_engine_setting['trip_cutoff_enable'] ) ? true : false;
$trip_cut_off_time = isset( $wp_travel_engine_setting['trip_cut_off_time'] ) ? $wp_travel_engine_setting['trip_cut_off_time'] : false;
$trip_cut_off_unit = isset( $wp_travel_engine_setting['trip_cut_off_unit'] ) ? $wp_travel_engine_setting['trip_cut_off_unit'] : 'days';

// Min- Max age
$min_max_age_enable = isset( $wp_travel_engine_setting['min_max_age_enable'] ) ? true : false;
$trip_minimum_age   = get_post_meta( $post->ID, 'wp_travel_engine_trip_min_age', true );
$trip_maximum_age   = get_post_meta( $post->ID, 'wp_travel_engine_trip_max_age', true );

// Min-Max Pax
$minmax_pax_enable   = isset( $wp_travel_engine_setting['minmax_pax_enable'] ) ? true : false;
$trip_minimum_pax   = isset( $wp_travel_engine_setting['trip_minimum_pax'] ) ? $wp_travel_engine_setting['trip_minimum_pax'] : '';
$trip_maximum_pax   = isset( $wp_travel_engine_setting['trip_maximum_pax'] ) ? $wp_travel_engine_setting['trip_maximum_pax'] : '';
?>
    <?php
        /**
         * wp_travel_engine_trip_code_display hook
         *
         * @hooked wpte_display_trip_code_section
         */
        str_replace("Trip Code", "replace", do_action( 'wp_travel_engine_trip_code_display' ));
    ?>

    <?php
        $duration_array = apply_filters( 'wp_travel_engine_trip_duration_units', array(
            'days'    => __( 'Dias', 'wp-travel-engine' ),
            // 'hours'   => __( 'Hours', 'wp-travel-engine' ),
            // 'minutes' => __( 'Minutes', 'wp-travel-engine' )
        ) );
    ?>
    <div class="wpte-field wpte-floated">
        <label class="wpte-field-label"><?php _e( 'Dias', 'wp-travel-engine' ); ?></label>
        <div class="wpte-multi-fields wpte-floated">
            <input type="number" min="1" step="1" name="wp_travel_engine_setting[trip_duration]" value="<?php echo $trip_duration ? esc_attr( $trip_duration ) : '' ?>" placeholder="<?php _e( 'Informe a duração', 'wp-travel-engine' ); ?>">
            <!-- <select name="wp_travel_engine_setting[trip_duration_unit]">
                <option><?php //_e( 'Selecione o tipo da duração', 'wp-travel-engine' ) ?></option>
                <?php
                    //$trip_duration_unit = isset( $wp_travel_engine_setting['trip_duration_unit'] ) ? $wp_travel_engine_setting['trip_duration_unit'] : 'Dias';
                    //foreach( $duration_array as $value => $label ) {
                        //echo '<option ' . selected( $trip_duration_unit, $value, false ) . ' value="' . esc_attr( $value ) . '">'. esc_html( $label ) .'</option>';
                    //}
                ?>
            </select> -->
        </div>
        <span class="wpte-tooltip"><?php esc_html_e( ' Informe a duração (número) de dias para o roteiro', 'wp-travel-engine' ) ?></span>
    </div>
    <?php // if ( $trip_duration_nights ) : ?>
        <div class="wpte-field wpte-floated">
            <label class="wpte-field-label"><?php _e( 'Noites', 'wp-travel-engine' ); ?> </label>
            <div class="wpte-multi-fields wpte-floated">
                <input type="number" min="1" step="1" name="wp_travel_engine_setting[trip_duration_nights]" value="<?php echo $trip_duration_nights ? esc_attr( $trip_duration_nights ) : '' ?>" placeholder="<?php _e( 'Informe a duração', 'wp-travel-engine' ); ?>">
                <!-- <select>
                    <option><?php //_e( 'Noite(s)', 'wp-travel-engine' ) ?></option>
                </select> -->
            </div>
            <span class="wpte-tooltip"><?php esc_html_e( 'Informe a duração (número) de noites para o roteiro', 'wp-travel-engine' ) ?></span>
        </div>
    <?php // endif; ?>
    <div class="wpte-field wpte-onoff-block">
        <a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $enable_cut_off ? 'active' : ''; ?>">
            <label for="wpte-enable-cut-off" class="wpte-field-label"><?php _e( 'Habilitar "cut -off"', 'wp-travel-engine' ); ?><span class="wpte-onoff-btn"></span></label>
        </a>
        <input id="wpte-enable-cut-off" type="checkbox" <?php checked( $enable_cut_off, true ); ?> name="wp_travel_engine_setting[trip_cutoff_enable]" value="true">
        <span class="wpte-tooltip"><?php _e( 'Habilitar o tempo de cut-off para o roteiro. Essa opção determina após quantos dias a partir da data atual o roteiro está habilitado para reservas.', 'wp-travel-engine' ) ?></span>
        <div <?php echo $enable_cut_off ? 'style="display:block;"' : ''; ?> class="wpte-onoff-popup">
            <div class="wpte-field wpte-multi-fields wpte-floated">
                <label class="wpte-field-label"><?php _e( 'Cut-off', 'wp-travel-engine' ); ?></label>
                <div class="wpte-floated">
                    <input type="number" step="1" min="1" name="wp_travel_engine_setting[trip_cut_off_time]" value="<?php echo $trip_cut_off_time ? $trip_cut_off_time : ''; ?>" placeholder="<?php _e( 'Informe o número', 'wp-travel-engine' ); ?>">
                    <select name="wp_travel_engine_setting[trip_cut_off_unit]">
                        <option><?php _e( 'Select Duration Type', 'wp-travel-engine' ) ?></option>
                        <?php
                            $cut_off = apply_filters( 'wp_travel_engine_trip_duration_units', array(
                                'days'  => __( 'Days', 'wp-travel-engine' ),
                                // 'hours' => __( 'Hours', 'wp-travel-engine' ),
                            ) );
                            foreach( $cut_off as $value => $label ) {
                                echo '<option ' . selected( $trip_cut_off_unit, $value, false ) . ' value="' . esc_attr( $value ) . '">'. esc_html( $label ) .'</option>';
                            }
                        ?>
                    </select>
                </div>
                <span class="wpte-tooltip"><?php _e( 'Informe o valor em número de dias. Se você informar para 1 dia, o roteiro não poderá ser reservado com a data de início atual. Se você informar para 2 dias, o roteiro não poderá ser reservado com a data atual e mais 1 dia, assim sucessivamente.', 'wp-travel-engine' ) ?></span>
            </div>
        </div>
    </div>

    <div class="wpte-field wpte-onoff-block">
        <a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $min_max_age_enable ? 'active' : ''; ?>">
            <label for="wpte-enable-min-max-age" class="wpte-field-label"><?php _e( 'Idade mínima e máxima', 'wp-travel-engine' ); ?><span class="wpte-onoff-btn"></span></label>
        </a>
        <input id="wpte-enable-min-max-age" type="checkbox" <?php checked( $min_max_age_enable, true ); ?> name="wp_travel_engine_setting[min_max_age_enable]" value="true">
        <span class="wpte-tooltip"><?php _e( 'Habilite idade mínima e máxima para reservar essa viagem.', 'wp-travel-engine' ) ?></span>
        <div <?php echo $min_max_age_enable ? 'style="display:block;"' : ''; ?> class="wpte-onoff-popup">
            <div class="wpte-field wpte-minmax wpte-floated">
                <div class="wpte-min">
                    <label class="wpte-field-label"><?php _e( 'Idade mínima', 'wp-travel-engine' ); ?></label>
                    <input type="number" step="1" min="1" name="wp_travel_engine_trip_min_age" value="<?php echo esc_attr( $trip_minimum_age ); ?>" placeholder="<?php _e( 'Informe o valor', 'wp-travel-engine' ) ?>">
                </div>
                <div class="wpte-max">
                    <label class="wpte-field-label"><?php _e( 'Idade máxima', 'wp-travel-engine' ); ?></label>
                    <input type="number" step="1" min="1" name="wp_travel_engine_trip_max_age" value="<?php echo esc_attr( $trip_maximum_age ); ?>" placeholder="<?php _e( 'Informe o valor', 'wp-travel-engine' ) ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="wpte-field wpte-onoff-block">
        <a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $minmax_pax_enable ? 'active' : ''; ?>">
            <label for="wpte-fsd-enable-min-max" class="wpte-field-label"><?php _e( 'Informe mínimo e máximo participantes ', 'wp-travel-engine' ); ?><span class="wpte-sublabel"><?php _e( '(Optional)', 'wp-travel-engine' ); ?></span><span class="wpte-onoff-btn"></span></label>
        </a>
        <input id="wpte-fsd-enable-min-max" type="checkbox" <?php checked( $minmax_pax_enable, true ); ?> name="wp_travel_engine_setting[minmax_pax_enable]" value="true">
        <span class="wpte-tooltip"><?php _e( 'Habilitar mínimo e máximo participantes para reservar esse roteiro.', 'wp-travel-engine' ); ?></span>
        <div <?php echo $minmax_pax_enable ? 'style="display:block;"' : ''; ?> class="wpte-onoff-popup">
            <div class="wpte-field wpte-minmax wpte-floated">
                <div class="wpte-min">
                    <label class="wpte-field-label"><?php _e( 'Mínimo de participantes', 'wp-travel-engine' ); ?></label>
                    <input type="number" step="1" min="1" name="wp_travel_engine_setting[trip_minimum_pax]" value="<?php echo esc_attr( $trip_minimum_pax ); ?>" placeholder="<?php _e( 'Enter minimum participants', 'wp-travel-engine' ) ?>">
                </div>
                <div class="wpte-max">
                    <label class="wpte-field-label"><?php _e( 'Máximo de participantes', 'wp-travel-engine' ); ?></label>
                    <input type="number" step="1" min="1" name="wp_travel_engine_setting[trip_maximum_pax]" value="<?php echo esc_attr( $trip_maximum_pax ); ?>" placeholder="<?php _e( 'Enter maximum participants', 'wp-travel-engine' ) ?>">
                </div>
            </div>       </div>
    </div>
    <?php if ( $next_tab ) : ?>
        <div class="wpte-field wpte-submit">
            <input data-tab="general" data-post-id="<?php echo esc_attr( $post->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte-trip-tab-save-continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php _e( 'Salvar e continuar', 'wp-travel-engine' ); ?>">
        </div>
    <?php endif;
