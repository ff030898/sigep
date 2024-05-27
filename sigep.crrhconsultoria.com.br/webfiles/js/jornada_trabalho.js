$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#jornada_trabalho_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/jornada_trabalho/add");

});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/jornada_trabalho");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (!validade($("#txtHoras"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/jornada_trabalho/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'descricao': $('#txtDescricao').val(),
                'horas': $('#txtHoras').val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/jornada_trabalho");
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
    if (!validade($("#txtHoras"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/jornada_trabalho/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'descricao': $('#txtDescricao').val(),
                'horas': $('#txtHoras').val(),
                'status': $("input[name='radStatus']:checked").val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/jornada_trabalho");
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
    $.redirect("/jornada_trabalho/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/jornada_trabalho/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/jornada_trabalho");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
