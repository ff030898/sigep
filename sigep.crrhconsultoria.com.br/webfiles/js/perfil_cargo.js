$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#perfil_cargo_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/perfil_cargo/add");
});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/perfil_cargo");
});

$("#save").on('click', function () {

    var test = true;
    var indicador = new Array();
    var sub = new Array();
    var valor = new Array();
    var selCargoInterna = new Array();
    var txtTempo = new Array();
    var selTipo = new Array();
    var campos = "";

    test = (!validade($("#selCargo")) ? false : test);
    test = (!validade($("#txtElaboracao")) ? false : test);
    test = (!validade($("#txtAprovacao")) ? false : test);
    test = (!validade($("#txtQualifBasica")) ? false : test);
    test = (!validade($("#txtSumDesc")) ? false : test);
    test = (!validade($("#txtFonteExterna")) ? false : test);

    campos += (!validade("#selCargo") ? "Cargo\n" : "");
    campos += (!validade("#txtElaboracao") ? "Elaboração\n" : "");
    campos += (!validade("#txtAprovacao") ? "Aprovação\n" : "");
    campos += (!validade("#txtQualifBasica") ? "Qualificação Básica\n" : "");
    campos += (!validade("#txtSumDesc") ? "Sumário das Descrições\n" : "");
    campos += (!validade("#txtFonteExterna") ? "Fonte Externa\n" : "");

    $("textarea[id_indicador]").each(function (index, element) {
        var id_sub = $(element).attr("id_sub");

        if (id_sub !== "12") {
            test = (!validade($(element)) ? false : test);
        }


        indicador[index] = $(element).attr("id_indicador");
        sub[index] = $(element).attr("id_sub");
        valor[index] = $(element).val();

    });

    for (i = 1; i <= counter_interna; i++) {
        if ($("#selCargoInterna" + i).val() != "") {
            c = i - 1;
            selCargoInterna[c] = $("#selCargoInterna" + i).val();
            txtTempo[c] = $("#txtTempo" + i).val();
            selTipo[c] = $("#selTipo" + i).val();
        }
    }


    if (test) {
        Loading();
        $.ajax({
            url: '/perfil_cargo/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'id_cargo': $('#selCargo').val(),
                'elaboracao': $('#txtElaboracao').val(),
                'aprovacao': $('#txtAprovacao').val(),
                'qualificacao_basica': $('#txtQualifBasica').val(),
                'sumario': $('#txtSumDesc').val(),
                'cargo_interno': selCargoInterna,
                'tempo': txtTempo,
                'tipo': selTipo,
                'conceito': $('#selConceito').val(),
                'advertencia': $('#selAdvertência').val(),
                'fonte_externa': $('#txtFonteExterna').val(),
                'indicador': indicador,
                'sub': sub,
                'valor': valor
            }
        }).done(function (data) {
            (isInt(data) ? ModalSucesso("/perfil_cargo") : ModalJaExisteCadastro());
        });
    } else {
        ModalFaltaCadastro(campos);
    }

});

$("#edit").click(function () {
    var test = true;
    var indicador = new Array();
    var sub = new Array();
    var valor = new Array();
    var selCargoInterna = new Array();
    var txtTempo = new Array();
    var selTipo = new Array();

    test = (!validade($("#selCargo")) ? false : test);
    test = (!validade($("#txtElaboracao")) ? false : test);
    test = (!validade($("#txtAprovacao")) ? false : test);
    test = (!validade($("#txtQualifBasica")) ? false : test);
    test = (!validade($("#txtSumDesc")) ? false : test);
    test = (!validade($("#txtFonteExterna")) ? false : test);

    $("textarea[id_indicador]").each(function (index, element) {

        var id_sub = $(element).attr("id_sub");

        if (id_sub !== "12") {
            test = (!validade($(element)) ? false : test);
        }

        indicador[index] = $(element).attr("id_indicador");
        sub[index] = $(element).attr("id_sub");
        valor[index] = $(element).val();

    });

    for (i = 1; i <= counter_interna; i++) {
        if ($("#selCargoInterna" + i).val() != "") {
            c = i - 1;
            selCargoInterna[c] = $("#selCargoInterna" + i).val();
            txtTempo[c] = $("#txtTempo" + i).val();
            selTipo[c] = $("#selTipo" + i).val();
        }
    }

    if (test) {
        Loading();

        $.ajax({
            url: '/perfil_cargo/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'id_cargo': $('#txtID_Cargo').val(),
                'elaboracao': $('#txtElaboracao').val(),
                'aprovacao': $('#txtAprovacao').val(),
                'qualificacao_basica': $('#txtQualifBasica').val(),
                'sumario': $('#txtSumDesc').val(),
                'cargo_interno': selCargoInterna,
                'tempo': txtTempo,
                'tipo': selTipo,
                'conceito': $('#selConceito').val(),
                'advertencia': $('#selAdvertência').val(),
                'fonte_externa': $('#txtFonteExterna').val(),
                'indicador': indicador,
                'sub': sub,
                'valor': valor
            }
        }).done(function (data) {
            (isInt(data) ? ModalSucesso("/perfil_cargo") : ModalJaExisteCadastro());
        });

    } else {
        ModalFaltaCadastro();
    }
});

function setCompOrganizacionais() {
    id = $('#selCargo').val();
    $.ajax({
        url: '/cargo/get_ind_quant_nat',
        type: 'post',
        data: {
            'id_cargo': id
        }
    }).done(function (data) {
        select = data;

        for (i = 1; i < counter_resultado; i++) {
            $('#selResultado' + i).empty();
            $('#selResultado' + i).append(data);
        }

        for (i = 1; i < counter_valores; i++) {
            $('#selValores' + i).empty();
            $('#selValores' + i).append(data);
        }

        for (i = 1; i < counter_imagem; i++) {
            $('#selImagem' + i).empty();
            $('#selImagem' + i).append(data);
        }

        for (i = 1; i < counter_processo; i++) {
            $('#selProcesso' + i).empty();
            $('#selProcesso' + i).append(data);
        }
    });
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $("#loading").modal({backdrop: "static"});
    $.ajax({
        url: '/perfil_cargo/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        $("#loading").modal("hide");
        if (data == "1") {
            window.location.replace("/perfil_cargo");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});

function edita(id) {
    $.redirect("/perfil_cargo/edit/", {'id': id});
}

$("#btnGerarRel").click(function () {
    var test = true;
    if (!validade($("#selCargo"))) {
        test = false;
    }

    if (test) {
        Loading();

        if ($("input[name='radSaida']:checked").val() === "1") {
            $.ajax({
                url: '/perfil_cargo/gerar_relatorio',
                type: 'post',
                dataType: 'html',
                data: {
                    'id_perfil': $('#selCargo').val(),
                    'saida': $("input[name='radSaida']:checked").val()
                }
            }).done(function (data) {
                $("#resultado").html("");
                $('#resultado').append("");
                $('#resultado').append(data);
            });
        } else {
            $.redirect('/perfil_cargo/gerar_relatorio', {'id_perfil': $('#selCargo').val(), 'saida': $("input[name='radSaida']:checked").val()});
        }

        FimLoading();

    } else {
        ModalFaltaCadastro();
    }
});
