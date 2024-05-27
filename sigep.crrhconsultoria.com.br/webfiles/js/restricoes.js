var idc;

$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});


$('#add').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/restricoes/add");

});

$('#close').on('click', function () {
    var $btn = $(this).button('loading');
    $('#save').button('loading');
    window.location.replace("/restricoes");


});

$("#save").click(function () {
    test = true;

    if (!validade($("#txtNome"))) {
        test = false;
    }

    if (!validade($("#txtHoraIni"))) {
        test = false;
    }

    if (!validade($("#txtHoraFIm"))) {
        test = false;
    }

    if (test) {
        enviar();
    }


});

$("#edit").click(function () {
    test = true;

    if (!validade($("#txtHoraIni"))) {
        test = false;
    }

    if (!validade($("#txtHoraFIm"))) {
        test = false;
    }

    if (test) {
        editar();
    }


});

$("#edita").click(function () {

    window.location.replace("/restricoes/edit/id/" + $("#edita").val());

});

function enviar() {
    $('#close').button('loading');
    $('#save').button('loading');
    $.ajax({
        url: '/restricoes/adicionar',
        type: 'post',
        dataType: 'html',
        data: {
            'nome': $('#txtNome').val(),
            'hora_ini': $('#txtHoraIni').val(),
            'hora_fim': $('#txtHoraFim').val(),
            'seg': $("input[name='chkSeg']:checked").val(),
            'ter': $("input[name='chkTer']:checked").val(),
            'qua': $("input[name='chkQua']:checked").val(),
            'qui': $("input[name='chkQui']:checked").val(),
            'sex': $("input[name='chkSex']:checked").val(),
            'sab': $("input[name='chkSab']:checked").val(),
            'dom': $("input[name='chkDom']:checked").val()
        }
    }).done(function (data) {
        resultado = data;
        if (resultado) {
            ModalSucesso("/restricoes");
        } else {
            $("#myModal3").modal({backdrop: "static"});
        }
    });
    $('#close').button('reset');
    $('#save').button('reset');
}

function editar() {
    $('#close').button('loading');
    $('#save').button('loading');
    $.ajax({
        url: '/restricoes/editar',
        type: 'post',
        dataType: 'html',
        data: {
            'id': $('#txtID').val(),
            'hora_ini': $('#txtHoraIni').val(),
            'hora_fim': $('#txtHoraFim').val(),
            'seg': $("input[id='chkSeg']:checked").val(),
            'ter': $("input[id='chkTer']:checked").val(),
            'qua': $("input[id='chkQua']:checked").val(),
            'qui': $("input[id='chkQui']:checked").val(),
            'sex': $("input[id='chkSex']:checked").val(),
            'sab': $("input[id='chkSab']:checked").val(),
            'dom': $("input[id='chkDom']:checked").val()
        }
    }).done(function (data) {
        resultado = data;
        if (resultado) {
            ModalSucesso("/restricoes");
        } else {
            $("#myModal3").modal({backdrop: "static"});
        }
    });
    $('#close').button('reset');
    $('#save').button('reset');
}

$('#search').on('click', function () {
    $("#myModal2").modal({backdrop: "static"});
});

$('#remover').on('click', function () {
    $.ajax({
        url: '/restricoes/remove',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data) {
            window.location.replace("/restricoes");
        } else {
            $("#myModal3").modal({backdrop: "static"});
        }
    });
});

$('#remove').on('click', function () {
    idc = $('#remove').val();
    $("#ModalRemove").modal({backdrop: "static"});
});