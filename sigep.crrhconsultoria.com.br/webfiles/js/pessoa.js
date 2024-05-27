$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#pessoas_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    Loading();
    $.redirect("/pessoa/add");
});

$('#close').on('click', function () {
    Loading();
    $.redirect("/pessoa");
});

$("#save").click(function () {
    test = true;
    var campos = "";

    test = (!validade("#txtNome") ? false : test);
    campos += (!validade("#txtNome") ? "Nome\n" : "");
    test = (!validade("#txtDataNascimento") ? false : test);
    campos += (!validade("#txtDataNascimento") ? "Data Nascimento\n" : "");
    test = (!validade("#selCidadeNasc") ? false : test);
    campos += (!validade("#selCidadeNasc") ? "Cidade Nascimento\n" : "");
    test = (!validade("#txtNomeMae") ? false : test);
    campos += (!validade("#txtNomeMae") ? "Nome Mãe\n" : "");
    test = (!validade("#txtIndentidade") ? false : test);
    campos += (!validade("#txtIndentidade") ? "Identidade\n" : "");
    test = (!validade("#txtCPF") ? false : test);
    campos += (!validade("#txtCPF") ? "CPF\n" : "");
    test = (!TestaCPF($("#txtCPF").val()) ? false : test);
    campos += (!TestaCPF($("#txtCPF").val()) ? "CPF Inválido\n" : "");
    test = (!validade("#txtCEP") ? false : test);
    campos += (!validade("#txtCEP") ? "CEP\n" : "");
    test = (!validade("#txtEndereco") ? false : test);
    campos += (!validade("#txtEndereco") ? "Endereço\n" : "");
    test = (!validade("#txtNumero") ? false : test);
    campos += (!validade("#txtNumero") ? "Número\n" : "");
    test = (!validade("#txtBairro") ? false : test);
    campos += (!validade("#txtBairro") ? "Bairro\n" : "");
    test = (!validade("#selCidade") ? false : test);
    campos += (!validade("#selCidade") ? "Bairro\n" : "");

    if (test) {
        Loading();
        $.ajax({
            url: '/pessoa/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'nome': $('#txtNome').val(),
                'dt_nascimento': $('#txtDataNascimento').val(),
                'sexo': $('#selSexo').val(),
                'uf_nascimento': $('#selUfNasc').val(),
                'naturalidade': $('#selCidadeNasc').val(),
                'grau_instrucao': $('#selGrauInst').val(),
                'estado_civil': $('#selEstadoCivil').val(),
                'nome_pai': $('#txtNomePai').val(),
                'nome_mae': $('#txtNomeMae').val(),
                'identidade': $('#txtIndentidade').val(),
                'orgao_emissao': $('#txtOrgEmissor').val(),
                'uf_emissao': $('#selUfEmissao').val(),
                'dt_emissao': $('#txtDataIdentidade').val(),
                'cpf': $('#txtCPF').val(),
                'cartao_sus': $('#txtCartSaude').val(),
                'nis': $('#txtNis').val(),
                'cep': $('#txtCEP').val(),
                'endereco': $('#txtEndereco').val(),
                'numero': $('#txtNumero').val(),
                'bairro': $('#txtBairro').val(),
                'estado': $('#selEstado').val(),
                'cidade': $('#selCidade').val(),
                'fone1': $('#txtFone1').val(),
                'fone2': $('#txtFone2').val(),
                'email': $('#txtEmail').val(),
                'complemento': $('#txtComplemento').val(),
                'cutis': $('#selCutis').val(),
                'cabelo': $('#selCabelo').val(),
                'olhos': $('#selOlhos').val(),
                'estatura': $('#txtEstatura').val(),
                'peso': $('#txtPeso').val(),
                'fator_rh': $('#selFatRH').val(),
                'sangue': $('#selSangue').val(),
                'port_especial': $("input[name='radPortNes']:checked").val(),
                'doador': $("input[name='radDoador']:checked").val(),
                'certidao': $('#selCerti').val(),
                'dt_certidao': $('#txtDataCertidao').val(),
                'termo': $('#txtTermo').val(),
                'livro': $('#txtLivro').val(),
                'folha': $('#txtFolha').val(),
                'cartorio': $('#txtCartorio').val(),
                'uf_certidao': $('#selUfCert').val(),
                'cidade_certidao': $('#selCityCert').val(),
                'observacao': $('#txtObservacao').val()
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);
            (retorno["status"] ? ModalSucesso("/pessoa") : ModalErro(retorno["message"]));
        });
    } else {
        ModalFaltaCadastro(campos);
    }

});

