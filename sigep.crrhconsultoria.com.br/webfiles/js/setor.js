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
    window.location.replace("/setor/add");
    
});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/setor");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#selArea"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/setor/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'area': $('#selArea').val(),
                'descricao': $('#txtDescricao').val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/setor");
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
    if (!validade($("#selArea"))) {
        test = false;
    }
    if (!validade($("#txtDescricao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/setor/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'area': $('#selArea').val(),
                'descricao': $('#txtDescricao').val(),
                'status': $("input[name='radStatus']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/setor");
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
    $.redirect("/setor/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/setor/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/setor");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
