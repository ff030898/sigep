
$("#menu_6").addClass("active");

$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#usuarios_clientes_listas').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    $.redirect("/clients_users/add/");
});

$('#close').on('click', function () {
    $('#save').button('loading');
    $('#close').button('loading');

    $.redirect("/clients_users/");

});

$("#save").click(function () {
    test = true;

    test = (!validade($("#txtNome")) ? false : test);

    if (!TestaEmail($("#txtEmail").val()) && $("#txtEmail").val() != "") {
        test = false;
        $("#txtEmail").css('background', '#0F0');
    } else {
        $("#txtEmail").css('background', '#FFF');
    }

    if (test) {
        Loading();

        $('#close').button('loading');
        $('#save').button('loading');

        var menu = {};
        $.each($('#frmSave').serializeArray(), function (i, field) {
            if (field.name.substring(0, 7) === "chkMenu") {
                menu[field.name.substring(7, 9999)] = field.name.substring(7, 9999);
            }
        });

        var avaliacao = [];
        $.each($('#frmSave').serializeArray(), function (i, field) {
            if (field.name.substring(0, 7) === "chkCelu") {
                avaliacao[field.name.substring(7, 9999)] = field.name.substring(7, 9999);
            }
        });

        avaliacao = avaliacao.filter(Number);

        $.ajax({
            url: '/clients_users/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'nome': $('#txtNome').val(),
                'email': $('#txtEmail').val(),
                'restricao': $("#selRestricao").val(),
                'status': $("input[name='radStatus']:checked").val(),
                'menu': menu,
                'avaliacao': avaliacao
            }
        }).done(function (data) {
            (isInt(data) ? ModalSucesso("/clients_users") : ModalJaExisteCadastro());
        });

        $('#close').button('reset');
        $('#save').button('reset');
    } else {
        ModalFaltaCadastro();
    }
});

$("#edit").click(function () {
    test = true;

    test = (!validade($("#txtNome")) ? false : test);

    if (!TestaEmail($("#txtEmail").val()) && $("#txtEmail").val() != "") {
        test = false;
        $("#txtEmail").css('background', '#0F0');
    } else {
        $("#txtEmail").css('background', '#FFF');
    }

    if (test) {
        Loading();

        $('#close').button('loading');
        $('#save').button('loading');

        var menu = {};
        $.each($('#frmSave').serializeArray(), function (i, field) {
            if (field.name.substring(0, 7) === "chkMenu") {
                menu[field.name.substring(7, 9999)] = field.name.substring(7, 9999);
            }

        });

        var avaliacao = [];
        $.each($('#frmSave').serializeArray(), function (i, field) {
            if (field.name.substring(0, 7) === "chkCelu") {
                avaliacao[field.name.substring(7, 9999)] = field.name.substring(7, 9999);
            }
        });

        avaliacao = avaliacao.filter(Number);

        $.ajax({
            url: '/clients_users/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'nome': $('#txtNome').val(),
                'email': $('#txtEmail').val(),
                'restricao': $("#selRestricao").val(),
                'status': $("input[name='radStatus']:checked").val(),
                'menu': menu,
                'avaliacao': avaliacao
            }
        }).done(function (data) {
            (isInt(data) ? ModalSucesso("/clients_users") : ModalJaExisteCadastro());
        });
        $('#close').button('reset');
        $('#save').button('reset');

    } else {
        ModalFaltaCadastro();
    }


});


function edita(id) {
    $.redirect('/clients_users/edit/', {'id': id});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/clients_users/remove',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        (isInt(data) ? ModalSucesso("clients_users") : ModalJaExisteCadastro());
    });
});

function remove(id) {
    idc = id;
    ModalRemover();
}