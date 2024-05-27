var idc;

$('#add').on('click', function () {
    var $btn = $(this).button('loading');
    $('#close').button('loading');
    window.location.replace("/usuarios/add");
});

$('#close').on('click', function () {
    var $btn = $(this).button('loading');
    $('#save').button('loading');
    window.location.replace("/usuarios");
//    

});

$("#save").click(function () {
    test = true;
    if (!validade($("#txtNome"))) {
        test = false;
    }
    if (!validade($("#txtSenha"))) {
        test = false;
    }
    if (!validade($("#txtConfirmarSenha"))) {
        test = false;
    }

    if (!TestaEmail($("#txtEmail").val()) && $("#txtEmail").val() != "") {
        test = false;
        $("#txtEmail").css('background', '#0F0');
    } else {
        $("#txtEmail").css('background', '#FFF');
    }

    if ($("#txtSenha").val() != $("#txtConfirmarSenha").val()) {
        test = false;
        $("#txtSenha").css('background', '#0F0');
        $("#txtConfirmarSenha").css('background', '#0F0');
    } else {
        $("#txtSenha").css('background', '#FFF');
        $("#txtConfirmarSenha").css('background', '#FFF');
    }

    if (test) {
        var menu = {};
        $.each($('#frmSave').serializeArray(), function (i, field) {
            if (field.name.substring(0, 7) === "chkMenu") {
                menu[field.name.substring(7, 9999)] = field.name.substring(7, 9999);
            }

        });
        $("#loading").modal({backdrop: "static"});
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/usuarios/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'nome': $('#txtNome').val(),
                'email': $('#txtEmail').val(),
                'status': $("input[name='radStatus']:checked").val(),
                'restricao': $('#selRestricao').val(),
                'menu': menu,
                'senha': $("#txtSenha").val()
            }
        }).done(function (data) {
            if (data) {
                ModalSucesso("/usuarios");
            } else {
                $('#close').button('reset');
                $('#save').button('reset');
                $("#loading").modal("hide");
                ModalJaExisteCadastro();
            }

        });
    } else {
        ModalFaltaCadastro();
    }
});

$("#edit").click(function () {
    test = true;

    if (!validade($("#txtNome"))) {
        test = false;
    }
    if (!TestaEmail($("#txtEmail").val()) && $("#txtEmail").val() != "") {
        test = false;
        $("#txtEmail").css('background', '#0F0');
    } else {
        $("#txtEmail").css('background', '#FFF');
    }

    if ($("#txtSenha").val() != "") {
        if ($("#txtSenha").val() != $("#txtConfirmarSenha").val()) {
            test = false;
            $("#txtSenha").css('background', '#0F0');
            $("#txtConfirmarSenha").css('background', '#0F0');
        } else {
            $("#txtSenha").css('background', '#FFF');
            $("#txtConfirmarSenha").css('background', '#FFF');
        }
    }

    if (test) {
        var menu = {};
        $.each($('#frmSave').serializeArray(), function (i, field) {
            if (field.name.substring(0, 7) === "chkMenu") {
                menu[field.name.substring(7, 9999)] = field.name.substring(7, 9999);
            }
        });
        $('#close').button('loading');
        $('#save').button('loading');
        $("#loading").modal({backdrop: "static"});
        $.ajax({
            url: '/usuarios/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'nome': $('#txtNome').val(),
                'status': $("input[name='radStatus']:checked").val(),
                'email': $('#txtEmail').val(),
                'restricao': $('#selRestricao').val(),
                'menu': menu,
                'senha': $("#txtSenha").val()
            }
        }).done(function (data) {
            resultado = data;

            if (resultado) {
                window.location.replace("/usuarios");
            } else {
                $("#loading").modal("hide");
                $("#myModal2").modal({backdrop: "static"});
            }

        });
        $('#close').button('reset');
        $('#save').button('reset');
        $("#loading").modal("hide");
    }


});

$("#edit_passwd").click(function () {
    test = true;
    if (!validade($("#txtNome"))) {
        test = false;
    }

    if ($("#password").val() != $("#password1").val()) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#edit_passwd').button('loading');
        $("#loading").modal({backdrop: "static"});

        $.ajax({
            url: '/usuarios/altera_senha',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'nome': $('#txtNome').val(),
                'email': $('#txtEmail').val(),
                'password': $('#password').val()
            }
        }).done(function (data) {
            resultado = data;

            if (resultado) {
                window.location.replace("/login/sair");
            } else {
                $("#loading").modal("hide");
                $("#myModal2").modal({backdrop: "static"});
            }

        });

        $('#close').button('reset');
        $('#edit_passwd').button('reset');
        $("#loading").modal("hide");
    }

});
function edita(id) {
    $.redirect('/usuarios/edit/', {'id': id});
}

function editar() {

    $('#close').button('loading');
    $('#save').button('loading');
    $.ajax({
        url: '/usuarios/editar',
        type: 'post',
        dataType: 'html',
        data: {
            'id': $('#txtID').val(),
            'name': $('#txtNome').val(),
            'nome_abre': $('#txtNomeAbr').val(),
            'dt_nasc': $('#txtDTNasc').val(),
            'uf_nasc': $('#selUfNasc').val(),
            'grau_inst': $('#selGrauInst').val(),
            'nome_pai': $('#txtNomePai').val(),
            'sx': $('#selSexo').val(),
            'city_nasc': $('#selCityNasc').val(),
            'est_civil': $('#selEstCivil').val(),
            'nome_mae': $('#txtNomeMae').val(),
            'status': $("input[name='radStatus']:checked").val(),
            'identidade': $('#txtIndentidade').val(),
            'uf_emissao': $('#selUfEmissao').val(),
            'cpf': $('#txtCPF').val(),
            'org_emissor': $('#txtOrgEmissor').val(),
            'emissao_ide': $('#txtEmissaoIde').val(),
            'cart_n_saude': $('#txtCartNacSaude').val(),
            'endereco': $('#txtAddress').val(),
            'bairro': $('#txtDistrict').val(),
            'estado': $('#selProvincy').val(),
            'phone1': $('#txtPhone1').val(),
            'email': $('#txtEmail').val(),
            'number': $('#txtNumber').val(),
            'cidade': $('#selCity').val(),
            'cep': $('#txtCEP').val(),
            'phone2': $('#txtPhone2').val(),
            'complemento': $('#txtComplement').val(),
            'cutis': $('#selCutis').val(),
            'olhos': $('#selOlhos').val(),
            'peso': $('#txtPeso').val(),
            'sangue': $('#selSangue').val(),
            'doador': $('#radDoador').val(),
            'cabelo': $('#selCabelo').val(),
            'estatura': $('#txtEstatura').val(),
            'fator_rh': $('#selFatRH').val(),
            'necessi_espe': $('#radPortNes').val(),
            'certidao': $('#selCerti').val(),
            'termo': $('#txtTermo').val(),
            'folha': $('#txtFolha').val(),
            'uf_cert': $('#selUfCert').val(),
            'emiss_cert': $('#txtEmisCert').val(),
            'livro': $('#txtLivro').val(),
            'cartorio': $('#txtCartorio').val(),
            'cidade_certi': $('#selCityCert').val()

        }
    }).done(function (data) {
        resultado = data;
        if (resultado) {
            ModalSucesso("/usuarios");
        } else {
            $("#myModal2").modal({backdrop: "static"});
        }

    });
    $('#close').button('reset');
    $('#save').button('reset');
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/usuarios/remove',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/usuarios");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}