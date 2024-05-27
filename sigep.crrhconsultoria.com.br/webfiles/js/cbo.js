$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#cbo_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/cbo/add");

});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/cbo");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#txtCodigo"))) {
        test = false;
    }

    if (!validade($("#txtTitulo"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/cbo/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'codigo': $('#txtCodigo').val(),
                'titulo': $('#txtTitulo').val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/cbo");
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
    if (!validade($("#txtCodigo"))) {
        test = false;
    }

    if (!validade($("#txtTitulo"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/cbo/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'codigo': $('#txtCodigo').val(),
                'titulo': $('#txtTitulo').val(),
                'status': $("input[name='radStatus']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/cbo");
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
    $.redirect("/cbo/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/cbo/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/cbo");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});


