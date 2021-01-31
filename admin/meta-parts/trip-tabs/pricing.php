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


if (!empty($wte_trip_settings['multiple_pricing'][0]['adult']['inicio'])) {  
    $timeZone = new DateTimeZone('UTC'); 

    function sortFunction( $a, $b ) {
        return DateTime::createFromFormat ('d/m/Y', $a['adult']["inicio"]) > DateTime::createFromFormat ('d/m/Y', $b['adult']["inicio"]);
    }
    usort($wte_trip_settings['multiple_pricing'], "sortFunction");  

    for ($i=0; $i < count($wte_trip_settings['multiple_pricing']); $i++) {
        $periodos[] = array(str_replace("/", "-", $wte_trip_settings['multiple_pricing'][$i]['adult']['inicio']), str_replace("/", "-", $wte_trip_settings['multiple_pricing'][$i]['adult']['termino'])); 
    }

    $data_periodo = json_encode($periodos); 
 
    echo "<input type=\"hidden\" id=\"periodos\" value='".$data_periodo."'>"; 
} 




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
    <style type="text/css">
        

.btn-xs {
        padding: 3px 7px !important;
    font-size: 11px !important;
    border-radius: .2rem !important;
}

.btn {
    display: inline-block;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    border-radius: .25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.btn-success {
    color: #fff;
    background-color: #198754;
    border-color: #198754;
}
.btn-success:hover {
    color: #fff;
    background-color: #157347;
    border-color: #146c43;
}
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-danger:hover {
    color: #fff;
    background-color: #bb2d3b;
    border-color: #b02a37;
}
    </style>
    <div class="wpte-multi-pricing-wrap" id="wpte-tarifa-repeter-holder" style="border-bottom: none">
        <h1 class="wp-heading-inline display_none" style="margin-bottom: 10px">Tarifas</h1> 
<button class="wpte-add-btn page-title-action display_none" onclick="wpte_add_tarifas()" >Novo período</button>
        <table class="wp-list-table widefat fixed striped table-view-list posts" id="lista_tarifas">
            <thead>
                <tr> 
                    <th scope="col" id="data_inicial" class="manage-column column-data_inicial">Data Inicial</th>
                    <th scope="col" id="data_final" class="manage-column column-data_final">Data Final</th>
                    <th scope="col" id="final" class="manage-column column-final" >Ações</th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php if (!empty($wte_trip_settings['multiple_pricing'][0]['adult']['inicio'])) {   ?>
                <?php for ($i=0; $i < count($wte_trip_settings['multiple_pricing']); $i++) {  ?> 

                <tr id="linha_tarifa_<?=$i?>" class="iedit author-self level-0 post-6935 type-trip status-publish has-post-thumbnail hentry">   
                    <td class="taxonomy-destination column-taxonomy-destination" data-colname="Local"><span class=""><?php echo $wte_trip_settings['multiple_pricing'][$i]['adult']['inicio']  ?></span></td>
                    <td class="taxonomy-activities column-taxonomy-activities" data-colname="Atividades"><span class=""><?php echo $wte_trip_settings['multiple_pricing'][$i]['adult']['termino'] ?></span></td> 
                    <td class="taxonomy-activities column-taxonomy-activities" data-colname="Atividades"><button class="btn btn-xs btn-success" onclick="show_tarifario('<?=$i?>')"><i class="fa fa-pencil"></i></button> <button class="btn btn-xs btn-danger" onclick="remove_tarifario('<?=$i?>')"><i class="fa fa-close"></i></button></td> 
                </tr> 
<?php } ?>
<?php }else{ ?>
<tr id="post-6935" class="iedit author-self level-0 post-6935 type-trip status-publish has-post-thumbnail hentry">   
                    <td class="taxonomy-destination column-taxonomy-destination" data-colname="Local"><span class=""> </span></td>
                    <td class="taxonomy-activities column-taxonomy-activities" data-colname="Atividades"><span class=""> </span></td> 
                    <td class="taxonomy-activities column-taxonomy-activities" data-colname="Atividades"> </td> 
                </tr> 
<?php } ?>
            </tbody>

            <tfoot>
                <tr> 
                    <th scope="col" id="data_inicial" class="manage-column column-data_inicial">Data Inicial</th>
                    <th scope="col" id="data_final" class="manage-column column-data_final">Data Final</th>
                    <th scope="col" id="final" class="manage-column column-final">Ações</th>
                </tr>
            </tfoot>
        </table>
        <br class=" display_none">
        <hr class=" display_none">
        <br class=" display_none">
        <?php if (!empty($wte_trip_settings['multiple_pricing'][0]['adult']['inicio'])) {   ?>
        <?php for ($i=0; $i < count($wte_trip_settings['multiple_pricing']); $i++) {  ?>
            <h3 id="titulo-wpte-tarifa<?=$i?>" style="border-bottom: 1px solid #ddd;display: none">Tarifário <i class="fa fa-arrow-down" onclick="show_tarifario('<?=$i?>')" style="float: right;cursor: pointer;"></i></h3>
        <div class="wpte-repeater-block wpte-tarifa-repeter" style="
    padding-top: 23px;
    border-bottom: none;display: none" id="wpte-tarifa-div<?=$i?>"> 
        <div class="wpte-field wpte-number wpte-floated">
                        <label class="wpte-field-label">
                            Período                    </label>
                        <div class="wpte-field wpte-multi-fields wpte-floated">
                            <div class="wpte-floated">
                                
                                <input type="text" class="inicio" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][adult][inicio]" id="inicio-<?=$i?>" value="<?php echo $wte_trip_settings['multiple_pricing'][$i]['adult']['inicio']  ?>" placeholder="Início">
                            </div>  
                            <div class="wpte-floated"> 
                                <input type="text" class="termino" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][adult][termino]" value="<?php echo $wte_trip_settings['multiple_pricing'][$i]['adult']['termino'] ?>" placeholder="Término" style="max-width: 65%">
                            </div>  
                        </div> 
                    </div>
        <div class="wpte-field wpte-number wpte-floated">
                        <label class="wpte-field-label">
                            Moeda                    </label>
                        <div class="wpte-floated"> 
                            <span> 
                                <select class="wpte-sublabel" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][adult][currency_code]" id="wp_travel_engine_setting[multiple_pricing][<?=$i?>][adult][currency_code]">
                                    <option value="0">Moeda</option>
                                    <option value="R$"  <?=($wte_trip_settings['multiple_pricing'][$i]['adult']['currency_code'] == 'R$' ? 'selected' : '')?>>R$</option>
                                    <option value="USD" <?=($wte_trip_settings['multiple_pricing'][$i]['adult']['currency_code'] == 'USD' ? 'selected' : '')?>>USD</option>
                                    <option value="U$" <?=($wte_trip_settings['multiple_pricing'][$i]['adult']['currency_code'] == 'U$' ? 'selected' : '')?>>U$</option>
                                    <option value="EUR" <?=($wte_trip_settings['multiple_pricing'][$i]['adult']['currency_code'] == 'EUR' ? 'selected' : '')?>>EUR</option>
                                    <option value="CAN"  <?=($wte_trip_settings['multiple_pricing'][$i]['adult']['currency_code'] == 'CAN' ? 'selected' : '')?>>CAN</option>
                                    <option value="AUS" <?=($wte_trip_settings['multiple_pricing'][$i]['adult']['currency_code'] == 'AUS' ? 'selected' : '')?>>AUS</option>
                                    <option value="NZ" <?=($wte_trip_settings['multiple_pricing'][$i]['adult']['currency_code'] == 'NZ' ? 'selected' : '')?>>NZ</option>
                                    <option value="GBP" <?=($wte_trip_settings['multiple_pricing'][$i]['adult']['currency_code'] == 'GBP' ? 'selected' : '')?>>GBP</option>
                                </select>
                            </span> 
                        </div>
                        <span class="wpte-tooltip">Essa será a moeda utilizada para o roteiro.</span>
                    </div>
        <?php
        // Pricing Loop Start.
        $x = 0;
        foreach( $saved_pricing_options as $option => $label ) : 

            $trip_prev_price = '';
            $trip_sale_price = '';
            $trip_sale_enable = false;
            $bind = '';
            $bind_sale = '';

            if ( 'adult' === $option && ! isset( $wte_trip_settings['multiple_pricing'][$i] ) ) :

                $trip_prev_price = isset( $wte_trip_settings[$i]['trip_prev_price'] ) && ! empty( $wte_trip_settings[$i]['trip_prev_price'] ) ? $wte_trip_settings[$i]['trip_prev_price'] : '';
                $trip_sale_enable = isset( $wte_trip_settings[$i]['sale'] ) && '1' === $wte_trip_settings[$i]['sale'] ? true : false;
                $trip_sale_price = isset( $wte_trip_settings[$i]['trip_price'] ) && ! empty( $wte_trip_settings[$i]['trip_price'] ) ? $wte_trip_settings[$i]['trip_price'] : '';

            endif;

            if ( 'child' === $option && ! isset( $wte_trip_settings['multiple_pricing'][$i] ) ) :

                $trip_prev_price = apply_filters( 'wte_apply_group_discount_default', $trip_prev_price );

            endif;

            if ($option === 'adult') {
                $label_campo = 'Adulto';
            }else if ($option === 'child') {
                $label_campo = 'Criança';
            }else if ($option === 'infant') {
                $label_campo = 'Bebê';
            }else if ($option === 'group') {
                $label_campo = 'Grupo';
            }

            $pricing_option_label = $label_campo;

            // $price =  $wte_trip_settings['multiple_pricing'][0][esc_attr( $option )]['price'];

            $pricing_option_type = isset( $wte_trip_settings['multiple_pricing'][$i][esc_attr( $option )]['price_type'] ) ? $wte_trip_settings['multiple_pricing'][$i][esc_attr( $option )]['price_type'] : 'per-person';

            $pricing_option_price = isset( $wte_trip_settings['multiple_pricing'][$i][esc_attr( $option )]['price'] ) ? $wte_trip_settings['multiple_pricing'][$i][esc_attr( $option )]['price'] : $trip_prev_price;

            $pricing_option_sale_price = isset( $wte_trip_settings['multiple_pricing'][$i][esc_attr( $option )]['sale_price'] ) ?  $wte_trip_settings['multiple_pricing'][$i][esc_attr( $option )]['sale_price'] : $trip_sale_price;

            $enable_sale_option = isset( $wte_trip_settings['multiple_pricing'][$i][esc_attr( $option )]['enable_sale'] ) && '1' === $wte_trip_settings['multiple_pricing'][$i][esc_attr( $option )]['enable_sale'] ? true : $trip_sale_enable;

            $sale_display = $enable_sale_option ? true : false;

            if ( 'adult' === $option && $i == 0 ) :
                $bind = 'bind="wpte-trip-mn-price"';
                $bind_sale = 'bindSale="wpte-trip-mn-sale-price"';
                ?>
                    <input id="wpte-trip-default-pper" type="hidden" name="wp_travel_engine_setting[trip_price_per]" value="<?php echo esc_attr( $pricing_option_type ); ?>">
                    <input <?php echo esc_attr( $bind ); ?> type="hidden" name="wp_travel_engine_setting[trip_prev_price]" value="<?php echo esc_attr( $pricing_option_price ); ?>">
                    <input <?php echo esc_attr( $bind_sale ); ?> type="hidden" name="wp_travel_engine_setting[trip_price]" value="<?php echo esc_attr( $pricing_option_sale_price ); ?>">
                    <input 
                        style="display: none" 
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
                        <label for="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][label]" class="wpte-field-label">
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
                            <input required type="text" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][label]" id="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][label]" 
                        value="<?php echo esc_attr( $pricing_option_label ); ?>"
                        placeholder="<?php _e( 'Nome da opção', 'wp-travel-engine' ); ?>"/>
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
                            <input type="text" class="price_trip" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][price]" 
                                id="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][price]" 
                                <?php echo esc_attr( $bind ); ?>
                                value="<?php echo esc_attr( $pricing_option_price ); ?>"
                                placeholder="<?php _e( 'Valor regular', 'wp-travel-engine' ); ?>" style="width: 40%;margin-right: 13px"  onKeyPress="return(moeda(this,'.',',',event))"/> 
                        </div>
                        <span class="wpte-tooltip"><?php echo esc_html( sprintf( __( 'Informe o valor para o passageiro do tipo %1$s. O valor informado será aplicado como base tarifária para %2$s.', 'wp-travel-engine' ), $mp_label, $mp_label ) ); ?></span>
                    </div>
                    <div class="wpte-onoff-block wpte-floated">
                        <a href="Javascript:void(0);" class="wpte-onoff-toggle <?php echo $sale_display ? 'active' : ''; ?>">
                            <label for="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][enable_sale]" class="wpte-field-label"><?php echo esc_html( 'Habilitar preço em oferta' ); ?><span class="wpte-onoff-btn"></span></label>
                        </a>
                        <input 
                        style="display: none" 
                            type    = "checkbox"
                            class   = "wp-travel-engine-setting-enable-pricing-sale"
                            id      = "wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][enable_sale]"
                            name    = "wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][enable_sale]"
                            value   = "1"
                            <?php checked( $enable_sale_option, true ); ?>
                        />
                        <div <?php echo $sale_display ? 'style="display:block;"' : ''; ?> class="wpte-onoff-popup">
                            <div class="wpte-field wpte-number">
                                <div class="wpte-floated">
                                    <input class="price_trip" <?php echo esc_attr( $bind_sale ); ?> type="text" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][sale_price]" 
                                        id="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][sale_price]" 
                                        value="<?php echo esc_attr( $pricing_option_sale_price ); ?>"
                                        placeholder="<?php _e( 'Valor de oferta', 'wp-travel-engine' ); ?>" style="width: 65%;margin-right: 13px" onKeyPress="return(moeda(this,'.',',',event))" /> 
                                </div>
                            </div>
                        </div>
                        <span class="wpte-tooltip"><?php //echo esc_html__( 'Enable sale price for this pricing option.', 'wp-travel-engine' ); ?></span>
                    </div>
                    <?php if ( 'group' !== $option ) : ?>
                        <div style="margin-top:20px;" class="wpte-field wpte-floated">
                            <label for="wpte-adult-price-pertype-sel" class="wpte-field-label"><?php echo esc_html__( 'Tipo de precificação', 'wp-travel-engine' ); ?></label>
                            <div class="wpte-floated">
                                <select id="wpte-adult-price-pertype-sel" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][price_type]">
                                    <option <?php selected( $pricing_option_type, 'per-person' ); ?> value="per-person"><?php esc_html_e( 'Por pessoa', 'wp-travel-engine' ); ?></option>
                                    <option <?php selected( $pricing_option_type, 'per-group' ); ?> value="per-group"><?php esc_html_e( 'Por grupo', 'wp-travel-engine' ); ?></option>
                                </select>
                            </div>
                            <span class="wpte-tooltip"><?php echo esc_html__( 'Altere o tipo de preço para esta opção de preço. Selecionar "Por Grupo" tratará o preço no total, independentemente do número de viajantes.', 'wp-travel-engine' ); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($option === 'child' || $option === 'infant') { ?>
                        <div class="wpte-field wpte-onoff-block" style="margin-top:26px;">
                            <label for="wpte-enable-min-max-age" class="wpte-field-label">Idade mínima e máxima </label>
                            <span class="wpte-tooltip" style="margin-top: 0">Habilite idade mínima e máxima para reservar essa viagem.</span>
                            <div style="display:block;" class="wpte-onoff-popup">
                                <div class="wpte-field wpte-minmax wpte-floated">
                                    <div class="wpte-min">
                                        <label class="wpte-field-label">Idade mínima</label>
                                        <input type="number" step="1" min="0" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][min_age]" value="<?=$wte_trip_settings['multiple_pricing'][$i][esc_attr( $option )]['min_age']?>" placeholder="Informe o valor" data-parsley-id="17" class="parsley-success">
                                    </div>
                                    <div class="wpte-max">
                                        <label class="wpte-field-label">Idade máxima</label>
                                        <input type="number" step="1" min="1" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][max_age]" value="<?=$wte_trip_settings['multiple_pricing'][$i][esc_attr( $option )]['max_age']?>" placeholder="Informe o valor" data-parsley-id="19" class="parsley-success">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                        /**
                         * Hook for pax limits and advanced options.
                         */
                        do_action( 'wte_after_pricing_option_setting_fields' );
                    ?>
                </div>
                <?php $x++; ?>
        <?php endforeach; ?>
        </div>
    <?php } ?>
