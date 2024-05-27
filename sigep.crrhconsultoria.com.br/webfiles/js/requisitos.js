$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#requisitos_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/requisitos/add");

});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/requisitos");
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
            url: '/requisitos/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'descricao': $('#txtDescricao').val(),
                'tipo': $("input[name='radTipo']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/requisitos");
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
            url: '/requisitos/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'descricao': $('#txtDescricao').val(),
                'tipo': $("input[name='radTipo']:checked").val(),
                'status': $("input[name='radStatus']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/requisitos");
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
    $.redirect("/requisitos/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/requisitos/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/requisitos");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
