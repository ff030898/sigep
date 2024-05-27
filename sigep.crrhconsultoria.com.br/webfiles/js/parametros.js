$("#save").click(function () {
    test = true;

    test = (!validade($("#txtTitle")) ? false : test);
    test = (!validade($("#txtUrl")) ? false : test);
    test = (!validade($("#txtServidor")) ? false : test);
    test = (!validade($("#txtPorta")) ? false : test);
    test = (!validade($("#txtPassword")) ? false : test);

    if ($("#txtEmail").val() !== "") {
        if (!TestaEmail($("#txtEmail").val())) {
            test = false;
            $("#txtEmail").css('background', '#0F0');
        } else {
            $("#txtEmail").css('background', '#FFF');
        }
    }

    if (test) {
        $('#save').button('loading');
        $.ajax({
            url: '/parametros/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'title': $('#txtTitle').val(),
                'url': $('#txtUrl').val(),
                'url_portal': $("#txtUrlPortal").val(),
                'email': $('#txtEmail').val(),
                'servidor': $('#txtServidor').val(),
                'porta': $('#txtPorta').val(),
                'password': $('#txtPassword').val()
            }
        }).done(function (data) {
            (data ? ModalSucesso("/parametros") : ModalJaExisteCadastro());
        });
        $('#save').button('reset');
    } else {
        ModalFaltaCadastro();
    }


});