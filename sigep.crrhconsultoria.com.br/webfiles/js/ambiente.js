$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#empresas_listas').DataTable({

    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/ambiente/add");
});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/ambiente");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#selSetor"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/ambiente/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'setor': $('#selSetor').val(),
                'descricao': $('#txtDescricao').val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/ambiente");
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
    if (!validade($("#selSetor"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/ambiente/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'setor': $('#selSetor').val(),
                'descricao': $('#txtDescricao').val(),
                'status': $("input[name='radStatus']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/ambiente");
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
    $.redirect("/ambiente/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/ambiente/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/ambiente");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