<?php } ?>
    </div>
    <script type="text/html" id="tmpl-wpte-add-tarifa-row" >
        <h3 id="titulo-wpte-tarifa<?=$i?>" style="border-bottom: 1px solid #ddd">Tarifário  <i class="fa fa-trash" onclick="remove_tarifario_js('{{(data.key-1)}}')" style="float: right;cursor: pointer;color: #f98d8d;"></i></h3>
            <div class="wpte-repeater-block wpte-tarifa-repeter" style="
    padding-top: 23px;
    border-bottom: none;" id="wpte-tarifa-div{{(data.key-1)}}"> 
                <div class="wpte-field wpte-number wpte-floated">
                        <label class="wpte-field-label">
                            Período                    </label>
                        <div class="wpte-field wpte-multi-fields wpte-floated">
                            <div class="wpte-floated">
                                
                                <input type="text" class="inicio" name="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][adult][inicio]" value="" placeholder="Início">
                            </div>  
                            <div class="wpte-floated"> 
                                <input type="text" class="termino" name="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][adult][termino]" value="" placeholder="Término" style="max-width: 65%">
                            </div>  
                        </div> 
                    </div>
        <div class="wpte-field wpte-number wpte-floated">
                        <label class="wpte-field-label">
                            Moeda                    </label>
                        <div class="wpte-floated"> 
                            <span> 
                                <select class="wpte-sublabel" name="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][adult][currency_code]" id="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][adult][currency_code]">
                                    <option value="0">Moeda</option>
                                    <option value="R$">R$</option>
                                    <option value="USD">USD</option>
                                    <option value="U$">U$</option>
                                    <option value="EUR">EUR</option>
                                    <option value="CAN">CAN</option>
                                    <option value="AUS">AUS</option>
                                    <option value="NZ">NZ</option>
                                    <option value="GBP">GBP</option>
                                </select>
                            </span> 
                        </div>
                        <span class="wpte-tooltip">Essa será a moeda utilizada para o roteiro.</span>
                    </div>
        <?php
        // Pricing Loop Start.
        $i = 0;
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

            if ($option === 'adult') {
                $label_campo = 'Adulto';
            }else if ($option === 'child') {
                $label_campo = 'Criança';
            }else if ($option === 'infant') {
                $label_campo = 'Bebê';
            }else if ($option === 'group') {
                $label_campo = 'Grupo';
            }

            $pricing_option_label = $label_campo;

            // $price =  $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price'];

            $pricing_option_type = isset( $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price_type'] ) ? $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price_type'] : 'per-person';

            $pricing_option_price = isset( $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price'] ) ? $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['price'] : $trip_prev_price;

            $pricing_option_sale_price = isset( $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['sale_price'] ) ?  $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['sale_price'] : $trip_sale_price;

            $enable_sale_option = isset( $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['enable_sale'] ) && '1' === $wte_trip_settings['multiple_pricing'][esc_attr( $option )]['enable_sale'] ? true : $trip_sale_enable;

            $sale_display = $enable_sale_option ? true : false; 
        ?>
                <div class="wpte-field wpte-multi-fields">
                    <div class="wpte-field wpte-floated">
                        <label for="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][label]" class="wpte-field-label">
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
                            <input required type="text" name="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][label]" id="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][label]" 
                        value="<?=$pricing_option_label?>"
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
                            <input type="text" class="price_trip" name="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][price]" 
                                id="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][price]" 
                                <?php echo esc_attr( $bind ); ?>
                                value=""
                                placeholder="<?php _e( 'Valor regular', 'wp-travel-engine' ); ?>" style="width: 40%;margin-right: 13px"  onKeyPress="return(moeda(this,'.',',',event))"/> 
                        </div>
                        <span class="wpte-tooltip"><?php echo esc_html( sprintf( __( 'Informe o valor para o passageiro do tipo %1$s. O valor informado será aplicado como base tarifária para %2$s.', 'wp-travel-engine' ), $mp_label, $mp_label ) ); ?></span>
                    </div>
                    <div class="wpte-onoff-block wpte-floated">
                        <a href="Javascript:void(0);" class="wpte-onoff-toggle ">
                            <label for="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][enable_sale]" class="wpte-field-label"><?php echo esc_html( 'Habilitar preço em oferta' ); ?><span class="wpte-onoff-btn"></span></label>
                        </a>
                        <input 
                            style="display: none"
                            type    = "checkbox"
                            class   = "wp-travel-engine-setting-enable-pricing-sale"
                            id      = "wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][enable_sale]"
                            name    = "wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][enable_sale]"
                            value   = "1"
                            <?php checked( $enable_sale_option, true ); ?>
                        />
                        <div class="wpte-onoff-popup">
                            <div class="wpte-field wpte-number">
                                <div class="wpte-floated">
                                    <input class="price_trip" <?php echo esc_attr( $bind_sale ); ?> type="text" name="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][sale_price]" 
                                        id="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][sale_price]" 
                                        value=""
                                        placeholder="<?php _e( 'Valor de oferta', 'wp-travel-engine' ); ?>" style="width: 65%;margin-right: 13px" onKeyPress="return(moeda(this,'.',',',event))" /> 
                                </div>
                            </div>
                        </div>
                        <span class="wpte-tooltip"><?php //echo esc_html__( 'Enable sale price for this pricing option.', 'wp-travel-engine' ); ?></span>
                    </div>
                    <?php if ( 'group' !== $option ) : ?>
                        <div style="margin-top:20px;" class="wpte-field wpte-floated">
                            <label for="wpte-adult-price-pertype-sel" class="wpte-field-label"><?php echo esc_html__( 'Tipo de precificação', 'wp-travel-engine' ); ?></label>
                            <div class="wpte-floated">
                                <select id="wpte-adult-price-pertype-sel" name="wp_travel_engine_setting[multiple_pricing][{{(data.key-1)}}][<?php echo esc_attr( $option ); ?>][price_type]">
                                    <option value="per-person"><?php esc_html_e( 'Por pessoa', 'wp-travel-engine' ); ?></option>
                                    <option value="per-group"><?php esc_html_e( 'Por grupo', 'wp-travel-engine' ); ?></option>
                                </select>
                            </div>
                            <span class="wpte-tooltip"><?php echo esc_html__( 'Altere o tipo de preço para esta opção de preço. Selecionar "Por Grupo" tratará o preço no total, independentemente do número de viajantes.', 'wp-travel-engine' ); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($option === 'child' || $option === 'infant') { ?>
                        <div class="wpte-field wpte-onoff-block" style="margin-top:26px;">
                            <label for="wpte-enable-min-max-age" class="wpte-field-label">Idade mínima e máxima </label>
                            <span class="wpte-tooltip" style="margin-top: 0">Habilite idade mínima e máxima para reservar essa viagem.</span>
                            <div style="display:block;" class="wpte-onoff-popup">
                                <div class="wpte-field wpte-minmax wpte-floated">
                                    <div class="wpte-min">
                                        <label class="wpte-field-label">Idade mínima</label>
                                        <input type="number" step="1" min="0" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][min_age]" value="" placeholder="Informe o valor" data-parsley-id="17" class="parsley-success">
                                    </div>
                                    <div class="wpte-max">
                                        <label class="wpte-field-label">Idade máxima</label>
                                        <input type="number" step="1" min="1" name="wp_travel_engine_setting[multiple_pricing][<?=$i?>][<?php echo esc_attr( $option ); ?>][max_age]" value="" placeholder="Informe o valor" data-parsley-id="19" class="parsley-success">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                        /**
                         * Hook for pax limits and advanced options.
                         */
                        do_action( 'wte_after_pricing_option_setting_fields' );
                    ?>
                </div>
                <?php $i++; ?>
        <?php endforeach; ?>
            </div>
    </script>
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
 
    <script type="text/javascript">
        function moeda(a, e, r, t) {
            let n = ""
              , h = j = 0
              , u = tamanho2 = 0
              , l = ajd2 = ""
              , o = window.Event ? t.which : t.keyCode;
              a.value = a.value.replace('R$ ','');            
            if (n = String.fromCharCode(o),
            -1 == "0123456789".indexOf(n))
                return !1;
            for (u = a.value.replace('R$ ','').length,
            h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
                ;
            for (l = ""; h < u; h++)
                -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
            if (l += n,
            0 == (u = l.length) && (a.value = ""),
            1 == u && (a.value = "0" + r + "0" + l),
            2 == u && (a.value = "0" + r + l),
            u > 2) {
                for (ajd2 = "",
                j = 0,
                h = u - 3; h >= 0; h--)
                    3 == j && (ajd2 += e,
                    j = 0),
                    ajd2 += l.charAt(h),
                    j++;
                for (a.value = "",
                tamanho2 = ajd2.length,
                h = tamanho2 - 1; h >= 0; h--)
                    a.value += ajd2.charAt(h);
                a.value += r + l.substr(u - 2, u)
            }
            return !1
        } 

    </script>
 
<script>
    jQuery(".inicio").datepicker({
        dateFormat: 'dd/mm/yy',
        showOtherMonths: true,
        selectOtherMonths: true,
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        minDate: 0,
        onSelect: function () {
            var dt2 = jQuery('.termino');
            var startDate = jQuery(this).datepicker('getDate'); 
            jQuery(this).attr('value', jQuery.datepicker.formatDate("dd/mm/yy", startDate));
            //add 30 days to selected date
            startDate.setDate(startDate.getDate()); 
            dt2.datepicker('option', 'minDate', startDate); 

            var data_inicio = JSON.parse(jQuery('#periodos').val()); 
            var data_comparativo = jQuery.datepicker.formatDate("dd/mm/yy", startDate);

            jQuery(data_inicio).each(function(i, item) {

                var data_inicial = item[0].replace("-", "/").replace("-", "/");  
                var data_final = item[1].replace("-", "/").replace("-", "/");

                var parte_data_inicial = data_inicial.split("/");
                var data_inicial2 = new Date(parte_data_inicial[2], parte_data_inicial[1] - 1, parte_data_inicial[0]);

                var parte_data_final = data_final.split("/");
                var data_final2 = new Date(parte_data_final[2], parte_data_final[1] - 1, parte_data_final[0]);

                var parte_data_comparativo = data_comparativo.split("/");
                var data_comparativo2 = new Date(parte_data_comparativo[2], parte_data_comparativo[1] - 1, parte_data_comparativo[0]);

                if (data_comparativo2 >= data_inicial2 && data_comparativo2 <= data_final2) {
                    console.log(data_comparativo2+ ">=" +data_inicial2+ "&&" +data_comparativo2+ "<=" +data_final2);
                    toastr.error('Data inicial em um período já cadastrado');
                    jQuery('.wpte_save_continue_link').prop('disabled', true);
                    jQuery('.wpte_save_continue_link').attr("disabled", true);
                    jQuery('.wpte_save_continue_link').attr("style", 'background-color: #ddd;border: 1px solid #ddd;color: #000;cursor: not-allowed;');     
                }else{
                    jQuery('.wpte_save_continue_link').prop('disabled', false);
                    jQuery('.wpte_save_continue_link').attr("disabled", false);
                    jQuery('.wpte_save_continue_link').attr("style", '');     
                }
 
            });
        }
    });
    jQuery('.termino').datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        minDate: 0,
        showOtherMonths: true,
        selectOtherMonths: true,
        onSelect: function () { 
            var startDate = jQuery(this).datepicker('getDate'); 
            jQuery(this).attr('value', jQuery.datepicker.formatDate("dd/mm/yy", startDate)); 

            var data_inicio = JSON.parse(jQuery('#periodos').val()); 
            var data_comparativo = jQuery.datepicker.formatDate("dd/mm/yy", startDate);

            jQuery(data_inicio).each(function(i, item) {

                var data_inicial = item[0].replace("-", "/").replace("-", "/");  
                var data_final = item[1].replace("-", "/").replace("-", "/");

                var parte_data_inicial = data_inicial.split("/");
                var data_inicial2 = new Date(parte_data_inicial[2], parte_data_inicial[1] - 1, parte_data_inicial[0]);

                var parte_data_final = data_final.split("/");
                var data_final2 = new Date(parte_data_final[2], parte_data_final[1] - 1, parte_data_final[0]);

                var parte_data_comparativo = data_comparativo.split("/");
                var data_comparativo2 = new Date(parte_data_comparativo[2], parte_data_comparativo[1] - 1, parte_data_comparativo[0]);

                if (data_comparativo2 >= data_inicial2 && data_comparativo2 <= data_final2) {
                    console.log(data_comparativo2+ ">=" +data_inicial2+ "&&" +data_comparativo2+ "<=" +data_final2);
                    toastr.error('Data final em um período já cadastrado');
                    jQuery('.wpte_save_continue_link').prop('disabled', true);
                    jQuery('.wpte_save_continue_link').attr("disabled", true);
                    jQuery('.wpte_save_continue_link').attr("style", 'background-color: #ddd;border: 1px solid #ddd;color: #000;cursor: not-allowed;');     
                }else{
                    jQuery('.wpte_save_continue_link').prop('disabled', false);
                    jQuery('.wpte_save_continue_link').attr("disabled", false);
                    jQuery('.wpte_save_continue_link').attr("style", '');     
                }
 
            });
        }
    });
</script>
 
