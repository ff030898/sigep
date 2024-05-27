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
    window.location.replace("/mont_estru_org/add");
    
});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/mont_estru_org");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#selEstruturaOrg"))) {
        test = false;
    }

    if (!validade($("#selClassOrg"))) {
        test = false;
    }

    if (!validade($("#txtPosicao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/mont_estru_org/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'estrutura_org': $('#selEstruturaOrg').val(),
                'class_organizacional': $('#selClassOrg').val(),
                'posicao': $('#txtPosicao').val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/mont_estru_org");
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
    if (!validade($("#selEstruturaOrg"))) {
        test = false;
    }

    if (!validade($("#selClassOrg"))) {
        test = false;
    }

    if (!validade($("#txtPosicao"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/mont_estru_org/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'estrutura_org': $('#selEstruturaOrg').val(),
                'class_organizacional': $('#selClassOrg').val(),
                'posicao': $('#txtPosicao').val(),
                'status': $("input[name='radStatus']:checked").val(),
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/mont_estru_org");
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
    $.redirect("/mont_estru_org/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/mont_estru_org/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/mont_estru_org");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
