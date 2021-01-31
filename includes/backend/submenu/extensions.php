<?php
/**
 * Extentions EDD Fetcah products showcase
 */
// Get addons data from marketplace.
ini_set("display_errors", 0);
$addons_data = get_transient( 'wp_travel_engine_store_addons_list' );

if ( ! $addons_data ) {
    $addons_data = wp_safe_remote_get( WP_TRAVEL_ENGINE_STORE_URL . '/edd-api/v2/products/?category=add-ons&number=-1' );

    if( is_wp_error( $addons_data ) )
        return;

    $addons_data = wp_remote_retrieve_body( $addons_data );
    set_transient( 'wp_travel_engine_store_addons_list', $addons_data, 48 * HOUR_IN_SECONDS );
}

if ( ! empty( $addons_data ) ) :

    $addons_data = json_decode( $addons_data );
    $addons_data = $addons_data->products;

endif;
?>
<div class="wrap" id="wpte-add-ons"> 
    <h1 class="wp-heading-inline"><?php _e('Gerenciador de roteiros','wp-travel-engine');?> <a class="page-title-action" id="" target=""><?php _e('Importar roteiros','wp-travel-engine');?></a> </h1> 

    <hr class="wp-header-end"> 
    <p style="margin-bottom: 0"><?php _e('Informe abaixo os dados necessários para realizar a importação de conteúdo de um operadora para o seu site.','wp-travel-engine');?></p>
    <div id="tab_container" style="width: 100%"> 
 
        <div class="wpte-extension" style="width: 50%;max-width: 50%;">
            <div class="inner-wrap"> 
                <h3 class="wpte-extension-title" style="margin-bottom: 0">URL</h3>  
                <p style="margin-top: 0">
                    <small style="font-style: italic;">Insira o domínio que irá receber os roteiros.</small>
                    <br>
                    <input type="text" name="url_import" size="150" value="" id="url_import" spellcheck="true" autocomplete="off" placeholder="ex. www.site.com.br" style="width: 100%">
                </p>

                <h3 class="wpte-extension-title" style="margin-bottom: 0">Usuário</h3>  
                <p style="margin-top: 0">
                    <small style="font-style: italic;">Insira o usuário que você utiliza para acessar o painel Wordpress do site.</small>
                    <br>
                    <input type="text" name="user_import" size="150" value="" id="user_import" spellcheck="true" autocomplete="off" placeholder="userlogin" style="width: 100%">
                </p>

                <h3 class="wpte-extension-title" style="margin-bottom: 0">Senha</h3>  
                <p style="margin-top: 0">
                    <small style="font-style: italic;">Insira a senha que você utiliza para acessar o painel Wordpress do site.</small>
                    <br>
                    <input type="password" name="pass_import" size="150" value="" id="pass_import" spellcheck="true" autocomplete="off" placeholder="*********" style="width: 100%">
                </p>
                <a onclick="salvar_dados_import()" title="" class="button-secondary" target="_blank" style="margin-bottom: 0"><?php _e('Salvar dados','wp-travel-engine');?></a>
            </div>
        </div>  

        <div class="wpte-extension" style="width: 50%;margin-bottom: 20px;max-width: 50%;">
                                <div class="inner-wrap"> 
        <?php

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://api.traveltec.com.br/serv/roteiros/fornecedores",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "GET",
                      CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache", 
                        "content-type: application/json" 
                      ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                      echo "cURL Error #:" . $err;
                    } else {
                        $dados = json_decode($response, true);

                        $fornecedores = $dados["message"];
                    }

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://api.traveltec.com.br/serv/roteiros/fornecedores_user",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "GET",
                      CURLOPT_HTTPHEADER => array(
                        "url: ".$_SERVER['HTTP_HOST'],
                        "cache-control: no-cache", 
                        "content-type: application/json" 
                      ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                      echo "cURL Error #:" . $err;
                    } else {
                        $dados = json_decode($response, true);

                        $fornecedores_user = $dados["message"];  
                    }
                ?>

                    <?php 
                        $var_ativ = [];
                        $var_desativ = [];
                                    $var_button_ativ = [];
                                    $var_button_import = [];
                        for ($i=0; $i < count($fornecedores); $i++) { 

                            if ($fornecedores[$i]["Nome"] == 'Freeway') { 

                            for ($x=0; $x < count($fornecedores_user); $x++) { 
                                if ($fornecedores_user[$x]["id_fornecedor"] == $fornecedores[$i]["id_fornecedor"]) {
                                    $var_ativ[$i] = '<span style="font-size:14px;margin-bottom:0px;color:green;margin-top: 0;position: relative;margin-right: 35px;margin-left: 35px;"><i class="fa fa-check"></i> Ativado</span>';
                                    $var_desativ[$i] = '<a onclick="del_fornecedor(\''.$i.'\', \''.$_SERVER['HTTP_HOST'].'\', \''.$fornecedores[$i]["id_fornecedor"].'\')" style="cursor:pointer"><span style="font-size:14px;margin-bottom:0px;color:black;margin-top: 0;"><i class="fa fa-close"></i> Desativar</span></a>';
                                    $var_button_ativ[$i] = 'display:none !important';
                                    $var_button_import[$i] = 'display:block !important';
                                } 
                            }
                    ?>   
                                    <h3 class="wpte-extension-title"><?=$fornecedores[$i]["Nome"]?> <?=$var_ativ[$i] ?>  <?=$var_desativ[$i] ?> <a id="button_toggle<?=$i?>" title="" class="button-secondary" target="_blank" style="margin-bottom: 0;float: right;margin-top: 0;display:block;<?=$var_button_ativ[$i]?>"><?php _e('Ativar','wp-travel-engine');?></a> <a onclick="import_dados('<?=$i?>', '<?=$_SERVER['HTTP_HOST']?>', '<?=$fornecedores[$i]["cnpj"]?>')" id="button_import<?=$i?>" title="" class="button-secondary" target="_blank" style="margin-bottom: 0;float: right;margin-top: 0;display:none;<?=$var_button_import[$i]?>"><?php _e('Importar','wp-travel-engine');?></a> </h3>  
                                    <p><strong>CNPJ: </strong><?=$fornecedores[$i]["cnpj"]?></p>
                                    <div class="paragraph<?=$i?>" style="border-top: 2px solid #ddd;border-bottom: 2px solid #ddd;padding: 22px; display: none">
                                        <input type="text" name="" id="key_fornecedor<?=$i?>" style="width: 294px" placeholder="Insira a chave de acesso para o fornecedor"> 
                                        <a onclick="ativ_fornecedor('<?=$i?>', '<?=$_SERVER['HTTP_HOST']?>', '<?=$fornecedores[$i]["cnpj"]?>')" id="button_save<?=$i?>" title="" class="button-secondary" target="_blank" style="margin-bottom: 0;/* float: right; */margin-top: -8px;/* display:none */"><?php _e('Salvar','wp-travel-engine');?></a>
                                    </div>
                                    
                    <?php } } ?>  
                                </div>
                            </div>

        <div class="clear"></div> 
    </div> 
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