$("#edit").click(function () {
    test = true;
    var campos = "";

    test = (!validade("#txtNome") ? false : test);
    campos += (!validade("#txtNome") ? "Nome\n" : "");
    test = (!validade("#txtDataNascimento") ? false : test);
    campos += (!validade("#txtDataNascimento") ? "Data Nascimento\n" : "");
    test = (!validade("#selCidadeNasc") ? false : test);
    campos += (!validade("#selCidadeNasc") ? "Cidade Nascimento\n" : "");
    test = (!validade("#txtNomeMae") ? false : test);
    campos += (!validade("#txtNomeMae") ? "Nome Mãe\n" : "");
    test = (!validade("#txtIndentidade") ? false : test);
    campos += (!validade("#txtIndentidade") ? "Identidade\n" : "");
    test = (!validade("#txtCPF") ? false : test);
    campos += (!validade("#txtCPF") ? "CPF\n" : "");
    test = (!TestaCPF($("#txtCPF").val()) ? false : test);
    campos += (!TestaCPF($("#txtCPF").val()) ? "CPF Inválido\n" : "");
    test = (!validade("#txtCEP") ? false : test);
    campos += (!validade("#txtCEP") ? "CEP\n" : "");
    test = (!validade("#txtEndereco") ? false : test);
    campos += (!validade("#txtEndereco") ? "Endereço\n" : "");
    test = (!validade("#txtNumero") ? false : test);
    campos += (!validade("#txtNumero") ? "Número\n" : "");
    test = (!validade("#txtBairro") ? false : test);
    campos += (!validade("#txtBairro") ? "Bairro\n" : "");
    test = (!validade("#selCidade") ? false : test);
    campos += (!validade("#selCidade") ? "Bairro\n" : "");

    if (test) {
        Loading();
        $.ajax({
            url: '/pessoa/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'status': $("input[name='radStatus']:checked").val(),
                'nome': $('#txtNome').val(),
                'dt_nascimento': $('#txtDataNascimento').val(),
                'sexo': $('#selSexo').val(),
                'uf_nascimento': $('#selUfNasc').val(),
                'naturalidade': $('#selCidadeNasc').val(),
                'grau_instrucao': $('#selGrauInst').val(),
                'estado_civil': $('#selEstadoCivil').val(),
                'nome_pai': $('#txtNomePai').val(),
                'nome_mae': $('#txtNomeMae').val(),
                'identidade': $('#txtIndentidade').val(),
                'orgao_emissao': $('#txtOrgEmissor').val(),
                'uf_emissao': $('#selUfEmissao').val(),
                'dt_emissao': $('#txtDataIdentidade').val(),
                'cpf': $('#txtCPF').val(),
                'cartao_sus': $('#txtCartSaude').val(),
                'nis': $('#txtNis').val(),
                'cep': $('#txtCEP').val(),
                'endereco': $('#txtEndereco').val(),
                'numero': $('#txtNumero').val(),
                'bairro': $('#txtBairro').val(),
                'estado': $('#selEstado').val(),
                'cidade': $('#selCidade').val(),
                'fone1': $('#txtFone1').val(),
                'fone2': $('#txtFone2').val(),
                'email': $('#txtEmail').val(),
                'complemento': $('#txtComplemento').val(),
                'cutis': $('#selCutis').val(),
                'cabelo': $('#selCabelo').val(),
                'olhos': $('#selOlhos').val(),
                'estatura': $('#txtEstatura').val(),
                'peso': $('#txtPeso').val(),
                'fator_rh': $('#selFatRH').val(),
                'sangue': $('#selSangue').val(),
                'port_especial': $("input[name='radPortNes']:checked").val(),
                'doador': $("input[name='radDoador']:checked").val(),
                'certidao': $('#selCerti').val(),
                'dt_certidao': $('#txtDataCertidao').val(),
                'termo': $('#txtTermo').val(),
                'livro': $('#txtLivro').val(),
                'folha': $('#txtFolha').val(),
                'cartorio': $('#txtCartorio').val(),
                'uf_certidao': $('#selUfCert').val(),
                'cidade_certidao': $('#selCityCert').val(),
                'observacao': $('#txtObservacao').val()
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);
            (retorno["status"] ? ModalSucesso("/pessoa") : ModalErro(retorno["message"]));
        });
    } else {
        ModalFaltaCadastro(campos);
    }

});

function edita(id) {
    Loading();
    $.redirect("/pessoa/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    Loading();
    $.ajax({
        url: '/pessoa/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        var retorno = JSON.parse(data);
        (retorno["status"] ? ModalSucesso("/pessoa") : ModalErro(retorno["message"]));
    });
});

$('#btnDataNasci').click(function () {
    $('#txtDataNascimento').datepicker("show");
});

$('#btnDataIdenti').click(function () {
    $('#txtDataIdentidade').datepicker("show");
});

$('#btnDataCerti').click(function () {
    $('#txtDataCertidao').datepicker("show");
});