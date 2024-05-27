var idc;
var contrato_id;

$("#menu_6").addClass("active");

$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#empresas_listas').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
    $('#empresas_listas_os').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
    $('#empresas_listas_boletos').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$("#save").click(function () {
    test = true;
    if (!validade($("#selTipoNegocio"))) {
        test = false;
    }
    if (!validade($("#txtName"))) {
        test = false;
    }
    if (!validade($("#txtSocial_razon"))) {
        test = false;
    }
    if (!validade($("#txtAddress"))) {
        test = false;
    }
    if (!validade($("#txtDistrict"))) {
        test = false;
    }
    if (!validade($("#selCity"))) {
        test = false;
    }
    if (!validade($("#txtNumber"))) {
        test = false;
    }
    if (!validade($("#selProvincy"))) {
        test = false;
    }
    if (!validade($("#txtCEP"))) {
        test = false;
    }
    if (!validade($("#txtPhone1"))) {
        test = false;
    }

    if ($("#selNature").val() === "1") {
        $("#txtCPF").val("");
        if (!TestaCNPJ($("#txtCNPJ").val())) {
            test = false;
            $("#txtCNPJ").css('background', '#0F0');
        } else {
            $("#txtCNPJ").css('background', '#FFF');
        }
    } else {
        $("#txtCNPJ").val("");
        if (!TestaCPF($("#txtCPF").val())) {
            test = false;
            $("#txtCPF").css('background', '#0F0');
        } else {
            $("#txtCPF").css('background', '#FFF');
        }
    }
    if (!TestaCep($("#txtCEP").val())) {
        test = false;
        $("#txtCEP").css('background', '#0F0');
    } else {
        $("#txtCEP").css('background', '#FFF');
    }
    if ($("#txtEmail").val() !== "") {
        if (!TestaEmail($("#txtEmail").val())) {
            test = false;
            $("#txtEmail").css('background', '#0F0');
        } else {
            $("#txtEmail").css('background', '#FFF');
        }
    }

    test = (!validade("#txtQtdeFuncionarios") ? false : test);

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/clientes/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'tipo_negocio': $('#selTipoNegocio').val(),
                'name': $('#txtName').val(),
                'social_razon': $('#txtSocial_razon').val(),
                'activity': $('#txtActivity').val(),
                'code_moni': $('#txtCodeMoni').val(),
                'nature': $('#selNature').val(),
                'cnpj': $('#txtCNPJ').val(),
                'cpf': $('#txtCPF').val(),
                'inss': $('#txtINSS').val(),
                'ie': $('#txtIE').val(),
                'im': $('#txtIM').val(),
                'cnae': $('#txtCNAE').val(),
                'address': $('#txtAddress').val(),
                'district': $('#txtDistrict').val(),
                'provincy': $('#selProvincy').val(),
                'pobox': $('#txtPOBox').val(),
                'number': $('#txtNumber').val(),
                'city': $('#selCity').val(),
                'cep': $('#txtCEP').val(),
                'complement': $('#txtComplement').val(),
                'phone1': $('#txtPhone1').val(),
                'phone2': $('#txtPhone2').val(),
                'phone3': $('#txtPhone3').val(),
                'ramal1': $('#txtRamal1').val(),
                'ramal2': $('#txtRamal2').val(),
                'ramal3': $('#txtRamal3').val(),
                'email': $('#txtEmail').val(),
                'site': $('#txtSite').val(),
                'observacao': $('#txtObservacao').val(),
                'pt_reference': $('#txtPtReference').val(),
                'qtde_funcionarios': $("#txtQtdeFuncionarios").val()
            }
        }).done(function (data) {
            if (data) {
                ModalSucesso("/clientes/empresas");
            } else {
                ModalJaExisteCadastro();
            }
        });
        $('#close').button('reset');
        $('#save').button('reset');
    } else {
        ModalFaltaCadastro();
    }

});

