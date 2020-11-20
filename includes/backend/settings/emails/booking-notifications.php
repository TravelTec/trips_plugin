<?php
/**
 * Booking Notifications Page
 */
$wp_travel_engine_settings  = get_option( 'wp_travel_engine_settings' );
$subject_book               = __( 'New Booking Order #order_id', 'wp-travel-engine' );
$disable_admin_notification = isset( $wp_travel_engine_settings['email']['disable_notif'] ) ? esc_attr( $wp_travel_engine_settings['email']['disable_notif'] ): '0';
$enable_customer_notification = isset( $wp_travel_engine_settings['email']['cust_notif'] ) ? esc_attr( $wp_travel_engine_settings['email']['cust_notif'] ): '0';

if ( isset( $wp_travel_engine_settings['email']['sale_subject'] ) && $wp_travel_engine_settings['email']['sale_subject']!='' ) {
    $subject_book = esc_attr( $wp_travel_engine_settings['email']['sale_subject'] );
}
?>
<div class="wpte-field wpte-textarea wpte-floated">
    <label class="wpte-field-label" for="wp_travel_engine_settings[email][emails]"><?php esc_html_e( 'E-mail de notificação de venda', 'wp-travel-engine' ); ?></label>
    <textarea class="large-text" cols="50" rows="5" name="wp_travel_engine_settings[email][emails]" id="wp_travel_engine_settings[email][emails]"><?php 
		$admin_email = get_option( 'admin_email' ); 
		if ( isset( $wp_travel_engine_settings['email']['emails'] ) && $wp_travel_engine_settings['email']['emails']!='' ) { 
            echo esc_attr($wp_travel_engine_settings['email']['emails']);
		} 
		else { echo esc_attr( $admin_email ); } ?></textarea>
    <span class="wpte-tooltip"><?php //esc_html_e( 'Enter the email address(es) that should receive a notification anytime a sale is made, separated by comma (,) and no spaces.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-checkbox advance-checkbox">
    <label class="wpte-field-label" for="disable-admin-notification"><?php esc_html_e( 'Desabilitar notificação admin', 'wp-travel-engine' ); ?></label>
    <div class="wpte-checkbox-wrap">
        <input type="checkbox"  name="wp_travel_engine_settings[email][disable_notif]"  value="1" <?php checked( $disable_admin_notification, '1' ); ?> id="disable-admin-notification">
        <label for="disable-admin-notification"></label>
    </div>
    <span class="wpte-tooltip"><?php //esc_html_e( 'Turn this on if you do not want to receive sales notification emails.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-checkbox advance-checkbox">
    <label class="wpte-field-label" for="enable-customer-enquiry-notification"><?php esc_attr_e( 'Habilitar notificação de contato do cliente', 'wp-travel-engine' ); ?></label>
    <div class="wpte-checkbox-wrap">
        <input type="checkbox" value="1" <?php checked( $enable_customer_notification, '1' ); ?> name="wp_travel_engine_settings[email][cust_notif]" id="enable-customer-enquiry-notification">
        <label for="enable-customer-enquiry-notification"></label>
    </div>
    <span class="wpte-tooltip"><?php //esc_html_e( 'Turn this on if you want to send enquiry notification emails to customer as well.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-text wpte-floated">
    <label class="wpte-field-label" for="wp_travel_engine_settings[email][sale_subject]"><?php _e( 'Assunto e-mail da venda','wp-travel-engine' ); ?></label>
    <input type="text" name="wp_travel_engine_settings[email][sale_subject]" id="wp_travel_engine_settings[email][sale_subject]" value="<?php echo esc_attr( $subject_book ); ?>">
    <span class="wpte-tooltip"><?php //esc_html_e( 'Enter the booking subject for the purchase receipt email.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-textarea wpte-floated">
    <label class="wpte-field-label" for="sales_wpeditor"><?php _e( 'Mensagem','wp-travel-engine' ); ?></label>
    <?php
    require_once plugin_dir_path( WP_TRAVEL_ENGINE_FILE_PATH ) . 'includes/class-wp-travel-engine-emails.php';

    $email_class = new WP_Travel_Engine_Emails();
    $value_wysiwyg = $email_class->get_email_template( 'booking', 'admin', true );

    if( isset( $wp_travel_engine_settings['email']['sales_wpeditor'] ) && $wp_travel_engine_settings['email']['sales_wpeditor']!='' )
    {
        $value_wysiwyg = $wp_travel_engine_settings['email']['sales_wpeditor'];
    }
    $editor_id = 'sales_wpeditor';
    $settings = array( 'media_buttons' => true, 'textarea_name' => 'wp_travel_engine_settings[email]['.$editor_id.']' );
    ?>
    <div class="wpte-field wpte-textarea wpte-floated wpte-rich-textarea delay">
        <!-- <div class="wte-editor-notice">
            <?php _e('Click to initialize RichEditor', 'wp-travel-engine'); ?>
        </div> -->
        <textarea 
            placeholder="<?php _e('Email Message', 'wp-travel-engine'); ?>"
            name="wp_travel_engine_settings[email][<?php echo esc_attr( $editor_id ); ?>]"
            class="wte-editor-area wp-editor-area" id="<?php echo esc_attr( $editor_id ); ?>"><?php echo wp_kses_post( $value_wysiwyg ); ?>
        </textarea>
    </div>
