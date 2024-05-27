function setPesquisa(pesquisa) {
    $('#txtUsuario').val(pesquisa);
    $("#myModal2").modal("hide");
}

function showResult2(str) {
    str = $('#txtSearch').val();

    if (str.length == 0) {
        document.getElementById("livesearch").innerHTML = "";
        document.getElementById("livesearch").style.border = "0px";
        return;
    }

    $.ajax({
        url: '/usuarios/search_acessos',
        type: 'post',
        dataType: 'html',
        data: {
            'search': $('#txtSearch').val(),
        }
    }).done(function (data) {
        resultado = data;
        document.getElementById("livesearch").innerHTML = resultado;
        document.getElementById("livesearch").style.border = "1px solid #A5ACB2";

    });
}

$('#btnDataIni').click(function () {
    $('#txtDataIni').datepicker("show");
});

$('#btnDataFim').click(function () {
    $('#txtDataFim').datepicker("show");
});

$('#search').on('click', function () {
    $('#txtSearch').val();
    showResult2();
    $("#myModal2").modal({backdrop: "static"});
});

$("#btnGerar").click(function () {
    $("#loading").modal({backdrop: "static"});
    $("#resultado").html("");
    $('#resultado').append("");
    $.ajax({
        url: '/usuarios/gerar',
        type: 'post',
        dataType: 'html',
        data: {
            'usuario': $('#txtUsuario').val(),
            'data_ini': $('#txtDataIni').val(),
            'data_fim': $('#txtDataFim').val(),
            'saida': $("input[name='radSaida']:checked").val()
        }
    }).done(function (data) {
        $("#loading").modal("hide");
        if (data === "OK-PDF") {
            window.location.replace("/temp/Acessos.pdf");
        } else if (data === "OK-EXCEL") {
            window.location.replace("/temp/Acessos.xlsx");
        } else {
            $("#resultado").html("");
            $('#resultado').append("");
            $('#resultado').append(data);
        }
    });
});