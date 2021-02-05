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
</style>
  
<div class="wpte-field wpte-multi-checkbox">

    <div class="wpte-title-wrap">

        <h3 class="wpte-title"><?php esc_html_e( 'Formas de recebimento', 'wp-travel-engine' ); ?></h3>

    </div>   
                <?php

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://api.traveltec.com.br/serv/pagamento/listar_dados",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "postman-token: 82934c35-3bd1-7c2d-4c2e-53571acfe5fc",
                        "url: ".$_SERVER['HTTP_HOST']
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
    
            <div class="wpte-checkbox">

                <div class="wpte-checkbox-wrap">

                <input type="radio" id="pre_reserva" class="" name="payment_form" value="<?=($message['status'] == 0 ? 0 : 1)?>" onclick="esconder_dados_pagseguro()" <?=($message['status'] == 0 ? 'checked' : '')?>>

                    <label for="pre_reserva"></label>

                </div>

                <label class="wpte-field-label" for="pre_reserva" style="margin-top: -12px">Pré reserva</label>

            </div>

            
            <div class="wpte-checkbox">

                <div class="wpte-checkbox-wrap">

                <input type="radio" id="pagseguro" class="paypal-payment" name="payment_form" value="<?=($message['status'] == 1 ? 1 : 0)?>" onclick="ver_dados_pagseguro()" <?=($message['status'] == 1 ? 'checked' : '')?>>

                    <label for="pagseguro"></label>

                </div>

                <label class="wpte-field-label" for="pagseguro" style="margin-top: -12px">PagSeguro</label>

            </div> 

            <div class="wpte-checkbox" style="width: 64%;border: 1px solid #ddd;padding: 28px; <?=($message['status'] == 1 ? 'display:block' : 'display:none')?>" id="dados_conta">


                <div class="wpte-field wpte-text wpte-floated">

                    <label for="wp_travel_engine_settings[book_btn_txt]" class="wpte-field-label" style="font-weight: 500"><?php _e('E-mail da conta','wp-travel-engine');?></label>

                    <input type="text" id="e_mail_conta" name="e_mail_conta" value="<?=$message['email']?>" placeholder="E-mail da conta"> 

                </div>

                <div class="wpte-field wpte-text wpte-floated" >

                    <label for="wp_travel_engine_settings[book_btn_txt]" class="wpte-field-label" style="font-weight: 500"><?php _e('Token da conta','wp-travel-engine');?></label>

                    <input type="text" id="token_conta" name="token_conta" value="<?=$message['token']?>" placeholder="Token da conta"> 

                </div>

            </div> 

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
    function save_dados_payment(){
        var email = $("#e_mail_conta").val(); 
        var token = $("#token_conta").val(); 
        var status = 0;
        if ($("#pagseguro").is(':checked')) {
            status = 1;
        }
        console.log(email);
        var settings = {
          "async": true,
          "crossDomain": true,
          "url": "https://api.traveltec.com.br/serv/pagamento/inserir_dados",
          "method": "POST",
          "headers": {
            "url": "<?=$_SERVER['HTTP_HOST']?>",
            "email": email,
            "token": token,
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