$("#edit").click(function () {
    test = true;
    if (!validade($("#selTipoNegocio"))) {
        test = false;
    }
    if (!validade($("#txtName"))) {
        test = false;
    }
    if (!validade($("#txtSocial_razon"))) {
        test = false;
    }
    if (!validade($("#txtAddress"))) {
        test = false;
    }
    if (!validade($("#txtDistrict"))) {
        test = false;
    }
    if (!validade($("#selCity"))) {
        test = false;
    }
    if (!validade($("#txtNumber"))) {
        test = false;
    }
    if (!validade($("#selProvincy"))) {
        test = false;
    }
    if (!validade($("#txtCEP"))) {
        test = false;
    }
    if (!validade($("#txtPhone1"))) {
        test = false;
    }

    test = (!validade("#txtQtdeFuncionarios") ? false : test);

    if ($("#selNature").val() === "1") {
        $("#txtCPF").val("");
        if (!TestaCNPJ($("#txtCNPJ").val())) {
            test = false;
            $("#txtCNPJ").css('background', '#0F0');
        } else {
            $("#txtCNPJ").css('background', '#FFF');
        }
    } else {
        $("#txtCNPJ").val("");
        if (!TestaCPF($("#txtCPF").val())) {
            test = false;
            $("#txtCPF").css('background', '#0F0');
        } else {
            $("#txtCPF").css('background', '#FFF');
        }
    }

    if ($("#txtEmail").val() !== "") {
        if (!TestaEmail($("#txtEmail").val())) {
            test = false;
            $("#txtEmail").css('background', '#0F0');
        } else {
            $("#txtEmail").css('background', '#FFF');
        }
    }

    if (!TestaCep($("#txtCEP").val())) {
        test = false;
        $("#txtCEP").css('background', '#0F0');
    } else {
        $("#txtCEP").css('background', '#FFF');
    }

    if (test) {
        $('#close').button('loading');
        $('#edit').button('loading');
        $.ajax({
            url: '/clientes/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'tipo_negocio': $('#selTipoNegocio').val(),
                'name': $('#txtName').val(),
                'social_razon': $('#txtSocial_razon').val(),
                'code_moni': $('#txtCodeMoni').val(),
                'activity': $('#txtActivity').val(),
                'nature': $('#selNature').val(),
                'cnpj': $('#txtCNPJ').val(),
                'cpf': $('#txtCPF').val(),
                'inss': $('#txtINSS').val(),
                'ie': $('#txtIE').val(),
                'im': $('#txtIM').val(),
                'cnae': $('#txtCNAE').val(),
                'address': $('#txtAddress').val(),
                'district': $('#txtDistrict').val(),
                'provincy': $('#selProvincy').val(),
                'pobox': $('#txtPOBox').val(),
                'number': $('#txtNumber').val(),
                'city': $('#selCity').val(),
                'cep': $('#txtCEP').val(),
                'complement': $('#txtComplement').val(),
                'phone1': $('#txtPhone1').val(),
                'phone2': $('#txtPhone2').val(),
                'phone3': $('#txtPhone3').val(),
                'ramal1': $('#txtRamal1').val(),
                'ramal2': $('#txtRamal2').val(),
                'ramal3': $('#txtRamal3').val(),
                'email': $('#txtEmail').val(),
                'site': $('#txtSite').val(),
                'status': $("input[name='radStatus']:checked").val(),
                'observacao': $('#txtObservacao').val(),
                'pt_reference': $('#txtPtReference').val(),
                'qtde_funcionarios': $("#txtQtdeFuncionarios").val()
            }
        }).done(function (data) {
            if (data) {
                ModalSucesso("/clientes/empresas");
            } else {
                ModalJaExisteCadastro();
            }
        });
        $('#close').button('reset');
        $('#edit').button('reset');
    } else {
        ModalFaltaCadastro();
    }


});

$('#add').on('click', function () {
    var $btn = $(this).button('loading');
    $('#close').button('loading');
    window.location.replace("/clientes/add");

});

$("#selNature").change(function () {
    if ($("#selNature").val() === "1") {

        $("#txtCPF").hide();
        $("#txtCPF").attr('required', false);
        $("#txtCNPJ").show();
        $("#txtCNPJ").attr('required', true);
        $("#txtINSS").attr("disabled", true);
        $("#txtCNAE").attr('disabled', false);
        $("#txtCNAE").attr('required', true);
    } else {
        $("#txtCNPJ").hide();
        $("#txtCNPJ").attr('required', false);
        $("#txtCPF").show();
        $("#txtCPF").attr('required', true);
        $("#txtINSS").attr("disabled", false);
        $("#txtCNAE").attr('required', false);
        $("#txtCNAE").attr('disabled', true);
    }
}).change();

$('#close').on('click', function () {
    var $btn = $(this).button('loading');
    $('#save').button('loading');
    window.location.replace("/clientes/empresas");
});

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/clientes/remove',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/clientes/empresas");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});