</div>

<div class="wpte-field wpte-tags">
    <p><?php //_e( 'Enter the text that is sent as purchase receipt email to users after completion of a successful purchase. HTML is accepted.', 'wp-travel-engine' ); ?></p>
    <p><b><?php _e( 'Tags reservadas', 'wp-travel-engine' ); ?>-</b></p>
    <ul class="wpte-list">
        <li>
            <b>{trip_url}</b>
            <span><?php _e( 'URL da viagem reservada', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{name}</b>
            <span><?php _e( 'Nome do solicitante', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{fullname}</b>
            <span><?php _e( 'Nome completo do solicitante', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{user_email}</b>
            <span><?php _e( 'Endereço do solicitante', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{billing_address}</b>
            <span><?php _e( 'Endereço de cobrança do solicitante', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{city}</b>
            <span><?php _e( 'Cidade do solicitante', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{country}</b>
            <span><?php _e( 'Estado do solicitante', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{tdate}</b>
            <span><?php _e( 'Data da viagem', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{date}</b>
            <span><?php _e( 'Data da reserva', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{traveler}</b>
            <span><?php _e( 'Número de passageiros', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{child-traveler}</b>
            <span><?php _e( 'Número de passageiros crianças', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{tprice}</b>
            <span><?php _e( 'Valor da viagem', 'wp-travel-engine' ); ?></span>
        </li><!-- 
        <li>
            <b>{price}</b>
            <span><?php //_e( 'The total payment made of the booking', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{total_cost}</b>
            <span><?php //_e( 'The total price of the booking', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{due}</b>
            <span><?php //_e( 'The due balance', 'wp-travel-engine' ); ?></span>
        </li> -->
        <li>
            <b>{sitename}</b>
            <span><?php _e( 'Seu site', 'wp-travel-engine' ); ?></span>
        </li>
        <!-- <li>
            <b>{booking_url}</b>
            <span><?php //_e( 'The trip booking link', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{ip_address}</b>
            <span><?php //_e( 'The buyer\'s IP Address', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{booking_id}</b>
            <span><?php //_e( 'The booking order ID', 'wp-travel-engine' ); ?></span>
        </li>
        <li>
            <b>{bank_details}</b>
            <span><?php //_e( 'Banks Accounts Details. This tag will be replaced with the bank details and sent to the customer receipt email when Bank Transfer method has chosen by the customer.', 'wp-travel-engine' ); ?></span>
        </li> -->
        <li>
            <b>{check_payment_instruction}</b>
            <span><?php _e( 'Instruções para pagamento.', 'wp-travel-engine' ); ?></span>
        </li>
    </ul>
    <?php
        /**
         * Hook to add additional e-mail tags by addons.
         */
        do_action( 'wte_additional_payment_email_tags' );
    ?>
</div>
<?php
