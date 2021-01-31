function bandeiraCartao(tipo) {
    var bandeira_cartao = $("#bandeira_cartao").val();
    var result = $('#numero_do_cartao').validateCreditCard()
    if (!result.valid) {
        Swal.fire({
            title: "Ops!",
            text: "Cartão inválido",
            icon: "error",
        });

        $("#numero_do_cartao").val("");
    } else {

        if (!$("#bandeiras_permitidas").val().includes(result.card_type.name)) {
            $("#numero_do_cartao").val("");
            return Swal.fire({
                title: "Ops!",
                text: "Cartão não aceito pela operadora",
                icon: "error",
            });
        } 
        switch (result.card_type.name) {
            case 'mastercard':
                $(".iconeCards").each(function () {
                    $(this).addClass("ocultarDisplay")
                })
                $("#iconeMaster").removeClass("ocultarDisplay").addClass("exibe");
                $("#numeroSeg").mask("000").attr('maxlength', 3);
                $("#bandeira_cartao").val("CA");
                $(".svg-inline--fa.fa-w-18").attr("style", "display:none");
                break;
            case 'visa':
                $(".iconeCards").each(function () {
                    $(this).addClass("ocultarDisplay")
                })
                $("#iconeVisa").removeClass("ocultarDisplay");
                $("#numeroSeg").mask("000").attr('maxlength', 3);
                $("#bandeira_cartao").val("VI");
                $(".svg-inline--fa.fa-w-18").attr("style", "display:none");
                break;
            case 'amex':
                $(".iconeCards").each(function () {
                    $(this).addClass("ocultarDisplay")
                })
                $("#iconeAmex").removeClass("ocultarDisplay");
                $("#numeroSeg").mask("0000").attr('maxlength', 4);
                $("#numeroSeg").attr('placeholder', 'XXXX');
                $("#numeroSeg2").attr('placeholder', 'XXXX');
                $(".svg-inline--fa.fa-w-18").attr("style", "display:none");

                $("#bandeira_cartao").val("AX");
                break;

            default:
                break;
        }
    }
}



function ValidarDadoValidadeCartao() {
    var validade = $("#validadeCartao").val();
    var validar = validade.split("/");

    var d = new Date();

    var anoVal = "20" + validar[1];
    var anoAtual = d.getFullYear();

    var mesVal = validar[0];
    var mesAtual = d.getMonth();

    if (anoVal == anoAtual && mesVal < mesAtual) {
        Swal.fire({
            text: "Confira a validade do cartão. A validade informada apresenta um cartão expirado."
        });
    } else if (anoVal < anoAtual) {
        Swal.fire({
            text: "Confira a validade do cartão. A validade informada apresenta um cartão expirado."
        });
    } else {

    }
}

function buscarCEP() {
    var cep = $("#cep")
        .val()
        .replace(/[^\d]+/g, "");
    //Verifica se campo cep possui valor informado.
    if (cep != "") {
        //ExpressÃ£o regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP. 
            //Preenche os campos com "..." enquanto consulta webservice.
            $(".endereco").val("...");
            $(".cidade").val("...");
            $("#bairro").val("...");
            $("#estado").val("...");

            //Consulta o webservice viacep.com.br/
            var settings = {
                async: true,
                crossDomain: true,
                url: "https://apps.widenet.com.br/busca-cep/api/cep.json?code=" + cep,
                method: "GET",
                headers: {
                    "cache-control": "no-cache",
                    "postman-token": "fb12c81c-5382-030a-8fde-f9955168d2d2",
                },
            };

            $.ajax(settings).done(function (response) {
                var resposta = JSON.parse(JSON.stringify({ response }));

                if (resposta["response"]["status"] == 200) {
                    //Atualiza os campos com os valores da consulta.
                    $(".endereco").val(resposta["response"]["address"]);
                    $(".endereco").focus();
                    $("#bairro").val(resposta["response"]["district"]);
                    $("#bairro").focus();
                    $(".cidade").val(resposta["response"]["city"]);
                    $(".cidade").focus();
                    $("#estado").val(resposta["response"]["state"]);
                    $("#estado").focus();
                    $("#numero").focus()
                } //end if.
                else {
                    //CEP pesquisado nÃ£o foi encontrado.
                    Swal.fire({text: "CEP não encontrado.", icon: "warning"});
                }
            }); 
    } //end if.
}