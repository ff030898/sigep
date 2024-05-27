var tabela;
var idc;
var tipo;
$(function () {
    $('input').focusout(function () {
        this.value = this.value.toLocaleUpperCase();
    });
});

$(document).ready(function () {
    $('#avaliacao_lista').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });
});

function abre_pdi(object) {
    var id_avaliacao = parseInt($(object).attr("id_avaliacao"));
    $.redirect("/pdi/plano_acao", {"id_avaliacao": id_avaliacao});
}

$("#close").on('click', function () {
    Loading();
    $.redirect("/pdi");
});

function modalPlanoAcao(tbl, tp) {
    $("#txtPlanoAcao").val("");
    $("#txtEvidencias").val("");
    $("#txtParticipante").val("");
    $("#selGut").val("").change();
    tabela = tbl;
    tipo = tp;
    idc = "";

    $("#ModelPlanoAcao").modal({backdrop: "static"});
}

$("#btnSalvar").on('click', function () {
    var test = true;
    test = (!validade("#txtPlanoAcao") ? false : test);
    test = (!validade("#txtEvidencias") ? false : test);
    test = (!validade("#selGut") ? false : test);
    if (test) {
        Loading();
        $("#ModelPlanoAcao").modal('hide');

        if (tipo === 1) {
            var url = (idc === "" ? "/pdi/adicionar_plano_acao" : "/pdi/editar_plano_acao");
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'html',
                data: {
                    'id_avaliacao_quantitativa': tabela,
                    'id_plano_acao': idc,
                    'plano_acao': $("#txtPlanoAcao").val(),
                    'evidencia': $("#txtEvidencias").val(),
                    'gut': $("#selGut").val(),
                    'participantes': $("#txtParticipante").val()
                }
            }).done(function (data) {
                var retorno = JSON.parse(data);
                FimLoading();
                if (retorno["status"]) {
                    var t = $('#tbl' + tabela).DataTable();
                    if (idc === "") {
                        t.row.add([$("#txtPlanoAcao").val(), $("#txtEvidencias").val(), $("#selGut").val(), $("#txtParticipante").val(),
                            '<button type="button" class="btn btn-link" onclick="javascript:edit_plano(' + retorno["id"] + ',' + tabela + ',1); "><i class="fa fa-pencil-square-o fa-2x"></i></button>',
                            '<button type="button" class="btn btn-link" onclick="javascript:remover_plano(' + retorno["id"] + ',' + tabela + ',1); "><i class="fa fa-trash fa-2x"></i></button>']).draw(false);
                    } else {
                        t.clear().draw();
                        $.each(retorno["planos"], function (index, value) {
                            t.row.add([value["plano_acao"], value["evidencia"], value["gut"], value["participantes"],
                                '<button type="button" class="btn btn-link" onclick="javascript:edit_plano(' + value["id"] + ',' + value["id_avaliacao_indicadores_quantitativos"] + ',1); "><i class="fa fa-pencil-square-o fa-2x"></i></button>',
                                '<button type="button" class="btn btn-link" onclick="javascript:remover_plano(' + value["id"] + ',' + value["id_avaliacao_indicadores_quantitativos"] + ',1); "><i class="fa fa-trash fa-2x"></i></button>']).draw(false);
                        });
                    }
                    ModalSucesso("", "", retorno["message"]);
                } else {
                    ModalErro(retorno["message"]);
                }

            });
        } else if (tipo === 2) {
            var url = (idc === "" ? "/pdi/adicionar_plano_acao_habilidade" : "/pdi/editar_plano_acao_habilidade");
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'html',
                data: {
                    'id_avaliacao_qualitativa': tabela,
                    'id_plano_acao': idc,
                    'plano_acao': $("#txtPlanoAcao").val(),
                    'evidencia': $("#txtEvidencias").val(),
                    'gut': $("#selGut").val(),
                    'participantes': $("#txtParticipante").val()
                }
            }).done(function (data) {
                var retorno = JSON.parse(data);
                FimLoading();
                if (retorno["status"]) {
                    var t = $('#tblHabilidades' + tabela).DataTable();

                    if (idc === "") {
                        t.row.add([$("#txtPlanoAcao").val(), $("#txtEvidencias").val(), $("#selGut").val(), $("#txtParticipante").val(),
                            '<button type="button" class="btn btn-link" onclick="javascript:edit_plano(' + retorno["id"] + ',' + tabela + ',2); "><i class="fa fa-pencil-square-o fa-2x"></i></button>',
                            '<button type="button" class="btn btn-link" onclick="javascript:remover_plano(' + retorno["id"] + ',' + tabela + ', 2); "><i class="fa fa-trash fa-2x"></i></button>']).draw(false);
                    } else {
                        t.clear().draw();
                        $.each(retorno["planos"], function (index, value) {
                            t.row.add([value["plano_acao"], value["evidencia"], value["gut"], value["participantes"],
                                '<button type="button" class="btn btn-link" onclick="javascript:edit_plano(' + value["id"] + ',' + value["id_avaliacao_indicadores_quantitativos"] + ',1); "><i class="fa fa-pencil-square-o fa-2x"></i></button>',
                                '<button type="button" class="btn btn-link" onclick="javascript:remover_plano(' + value["id"] + ',' + value["id_avaliacao_indicadores_quantitativos"] + ',1); "><i class="fa fa-trash fa-2x"></i></button>']).draw(false);
                        });
                    }
                    ModalSucesso("", "", retorno["message"]);
                } else {
                    ModalErro(retorno["message"]);
                }
            });
        }

        FimLoading();

    } else {
        ModalFaltaCadastro();
    }
});

