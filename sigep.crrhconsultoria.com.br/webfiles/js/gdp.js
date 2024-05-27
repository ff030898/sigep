$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#gdp_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/gdp/add");

});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/gdp");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (!validade($("#txtClassificacao"))) {
        test = false;
    }

    if (!validade($("#txtPerc_Min"))) {
        test = false;
    }

    if (!validade($("#txtPerc_Max"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/gdp/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'descricao': $('#txtDescricao').val(),
                'classificacao': $('#txtClassificacao').val(),
                'perc_min': $('#txtPerc_Min').val(),
                'perc_max': $('#txtPerc_Max').val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/gdp");
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
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (!validade($("#txtClassificacao"))) {
        test = false;
    }

    if (!validade($("#txtPerc_Min"))) {
        test = false;
    }

    if (!validade($("#txtPerc_Max"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/gdp/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'descricao': $('#txtDescricao').val(),
                'classificacao': $('#txtClassificacao').val(),
                'perc_min': $('#txtPerc_Min').val(),
                'perc_max': $('#txtPerc_Max').val(),
                'status': $("input[name='radStatus']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/gdp");
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
    $.redirect("/gdp/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/gdp/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/gdp");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
