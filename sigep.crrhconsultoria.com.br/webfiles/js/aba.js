var redi;
var val;

console.clear();

jQuery(function ($) {
    $('.tabs-menu ul li a').click(function () {
        var a = $(this);
        var active_tab_class = 'active-tab-menu';
        var the_tab = '.' + a.attr('data-tab');
        $('.tabs-menu ul li a').removeClass(active_tab_class);
        a.addClass(active_tab_class);
        $('.tabs-content .tabs').css({
            'display': 'none'
        });
        $(the_tab).show();
        return false;
    });
});

function IsValidJSONString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function trocaCliente() {
    $("#changeCliente").modal({backdrop: "static"});
}

function troca_cliente(id) {
    $("#loading").modal({backdrop: "static"});
    $.ajax({
        url: '/clientes/trocar_cliente',
        type: 'post',
        dataType: 'html',
        data: {
            'id': id
        }
    }).done(function (data) {
        if (data) {
            window.location.replace(window.location.pathname);
        } else {
            $("#loading").modal("hide");
        }

    });
}

function ModalSucesso(redirect, valor, mensagem) {
    redi = redirect;
    val = valor;

    FimLoading();
    $("#lblMsgWarning").text("");

    var obj = $("#lblMsgWarning").text(mensagem);
    obj.html(obj.html().replace(/\n/g, '<br/>'));


    $("#ModalSucesso").css("z-index", "1000000000");
    $("#ModalSucesso").modal({backdrop: "static"});
}

function ModalSucessoRedirect() {
    if (isInt(val)) {
        $.redirect(redi, {id: val});
    } else if (IsValidJSONString(val)) {
        $.redirect(redi, JSON.parse(val));
    } else {
        if (redi != "")
            $.redirect(redi);
    }
}

function ModalFaltaCadastro(mensagem) {
    FimLoading();

    $("#lblMsgFCadastro").text("");

    var obj = $("#lblMsgFCadastro").text(mensagem);
    obj.html(obj.html().replace(/\n/g, '<br/>'));


    $("#ModalFaltaCadastro").css("z-index", "1000000000");
    $("#ModalFaltaCadastro").modal({backdrop: "static"});
}

function Loading() {
    $(":button").button('loading');
    $("#Loading").css("z-index", "1000000000");
    $("#Loading").modal({backdrop: "static"});
}

function FimLoading() {
    $(':button').button('reset');
    $("#Loading").modal('hide');
}

function ModalRemover() {
    FimLoading();
    $("#ModalRemove").css("z-index", "1000000000");
    $("#ModalRemove").modal({backdrop: "static"});
}

function ModalErroRemover() {
    FimLoading();
    $("#ModalRemoveErro").css("z-index", "1000000000");
    $("#ModalRemoveErro").modal({backdrop: "static"});
}

function ModalErro(erro) {
    FimLoading();
    $("#lblMsgErro").text("");
    $("#lblMsgErro").text(erro);
    $("#ModalErro").css("z-index", "1000000000");
    $("#ModalErro").modal({backdrop: "static"});
}

function ModalJaExisteCadastro() {
    FimLoading();
    $("#ModalJaExisteCadastro").css("z-index", "1000000000");
    $("#ModalJaExisteCadastro").modal({backdrop: "static"});
}

$('#ModalSucessoOK').on('click', function () {
    FimLoading();
    ModalSucessoRedirect();
});

