<?php

/**

 * Payment General Tab

 */

$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings',true );

$payment_debug = isset( $wp_travel_engine_settings['payment_debug'] ) ? $wp_travel_engine_settings['payment_debug'] : 'no';

?>

<style type="text/css">
    .wpte-submit{
        display: none ;
    }
 
        .wpte-field.wpte-submit-payment input[type="submit"] {
    background: var(--primary-color);
    color: var(--white-color);
    border: 1px solid var(--primary-color);
    border-radius: 50px;
    padding: 12px 20px;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 0.2px;
    font-family: var(--primary-font);
    cursor: pointer; 
    }
    .wpte-field.wpte-submit-payment input[type="submit"]:hover {
    background: none;
    color: var(--primary-color);
}
.wpte-field.wpte-submit-payment {
    border: none;
    box-shadow: 0 -1px 2px rgba(var(--black-color-rgb), 0.06);
    margin-top: 80px;
    margin-left: -40px;
    margin-right: -40px;
    padding: 20px 20px 0 20px;
    justify-content: flex-end;
    text-align: right;
} 
.advance-checkbox input[type="checkbox"] + label {
    display: inline-block;
    background: rgba(var(--black-color-rgb), 0.15);
    width: 45px;
    height: 25px;
    border-radius: 50px;
    position: relative;
    -webkit-transition: all ease 0.35s;
    -moz-transition: all ease 0.35s;
    transition: all ease 0.35s;
}
</style>
  
<div class="wpte-block-content" style="padding: 0">

    <div class="wpte-title-wrap" style="padding: 0">

        <h3 class="wpte-title" style="margin: 0"><?php esc_html_e( 'Fornecedores disponíveis', 'wp-travel-engine' ); ?></h3>
        <span class="wpte-tooltip"><?php esc_html_e( 'Selecione um fornecedor para integrar o sistema de roteiros ao e-mail marketing e gerar campanhas de forma rápida e fácil.', 'wp-travel-engine' ); ?></span>

    </div>   

     <?php

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://api.traveltec.com.br/serv/marketing/listar_dados",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json", 
                        "url: wp01.montenegroev.com.br"
                      ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                      echo "cURL Error #:" . $err;
                    } else {
                        $dados = json_decode($response, tue);
                        $message = $dados['message']; 
                    }

                ?> 

                                            
<div class="wpte-form-block-wrap">

    <div class="wpte-form-block">

        <div class="wpte-form-content">

            <div class="wpte-field wpte-checkbox advance-checkbox" style="padding-bottom: 0;margin-bottom: 0;margin-top: 25px;border-bottom: 0">

                <label class="wpte-field-label" for="wp_travel_engine_settings[booking]"> <input type="radio" id="pagseguro" class="paypal-payment" name="payment_form" value="<?=($message['status'] == 1 ? 1 : 0)?>" onclick="ver_dados_pagseguro()" <?=($message['status'] == 1 ? 'checked' : '')?>> Locaweb</label>

                <div class="wpte-checkbox-wrap">

                    <input type="checkbox" id="wp_travel_engine_settings[booking]" class="pagseguro" name="wp_travel_engine_settings[booking]" <?=($message['status'] == 1 ? 'checked' : '')?> onclick="alterar_checked()">

                    <label for="wp_travel_engine_settings[booking]"></label> 

                </div>

                <span class="wpte-tooltip"></span>

            </div>   
            <div class="wpte-field wpte-checkbox advance-checkbox">  

                    <div class="" style="width: 64%;border: 1px solid #ddd;padding: 28px; <?=($message['status'] == 1 ? 'display:block' : 'display:none')?>" id="dados_conta">


                        <div class="wpte-field wpte-text wpte-floated">

                            <label for="wp_travel_engine_settings[book_btn_txt]" class="wpte-field-label" style="font-weight: 500"><?php _e('ID da conta','wp-travel-engine');?></label>

                            <input type="text" id="id_conta" name="e_mail_conta" value="<?=$message['id_conta']?>" placeholder="ID da conta"> 

                        </div>

                        <div class="wpte-field wpte-text wpte-floated" >

                            <label for="wp_travel_engine_settings[book_btn_txt]" class="wpte-field-label" style="font-weight: 500"><?php _e('Chave da conta','wp-travel-engine');?></label>

                            <input type="text" id="chave_conta" name="token_conta" value="<?=$message['chave_conta']?>" placeholder="Chave da conta"> 

                        </div>

                        <div class="wpte-field wpte-text wpte-floated" >

                            <label for="wp_travel_engine_settings[book_btn_txt]" class="wpte-field-label" style="font-weight: 500"><?php _e('Url da conta','wp-travel-engine');?></label>

                            <input type="text" id="endereco_conta" name="token_conta" value="<?=$message['endereco_conta']?>" placeholder="Url da conta"> 

                        </div>

                    </div>  

            </div>  

        </div>

    </div>

</div>
<input type="hidden" id="status" name="">


                                        </div>
<div class="wpte-field wpte-submit-payment">
                        <input data-tab="wpte-payment" data-nonce="177deed2bf" class="wpte-save-global-settings" type="submit" name="wpte_save_global_settings" value="Salvar e Continuar" onclick="save_dados_payment()">
                    </div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> 

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script type="text/javascript">
    function ver_dados_pagseguro(){
        $("#dados_conta").toggle(500);
    }
    function esconder_dados_pagseguro(){
        $("#dados_conta").attr('style', 'width: 64%;border: 1px solid #ddd;padding: 28px;display: none');
    }
    function alterar_checked(){
        if ($(".pagseguro").is(':checked')) {
            $("#status").val('1');
        } else{
            $("#status").val('0');
        }
    }
    function save_dados_payment(){
        var id = $("#id_conta").val(); 
        var chave = $("#chave_conta").val(); 
        var endereco = $("#endereco_conta").val(); 
        var status = $("#status").val(); 

        var settings = {
          "async": true,
          "crossDomain": true,
          "url": "https://api.traveltec.com.br/serv/marketing/insert_dados",
          "method": "POST",
          "headers": {
            "url": "<?=$_SERVER['HTTP_HOST']?>",
            "id": id,
            "chave": chave,
            "endereco": endereco,
            "status": status,
            "content-type": "application/json" 
          }
        }

        $.ajax(settings).done(function (response) {
            if (response.errors) {

                return Swal.fire({

                    title: "Não foi possível alterar",

                    text: "Dados não alterados. Por favor, tente novamente.",

                    icon: "error",

                }).then((result) => {

                    // Reload the Page

                    location.reload();

                });

            }else{ 

                return Swal.fire({

                    title: "Dados alterados",

                    text: "Informações atualizadas com sucesso.",

                    icon: "success",

                }).then((result) => {

                    // Reload the Page

                    location.reload();

                });

            }
        });
    }
</script>