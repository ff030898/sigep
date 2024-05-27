var counter = 2;
var counter_n = 2;
var counter_c = 2;
var counter_d = 2;
var counter_f = 2;
var counter_a = 2;
var counter_o = 2;
var counter_co = 2;
var select = "";
var counter_resultado = 2;
var counter_valores = 2;
var counter_imagem = 2;
var counter_processo = 2;
var counter_interna = 2;
var box_counter = [];

$("#addButtonAperfeicoamento").click(function () {
    var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivAperfeicoamento' + counter_a);
    newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_a + ': Descrição do Aperfeiçoamento*</label><textarea class="form-control" name="txtDescAperfeicoamento' + counter_a + '" id="txtDescAperfeicoamento' + counter_a + '" rows="1" onkeyup="validade(this)" onblur="validade(this)"></textarea></div></div></div>');
    newTextBoxDiv.appendTo("#TextBoxesGroupAperfeicoamento");
    counter_a++;
});

function adicionar_box(id, id_indicador, id_sub) {
    if (!box_counter[id]) {
        box_counter[id] = 2;
    }

    var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + id + box_counter[id]);
    newTextBoxDiv.after().html('<div class="row" id="teste">\n\
                                    <div class="col-sm-12 input_fields_wrap">\n\
                                        <div class="form-group ">\n\
                                            <label>Descrição:*</label>\n\
                                            <textarea class="form-control" id_indicador="' + id_indicador + '" id_sub="' + id_sub + '" name="txtDesc' + id + box_counter[id] + '" id="txtDesc' + id + box_counter[id] + '" rows="1" onkeyup="validade(this)" onblur="validade(this)"></textarea>\n\
                                            <a class="link_ativo" onclick="remover_box(' + id + box_counter[id] + ')">Remover</a>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>');
    newTextBoxDiv.appendTo("#TextBoxesGroup" + id);

    box_counter[id]++;
}

function remover_box(id) {
    $("#TextBoxDiv" + id).remove();
}

$(document).ready(function () {

//Indicdores
    $("#addButton").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter + ': Descrição de Cargo Detalhada*</label><textarea class="form-control" name="txtDescCargo' + counter + '" id="txtDescCargo' + counter + '" rows="3" onkeyup="validade(this)" onblur="validade(this)"></textarea></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroup");
        counter++;
    });

    $("#removeButton").click(function () {
        if (counter == 2) {
            return false;
        }
        counter--;
        $("#TextBoxDiv" + counter).remove();
    });
//Indicadores Fim


//Noções
    $("#addButtonNocoes").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivNocoes' + counter_n);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_n + ': Descrição Noções*</label><textarea class="form-control" name="txtDescNocoes' + counter_n + '" id="txtDescNocoes' + counter_n + '" rows="1" onkeyup="validade(this)" onblur="validade(this)"></textarea></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroupNocoes");
        counter_n++;
    });

    $("#removeButtonNocoes").click(function () {
        if (counter_n == 2) {
            return false;
        }
        counter_n--;
        $("#TextBoxDivNocoes" + counter_n).remove();
    });
//Noções Fim



//Conhecimentos
    $("#addButtonConhcimentos").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivConhcimentos' + counter_c);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_c + ': Descrição Conhecimentos*</label><textarea class="form-control" name="txtDescConhcimentos' + counter_c + '" id="txtDescConhcimentos' + counter_c + '" rows="1" onkeyup="validade(this)" onblur="validade(this)"></textarea></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroupConhcimentos");
        counter_c++;
    });

    $("#removeButtonConhcimentos").click(function () {
        if (counter_c == 2) {
            return false;
        }
        counter_c--;
        $("#TextBoxDivConhcimentos" + counter_c).remove();
    });
//Conhecimentos Fim

//Domínio
    $("#addButtonDominios").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivDominios' + counter_d);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_d + ': Descrição Domínios*</label><textarea class="form-control" name="txtDescDominios' + counter_d + '" id="txtDescDominios' + counter_d + '" rows="1" onkeyup="validade(this)" onblur="validade(this)"></textarea></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroupDominios");
        counter_d++;
    });

    $("#removeButtonDominios").click(function () {
        if (counter_d == 2) {
            return false;
        }
        counter_d--;
        $("#TextBoxDivDominios" + counter_d).remove();
    });
//Domínio Fim


//Formação Profissional
    $("#addButtonFormacao").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivFormacao' + counter_f);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_f + ': Descrição da Formação*</label><textarea class="form-control" name="txtDescFormacao' + counter_f + '" id="txtDescFormacao' + counter_f + '" rows="1" onkeyup="validade(this)" onblur="validade(this)"></textarea></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroupFormacao");
        counter_f++;
    });

    $("#removeButtonFormacao").click(function () {
        if (counter_f == 2) {
            return false;
        }
        counter_f--;
        $("#TextBoxDivFormacao" + counter_f).remove();
    });
