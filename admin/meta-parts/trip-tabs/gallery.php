<?php
/**
 * Gallery Template
 * 
 * @package Wp_Travel_Engine
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
$image_gallery        = get_post_meta( $post_id, 'wpte_gallery_id', true );
$enable_image_gallery = isset( $image_gallery['enable'] ) ? true : false;
$image_ids = array();

if ( ! empty( $image_gallery ) ) {
    if ( isset( $image_gallery['enable'] ) ) {
        unset( $image_gallery['enable'] );
    }
    $image_ids = $image_gallery;
}

$wp_travel_engine_setting = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

// Video Gallery settings
$enable_video_gallery = isset( $wp_travel_engine_setting['enable_video_gallery'] ) ? true : false;
?>
        <div class="wpte-onoff-block">
            <a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $enable_image_gallery ? 'active' : ''; ?>">
                <label for="wpte-enable-image-gallery" class="wpte-field-label"><?php esc_html_e( 'Habilitar galeria de imagem', 'wp-travel-engine' ); ?><span class="wpte-onoff-btn"></span></label>
            </a>
            <input id="wpte-enable-image-gallery" type="checkbox" <?php checked( $enable_image_gallery, true ); ?> name="wpte_gallery_id[enable]" value="1">
            <span class="wpte-tooltip"><?php esc_html_e( 'Adicionar imagens na galeria. Tamanho recomendado da imagem é de 990 x 490 pixels.', 'wp-travel-engine' ); ?> </span>
            <div class="wpte-onoff-popup" <?php echo $enable_image_gallery ? 'style=display:block' : ''; ?>>
                <div class="wpte-gallery">
                    <?php foreach( $image_ids as $key => $id ) : 
                        $image_prev = wp_get_attachment_image_url( $id, 'thumbnail' );
                    ?>
                            <!-- Image repeater -->
                            <div class="wpte-gal-img">
                                <input type="hidden" value="<?php echo esc_attr( $id ); ?>" readonly name="wpte_gallery_id[<?php echo esc_attr( $key ); ?>]">
                                <img src="<?php echo esc_url( $image_prev ); ?>" alt="">
                                <div class="wpte-gal-btns">
                                    <button data-uploader-button-text="<?php esc_attr_e( 'Substituir imagem', 'wp-travel-engine' ); ?>" data-uploader-title="<?php esc_attr_e( 'Adicionar imagem', 'wp-travel-engine' ); ?>" class="wpte-change wpte-change-gal-img"></button>
                                    <button class="wpte-delete wpte-delete-gal-img"></button>
                                </div>
                            </div>
                    <?php endforeach; ?>
                    <div id="wpte-gal-img-upldr-btn" class="wpte-img-uploader">
                        <button data-uploader-button-text="<?php esc_attr_e( 'Adicionar imagem', 'wp-travel-engine' ); ?>" data-uploader-title="<?php esc_attr_e( 'Adicionar imagens ao roteiro', 'wp-travel-engine' ); ?>" class="wpte-upload-btn wpte-add-gallery-img"><?php esc_html_e( 'Nova imagem.', 'wp-travel-engine' ); ?></button>
                        <span class="wpte-tooltip"><?php printf( __( 'Tamanho máximo da imagem %1$s | Tipos suportados: JPG, PNG', 'wp-travel-engine' ), '5MB' ); ?></span>
                    </div>

                </div>
            </div>
        </div>

        <div class="wpte-onoff-block">
            <a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $enable_video_gallery ? 'active' : ''; ?>">
                <label for = "wpte-enable-video-gallery" class="wpte-field-label"><?php esc_html_e( 'Habilitar galeria de vídeo', 'wp-travel-engine' ); ?><span class="wpte-onoff-btn"></span></label>
            </a>
            <input id="wpte-enable-video-gallery" type="checkbox" <?php checked( $enable_video_gallery, true ); ?> name="wp_travel_engine_setting[enable_video_gallery]" value="true">
            <span class="wpte-tooltip"><?php esc_html_e( 'Informe a URL do Youtube ou Vimeo', 'wp-travel-engine' ) ?></span>
            <div class="wpte-onoff-popup" <?php echo $enable_video_gallery ? 'style=display:block' : ''; ?>>
                <div class="wpte-field wpte-url">
                    <input id="wte-trip-vid-url" type="text" placeholder="<?php _e( 'Informe a URL do Youtube ou Vimeo', 'wp-travel-engine' ); ?>">
                    <button class="wp-travel-engine-trip-video-gallery-add-video"><?php _e( 'Adicionar Video', 'wp-travel-engine' ); ?></button>
                </div>
                <ul class="wp-travel-engine-trip-video-gallery wte-video-list-srtable">
                    <?php 
                    $wpte_vid_gallery = get_post_meta( $post_id, 'wpte_vid_gallery', true );
                    if ( ! empty( $wpte_vid_gallery ) ) :
                        foreach( $wpte_vid_gallery as $key => $gal ) :
                            $video_type  = isset( $gal['type'] ) ? $gal['type'] : '';
                            $video_id    = isset( $gal['id'] ) ? $gal['id'] : '';
                            $video_thumb = isset( $gal['thumb'] ) ? $gal['thumb'] : '';
                            ?>
                                <li class="wte-video-gal-<?php echo esc_html( $video_type ); ?>">
                                    <input type="hidden" name="wpte_vid_gallery[<?php echo esc_attr( $key ); ?>][id]" value="<?php echo esc_attr( $video_id ); ?>">
                                    <input type="hidden" name="wpte_vid_gallery[<?php echo esc_attr( $key ); ?>][type]" value="<?php echo esc_attr( $video_type ); ?>">
                                    <input type="hidden" name="wpte_vid_gallery[<?php echo esc_attr( $key ); ?>][thumb]" value="<?php echo esc_attr( $video_thumb ); ?>">
                                    <?php
                                        if ( 'youtube' === $video_type ) {
                                    ?>
                                        <img class="image-preview" src="<?php echo esc_url( $video_thumb ); ?>">
                                        <small><a class="remove-video" href="#"><?php _e( 'Remover video', 'wp-travel-engine' ); ?></a></small>
                                    <?php 
                                        } else {
                                    ?>
                                        <img class="image-preview" data-vimeo-id="<?php echo esc_attr( $video_id ); ?>" src="<?php echo esc_url( $video_thumb ); ?>">
                                        <small><a class="remove-video" href="#"><?php _e( 'Remover video', 'wp-travel-engine' ); ?></a></small>
                                    <?php
                                    }
                                    ?>
                                </li>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
                <script type="text/html" id="tmpl-wpte-trip-videogallery-row">
                    <li class="wte-video-gal-{{data.video_data.type}}">
                        <input type="hidden" name="wpte_vid_gallery[{{data.index}}][id]" value="{{data.video_data.id}}">
                        <input type="hidden" name="wpte_vid_gallery[{{data.index}}][type]" value="{{data.video_data.type}}">
                        <input type="hidden" name="wpte_vid_gallery[{{data.index}}][thumb]" value="{{data.thumb}}">
                        <#
                            if ( 'youtube' === data.video_data.type ) {
                        #>
                            <img class="image-preview" src="{{data.thumb}}">
                            <small><a class="remove-video" href="#"><?php _e( 'Remover video', 'wp-travel-engine' ); ?></a></small>
                        <# 
                            } else {
                        #>
                            <img class="image-preview" data-vimeo-id="{{data.video_data.id}}" src="{{data.thumb}}">
                            <small><a class="remove-video" href="#"><?php _e( 'Remover video', 'wp-travel-engine' ); ?></a></small>
                        <#
                        }
                        #>
                    </li>
                </script>
            </div>
        </div>
        <div class="wpte-info-block">
            <b><?php esc_html_e( 'Nota:', 'wp-travel-engine' ); ?></b>
            <p><?php 
                $page_shortcode     = "[wte_video_gallery trip_id='{$post_id}']";
                $template_shortcode = "<?php echo do_shortcode('[wte_video_gallery trip_id={$post_id}]'); ?>";
            _e( sprintf('Você pode usar esse shortcode <b>%1$s</b> para exibir a galeria de vídeo desse roteiro em posts/páginas ou usar esse código <b>%2$s</b> para exibir em templates.',$page_shortcode, $template_shortcode),'wp-travel-engine');?></p>
            <p>
            <?php esc_html_e( "Atributos adicionais são: type='popup/slider' title='' label='', onde type exibe tanto um popup quanto um slider, popup sendo default.", 'wp-travel-engine' ) ?>
            </p>
        </div>

    <?php if ( $next_tab ) : ?>
        <div class="wpte-field wpte-submit">
            <input data-tab="gallery" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte-trip-tab-save-continue' ) ); ?>" data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php _e( 'Salvar e continuar', 'wp-travel-engine' ); ?>">
        </div>
    <?php endif;
