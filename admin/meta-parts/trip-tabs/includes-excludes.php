<?php
/**
 * Includes / Excludes Tabs content.
 * 
 * @package WP_Travel_Engine/Admin/Meta_Parts
 */
global $post;
// Get post ID.
if ( ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
    $post_id  = $_POST['post_id'];
    $next_tab = $_POST['next_tab']; 
} else {
    $post_id = $post->ID;
}
// Get settings meta.
$wp_travel_engine_setting = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
$cost_tab_sec_title = isset( $wp_travel_engine_setting['cost_tab_sec_title'] ) ? $wp_travel_engine_setting['cost_tab_sec_title'] : '';
$cost_includes_title = isset( $wp_travel_engine_setting['cost']['includes_title'] ) ? $wp_travel_engine_setting['cost']['includes_title'] : '';
$cost_excludes_title = isset( $wp_travel_engine_setting['cost']['excludes_title'] ) ? $wp_travel_engine_setting['cost']['excludes_title'] : '';

$cost_includes_content = isset( $wp_travel_engine_setting['cost']['cost_includes'] ) ? $wp_travel_engine_setting['cost']['cost_includes'] : '';

$cost_excludes_content = isset( $wp_travel_engine_setting['cost']['cost_excludes'] ) ? $wp_travel_engine_setting['cost']['cost_excludes'] : '';
?>
    <div class="wpte-form-block-wrap">
        <div class="wpte-form-block">
            <div class="wpte-form-content">
                <div class="wpte-field wpte-text wpte-floated">
                    <label class="wpte-field-label"><?php _e( 'Título da seção', 'wp-travel-engine' ); ?></label>
                    <input type="text" name="wp_travel_engine_setting[cost_tab_sec_title]" value="<?php echo esc_attr( $cost_tab_sec_title ); ?>" placeholder="Informe aqui">
                    <span class="wpte-tooltip"><?php _e( 'Informe o título da seção de inclui/não inclui', 'wp-travel-engine' ); ?></span>
                </div>
            <div class="wpte-title-wrap">
                <h2 class="wpte-title"><?php _e( 'Incluso', 'wp-travel-engine' ); ?></h2>
            </div>
                <div class="wpte-field wpte-text wpte-floated">
                    <label class="wpte-field-label"><?php _e( 'Título', 'wp-travel-engine' ); ?></label>
                    <input type="text" name="wp_travel_engine_setting[cost][includes_title]" value="<?php esc_attr_e( $cost_includes_title, 'wp-travel-engine' ); ?>" placeholder="<?php esc_attr_e( 'Título', 'wp-travel-engine' ); ?>">
                    <span class="wpte-tooltip"></span>
                </div>
                <div class="wpte-field wpte-textarea wpte-floated">
                    <label class="wpte-field-label"><?php esc_html_e( 'Lista de serviços', 'wp-travel-engine' ); ?> </label>
                    <textarea name="wp_travel_engine_setting[cost][cost_includes]" placeholder="<?php esc_attr_e( 'Lista de serviços inclusos...', 'wp-travel-engine' ) ?>"><?php echo esc_html( $cost_includes_content ); ?></textarea>
                    <span class="wpte-tooltip"><?php esc_html_e( 'Conteúdo incluso', 'wp-travel-engine' ); ?></span>
                </div>
            </div>
        </div> <!-- .wpte-form-block -->

        <div class="wpte-form-block">
            <div class="wpte-title-wrap">
                <h2 class="wpte-title"><?php _e( 'Não inclui', 'wp-travel-engine' ); ?></h2>
            </div>
            <div class="wpte-form-content">
                <div class="wpte-field wpte-text wpte-floated">
                    <label class="wpte-field-label"><?php _e( 'Título', 'wp-travel-engine' ); ?></label>
                    <input type="text" name="wp_travel_engine_setting[cost][excludes_title]" value="<?php esc_attr_e( $cost_excludes_title, 'wp-travel-engine' ); ?>" placeholder="<?php esc_attr_e( 'Título', 'wp-travel-engine' ); ?>">
                    <span class="wpte-tooltip"></span>
                </div>
                <div class="wpte-field wpte-textarea wpte-floated">
                    <label class="wpte-field-label"><?php esc_html_e( 'Lista de serviços', 'wp-travel-engine' ); ?> </label>
                    <textarea name="wp_travel_engine_setting[cost][cost_excludes]" placeholder="<?php esc_attr_e( 'Lista de serviços não inclusos...', 'wp-travel-engine' ) ?>"><?php echo esc_html( $cost_excludes_content ); ?></textarea>
                    <span class="wpte-tooltip"><?php esc_html_e( 'Conteúdo não incluso', 'wp-travel-engine' ); ?></span>
                </div>
            </div>
        </div> <!-- .wpte-form-block -->
    </div> <!-- .wpte-form-block-wrap -->
    <?php if ( $next_tab ) : ?>
        <div class="wpte-field wpte-submit">
            <input data-tab="includes-excludes" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte-trip-tab-save-continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php _e( 'Salvar e continuar', 'wp-travel-engine' ); ?>">
        </div>
    <?php endif;
