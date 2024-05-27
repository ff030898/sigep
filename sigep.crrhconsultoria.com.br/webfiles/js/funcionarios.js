$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#funcionarios_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    Loading();
    $.redirect("/funcionarios/add");
});

$('#close').on('click', function () {
    Loading();
    $.redirect("/funcionarios");
});

$("#save").click(function () {
    test = true;
    var campos = "";
    test = (!validade("#txtPessoa") ? false : test);
    campos += (!validade("#txtPessoa") ? "Pessoa\n" : "");
    test = (!validade("#txtDataAdmissao") ? false : test);
    campos += (!validade("#txtDataAdmissao") ? "Data Admissão\n" : "");
    test = (!validade("#selCargo") ? false : test);
    campos += (!validade("#selCargo") ? "Cargo\n" : "");
    test = (!validade("#selTipo_fun") ? false : test);
    campos += (!validade("#selTipo_fun") ? "Tipo Funcionário\n" : "");
    test = (!validade("#selCat_sal") ? false : test);
    campos += (!validade("#selCat_sal") ? "Categoria Salarial\n" : "");
    test = (!validade("#selJornada") ? false : test);
    campos += (!validade("#selJornada") ? "Jornada\n" : "");
    test = (!validade("#selTurno") ? false : test);
    campos += (!validade("#selTurno") ? "Turno\n" : "");
    test = (!validade("#txtHoraIni") ? false : test);
    campos += (!validade("#txtHoraIni") ? "Hora Início\n" : "");
    test = (!validade("#txtHoraFim") ? false : test);
    campos += (!validade("#txtHoraFim") ? "Hora Fim\n" : "");
    test = (!validade("#txtSalario") ? false : test);
    campos += (!validade("#txtSalario") ? "Salário\n" : "");

    if (test) {
        Loading();
        $.ajax({
            url: '/funcionarios/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'pessoa': $('#txtPessoa').val(),
                'cargo': $('#selCargo').val(),
                'data_admissao': $('#txtDataAdmissao').val(),
                'tipo_funcionario': $('#selTipo_fun').val(),
                'cat_salarial': $('#selCat_sal').val(),
                'jornada': $('#selJornada').val(),
                'turno': $('#selTurno').val(),
                'hora_ini': $('#txtHoraIni').val(),
                'hora_fim': $('#txtHoraFim').val(),
                'status': $("input[name='radStatus']:checked").val(),
                'ctps_num': $('#txtCtps_num').val(),
                'ctps_serie': $('#txtCtps_serie').val(),
                'ctps_uf': $('#selCtps_uf').val(),
                'ctps_data': $('#txtCtps_data').val(),
                'pis': $('#txtPis').val(),
                'salario': $('#txtSalario').val(),
                'observacao': $('#txtObservacao').val()
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);
            (retorno["status"] ? ModalSucesso("/funcionarios") : ModalErro(retorno["message"]));
        });
    } else {
        ModalFaltaCadastro(campos);
    }

});

$("#edit").click(function () {
    test = true;
    var campos = "";
    test = (!validade("#txtPessoa") ? false : test);
    campos += (!validade("#txtPessoa") ? "Pessoa\n" : "");
    test = (!validade("#txtDataAdmissao") ? false : test);
    campos += (!validade("#txtDataAdmissao") ? "Data Admissão\n" : "");
    test = (!validade("#selCargo") ? false : test);
    campos += (!validade("#selCargo") ? "Cargo\n" : "");
    test = (!validade("#selTipo_fun") ? false : test);
    campos += (!validade("#selTipo_fun") ? "Tipo Funcionário\n" : "");
    test = (!validade("#selCat_sal") ? false : test);
    campos += (!validade("#selCat_sal") ? "Categoria Salarial\n" : "");
    test = (!validade("#selJornada") ? false : test);
    campos += (!validade("#selJornada") ? "Jornada\n" : "");
    test = (!validade("#selTurno") ? false : test);
    campos += (!validade("#selTurno") ? "Turno\n" : "");
    test = (!validade("#txtHoraIni") ? false : test);
    campos += (!validade("#txtHoraIni") ? "Hora Início\n" : "");
    test = (!validade("#txtHoraFim") ? false : test);
    campos += (!validade("#txtHoraFim") ? "Hora Fim\n" : "");
    test = (!validade("#txtSalario") ? false : test);
    campos += (!validade("#txtSalario") ? "Salário\n" : "");

    if (test) {
        Loading();
        $.ajax({
            url: '/funcionarios/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'pessoa': $('#txtPessoa').val(),
                'cargo': $('#selCargo').val(),
                'data_admissao': $('#txtDataAdmissao').val(),
                'tipo_funcionario': $('#selTipo_fun').val(),
                'cat_salarial': $('#selCat_sal').val(),
                'jornada': $('#selJornada').val(),
                'turno': $('#selTurno').val(),
                'hora_ini': $('#txtHoraIni').val(),
                'hora_fim': $('#txtHoraFim').val(),
                'status': $("input[name='radStatus']:checked").val(),
                'ctps_num': $('#txtCtps_num').val(),
                'ctps_serie': $('#txtCtps_serie').val(),
                'ctps_uf': $('#selCtps_uf').val(),
                'ctps_data': $('#txtCtps_data').val(),
                'pis': $('#txtPis').val(),
                'salario': $('#txtSalario').val(),
                'observacao': $('#txtObservacao').val()
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);
            (retorno["status"] ? ModalSucesso("/funcionarios") : ModalErro(retorno["message"]));
        });
    } else {
        ModalFaltaCadastro(campos);
    }

});

