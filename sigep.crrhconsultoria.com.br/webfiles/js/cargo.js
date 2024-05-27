var habilidades = "";
var counter = 2;
var id_estrutura = 0;

$(document).ready(function () {

    $("#addButton").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter + ': Indicadores Quantitativo*</label><select class="form-control" autocomplete="off" name="selIndQuantitativo' + counter + '" id="selIndQuantitativo' + counter + '" ><option selected value="">Selecione</option></select></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroup");
        $('#selIndQuantitativo' + counter).empty();
        $('#selIndQuantitativo' + counter).append(select);
        counter++;
    });

    $("#removeButton").click(function () {
        if (counter == 1) {
            return false;
        }
        counter--;
        $("#TextBoxDiv" + counter).remove();
    });
});

$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
    });
});

$(document).ready(function () {
    $('#cargo_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

$('#add').on('click', function () {
    $('#add').button('loading');
    window.location.replace("/cargo/add");

});

$('#close').on('click', function () {
    $('#close').button('loading');
    window.location.replace("/cargo");
});

$("#save").click(function () {
    var test = true;
    var campos = "";

    test = (!validade("#selTipo") ? false : test);
    campos += (!validade("#selTipo") ? "Tipo\n" : "");

    test = (!validade("#txtDescricao") ? false : test);
    campos += (!validade("#txtDescricao") ? "Descrição\n" : "");

    test = (!validade("#selSubTipo") ? false : test);
    campos += (!validade("#selSubTipo") ? "Subtipo\n" : "");

    test = (!validade("#txtCBO") ? false : test);
    campos += (!validade("#txtCBO") ? "CBO\n" : "");

    test = (!validade("#selAscencao") ? false : test);
    campos += (!validade("#selAscencao") ? "Cargo de ascenção\n" : "");

    test = (!validade("#selNatOcupacional") ? false : test);
    campos += (!validade("#selNatOcupacional") ? "Natureza Ocupacional\n" : "");

    test = (!validade("#txtSalMin") ? false : test);
    campos += (!validade("#txtSalMin") ? "Faixa salarial mínima (R$)\n" : "");

    test = (!validade("#txtSalMax") ? false : test);
    campos += (!validade("#txtSalMax") ? "Faixa salarial máxima (R$)\n" : "");

    test = (!validade("#selGrauInst") ? false : test);
    campos += (!validade("#selGrauInst") ? "Grau de instrução mínimo\n" : "");

    test = (!validade("#txtPeriAvaliacao") ? false : test);
    campos += (!validade("#txtPeriAvaliacao") ? "Periodiciodade Avaliação (Meses)\n" : "");

    test = (!id_estrutura ? false : test);
    campos += (!id_estrutura ? "Lotação\n" : "");

    test = (habilidades === "" ? false : test);
    campos += (habilidades === "" ? "Habilidades\n" : "");

    var requisitos = [];
    var c = 0;
    $.each($('#frmSave').serializeArray(), function (i, field) {
        if (field.name.substring(0, 7) === "chkRequ") {
            requisitos[c] = field.name.substring(7, 9999);
            c++;
        }
    });

    if (requisitos.length <= 0) {
        test = false;
        campos += "Requisitos";
    }

    if (test) {
        Loading();

        $.ajax({
            url: '/cargo/adicionar',
            type: 'post',
            dataType: 'html',
            data: {
                'tipo': $('#selTipo').val(),
                'descricao': $('#txtDescricao').val(),
                'subtipo': $('#selSubTipo').val(),
                'cbo': $('#txtCBO').val(),
                'ascencao': $('#selAscencao').val(),
                'nat_ocupacional': $('#selNatOcupacional').val(),
                'sal_min': $('#txtSalMin').val(),
                'sal_max': $('#txtSalMax').val(),
                'grau_min': $('#selGrauInst').val(),
                'per_avaliacao': $('#txtPeriAvaliacao').val(),
                'id_estrutura_organizacional': id_estrutura,
                'requisitos': requisitos,
                'habilidades': habilidades,
                'observacoes': $('#txtObservacoes').val()

            }
        }).done(function (data) {
            var retorno = JSON.parse(data);
            (retorno["status"] ? ModalSucesso("/cargo") : ModalErro(retorno["message"]));
        });

    } else {
        ModalFaltaCadastro(campos);
    }

});

$("#edit").click(function () {
    var test = true;

    test = (!validade("#selTipo") ? false : test);

    test = (!validade("#txtDescricao") ? false : test);

    test = (!validade("#selSubTipo") ? false : test);
    test = (!validade("#txtCBO") ? false : test);
    test = (!validade("#selAscencao") ? false : test);
    test = (!validade("#selNatOcupacional") ? false : test);
    test = (!validade("#txtSalMin") ? false : test);
    test = (!validade("#txtSalMax") ? false : test);
    test = (!validade("#selGrauInst") ? false : test);
    test = (!validade("#txtPeriAvaliacao") ? false : test);
    test = (!id_estrutura ? false : test);

    test = (habilidades === "" ? false : test);


    var requisitos = [];
    var c = 0;
    $.each($('#frmSave').serializeArray(), function (i, field) {
        if (field.name.substring(0, 7) === "chkRequ") {
            requisitos[c] = field.name.substring(7, 9999);
            c++;
        }
    });

    if (requisitos.length <= 0) {
        test = false;
    }

    if (test) {

        Loading();

        $.ajax({
            url: '/cargo/editar',
            type: 'post',
            dataType: 'html',
            data: {
                'id': $('#txtID').val(),
                'tipo': $('#selTipo').val(),
                'descricao': $('#txtDescricao').val(),
                'subtipo': $('#selSubTipo').val(),
                'cbo': $('#txtCBO').val(),
                'ascencao': $('#selAscencao').val(),
                'nat_ocupacional': $('#selNatOcupacional').val(),
                'sal_min': $('#txtSalMin').val(),
                'sal_max': $('#txtSalMax').val(),
                'grau_min': $('#selGrauInst').val(),
                'per_avaliacao': $('#txtPeriAvaliacao').val(),
                'id_estrutura_organizacional': id_estrutura,
                'requisitos': requisitos,
                'habilidades': habilidades,
                'observacoes': $('#txtObservacoes').val()
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);
            (retorno["status"] ? ModalSucesso("/cargo") : ModalErro(retorno["message"]));
        });

    } else {
        ModalFaltaCadastro();
    }

});

function edita(id) {
    $.redirect("/cargo/edit/", {'id': id});
}

function remove(id) {
    idc = id;
    $("#ModalRemove").modal({backdrop: "static"});
}

$('#remover').on('click', function () {
    $.ajax({
        url: '/cargo/remover',
        type: 'post',
        dataType: 'html',
        data: {
            'id': idc
        }
    }).done(function (data) {
        if (data == "1") {
            window.location.replace("/cargo");
        } else {
            $("#ModalRemoveErro").modal({backdrop: "static"});
        }
    });
});

function showCBO(str) {
    str = $('#txtCBO').val();
    if (str.length == 0) {
        document.getElementById("livesearch").innerHTML = "";
        document.getElementById("livesearch").style.border = "0px";
        return;
    }
    $.ajax({
        url: '/cbo/search_cbo',
        type: 'post',
        dataType: 'html',
        data: {
            'pesquisa': $('#txtCBO').val(),
            'funcao': "setCBO"
        }
    }).done(function (data) {
        resultado = data;
        document.getElementById("livesearch").innerHTML = resultado;
        document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
    });
}

function setCBO(cbo) {
    document.getElementById("livesearch").innerHTML = "";
    document.getElementById("livesearch").style.border = "0px";
    $('#txtCBO').val(cbo);
}

function fechar_pesquisa() {
    document.getElementById("livesearch").innerHTML = "";
    document.getElementById("livesearch").style.border = "0px";
    $('#txtCBO').val("");
}

function showCargo(str) {
    str = $('#txtDescricao').val();
    if (str.length == 0) {
        document.getElementById("livesearch").innerHTML = "";
        document.getElementById("livesearch").style.border = "0px";
        return;
    }
    $.ajax({
        url: '/cargo/search_cargo',
        type: 'post',
        dataType: 'html',
        data: {
            'pesquisa': $('#txtDescricao').val(),
            'funcao': "setCargo"
        }
    }).done(function (data) {
        if (data != "") {
            document.getElementById("livesearchcargo").innerHTML = data;
            document.getElementById("livesearchcargo").style.border = "1px solid #A5ACB2";
        } else {
            fechar_pesquisa_cargo();
        }

    });
}

function setCargo(tipo, cargo, cbo, celula) {
    document.getElementById("livesearchcargo").innerHTML = "";
    document.getElementById("livesearchcargo").style.border = "0px";
    $('#txtDescricao').val(cargo);
    $('#txtCBO').val(cbo);
    $("#selCelula").val(celula).change();
    $("#selTipo").val(tipo).change();
    $('#selSubTipo').focus();
}

function fechar_pesquisa_cargo() {
    document.getElementById("livesearchcargo").innerHTML = "";
    document.getElementById("livesearchcargo").style.border = "0px";
}

jQuery(document).ready(function () {
    var list = jQuery('#habilidades');
    list.sortable({
        opacity: 0.7,
        update: function () {
            var lista = $(this).sortable('toArray').toString();
            habilidades = lista;
        }
    });
    list.disableSelection();
});

function setNatOcupacional() {
    id = $('#selNatOcupacional').val();
    $.ajax({
        url: '/cargo/get_ind_quant',
        type: 'post',
        data: {
            'id_nat_ocupacional': id
        }
    }).done(function (data) {
        select = data;

        for (i = 1; i < counter; i++) {
            $('#selIndQuantitativo' + i).empty();
            $('#selIndQuantitativo' + i).append(data);
        }
    });
}

function teste_teste(object) {
    $(object).prevAll("input[type=text]").val()
//    window.alert($(object).prevAll("label").text());
}