function edita(id) {
    $.redirect("/clientes/edit/", {'id': id});
}

function usuarios(id) {
    $.redirect("/clientes/usuarios/", {'id': id});
}

function show(id) {
    $.redirect("/clientes/show/", {'id': id});
}

$("#remove_cliente").click(function () {
    remove($('#txtID').val());
});

function preview_os(id) {
    $.redirect("/os/preview", {'id': id});
}

function add_contrato() {
    $("#lista_contrato").modal({backdrop: "static"});
}

function consultaCNPJ(cnpj) {
    var s = document.createElement("script");

    numero = $(cnpj).val().replace("/", "");
    numero = numero.replace(".", "");
    numero = numero.replace(".", "");
    numero = numero.replace("-", "");
//view-source:https://www.infoplex.com.br/api/v1/perfil/16104890000111.json
    s.src = "https://www.receitaws.com.br/v1/cnpj/" + numero + "?callback=myDisplayFunction";
    document.body.appendChild(s);
}

function myDisplayFunction(myObj) {

    $("#txtCNAE").val(myObj.atividade_principal[0].code);
    $('#txtSocial_razon').val(myObj.nome);
    $('#txtActivity').val(myObj.atividade_principal[0].text);
    $('#txtAddress').val(myObj.logradouro);
    $('#txtNumber').val(myObj.numero);
    $('#txtDistrict').val(myObj.bairro);


    $("#selProvincy").val(myObj.uf).change();
    $("#selCity").val(myObj.municipio).change();

    $('#txtCEP').val(myObj.cep);
    $('#txtPhone1').val(myObj.telefone);
    $('#txtEmail').val(myObj.email);


}

function btnEmitirContrato(id) {
    var contrato_id = $("input[name='radContrato']:checked").val();
    if (contrato_id) {
        $('#btnEdita').button('loading');
        $('#close').button('loading');
        $('#remove_cliente').button('loading');
        $('#add_contrato').button('loading');
        $("#loading").modal({backdrop: "static"})

        $.ajax({
            url: '/clientes/adicionar_contrato',
            type: 'post',
            dataType: 'html',
            data: {
                'contrato': contrato_id,
                'cliente': id,
                'data_emissao': $('#txtDataContrato').val()
            }
        }).done(function (data) {
            $("#lista_contrato").modal("hide");
            $("#loading").modal("hide");
            $('#btnEdita').button('reset');
            $('#close').button('reset');
            $('#remove_cliente').button('reset');
            $('#add_contrato').button('reset');
//            $.redirect("/contratos_pdf/" + data);
        });
    }
}

function remove_contrato(id_contrato) {
    $('#btnEdita').button('loading');
    $('#close').button('loading');
    $('#remove_cliente').button('loading');
    $('#add_contrato').button('loading');
    $("#loading").modal({backdrop: "static"})

    $.ajax({
        url: '/clientes/remover_contrato',
        type: 'post',
        dataType: 'html',
        data: {
            'id_contrato': id_contrato,
        }
    }).done(function (data) {
        $("#lista_contrato").modal("hide");
        $("#loading").modal("hide");
        $('#btnEdita').button('reset');
        $('#close').button('reset');
        $('#remove_cliente').button('reset');
        $('#add_contrato').button('reset');

        ModalSucesso("/clientes/show", $('#txtID').val());
    });
}

function upload_contrato(id_contrato) {
    contrato_id = id_contrato;
    $("#txtIdContrato").val(id_contrato);
    $("#m_upload_contrato").modal({backdrop: "static"});
}

function envia_contrato() {
    var upload_contrato = $('#upload_contrato').val();
    if (upload_contrato) {

        $('#btnEdita').button('loading');
        $('#close').button('loading');
        $('#remove_cliente').button('loading');
        $('#add_contrato').button('loading');
        $("#loading").modal({backdrop: "static"})

        var formData = new FormData();
        formData.append('id_contrato', $('#txtIdContrato').val());
        formData.append('contrato', $('#upload_contrato')[0].files[0]);
        $.ajax({
            url: '/clientes/adicionar_contrato_assinado',
            type: 'post',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (data) {
            $("#m_upload_contrato").modal("hide");
            $("#lista_contrato").modal("hide");
            $("#loading").modal("hide");
            $('#btnEdita').button('reset');
            $('#close').button('reset');
            $('#remove_cliente').button('reset');
            $('#add_contrato').button('reset');


        });

    }

}


