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
    window.location.replace("/escolaridade/add");
    
});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/escolaridade");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#txtCodEsocial"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/escolaridade/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'code_esocial': $('#txtCodEsocial').val(),
                'descricao': $('#txtDescricao').val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/escolaridade");
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
    if (!validade($("#txtCodEsocial"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/escolaridade/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'code_esocial': $('#txtCodEsocial').val(),
                'descricao': $('#txtDescricao').val(),
                'status': $("input[name='radStatus']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/escolaridade");
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
    $.redirect("/escolaridade/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/escolaridade/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/escolaridade");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
