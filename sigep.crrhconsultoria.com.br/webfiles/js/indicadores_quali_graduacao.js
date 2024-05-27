$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#indicadores_quali_graduacao_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/indicadores_quali_graduacao/add");

});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/indicadores_quali_graduacao");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#selIndicador"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (!validade($("#txtDescricaoResumida"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/indicadores_quali_graduacao/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'indicador_qualitativo': $('#selIndicador').val(),
                'descricao': $('#txtDescricao').val(),
                'descricao_resumida': $('#txtDescricaoResumida').val(),
                'ordem_horizontal': $("input[name='radOrdem']:checked").val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/indicadores_quali_graduacao");
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

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/indicadores_quali_graduacao/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'indicador_qualitativo': $('#selIndicador').val(),
                'descricao': $('#txtDescricao').val(),
                'descricao_resumida': $('#txtDescricaoResumida').val(),
                'ordem_horizontal': $("input[name='radOrdem']:checked").val(),
                'status': $("input[name='radStatus']:checked").val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/indicadores_quali_graduacao");
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
    $.redirect("/indicadores_quali_graduacao/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/indicadores_quali_graduacao/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/indicadores_quali_graduacao");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
