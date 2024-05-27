var _root;
var _id = 0;

$(function () {
    $('input').focusout(function () {
        this.value = this.value.toUpperCase();
    });
});

function adicionar(root) {
    $("#root").text("");

    _root = root;
    _id = 0;

    $('#txtAdicionar').val("");
    $("#ModalAdicionar").modal({backdrop: "static"});
}

function editar(id, descricao, ordem) {
    _id = id;

    $('#txtAdicionar').val(descricao);
    $("#ModalAdicionar").modal({backdrop: "static"});
}

function remover(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/estrutura_organizacional/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        var retorno = JSON.parse(data);

        (retorno["status"] ? ModalSucesso("/estrutura_organizacional") : ModalErro(retorno["message"]));
    });
});

$("#save").on('click', function () {

    var test = true;

    test = (!validade($("#txtAdicionar")) ? false : test);
    test = (!validade($("#txtOrdem")) ? false : test);

    if (_id === 0) {
        var url = '/estrutura_organizacional/adicionar';
    } else {
        var url = '/estrutura_organizacional/editar';
    }

    if (test) {
        Loading();
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            data: {
                'root': _root,
                'id': _id,
                'descricao': $('#txtAdicionar').val(),
                'ordem': $("#txtOrdem").val()
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);
            (retorno["status"] ? ModalSucesso("/estrutura_organizacional") : ModalErro(retorno["message"]));
        });
    } else {
        ModalFaltaCadastro();
    }


});