function edita(id) {
    Loading();
    $.redirect("/funcionarios/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    Loading();
    $.ajax({
        url: '/funcionarios/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        var retorno = JSON.parse(data);
        (retorno["status"] ? ModalSucesso("/funcionarios") : ModalErro(retorno["message"]));
    });
});

function showPessoa(str) {
    str = $('#txtPessoa').val();
    if (str.length == 0) {
        document.getElementById("livesearPessoa").innerHTML = "";
        document.getElementById("livesearPessoa").style.border = "0px";
        return;
    }
    $.ajax({
        url: '/pessoa/search',
        type: 'post',
        dataType: 'html',
        data: {
            'pesquisa': $('#txtPessoa').val(),
            'funcao': "setPessoa"
        }
    }).done(function (data) {
        if (data != "") {
            document.getElementById("livesearPessoa").innerHTML = data;
            document.getElementById("livesearPessoa").style.border = "1px solid #A5ACB2";
        } else {
            fechar_pesquisa_pessoa();
        }

    });
}

function setPessoa(id) {
    document.getElementById("txtPessoa").innerHTML = "";
    document.getElementById("txtPessoa").style.border = "0px";
    fechar_pesquisa_pessoa();
    $('#txtPessoa').val(id);
    $('#txtDt_admi').focus();
}

function fechar_pesquisa_pessoa() {
    document.getElementById("livesearPessoa").innerHTML = "";
    document.getElementById("livesearPessoa").style.border = "0px";
}

function setArea(id_area = null) {

    id = $('#selUnidade').val();
    $.ajax({
        url: '/area/get_unidade_area',
        type: 'post',
        data: {
            'id_unidade': id,
            'id_area': id_area
        }
    }).done(function (data) {
        $('#selArea').empty();
        $('#selArea').append(data);

        $('#selSetor').empty();
        $('#selSetor').append('<option selected value="">Selecione</option>');
        $('#selAmbiente').empty();
        $('#selAmbiente').append('<option selected value="">Selecione</option>');
        $('#selCelula').empty();
        $('#selCelula').append('<option selected value="">Selecione</option>');

    });
}

function setSetor(id_setor = null) {

    id = $('#selArea').val();
    $.ajax({
        url: '/setor/get_area_setor',
        type: 'post',
        data: {
            'id_area': id,
            'id_setor': id_setor
        }
    }).done(function (data) {
        $('#selSetor').empty();
        $('#selSetor').append(data);

        $('#selAmbiente').empty();
        $('#selAmbiente').append('<option selected value="">Selecione</option>');
        $('#selCelula').empty();
        $('#selCelula').append('<option selected value="">Selecione</option>');

    });
}

function setAmbiente() {

    id = $('#selSetor').val();
    $.ajax({
        url: '/ambiente/get_setor_ambiente',
        type: 'post',
        data: {
            'id_setor': id
        }
    }).done(function (data) {
        $('#selAmbiente').empty();
        $('#selAmbiente').append(data);

        $('#selCelula').empty();
        $('#selCelula').append('<option selected value="">Selecione</option>');

    });
}

function setCelula() {

    id = $('#selAmbiente').val();
    $.ajax({
        url: '/celula/get_ambiente_celula',
        type: 'post',
        data: {
            'id_ambiente': id
        }
    }).done(function (data) {
        $('#selCelula').empty();
        $('#selCelula').append(data);
    });
}