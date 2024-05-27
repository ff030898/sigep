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
    window.location.replace("/unidade/add");
    
});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/unidade");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#txtDescricao"))) {
        test = false;
    }
    if (!validade($("#selTipo"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/unidade/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'descricao': $('#txtDescricao').val(),
                'tipo': $('#selTipo').val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/unidade");
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
    if (!validade($("#selTipo"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/unidade/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'tipo': $('#selTipo').val(),
                'descricao': $('#txtDescricao').val(),
                'status': $("input[name='radStatus']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/unidade");
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
    $.redirect("/unidade/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/unidade/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/unidade");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
