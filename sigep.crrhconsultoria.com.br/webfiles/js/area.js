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
    window.location.replace("/area/add");
});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/area");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#selUnidade"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/area/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'unidade': $('#selUnidade').val(),
                'descricao': $('#txtDescricao').val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/area");
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
    if (!validade($("#selUnidade"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/area/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'unidade': $('#selUnidade').val(),
                'descricao': $('#txtDescricao').val(),
                'status': $("input[name='radStatus']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/area");
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
    $.redirect("/area/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/area/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/area");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
