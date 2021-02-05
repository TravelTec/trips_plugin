<?php
/**
 * Mini cart template.
 *
 * @package WP Travel Engine.
 */
global $wte_cart;
        global $post;
        $wp_travel_engine_setting = get_post_meta( $post->ID,'wp_travel_engine_setting',true );

$cart_items   = $wte_cart->getItems();   
$date_format  = get_option( 'date_format' );
$cart_totals  = $wte_cart->get_total(false);
$wte_settings = get_option( 'wp_travel_engine_settings' );
$extra_service_title = isset( $wte_settings['extra_service_title'] ) ? $wte_settings['extra_service_title'] : __( 'Extra Services', 'wp-travel-engine' );
$cart_discounts = $wte_cart->get_discounts();
$partial_final_total = 0;
if ( ! empty( $cart_items ) ) :
$currency =wp_travel_engine_get_currency_code_or_symbol(); 

?>
    
    <div class="wpte-bf-summary-wrap" style="padding: 0px"> 


        <?php
        foreach( $cart_items as $key => $cart_item ) : ?>
            <?php $wp_travel_engine_setting = get_post_meta( $cart_item['trip_id'],'wp_travel_engine_setting',true ); ?> 
            <?php $duracao = $wp_travel_engine_setting['trip_duration'];
                $facts =  $wp_travel_engine_setting['trip_facts']; ?>
            <?php    
                $adults = $cart_item['pax']['adult'];
                $qtd_pax = $adults;
                if (!empty($cart_item['pax']['child'])) {
                    $child = $cart_item['pax']['child'];
                    $qtd_pax = $adults+$child;
                }
                if (!empty($cart_item['pax']['infant'])) {
                    $infant = $cart_item['pax']['infant'];
                    $qtd_pax = $adults+$child+$infant;
                } 

                $entrada = date("d/m/Y", strtotime($cart_item['trip_date']));

                $data_entrada = explode("/", $entrada);
                for ($i=0; $i < count($wp_travel_engine_setting['multiple_pricing']); $i++) { 
                    $data_saida = explode("/", $wp_travel_engine_setting['multiple_pricing'][$i]['adult']['inicio']);
                     
                    if ($data_entrada[0] >= $data_saida[0] && $data_entrada[1] >= $data_saida[1]) {
                        $saida = $wp_travel_engine_setting['multiple_pricing'][$i]['adult']['termino'];
                    }
                }
                

                echo "<input type='hidden' id='min_age_child' value='".$wp_travel_engine_setting['multiple_pricing'][0]['child']['min_age']."'>";
                echo "<input type='hidden' id='max_age_child' value='".$wp_travel_engine_setting['multiple_pricing'][0]['child']['max_age']."'>";
                echo "<input type='hidden' id='min_age_infant' value='".$wp_travel_engine_setting['multiple_pricing'][0]['infant']['min_age']."'>";
                echo "<input type='hidden' id='max_age_infant' value='".$wp_travel_engine_setting['multiple_pricing'][0]['infant']['max_age']."'>";
                
            ?>
            <div class="card" style="border: 1px solid #eee;text-align: justify !important;background-color: #fff;"> 
                            <div class="card-body" style="padding: 10px">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <strong>Tarifa por hóspede</strong>
                                    </div> 
                                </div>
                                <?php foreach( $cart_item['pax'] as $pax_label => $pax ) :
                                    if ( $pax == '0' ) continue;

                                    if ($pax_label == 'adult') {
                                        $pax_label_disp = ($pax > 1 ? 'Adultos' : 'Adulto'); 
                                        $info_adicional = '';
                                    }else if ($pax_label == 'child') {
                                        $pax_label_disp = ($pax > 1 ? 'Crianças' : 'Criança'); 
                                        $info_adicional = '<br><p style="color: #555;font-weight: 500;font-size: 10px;margin-top: -7px;">De '.$wp_travel_engine_setting['multiple_pricing'][0]['child']['min_age'].' a '.$wp_travel_engine_setting['multiple_pricing'][0]['child']['max_age'].' anos</p>';
                                    }else if ($pax_label == 'infant') {
                                        $pax_label_disp = ($pax > 1 ? 'Bebês' : 'Bebê'); 
                                        $info_adicional = '<br><p style="color: #555;font-weight: 500;font-size: 10px;margin-top: -7px;">De '.$wp_travel_engine_setting['multiple_pricing'][0]['infant']['min_age'].' a '.$wp_travel_engine_setting['multiple_pricing'][0]['infant']['max_age'].' anos</p>';
                                    }else if ($pax_label == 'group') {
                                        $pax_label_disp = ($pax > 1 ? 'Grupos' : 'Grupo'); 
                                        $info_adicional = '';
                                    }
                                ?>
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-7">
                                            <small style="color: #555;font-weight: 500"><?php printf( __( '%2$s', 'wp-travel-engine' ), number_format_i18n( $pax ), ucfirst( $pax_label_disp ) ); ?> (<?php printf( __( '%1$s', 'wp-travel-engine' ), number_format_i18n( $pax ), ucfirst( $pax_label_disp ) ); ?>)</small><?=$info_adicional;?>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-5" style="text-align: right;">
                                            <small style="color: #555;font-weight: 500"><?php echo $wp_travel_engine_setting['multiple_pricing'][0]['adult']['currency_code'].' '.number_format($cart_item['pax_cost'][ $pax_label ]*$cart_item['pax'][$pax_label], 2,  ',', '.'); ?></small>
                                        </div>
                                    </div> 
                                <?php endforeach; ?> 
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-7">
                                        <small style="color: #555;font-weight: 500">Taxas e encargos</small>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-5" style="text-align: right;">
                                        <small style="color: #555;font-weight: 500"><?= $wp_travel_engine_setting['multiple_pricing'][0]['adult']['currency_code'] ?> 0,00</small>
                                    </div>
                                </div>
                                <?php
        if( (!empty($cart_discounts) || sizeof($cart_discounts) !== 0) &&wp_travel_engine_is_trip_partially_payable($cart_item['trip_id']) ){
            $payable_now = $partial_final_total;
        }elseif( (!empty($cart_discounts) || sizeof($cart_discounts) !== 0) && !wp_travel_engine_is_trip_partially_payable($cart_item['trip_id']) ){
            $payable_now = $new_tcost;
        }else{
            $payable_now = wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ? $cart_totals['total_partial']: $cart_totals['cart_total'];
        }

        $payable_now = wp_travel_engine_is_trip_partially_payable( $cart_item['trip_id'] ) ? $cart_totals['total_partial'] : $cart_totals['cart_total'];

        echo "<input type='hidden' id='total_roteiro' value='".str_replace(",", "", number_format($payable_now.'00', 3,  ',', '.')).'.00'."'>";
    ?>
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-6">
                                        <p style="margin-bottom: 0;color: #139298;margin-top: 5px;font-weight: 700">Valor total</p>
                                    </div> 
                                        <div class="col-lg-5 col-md-5 col-6" style="text-align: right;">
                                            <p style="margin-bottom: 0;color: #139298;margin-top: 5px;font-weight: 700" > <?php echo $wp_travel_engine_setting['multiple_pricing'][0]['adult']['currency_code'].'  '.str_replace(",", ".", number_format($payable_now.'00', 3,  ',', '.')).',00'; ?> </p>
                                        </div> 
                                </div>
                            </div>
                        </div>
        <?php endforeach; ?>

        <div class="card" style="border: 1px solid #eee;text-align: justify !important;padding: 10px;background-color: #fff;margin-top: 25px;"> 
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <strong>Informações da viagem</strong>  
                                            <p style="font-size: 13px;font-weight: 700;margin-top: 12px;margin-bottom: 12px"><span style="background-color: #139298;color: #fff;padding: 3px 10px 3px 10px;border-radius: 4px;margin-right: 12px;">Roteiro</span> <span style="font-weight: 700"></span></p>
                                            <p style="font-size: 13px;fmargin-top: 12px;margin-bottom: 0;color: #555">Nome: <strong><?php echo esc_html( get_the_title( $cart_item['trip_id'] ) ); ?></strong></p>
                                            <p style="font-size: 13px; margin-top: 0px;margin-bottom: 0px;color: #555">Início da viagem: <strong><?=$entrada?></strong></p> 
                                            <p style="font-size: 13px; margin-top: 0px;margin-bottom: 4px;color: #555">Término da viagem: <strong><?=$saida?></strong></p> 
                                            <br> 
                                    </div>  
                                </div> 
                            </div>
                        </div>

                        <?php foreach ($facts['field_type'] as $key => $value) { ?>
                            <?php if($wp_travel_engine_setting['trip_facts']['field_id'][$key] == 'Políticas e cancelamento'){ ?>
                                <?php $dados_cancelamento = $wp_travel_engine_setting['trip_facts'][$key][$key]; ?>
                            <?php } ?>
                        <?php } ?>

                        <?php if (!empty($dados_cancelamento)) { ?>

        <div class="card" style="border-top: 1px solid #eee;text-align: justify !important;padding: 0px;background-color: #139298;margin-top: 25px;"> 
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2" style="background-color: #139298;text-align: center;height: 113px;padding-right: 0;">
                <i class="fa fa-info" style="font-size: 38px;color: #fff;padding-top: 10px;"></i>
            </div>   
            <div class="col-lg-10" style="background-color: #fff;padding-top: 13px;padding-bottom: 13px">
                <p style="font-size: 13px;margin-bottom: 0;font-weight: 700">INFORMAÇÕES IMPORTANTES:</p>
                <hr style="margin-top: 10px;margin-bottom: 10px;">
                <p style="font-size: 13px;margin-bottom: 0;font-weight: 700">POLÍTICA DE ALTERAÇÕES E CANCELAMENTOS</p>
                    <p style="font-size: 13.5px;margin-top: 10px"><?php echo $dados_cancelamento; ?></p>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    <?php } ?>
    </div>   

        <script type="text/javascript">
            jQuery(document).ready(function(){ 
                function toFixed(num, fixed) {
    var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (fixed || -1) + '})?');
    return num.toString().match(re)[0];
}

    var hotel = localStorage.hotel; 
    var categoria = localStorage.categoria; 
    var moeda = localStorage.moeda; 
    var diaria = localStorage.diaria; 
    var diarias = "<?=$duracao?>";
    var qtd_pax = "<?= $qtd_pax ?>";
    var total = (diaria.replace(",", ".").replace(".", "")*diarias)*qtd_pax;
    total = parseFloat(total+",00");

    jQuery('#nome_hotel').html(hotel);
    jQuery('#categoria_apto').html(categoria);
    jQuery('#moeda').html(moeda);
    jQuery('#preco_total').html(total+",00");

    var i;
    var retorno = '';
    for (i = 0; i < diarias; i++) {
      retorno += '<p style="margin-bottom: 5px;font-size: 14px;float: right;margin-top: -5px;">'+moeda+' '+diaria+' </p>';
    } 
    jQuery('#valores_diarias').html(retorno);
  }) 
        </script>
<?php
endif;

