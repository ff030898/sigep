$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#tipo_negocio_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/tipo_negocio/add");

});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/tipo_negocio");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/tipo_negocio/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'descricao': $('#txtDescricao').val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/tipo_negocio");
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
            url: '/tipo_negocio/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'descricao': $('#txtDescricao').val(),
                'status': $("input[name='radStatus']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/tipo_negocio");
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
    $.redirect("/tipo_negocio/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/tipo_negocio/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/tipo_negocio");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
