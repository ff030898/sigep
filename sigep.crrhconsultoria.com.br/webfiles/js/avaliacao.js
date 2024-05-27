$(function () {
    $('input').keyup(function () {
        this.value = this.value.toUpperCase();
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
function avaliar(id_funcionario) {
    Loading();
    $.redirect("/avaliacao/avaliar", {'id': id_funcionario});
}

$("#info").on('click', function () {
    $("#ModalInfo").modal({backdrop: "static"});
});
function abre_avaliacao(object) {
    var id_funcionario = parseInt($(object).attr("id_funcionario"));
    $.redirect("/avaliacao/avaliar", {"id_funcionario": id_funcionario});
}

function mostra_negacao(object) {
    var val = $(object).val();
    var negacao = $(object).attr("negacao");
    if (val === "0") {
        $("#" + negacao).removeClass('hidden');
        $("#" + negacao).show();
    } else {
        $("#" + negacao).hide();
    }
}

$("#save").on('click', function () {
    var test = true;
    var mensagem_erro = "";
    var msg_erro;
    var c = 0;
    var neg = 0;
    var indicadores_quantitativos = {};
    var indicadores_qualitativos = {};
    $("input[type=radio][tipo=comp]").each(function () {
        var name = $(this).attr("name");
        if ($("input[name=" + name + "]:checked").length) {
            if ($("input[name=" + name + "]:checked").val() === "0" && !$("input[name=" + name + "Neg]:checked").length) {
                test = false;
                if (msg_erro !== $("input[name=" + name + "Neg]").attr("mensagem")) {
                    mensagem_erro += $("input[name=" + name + "Neg]").attr("mensagem") + "\n";
                    msg_erro = $("input[name=" + name + "Neg]").attr("mensagem");
                    neg = 0;
                }
            }

        } else {
            test = false;
            if (msg_erro !== $("input[name=" + name + "]").attr("mensagem")) {
                mensagem_erro += $("input[name=" + name + "]").attr("mensagem") + "\n";
                msg_erro = $("input[name=" + name + "]").attr("mensagem");
            }
        }
    });
    $("input[type=radio][tipo=comp]:checked").each(function () {

        var name = $(this).attr("name");
        var neg = 0;
        if ($(this).val() === "0" && $("input[name=" + name + "Neg]:checked").length) {
            neg = $("input[name=" + name + "Neg]:checked").attr("id_indicadores_quant_negacao");
        }

        indicadores_quantitativos[c] = {
            "id_indicadores_quant_tipo": $(this).attr("id_indicadores_quant_tipo"),
            "id_indicadores_quant_sub": $(this).attr("id_indicadores_quant_sub"),
            "id_perfil_cargo_indicadores": $(this).attr("id_perfil_cargo_indicadores"),
            "id_indicadores_quant_sub_negacao": neg,
            "resposta": $(this).val()
        };
        c++;
    });
    $("input[type=radio][tipo=habi]").each(function () {
        var name = $(this).attr("name");
        if (!$("input[name=" + name + "]:checked").length) {
            test = false;
            if (msg_erro !== $("input[name=" + name + "]").attr("mensagem")) {
                mensagem_erro += $("input[name=" + name + "]").attr("mensagem") + "\n";
                msg_erro = $("input[name=" + name + "]").attr("mensagem");
            }
        }
    });
    c = 0;
    $("input[type=radio][tipo=habi]:checked").each(function () {
        indicadores_qualitativos[c] = {
            "id_indicadores_quali": $(this).attr("id_indicadores_quali"),
            "id_indicadores_quali_graduacao": $(this).attr("id_indicadores_quali_graduacao"),
            "intermediario": $(this).attr("intermediario")
        };
        c++;
    });
    if (test) {
        Loading();
        $.ajax({
            url: "/avaliacao/adicionar",
            type: 'post',
            dataType: 'html',
            data: {
                'id_funcionario': $("#txtIdFuncionario").val(),
                'id_pessoa': $("#txtIdPessoa").val(),
                'id_cargo': $("#txtIdCargo").val(),
                'id_perfil_cargo': $("#txtIdPerfilCargo").val(),
                'indicadores_quantitativos': indicadores_quantitativos,
                'indicadores_qualitativos': indicadores_qualitativos
            }
        }).done(function (data) {
            var retorno = JSON.parse(data);
            (retorno["status"] ? ModalSucesso("/avaliacao", "", retorno["message"]) : ModalErro(retorno["message"]));
        });
    } else {
        ModalFaltaCadastro(mensagem_erro);
    }
});
$("#close").on('click', function () {
    Loading();
    $.redirect("/avaliacao");
});
function abre_dica(object) {
    Loading();
    var id_funcionario = $(object).attr("id_funcionario");
    $.redirect("/avaliacao/dica_funcionario", {"id_funcionario": id_funcionario});
}

$("#close_dica").on('click', function () {
    Loading();
    $.redirect("/avaliacao/dica");
});

$("#btnGerarComparativo").on('click', function () {
    var test = true;
    test = (validade($("#txtDataIni"), true) ? test : false);
    test = (validade($("#txtDataFim"), true) ? test : false);
    if (test) {
        Loading();
        $.ajax({
            url: "/avaliacao/gerarComparativo",
            type: 'post',
            dataType: 'html',
            data: {
                'dataIni': $("#txtDataIni").val(),
                'dataFim': $("#txtDataFim").val()
            }
        }).done(function (data) {
            FimLoading();
            var retorno = JSON.parse(data);
            qualiXquant(retorno["qualiXquant"]["resultado_quantitativo"], retorno["qualiXquant"]["resultado_qualitativa"]);

            gdpXescolaridade(retorno["GdpXEscolaridade"]);

            gdpXsexo(retorno["gdpXsexo"]);
            gdpXsalario(retorno["gdpXsalario"]);
            gdpXfaixaIdade(retorno["gdpXfaixaIdade"]);
            gdpXarea(retorno["gdpXarea"]);
            gdpXcargo(retorno["gdpXcargo"]);
            gdpXnat(retorno["gdpXnat"]);
            gdpXfuncionario(retorno["gdpxFuncionario"]);
        });
    } else {
        ModalFaltaCadastro("Verifique se os campos de datas estão devidamente preenchidos");
    }

});

function qualiXquant(resultado_quantitativo, resultado_qualitativa) {

    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChartQualiXQuant);
    function drawChartQualiXQuant() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
            ['Quantitativo', resultado_quantitativo],
            ['Qualitativo', resultado_qualitativa],
        ]);

        var options = {
            title: 'Comparativo Qualitativo x Quantitativos',
            height: '100%',
            width: '100%',
            chartArea: {
                width: '100%'
            },
            is3D: true,
            legend: {
                position: "bottom"
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('qualiXquant'));
        chart.draw(data, options);
    }

}

function gdpXescolaridade(dados) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawAnnotations);

    function drawAnnotations() {
        var data = google.visualization.arrayToDataTable(dados);

        var options = {
            title: 'GDP por Escolaridade',
            height: '100%',
            width: '100%',
            chartArea: {
                width: '100%'
            },
            annotations: {
                alwaysOutside: true,
                textStyle: {
                    fontSize: 12,
                    auraColor: 'none',
                    color: '#555'
                },
                boxStyle: {
                    stroke: '#ccc',
                    strokeWidth: 1,
                    gradient: {
                        color1: '#f3e5f5',
                        color2: '#f3e5f5',
                        x1: '0%', y1: '0%',
                        x2: '100%', y2: '100%'
                    }
                }
            },
            hAxis: {
                title: 'Grau de Instrução',
                minValue: 0
            },
            vAxis: {
                title: 'Pontuação no GDP'
            }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('gdpXescolaridade'));
        chart.draw(data, options);
    }

}

