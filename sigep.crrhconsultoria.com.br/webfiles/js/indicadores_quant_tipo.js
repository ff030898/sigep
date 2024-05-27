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
    window.location.replace("/indicadores_quant_tipo/add");

});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/indicadores_quant_tipo");
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
            url: '/indicadores_quant_tipo/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'descricao': $('#txtDescricao').val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/indicadores_quant_tipo");
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
            url: '/indicadores_quant_tipo/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'descricao': $('#txtDescricao').val(),
                'status': $("input[name='radStatus']:checked").val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/indicadores_quant_tipo");
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
    $.redirect("/indicadores_quant_tipo/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/indicadores_quant_tipo/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data === "1") {
            window.location.replace("/indicadores_quant_tipo");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});

function subcompetencias(id) {
    $.redirect("/indicadores_quant_tipo/sub/", {'id': id});
}

$("#add_sub").on('click', function () {
    Loading();
    $.redirect('/indicadores_quant_tipo/sub_add', {'id': $("#txtID").val()});
});

$("#close_sub").on('click', function () {
    Loading();
    $.redirect('/indicadores_quant_tipo/sub', {'id': $("#txtID").val()});
});

$("#save_sub").click(function () {
    var test = true;

    test = (!validade($("#txtDescricao")) ? false : test);

    if (test) {
        Loading();
        $.ajax({
            url: '/indicadores_quant_tipo/sub_adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $("#txtID").val(),
                'descricao': $('#txtDescricao').val()
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);

            (retorno["status"] ? ModalSucesso("/indicadores_quant_tipo/sub", $("#txtID").val()) : ModalErro(retorno["message"]));
        });
    } else {
        ModalFaltaCadastro();
    }
});

function edita_sub(id) {
    $.redirect('/indicadores_quant_tipo/sub_edit', {'id_sub': id, 'id': $("#txtID").val()});
}

$("#edit_sub").click(function () {
    var test = true;

    test = (!validade($("#txtDescricao")) ? false : test);

    if (test) {
        Loading();
        $.ajax({
            url: '/indicadores_quant_tipo/sub_editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $("#txtID").val(),
                'id_sub': $("#txtIDSub").val(),
                'descricao': $('#txtDescricao').val(),
                'status': $("input[name='radStatus']:checked").val()
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);

            (retorno["status"] ? ModalSucesso("/indicadores_quant_tipo/sub", $("#txtID").val()) : ModalErro(retorno["message"]));
        });
    } else {
        ModalFaltaCadastro();
    }
});

function remove_sub(id) {
    idc = id;
    $("#ModalRemoveSub").modal({backdrop: "static"});
}

$('#remover_sub').on('click', function () {
    $.ajax({
        url: '/indicadores_quant_tipo/remover_sub',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        var retorno = JSON.parse(data);

        (retorno["status"] ? ModalSucesso("/indicadores_quant_tipo/sub", $("#txtID").val()) : ModalErro(retorno["message"]));
    });
});

function negacao(id) {
    $.redirect('/indicadores_quant_tipo/negacao', {'id_sub': id, 'id': $("#txtID").val()});
}

$("#close_negacao").on('click', function () {
    Loading();
    $.redirect('/indicadores_quant_tipo/negacao', {'id': $("#txtID").val(), 'id_sub': $("#txtIdSub").val()});
});

$("#add_negacao").on('click', function () {
    Loading();
    $.redirect('/indicadores_quant_tipo/negacao_add', {'id': $("#txtID").val(), 'id_sub': $("#txtIdSub").val()});
});

$("#save_negacao").click(function () {
    var test = true;

    test = (!validade($("#txtDescricao")) ? false : test);

    if (test) {
        Loading();
        $.ajax({
            url: '/indicadores_quant_tipo/negacao_adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $("#txtID").val(),
                'id_sub': $("#txtIdSub").val(),
                'descricao': $('#txtDescricao').val()
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);
            var data = '{"id": "' + $("#txtID").val() + '","id_sub": "' + $("#txtIdSub").val() + '"}';
            (retorno["status"] ? ModalSucesso("/indicadores_quant_tipo/negacao", data) : ModalErro(retorno["message"]));
        });
    } else {
        ModalFaltaCadastro();
    }
});

function edita_negacao(id) {
    $.redirect('/indicadores_quant_tipo/negacao_edit', {'id_negacao': id, 'id_sub': $("#txtIdSub").val(), 'id': $("#txtID").val()});
}

$("#edit_negacao").click(function () {
    var test = true;

    test = (!validade($("#txtDescricao")) ? false : test);

    if (test) {
        Loading();
        $.ajax({
            url: '/indicadores_quant_tipo/negacao_editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $("#txtID").val(),
                'id_sub': $("#txtIdSub").val(),
                'id_negacao': $("#txtIdNegacao").val(),
                'descricao': $('#txtDescricao').val()
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);
            var data = '{"id": "' + $("#txtID").val() + '","id_sub": "' + $("#txtIdSub").val() + '"}';
            (retorno["status"] ? ModalSucesso("/indicadores_quant_tipo/negacao", data) : ModalErro(retorno["message"]));
        });
    } else {
        ModalFaltaCadastro();
    }
});

function remove_negacao(id) {
    idc = id;
    $("#ModalRemoveNegacao").modal({backdrop: "static"});
}

$('#remover_negacao').on('click', function () {
    $.ajax({
        url: '/indicadores_quant_tipo/negacao_remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        var retorno = JSON.parse(data);
        var data = '{"id": "' + $("#txtID").val() + '","id_sub": "' + $("#txtIdSub").val() + '"}';
        (retorno["status"] ? ModalSucesso("/indicadores_quant_tipo/negacao", data) : ModalErro(retorno["message"]));

    });
});