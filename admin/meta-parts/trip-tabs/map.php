<?php
/**
 * Map Template.
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
$src[0] = '';
if ( isset( $wp_travel_engine_setting['map']['image_url'] ) && $wp_travel_engine_setting['map']['image_url'] != '' ){
    $src = wp_get_attachment_image_src( $wp_travel_engine_setting['map']['image_url'],'medium' );
}
$map_section_title        = isset( $wp_travel_engine_setting['map_section_title'] ) ? $wp_travel_engine_setting['map_section_title'] : '';
?>
    <div class="wpte-field wpte-text wpte-floated">
        <label class="wpte-field-label"><?php esc_html_e( 'Título', 'wp-travel-engine' ); ?> </label>
        <input type="text" name="wp_travel_engine_setting[map_section_title]" value="<?php esc_attr_e( $map_section_title, 'wp-travel-engine' ); ?>" placeholder="<?php esc_attr_e( 'Informe o titulo', 'wp-travel-engine' ) ?>">
        <span class="wpte-tooltip"><?php esc_html_e('Informe o título da seção para o mapa. O título será exibido na aba do Mapa.', 'wp-travel-engine' ) ?></span>
    </div>

    <div class="wpte-field wpte-file wpte-floated">
        <label class="wpte-field-label"><?php esc_html_e( 'Imagem', 'wp-travel-engine' ); ?></label>
        <div class="wpte-file-wrap">
            <input type="hidden" name="wp_travel_engine_setting[map][image_url]" id="image_url" class="regular-text" value="<?php echo isset($wp_travel_engine_setting['map']['image_url']) ? esc_attr($wp_travel_engine_setting['map']['image_url']): ''; ?>">
            <label class="wpte-file-label" id="wpte-upload-map-img"><?php esc_html_e( 'Upload imagem', 'wp-travel-engine' ); ?></label>
            <div class="wpte-file-preview">
                <img id="map-image-prev-hldr" src="<?php echo ( isset( $wp_travel_engine_setting['map']['image_url'] ) && $wp_travel_engine_setting['map']['image_url']!='' ) ? $src[0] : plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/css/images/img-fallback.png'; ?>" alt="">
                <?php
                    $rem_disp =  ! empty($wp_travel_engine_setting['map']['image_url']) ? 'style="display:block"' :   'style="display:none"'; 
                
                ?>
                    <button <?php echo $rem_disp; ?> data-fallback="<?php echo plugin_dir_url( WP_TRAVEL_ENGINE_FILE_PATH ) . 'admin/css/images/img-fallback.png'; ?>" class="wpte-delete wpte-delete-map-img"></button>
            </div>
        </div>
    </div>

    <div class="wpte-field wpte-textarea wpte-floated">
        <label class="wpte-field-label"><?php esc_html_e( 'Código do iframe', 'wp-travel-engine' ); ?></label>
        <textarea name="wp_travel_engine_setting[map][iframe]" id="wp_travel_engine_setting[map][iframe]"><?php echo isset($wp_travel_engine_setting['map']['iframe']) ? $wp_travel_engine_setting['map']['iframe']:'' ?></textarea>
        <span class="wpte-tooltip"> 

            Para inserir o código iframe para utilizar como mapa, siga os passos abaixo:  <br> <br>
            <strong>1. </strong> Acesse o site https://www.google.com.br/maps; <br>
            <strong>2. </strong> Informe o endereço desejado de forma completa e em seguida clique na lupa para pesquisar; <br>
            <strong>3. </strong> Ao pesquisar, o Google exibirá a localização exata do mapa. Abaixo da opção 'Rotas' clique em 'Compartilhar'; <br>
            <strong>4. </strong> Na janela que se abrir, clique na opção 'Incorporar mapa'; <br>
            <strong>5. </strong> Após clicar em Incorporar, você também poderá alterar o tamanho conforme desejar ou deixar no formato padrão fornecido pelo Google; <br>
            <strong>6. </strong> No campo 'Iframe', copie o código fornecido e cole no espaço acima. <br>

        </span>
    </div>

    <?php 
        $page_shortcode     = '[wte_trip_map id='."'".$post_id."'".']';
        $template_shortcode = "&lt;?php echo do_shortcode('[wte_trip_map id=".$post_id."]'); ?&gt;";
    ?>
    <div class="wpte-shortcode">
        <span class="wpte-tooltip"><?php esc_html_e( 'Para exibir o mapa desse roteiro em posts/páginas, use o shortcode: ', 'wp-travel-engine' ); ?> <b><?php esc_html_e( 'Shortcode.', 'wp-travel-engine' ); ?></b></span>
        <div class="wpte-field wpte-field-gray wpte-floated">
            <input id="wpte-map-code" readonly type="text" value="<?php esc_attr_e( $page_shortcode, 'wp-travel-engine' ); ?>">
            <button data-copyid="wpte-map-code" class="wpte-copy-btn"><?php esc_html_e( 'Copiar', 'wp-travel-engine' ); ?></button>
        </div>
    </div>

    <div class="wpte-shortcode">
        <span class="wpte-tooltip"><?php esc_html_e( 'Para exibir o mapa desse roteiro no seu template, use o código: ', 'wp-travel-engine' ); ?> <b><?php esc_html_e( 'PHP Funtion.', 'wp-travel-engine' ); ?></b></span>
        <div class="wpte-field wpte-field-gray wpte-floated">
            <input id="wpte-map-temp-code" readonly type="text" value="<?php esc_attr_e( $template_shortcode, 'wp-travel-engine' ); ?>">
            <button data-copyid="wpte-map-temp-code" class="wpte-copy-btn"><?php esc_html_e( 'Copiar', 'wp-travel-engine' ); ?></button>
        </div>
    </div>

    <?php if ( $next_tab ) : ?>
        <div class="wpte-field wpte-submit">
            <input data-tab="map" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte-trip-tab-save-continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php _e( 'Salvar e continuar', 'wp-travel-engine' ); ?>">
        </div>
    <?php endif;