function remover_plano(id_avaliacao_plano_acao, tbl, tp) {
    idc = id_avaliacao_plano_acao;
    tabela = tbl;
    tipo = tp;
    ModalRemover();
}

function edit_plano(id_avaliacao_plano_acao, tbl, tp) {
    Loading();

    if (tp === 1) {
        $.ajax({
            url: '/pdi/busca_plano_acao',
            type: 'post',
            dataType: 'html',
            data: {
                'id_avaliacao_plano_acao': id_avaliacao_plano_acao
            }
        }).done(function (data) {
            FimLoading();

            var retorno = JSON.parse(data);
            if (retorno["status"]) {
                value = retorno["plano"];
                $("#txtPlanoAcao").val(value["plano_acao"]);
                $("#txtEvidencias").val(value["evidencia"]);
                $("#txtParticipante").val(value["participantes"]);
                $("#selGut").val(value["gut"]).change();
                tabela = tbl;
                tipo = tp;
                idc = id_avaliacao_plano_acao;

                $("#ModelPlanoAcao").modal({backdrop: "static"});

            } else {
                ModalErro();
            }
        });
    } else {
        $.ajax({
            url: '/pdi/busca_plano_acao_habilidades',
            type: 'post',
            dataType: 'html',
            data: {
                'id_avaliacao_plano_acao': id_avaliacao_plano_acao
            }
        }).done(function (data) {
            FimLoading();

            var retorno = JSON.parse(data);
            if (retorno["status"]) {
                value = retorno["plano"];
                $("#txtPlanoAcao").val(value["plano_acao"]);
                $("#txtEvidencias").val(value["evidencia"]);
                $("#txtParticipante").val(value["participantes"]);
                $("#selGut").val(value["gut"]).change();
                tabela = tbl;
                tipo = tp;
                idc = id_avaliacao_plano_acao;

                $("#ModelPlanoAcao").modal({backdrop: "static"});

            } else {
                ModalErro();
            }
        });
    }

    FimLoading();
}