function gdpXsexo(dados) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawAnnotations);

    function drawAnnotations() {
        var data = google.visualization.arrayToDataTable(dados);

        var options = {
            title: 'GDP por Sexo',
            height: '100%',
            width: '100%',
            chartArea: {
                width: '100%'
            },
            annotations: {
                alwaysOutside: true,
                textStyle: {
                    fontSize: 12,
                    auraColor: 'none',
                    color: '#555'
                },
                boxStyle: {
                    stroke: '#ccc',
                    strokeWidth: 1,
                    gradient: {
                        color1: '#f3e5f5',
                        color2: '#f3e5f5',
                        x1: '0%', y1: '0%',
                        x2: '100%', y2: '100%'
                    }
                }
            },
            hAxis: {
                title: 'Sexo',
                minValue: 0
            },
            vAxis: {
                title: 'Pontuação no GDP'
            }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('gdpXsexo'));
        chart.draw(data, options);
    }
}

function gdpXsalario(dados) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawAnnotations);

    function drawAnnotations() {
        var data = google.visualization.arrayToDataTable(dados);

        var options = {
            title: 'GDP por Faixa Salarial',
            height: '100%',
            width: '100%',
            chartArea: {
                width: '100%'
            },
            annotations: {
                alwaysOutside: true,
                textStyle: {
                    fontSize: 12,
                    auraColor: 'none',
                    color: '#555'
                },
                boxStyle: {
                    stroke: '#ccc',
                    strokeWidth: 1,
                    gradient: {
                        color1: '#f3e5f5',
                        color2: '#f3e5f5',
                        x1: '0%', y1: '0%',
                        x2: '100%', y2: '100%'
                    }
                }
            },
            hAxis: {
                title: 'Faixa Salarial',
                minValue: 0
            },
            vAxis: {
                title: 'Pontuação no GDP'
            }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('gdpXsalario'));
        chart.draw(data, options);
    }

}

