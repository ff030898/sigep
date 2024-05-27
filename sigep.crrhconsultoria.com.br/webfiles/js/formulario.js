$('#frmLogin').submit(function (e) {
    e.preventDefault();
    $('#entra').button('loading');

    $.ajax({
        url: '/login/logar',
        type: 'post',
        dataType: 'html',
        data: {
            'password': $('#password').val(),
            'login': $('#login').val()
        }
    }).done(function (data) {
        if (data) {
            window.location.replace("/home");
        } else {
            $("#myModal2").modal({backdrop: "static"});
            $('#entra').button('reset');
        }

    });

});

$('#recover_password').on('click', function () {

    window.location.replace("/login/recover");

})

$('#cancelar').on('click', function () {

    window.location.replace("/");

});

$('#fecha').on('click', function () {
    var $btn = $(this).button('loading');
    window.location.replace("/");
    
});

$('#frmRecover').submit(function (e) {
    e.preventDefault();
    $('#entra').button('loading');
    $('#cancelar').button('loading');

    $.ajax({
        url: '/login/recuperar',
        type: 'post',
        dataType: 'html',
        data: {
            'email': $('#email').val()
        }
    }).done(function (data) {

        resultado = data;

        if (resultado) {
            $('#myModal').modal({backdrop: "static"});
            $('#entra').button('reset');
            $('#cancelar').button('reset');
        } else {
            $('#myModal2').modal({backdrop: "static"});
            $('#entra').button('reset');
            $('#cancelar').button('reset');
        }
    });
});

$('#frmResetar').submit(function (e) {
    e.preventDefault();
    $('#reset').button('loading');

    var mail = $('#password').val();
    var mail1 = $('#password1').val();

    if (mail !== mail1) {
        $('#password').val("");
        $('#password1').val("");
        $('#reset').button('reset');
        $('#texto').text('As senhas informadas não combinam!');
        $('#myModal2').modal({backdrop: "static"});
    } else if (!validatePassword(mail)) {
        $('#texto').text("Senha muito fraca!");

        $('#myModal2').modal({backdrop: "static"});
        $('#reset').button('reset');
    } else {
        $.ajax({
            url: '/login/resetar',
            type: 'post',
            dataType: 'html',
            data: {
                'password': $('#password').val(),
                'email': $('#email').val(),
                'recover_key': $('#recover_key').val()
            }
        }).done(function (data) {

            resultado = data;
            if (resultado == true) {
                $('#myModal').modal({backdrop: "static"});
                $('#reset').button('reset');
            } else {
                $('#texto').text('Dados inválidos!');
                $('#myModal2').modal({backdrop: "static"});
                $('#reset').button('reset');
            }
        });
    }
});
