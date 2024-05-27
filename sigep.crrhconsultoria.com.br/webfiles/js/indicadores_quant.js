$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#empresas_listas').DataTable({
        "ordering": false
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/indicadores_quant/add");
    
});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/indicadores_quant");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#selTipoIndicador"))) {
        test = false;
    }
    if (!validade($("#selNatOcupacional"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/indicadores_quant/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'tipo_indicador': $('#selTipoIndicador').val(),
                'nat_ocupacional': $('#selNatOcupacional').val(),
                'descricao': $('#txtDescricao').val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/indicadores_quant");
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
    if (!validade($("#selTipoIndicador"))) {
        test = false;
    }
    if (!validade($("#selNatOcupacional"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/indicadores_quant/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'tipo_indicador': $('#selTipoIndicador').val(),
                'nat_ocupacional': $('#selNatOcupacional').val(),
                'descricao': $('#txtDescricao').val(),
                'status': $("input[name='radStatus']:checked").val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/indicadores_quant");
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

function edita(id) {
    $.redirect("/indicadores_quant/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/indicadores_quant/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/indicadores_quant");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