$('#remover').on('click', function () {
    Loading();

    if (tipo === 1) {
        $.ajax({
            url: '/pdi/remover_plano_acao',
            type: 'post',
            dataType: 'html',
            data: {
                'id_avaliacao_plano_acao': idc
            }
        }).done(function (data) {
            FimLoading();
            var retorno = JSON.parse(data);
            if (retorno["status"]) {
                var t = $('#tbl' + tabela).DataTable();
                t.clear().draw();
                $.each(retorno["planos"], function (index, value) {
                    t.row.add([value["plano_acao"], value["evidencia"], value["gut"], value["participantes"],
                        '<button type="button" class="btn btn-link" onclick="javascript:edit_plano(' + value["id"] + ',' + value["id_avaliacao_indicadores_quantitativos"] + ',1); "><i class="fa fa-pencil-square-o fa-2x"></i></button>',
                        '<button type="button" class="btn btn-link" onclick="javascript:remover_plano(' + value["id"] + ',' + value["id_avaliacao_indicadores_quantitativos"] + ',1); "><i class="fa fa-trash fa-2x"></i></button>']).draw(false);
                });
            } else {
                ModalErroRemover();
            }
        });
    } else if (tipo === 2) {
        $.ajax({
            url: '/pdi/remover_plano_acao_habilidade',
            type: 'post',
            dataType: 'html',
            data: {
                'id_avaliacao_plano_acao': idc
            }
        }).done(function (data) {
            FimLoading();
            var retorno = JSON.parse(data);
            if (retorno["status"]) {
                var t = $('#tblHabilidades' + tabela).DataTable();
                t.clear().draw();
                $.each(retorno["planos"], function (index, value) {
                    t.row.add([value["plano_acao"], value["evidencia"], value["gut"], value["participantes"],
                        '<button type="button" class="btn btn-link"  onclick="javascript:edit_plano(' + value["id"] + ',' + value["id_avaliacao_indicadores_qualitativa"] + ',2); "><i class="fa fa-pencil-square-o fa-2x"></i></button>',
                        '<button type="button" class="btn btn-link"  onclick="javascript:remover_plano(' + value["id"] + ',' + value["id_avaliacao_indicadores_qualitativa"] + ', 2); "><i class="fa fa-trash fa-2x"></i></button>']).draw(false);
                });
            } else {
                ModalErroRemover();
            }
        });
    }

    FimLoading();

});

$("#save").on('click', function () {
    Loading();

    $.ajax({
        url: '/pdi/finalizar',
        type: 'post',
        dataType: 'html',
        data: {
            'id_avaliacao': $("#txtId").val()
        }
    }).done(function (data) {
        FimLoading();
        var retorno = JSON.parse(data);

        (retorno["status"] ? ModalSucesso("/pdi", "", retorno["message"]) : ModalErro(retorno["message"]));

    });
});

function emitir_pdi(object) {
    var id_avaliacao = parseInt($(object).attr("id_avaliacao"));
    $.redirect("/pdi/emitir_pdi", {"id_avaliacao": id_avaliacao});
}

function abre_plano(object) {
    var id_avaliacao = parseInt($(object).attr("id_avaliacao"));
    $.redirect("/pdi/abre_plano", {"id_avaliacao": id_avaliacao});
}

$("#concluir_plano_acao").on('click', function () {
    Loading();

    $.ajax({
        url: '/pdi/concluir',
        type: 'post',
        dataType: 'html',
        data: {
            'id_avaliacao': $("#txtId").val()
        }
    }).done(function (data) {
        FimLoading();
        var retorno = JSON.parse(data);

        (retorno["status"] ? ModalSucesso("/pdi/concluir_lista", "", retorno["message"]) : ModalErro(retorno["message"]));

    });
});

function atualizar_plano_acao(object) {
    var id_avaliacao = parseInt($(object).attr("id_avaliacao"));
    $.redirect("/pdi/atualizar_pdi", {"id_avaliacao": id_avaliacao});
}

$("#close_plano_acao").on('click', function () {
    Loading();
    $.redirect("/pdi/atualizar_plano_lista");
});

$("#atualizar_status_plano_acao").on('click', function () {

    Loading();

    var indicadores_quantitativos = {};
    var indicadores_qualitativos = {};

    $("select[tipo=quantitativos]").each(function (index, element) {
        indicadores_quantitativos[index] = {
            "id_indicador": $(element).attr("id_plano_acao"),
            "situacao": $(element).val()
        };
    });

    $("select[tipo=qualitativos]").each(function (index, element) {
        indicadores_qualitativos[index] = {
            "id_indicador": $(element).attr("id_plano_acao"),
            "situacao": $(element).val()
        };
    });

    $.ajax({
        url: '/pdi/atualizar_status_plano_acao',
        type: 'post',
        dataType: 'html',
        data: {
            'id_avaliacao': $("#txtId").val(),
            'indicadores_quantitativos': indicadores_quantitativos,
            'indicadores_qualitativos': indicadores_qualitativos
        }
    }).done(function (data) {
        FimLoading();

        var retorno = JSON.parse(data);

        (retorno["status"] ? ModalSucesso("/pdi/atualizar_plano_lista", "", retorno["message"]) : ModalErro(retorno["message"]));

    });

});