<?php 
    for ($i=0; $i < 6; $i++) {  
?>
<script type="text/javascript">
    $("#button_toggle<?=$i?>").click(function(){
        $(".paragraph<?=$i?>").toggle(500);
      });
</script>
<?php } ?>

<script type="text/javascript">
    function salvar_dados_import(){
        var url = $("#url_import").val();
        var user = $("#user_import").val();
        var pass = $("#pass_import").val();

        var dado_import = JSON.stringify({ url: url, user: user, pass:pass });

        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "https://api.traveltec.com.br/serv/roteiros/user",
            "method": "POST",
            "headers": {
                "content-type": "application/json" 
            },
            "processData": false,
            "data": dado_import
        }

        $.ajax(settings).done(function (response) {
            if (response.errors) {
                return Swal.fire({
                    title: "Erro encontrado",
                    text: response.message,
                    icon: "error",
                });
                $("#url_import").val('');
                $("#user_import").val('');
                $("#pass_import").val('');
            }else{ 
                return Swal.fire({
                    title: "Dados cadastrados",
                    text: response.message,
                    icon: "success",
                });
                $("#url_import").val('');
                $("#user_import").val('');
                $("#pass_import").val('');
            }
        });
    }  

    function import_dados(i, url, cnpj){
        $("#button_import"+i).html('Aguarde... <img src="https://dev.traveltec.com.br/assets/images/loader.gif" style="height: 22px;width: 24px;">');

        var settings = {
          "async": true,
          "crossDomain": true,
          "url": "https://api.traveltec.com.br/serv/roteiros/import",
          "method": "PUT",
          "headers": {
            "content-type": "application/json",
            "cnpj": cnpj,
            "limit": "10",
            "url": url,
            "authorization": "Basic d3AwMm1vbnRlbmVncm86d3AwMm1vbnRlIzIwMTk=", 
          }
        }

        $.ajax(settings).done(function (response) {
                $("#button_import"+i).html('Importar');
            if (response.errors) {
                return Swal.fire({
                    title: "Erro encontrado",
                    text: response.message,
                    icon: "error",
                }).then((result) => {
  // Reload the Page
  location.reload();
});
            }else{ 
                return Swal.fire({
                    title: "Dados cadastrados",
                    text: response.message,
                    icon: "success",
                }).then((result) => {
  // Reload the Page
  location.reload();
});
            }
        });
    }

    function ativ_fornecedor(i, url, cnpj){
        var key = $("#key_fornecedor"+i).val(); 

        var settings = {
          "async": true,
          "crossDomain": true,
          "url": "https://api.traveltec.com.br/serv/roteiros/save",
          "method": "PUT",
          "headers": {
            "content-type": "application/json",
            "cnpj": cnpj, 
            "url": url,
            "key": key,
            "authorization": "Basic d3AwMm1vbnRlbmVncm86d3AwMm1vbnRlIzIwMTk=", 
          }
        }

        $.ajax(settings).done(function (response) { 
            if (response.errors) {
                return Swal.fire({
                    title: "Erro encontrado",
                    text: response.message,
                    icon: "error",
                }).then((result) => {
  // Reload the Page
  location.reload();
});
            }else{ 
                return Swal.fire({
                    title: "Dados cadastrados",
                    text: response.message,
                    icon: "success",
                }).then((result) => {
  // Reload the Page
  location.reload();
});
            }
        });
    }

    function del_fornecedor(i, url, id){ 
        var settings = {
          "async": true,
          "crossDomain": true,
          "url": "https://api.traveltec.com.br/serv/roteiros/delete",
          "method": "DELETE",
          "headers": {
            "content-type": "application/json", 
            "url": url,
            "key": id,
            "authorization": "Basic d3AwMm1vbnRlbmVncm86d3AwMm1vbnRlIzIwMTk=", 
          }
        }

        $.ajax(settings).done(function (response) { 
            if (response.errors) {
                return Swal.fire({
                    title: "Erro encontrado",
                    text: response.message,
                    icon: "error",
                }).then((result) => {
  // Reload the Page
  location.reload();
});

            }else{ 
                return Swal.fire({
                    title: "Fornecedor desativado",
                    text: response.message,
                    icon: "success",
                }).then((result) => {
  // Reload the Page
  location.reload();
});
            }
        });
    }
</script>