//Formação Profissional Fim




//Formação Aperfeicoamento


    $("#removeButtonAperfeicoamento").click(function () {
        if (counter_a == 2) {
            return false;
        }
        counter_a--;
        $("#TextBoxDivAperfeicoamento" + counter_a).remove();
    });
//Formação Aperfeicoamento Fim

//Formação Normas
    $("#addButtonNormas").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivNormas' + counter_o);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_o + ': Descrição das Normas*</label><textarea class="form-control" name="txtDescNormas' + counter_o + '" id="txtDescNormas' + counter_o + '" rows="1" onkeyup="validade(this)" onblur="validade(this)"></textarea></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroupNormas");
        counter_o++;
    });

    $("#removeButtonNormas").click(function () {
        if (counter_o == 2) {
            return false;
        }
        counter_o--;
        $("#TextBoxDivNormas" + counter_o).remove();
    });
//Formação Normas Fim



//Formação Comportamento
    $("#addButtonComportamento").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivComportamento' + counter_co);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_co + ': Descrição dos Comportamentos*</label><textarea class="form-control" name="txtDescComportamento' + counter_co + '" id="txtDescComportamento' + counter_co + '" rows="1" onkeyup="validade(this)" onblur="validade(this)"></textarea></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroupComportamento");
        counter_co++;
    });

    $("#removeButtonComportamento").click(function () {
        if (counter_co == 2) {
            return false;
        }
        counter_co--;
        $("#TextBoxDivComportamento" + counter_co).remove();
    });
//Formação Comportamento Fim

//Formação Resultado
    $("#addButtonResultado").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivResultado' + counter_resultado);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_resultado + ': Resultado*</label><select class="form-control " data-live-search="true" autocomplete="off" name="selResultado' + counter_resultado + '" id="selResultado' + counter_resultado + '" onkeyup="validade(this)" onblur="validade(this)" ><option selected value="">Selecione</option></select></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroupResultado");
        $('#selResultado' + counter_resultado).empty();
        $('#selResultado' + counter_resultado).append(select);
        counter_resultado++;
    });

    $("#removeButtonResultado").click(function () {
        if (counter_resultado == 2) {
            return false;
        }
        counter_resultado--;
        $("#TextBoxDivResultado" + counter_resultado).remove();
    });
//Formação Resultado Fim

//Formação Valores
    $("#addButtonValores").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivValores' + counter_valores);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_valores + ': Valores*</label><select class="form-control " data-live-search="true" autocomplete="off" name="selValores' + counter_valores + '" id="selValores' + counter_valores + '" onkeyup="validade(this)" onblur="validade(this)"><option selected value="">Selecione</option></select></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroupValores");
        $('#selValores' + counter_valores).empty();
        $('#selValores' + counter_valores).append(select);
        counter_valores++;
    });

    $("#removeButtonValores").click(function () {
        if (counter_valores == 2) {
            return false;
        }
        counter_valores--;
        $("#TextBoxDivValores" + counter_valores).remove();
    });
//Formação Valores Fim

//Formação Imagem
    $("#addButtonImagem").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivImagem' + counter_imagem);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_imagem + ': Imagem*</label><select class="form-control " data-live-search="true" autocomplete="off" name="selImagem' + counter_imagem + '" id="selImagem' + counter_imagem + '" onkeyup="validade(this)" onblur="validade(this)"><option selected value="">Selecione</option></select></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroupImagem");
        $('#selImagem' + counter_imagem).empty();
        $('#selImagem' + counter_imagem).append(select);
        counter_imagem++;
    });

    $("#removeButtonImagem").click(function () {
        if (counter_imagem == 2) {
            return false;
        }
        counter_imagem--;
        $("#TextBoxDivImagem" + counter_imagem).remove();
    });
//Formação Imagem Fim

//Formação Processo
    $("#addButtonProcesso").click(function () {
        var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDivProcesso' + counter_processo);
        newTextBoxDiv.after().html('<div class="row" id="teste"><div class="col-sm-12 input_fields_wrap"><div class="form-group "><label>' + counter_processo + ': Processo</label><select class="form-control " data-live-search="true" autocomplete="off" name="selProcesso' + counter_processo + '" id="selProcesso' + counter_processo + '" onkeyup="validade(this)" onblur="validade(this)"><option selected value="">Selecione</option></select></div></div></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroupProcesso");
        $('#selProcesso' + counter_processo).empty();
        $('#selProcesso' + counter_processo).append(select);
        counter_processo++;
    });

    $("#removeButtonProcesso").click(function () {
        if (counter_processo == 2) {
            return false;
        }
        counter_processo--;
        $("#TextBoxDivProcesso" + counter_processo).remove();
    });
//Formação Processo Fim
});

