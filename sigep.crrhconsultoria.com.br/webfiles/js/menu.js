
$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/menu/add");
});

$('#close').on('click', function () {
    $('#save').button('loading');
    $('#close').button('loading');
    window.location.replace("/menu/");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#txtNome"))) {
        test = false;
    }
    if (!validade($("#txtOrdem"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/menu/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'name': $('#txtNome').val(),
                'ordem': $('#txtOrdem').val(),
                'link': $('#txtLink').val(),
                'icone': $('#txtIcone').val(),
                'n_interno': $('#txtNInterno').val(),
                'target': $('#selTarget').val(),
                'root': $("input[name='radRoot']:checked").val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/menu/");
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

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/menu/remove',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (isInt(data)) {
            ModalSucesso("/menu/");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});

function edita(id) {
    $.redirect("/menu/edit/", {'id': id});
}

$("#edit").click(function () {
    test = true;
    if (!validade($("#txtNome"))) {
        test = false;
    }
    if (!validade($("#txtOrdem"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/menu/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'name': $('#txtNome').val(),
                'ordem': $('#txtOrdem').val(),
                'link': $('#txtLink').val(),
                'icone': $('#txtIcone').val(),
                'n_interno': $('#txtNInterno').val(),
                'target': $('#selTarget').val(),
                'root': $("input[name='radRoot']:checked").val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/menu/");
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