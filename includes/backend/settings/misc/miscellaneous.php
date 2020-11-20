<?php
/**
 * Misc Tab
 */
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings',true );
$feat_img                  = isset( $wp_travel_engine_settings['feat_img'] ) ? esc_attr( $wp_travel_engine_settings['feat_img'] ) : '0';
?>
<div class="wpte-form-block-wrap">
    <div class="wpte-form-block">
        <div class="wpte-form-content">

            <div class="wpte-field wpte-text wpte-floated">
                <label for="wp_travel_engine_settings[book_btn_txt]" class="wpte-field-label"><?php _e('Texto botão de reservas','wp-travel-engine');?></label>
                <input type="text" id="wp_travel_engine_settings[book_btn_txt]" name="wp_travel_engine_settings[book_btn_txt]" value="<?php echo isset($wp_travel_engine_settings['book_btn_txt']) ? esc_attr( $wp_travel_engine_settings['book_btn_txt'] ): 'Book Now';?>" placeholder="<?php _e('Book now button label','wp-travel-engine');?>">
                <span class="wpte-tooltip"> <?php //esc_html_e( 'Book Now button text', 'wp-travel-engine' ) ?></span>
            </div>

            <div class="wpte-field wpte-text wpte-floated">
                <label for="wp_travel_engine_settings[query_subject]" class="wpte-field-label"><?php esc_html_e( 'Assunto do e-mail de contato', 'wp-travel-engine' ); ?></label>
                <input type="text" id="wp_travel_engine_settings[query_subject]" name="wp_travel_engine_settings[query_subject]" value="<?php echo isset($wp_travel_engine_settings['query_subject']) ? esc_attr( $wp_travel_engine_settings['query_subject'] ): __( 'Enquiry received', 'wp-travel-engine' );?>">
                <span class="wpte-tooltip"> <?php //esc_html_e( 'Email subject for admin if a query is received. Supported Email tags - {enquirer_name}, {enquirer_email}', 'wp-travel-engine' ) ?></span>
            </div>

            <div class="wpte-field wpte-text wpte-floated">
                <label for="wp_travel_engine_settings[person_format]" class="wpte-field-label"><?php _e('Formato por pessoa','wp-travel-engine');?></label>
                <input type="text" id="wp_travel_engine_settings[person_format]" name="wp_travel_engine_settings[person_format]" value="<?php echo isset($wp_travel_engine_settings['person_format']) ? esc_attr( $wp_travel_engine_settings['person_format'] ): __( '/person', 'wp-travel-engine' );?>">
                <span class="wpte-tooltip"><?php //esc_html_e( "Per Person format in the trip booking form. Default is '/person'", 'wp-travel-engine' ); ?></span>
            </div>

            <div class="wpte-field wpte-textarea wpte-floated">
                <label for="wp_travel_engine_settings[confirmation_msg]" class="wpte-field-label"><?php _e('Mensagem de confirmação de reserva','wp-travel-engine');?></label>
                <textarea rows="4" cols="30" id="wp_travel_engine_settings[confirmation_msg]" name="wp_travel_engine_settings[confirmation_msg]"><?php echo isset($wp_travel_engine_settings['confirmation_msg']) ? esc_attr( $wp_travel_engine_settings['confirmation_msg'] ): __( 'Agradecemos por reservar essa viagem. Por favor, cheque o seu e-mail para confirmação. Abaixo os detalhes da sua reserva:', 'wp-travel-engine' );?></textarea>
            </div>

            <div class="wpte-field wpte-textarea wpte-floated">
                <label for="wp_travel_engine_settings[gdpr_msg]" class="wpte-field-label"><?php _e('Mensagem GDPR','wp-travel-engine');?></label>
                <textarea rows="4" cols="30" id="wp_travel_engine_settings[gdpr_msg]" name="wp_travel_engine_settings[gdpr_msg]"><?php echo isset($wp_travel_engine_settings['gdpr_msg']) ? esc_attr( $wp_travel_engine_settings['gdpr_msg'] ): 'Entrando em contato conosco, você aceita os nossos Termos de Serviço';?></textarea>
            </div>

            <div class="wpte-field wpte-text wpte-floated">
                <label class="wpte-field-label" for="wp_travel_engine_settings[feat_trip_num]"><?php _e('Número de viagens relacionadas','wp-travel-engine');?></label>
                <input type="number" id="wp_travel_engine_settings[feat_trip_num]" name="wp_travel_engine_settings[feat_trip_num]" min= "0" value="<?php echo isset($wp_travel_engine_settings['feat_trip_num']) ? $wp_travel_engine_settings['feat_trip_num'] : 2; ?>">
                <span class="wpte-tooltip"><?php //esc_html_e( 'Set the number of featured trips to show in the archive pages.', 'wp-travel-engine' ); ?></span>
            </div>

        </div>
    </div>
</div>
<?php
