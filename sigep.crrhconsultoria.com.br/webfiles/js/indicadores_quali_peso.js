$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#indicadores_quali_peso').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/indicadores_quali_peso/add");

});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/indicadores_quali_peso");
});

$("#save").click(function () {
    test = true;
    if (!validade($("#selIndicador"))) {
        test = false;
    }
    if (!validade($("#txtPeso1"))) {
        test = false;
    }
    if (!validade($("#txtInter1"))) {
        test = false;
    }
    if (!validade($("#txtPeso2"))) {
        test = false;
    }
    if (!validade($("#txtInter2"))) {
        test = false;
    }
    if (!validade($("#txtPeso3"))) {
        test = false;
    }
    if (!validade($("#txtInter3"))) {
        test = false;
    }
    if (!validade($("#txtPeso4"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/indicadores_quali_peso/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'indicador_qualitativo': $('#selIndicador').val(),
                'peso1': $('#txtPeso1').val(),
                'inter1': $('#txtInter1').val(),
                'peso2': $('#txtPeso2').val(),
                'inter2': $('#txtInter2').val(),
                'peso3': $('#txtPeso3').val(),
                'inter3': $('#txtInter3').val(),
                'peso4': $('#txtPeso4').val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/indicadores_quali_peso");
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
    if (!validade($("#txtPeso1"))) {
        test = false;
    }
    if (!validade($("#txtInter1"))) {
        test = false;
    }
    if (!validade($("#txtPeso2"))) {
        test = false;
    }
    if (!validade($("#txtInter2"))) {
        test = false;
    }
    if (!validade($("#txtPeso3"))) {
        test = false;
    }
    if (!validade($("#txtInter3"))) {
        test = false;
    }
    if (!validade($("#txtPeso4"))) {
        test = false;
    }

    if (test) {
        $('#close').button('loading');
        $('#save').button('loading');
        $.ajax({
            url: '/indicadores_quali_peso/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'indicador_qualitativo': $('#selIndicador').val(),
                'peso1': $('#txtPeso1').val(),
                'inter1': $('#txtInter1').val(),
                'peso2': $('#txtPeso2').val(),
                'inter2': $('#txtInter2').val(),
                'peso3': $('#txtPeso3').val(),
                'inter3': $('#txtInter3').val(),
                'peso4': $('#txtPeso4').val(),
                'status': $("input[name='radStatus']:checked").val()
            }
        }).done(function (data) {
            if (isInt(data)) {
                ModalSucesso("/indicadores_quali_peso");
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
    $.redirect("/indicadores_quali_peso/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/indicadores_quali_peso/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/indicadores_quali_peso");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});
