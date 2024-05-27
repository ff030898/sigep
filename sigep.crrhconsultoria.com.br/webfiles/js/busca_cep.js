function pesquisacep(valor) {

    var cep = valor.replace(/\D/g, '');

    if (cep != "") {

        var validacep = /^[0-9]{8}$/;

        if (validacep.test(cep)) {

            var script = document.createElement('script');

            script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';

            document.body.appendChild(script);
        } else {
            alert("Cep inválido");
        }
    }
}

function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
        var cidade = conteudo.localidade;

        $("#selEstado").val(conteudo.uf).change();
        $("#txtEndereco").val(conteudo.logradouro);
        $("#txtBairro").val(conteudo.bairro);
        $("#selCidade").val(cidade.toUpperCase()).change();
        $("#txtCidadeIBGE").val(conteudo.ibge);

    } else {
        alert("CEP não encontrado.");
    }
}