function gdpXfaixaIdade(dados) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawAnnotations);

    function drawAnnotations() {
        var data = google.visualization.arrayToDataTable(dados);

        var options = {
            title: 'GDP por Idade',
            height: '100%',
            width: '100%',
            chartArea: {
                width: '100%'
            },
            annotations: {
                alwaysOutside: true,
                textStyle: {
                    fontSize: 12,
                    auraColor: 'none',
                    color: '#555'
                },
                boxStyle: {
                    stroke: '#ccc',
                    strokeWidth: 1,
                    gradient: {
                        color1: '#f3e5f5',
                        color2: '#f3e5f5',
                        x1: '0%', y1: '0%',
                        x2: '100%', y2: '100%'
                    }
                }
            },
            hAxis: {
                title: 'Faixa de Idade',
                minValue: 0
            },
            vAxis: {
                title: 'Pontuação no GDP'
            }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('gdpXfaixaIdade'));
        chart.draw(data, options);
    }
}

function gdpXarea(dados) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawAnnotations);

    function drawAnnotations() {
        var data = google.visualization.arrayToDataTable(dados);

        var options = {
            title: 'GDP por Área',
            height: '100%',
            width: '100%',
            chartArea: {
                width: '100%'
            },
            annotations: {
                alwaysOutside: true,
                textStyle: {
                    fontSize: 12,
                    auraColor: 'none',
                    color: '#555'
                },
                boxStyle: {
                    stroke: '#ccc',
                    strokeWidth: 1,
                    gradient: {
                        color1: '#f3e5f5',
                        color2: '#f3e5f5',
                        x1: '0%', y1: '0%',
                        x2: '100%', y2: '100%'
                    }
                }
            },
            hAxis: {
                title: 'Área',
                minValue: 0
            },
            vAxis: {
                title: 'Pontuação no GDP'
            }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('gdpXarea'));
        chart.draw(data, options);
    }
}

function gdpXcargo(dados) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawAnnotations);

    function drawAnnotations() {
        var data = google.visualization.arrayToDataTable(dados);

        var options = {
            title: 'GDP por Cargo',
            height: '100%',
            width: '100%',
            chartArea: {
                width: '100%'
            },
            annotations: {
                alwaysOutside: true,
                textStyle: {
                    fontSize: 12,
                    auraColor: 'none',
                    color: '#555'
                },
                boxStyle: {
                    stroke: '#ccc',
                    strokeWidth: 1,
                    gradient: {
                        color1: '#f3e5f5',
                        color2: '#f3e5f5',
                        x1: '0%', y1: '0%',
                        x2: '100%', y2: '100%'
                    }
                }
            },
            hAxis: {
                title: 'Cargo',
                minValue: 0
            },
            vAxis: {
                title: 'Pontuação no GDP'
            }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('gdpXcargo'));
        chart.draw(data, options);
    }
}

function gdpXnat(dados) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawAnnotations);

    function drawAnnotations() {
        var data = google.visualization.arrayToDataTable(dados);

        var options = {
            title: 'GDP por Natureza Ocupacional',
            height: '100%',
            width: '100%',
            chartArea: {
                width: '100%'
            },
            annotations: {
                alwaysOutside: true,
                textStyle: {
                    fontSize: 12,
                    auraColor: 'none',
                    color: '#555'
                },
                boxStyle: {
                    stroke: '#ccc',
                    strokeWidth: 1,
                    gradient: {
                        color1: '#f3e5f5',
                        color2: '#f3e5f5',
                        x1: '0%', y1: '0%',
                        x2: '100%', y2: '100%'
                    }
                }
            },
            hAxis: {
                title: 'Natureza Ocupacional',
                minValue: 0
            },
            vAxis: {
                title: 'Pontuação no GDP'
            }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('gdpXnat'));
        chart.draw(data, options);
    }
}

function gdpXfuncionario(dados) {
    console.log(dados);
    var t = $('#gdpXfuncionario').DataTable({
        order: [],
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "Todas"]],
        stateSave: true
    });


    t.clear().draw();

    $.each(dados, function (kye, entry) {
        t.row.add([entry.descricao, entry.nome, entry.data, entry.gdp]).draw(true);
    });
}
