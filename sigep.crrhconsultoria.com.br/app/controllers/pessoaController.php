<?php

class pessoa extends Controller {

    public function index_action() {

        $pessoa = new Pessoa_Model();
        $dados["pessoa"] = $pessoa->getPessoa();

        $this->view('header');
        $this->view('menu');
        $this->view('content_pessoa', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_pessoa_add');
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $pessoa = new Pessoa_Model();

        $nome = $security->antiInjection(filter_input(INPUT_POST, "nome"));
        $dt_nascimento = $security->antiInjection(filter_input(INPUT_POST, "dt_nascimento"));
        $sexo = $security->antiInjection(filter_input(INPUT_POST, "sexo"));
        $uf_nascimento = $security->antiInjection(filter_input(INPUT_POST, "uf_nascimento"));
        $naturalidade = $security->antiInjection(filter_input(INPUT_POST, "naturalidade"));
        $grau_instrucao = $security->antiInjection(filter_input(INPUT_POST, "grau_instrucao"));
        $estado_civil = $security->antiInjection(filter_input(INPUT_POST, "estado_civil"));
        $nome_pai = $security->antiInjection(filter_input(INPUT_POST, "nome_pai"));
        $nome_mae = $security->antiInjection(filter_input(INPUT_POST, "nome_mae"));
        $identidade = $security->antiInjection(filter_input(INPUT_POST, "identidade"));
        $orgao_emissao = $security->antiInjection(filter_input(INPUT_POST, "orgao_emissao"));
        $uf_emissao = $security->antiInjection(filter_input(INPUT_POST, "uf_emissao"));
        $dt_emissao = $security->antiInjection(filter_input(INPUT_POST, "dt_emissao"));
        $cpf = $security->antiInjection(filter_input(INPUT_POST, "cpf"));
        $cartao_sus = $security->antiInjection(filter_input(INPUT_POST, "cartao_sus"));
        $nis = $security->antiInjection(filter_input(INPUT_POST, "nis"));
        $cep = $security->antiInjection(filter_input(INPUT_POST, "cep"));
        $endereco = $security->antiInjection(filter_input(INPUT_POST, "endereco"));
        $numero = $security->antiInjection(filter_input(INPUT_POST, "numero"));
        $bairro = $security->antiInjection(filter_input(INPUT_POST, "bairro"));
        $estado = $security->antiInjection(filter_input(INPUT_POST, "estado"));
        $cidade = $security->antiInjection(filter_input(INPUT_POST, "cidade"));
        $fone1 = $security->antiInjection(filter_input(INPUT_POST, "fone1"));
        $fone2 = $security->antiInjection(filter_input(INPUT_POST, "fone2"));
        $email = $security->antiInjection(filter_input(INPUT_POST, "email"));
        $complemento = $security->antiInjection(filter_input(INPUT_POST, "complemento"));
        $cutis = $security->antiInjection(filter_input(INPUT_POST, "cutis"));
        $cabelo = $security->antiInjection(filter_input(INPUT_POST, "cabelo"));
        $olhos = $security->antiInjection(filter_input(INPUT_POST, "olhos"));
        $estatura = $security->antiInjection(filter_input(INPUT_POST, "estatura"));
        $peso = $security->antiInjection(filter_input(INPUT_POST, "peso"));
        $fator_rh = $security->antiInjection(filter_input(INPUT_POST, "fator_rh"));
        $sangue = $security->antiInjection(filter_input(INPUT_POST, "sangue"));
        $port_especial = $security->antiInjection(filter_input(INPUT_POST, "port_especial"));
        $doador = $security->antiInjection(filter_input(INPUT_POST, "doador"));
        $certidao = $security->antiInjection(filter_input(INPUT_POST, "certidao"));
        $dt_certidao = $security->antiInjection(filter_input(INPUT_POST, "dt_certidao"));
        $termo = $security->antiInjection(filter_input(INPUT_POST, "termo"));
        $livro = $security->antiInjection(filter_input(INPUT_POST, "livro"));
        $folha = $security->antiInjection(filter_input(INPUT_POST, "folha"));
        $cartorio = $security->antiInjection(filter_input(INPUT_POST, "cartorio"));
        $uf_certidao = $security->antiInjection(filter_input(INPUT_POST, "uf_certidao"));
        $cidade_certidao = $security->antiInjection(filter_input(INPUT_POST, "cidade_certidao"));
        $observacao = $security->antiInjection(filter_input(INPUT_POST, "observacao"));

        print_r($pessoa->adicionar($nome, $dt_nascimento, $sexo, $uf_nascimento, $naturalidade, $grau_instrucao, $estado_civil, $nome_pai, $nome_mae, $identidade, $orgao_emissao, $uf_emissao, $dt_emissao, $cpf, $cartao_sus, $nis, $cep, $endereco, $numero, $bairro, $estado, $cidade, $fone1, $fone2, $email, $complemento, $cutis, $cabelo, $olhos, $estatura, $peso, $fator_rh, $sangue, $port_especial, $doador, $certidao, $dt_certidao, $termo, $livro, $folha, $cartorio, $uf_certidao, $cidade_certidao, $observacao));
    }

    public function edit() {
        $pessoa = new Pessoa_Model();
        $security = new securityHelper();

        $dados["pessoa"] = $pessoa->getPessoaByID($security->antiInjection(filter_input(INPUT_POST, 'id')));

        $this->view('header');
        $this->view('menu');
        $this->view('content_pessoa_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $pessoa = new Pessoa_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));
        $nome = $security->antiInjection(filter_input(INPUT_POST, "nome"));
        $dt_nascimento = $security->antiInjection(filter_input(INPUT_POST, "dt_nascimento"));
        $sexo = $security->antiInjection(filter_input(INPUT_POST, "sexo"));
        $uf_nascimento = $security->antiInjection(filter_input(INPUT_POST, "uf_nascimento"));
        $naturalidade = $security->antiInjection(filter_input(INPUT_POST, "naturalidade"));
        $grau_instrucao = $security->antiInjection(filter_input(INPUT_POST, "grau_instrucao"));
        $estado_civil = $security->antiInjection(filter_input(INPUT_POST, "estado_civil"));
        $nome_pai = $security->antiInjection(filter_input(INPUT_POST, "nome_pai"));
        $nome_mae = $security->antiInjection(filter_input(INPUT_POST, "nome_mae"));
        $identidade = $security->antiInjection(filter_input(INPUT_POST, "identidade"));
        $orgao_emissao = $security->antiInjection(filter_input(INPUT_POST, "orgao_emissao"));
        $uf_emissao = $security->antiInjection(filter_input(INPUT_POST, "uf_emissao"));
        $dt_emissao = $security->antiInjection(filter_input(INPUT_POST, "dt_emissao"));
        $cpf = $security->antiInjection(filter_input(INPUT_POST, "cpf"));
        $cartao_sus = $security->antiInjection(filter_input(INPUT_POST, "cartao_sus"));
        $nis = $security->antiInjection(filter_input(INPUT_POST, "nis"));
        $cep = $security->antiInjection(filter_input(INPUT_POST, "cep"));
        $endereco = $security->antiInjection(filter_input(INPUT_POST, "endereco"));
        $numero = $security->antiInjection(filter_input(INPUT_POST, "numero"));
        $bairro = $security->antiInjection(filter_input(INPUT_POST, "bairro"));
        $estado = $security->antiInjection(filter_input(INPUT_POST, "estado"));
        $cidade = $security->antiInjection(filter_input(INPUT_POST, "cidade"));
        $fone1 = $security->antiInjection(filter_input(INPUT_POST, "fone1"));
        $fone2 = $security->antiInjection(filter_input(INPUT_POST, "fone2"));
        $email = $security->antiInjection(filter_input(INPUT_POST, "email"));
        $complemento = $security->antiInjection(filter_input(INPUT_POST, "complemento"));
        $cutis = $security->antiInjection(filter_input(INPUT_POST, "cutis"));
        $cabelo = $security->antiInjection(filter_input(INPUT_POST, "cabelo"));
        $olhos = $security->antiInjection(filter_input(INPUT_POST, "olhos"));
        $estatura = $security->antiInjection(filter_input(INPUT_POST, "estatura"));
        $peso = $security->antiInjection(filter_input(INPUT_POST, "peso"));
        $fator_rh = $security->antiInjection(filter_input(INPUT_POST, "fator_rh"));
        $sangue = $security->antiInjection(filter_input(INPUT_POST, "sangue"));
        $port_especial = $security->antiInjection(filter_input(INPUT_POST, "port_especial"));
        $doador = $security->antiInjection(filter_input(INPUT_POST, "doador"));
        $certidao = $security->antiInjection(filter_input(INPUT_POST, "certidao"));
        $dt_certidao = $security->antiInjection(filter_input(INPUT_POST, "dt_certidao"));
        $termo = $security->antiInjection(filter_input(INPUT_POST, "termo"));
        $livro = $security->antiInjection(filter_input(INPUT_POST, "livro"));
        $folha = $security->antiInjection(filter_input(INPUT_POST, "folha"));
        $cartorio = $security->antiInjection(filter_input(INPUT_POST, "cartorio"));
        $uf_certidao = $security->antiInjection(filter_input(INPUT_POST, "uf_certidao"));
        $cidade_certidao = $security->antiInjection(filter_input(INPUT_POST, "cidade_certidao"));
        $observacao = $security->antiInjection(filter_input(INPUT_POST, "observacao"));

        print_r($pessoa->editar($id, $status, $nome, $dt_nascimento, $sexo, $uf_nascimento, $naturalidade, $grau_instrucao, $estado_civil, $nome_pai, $nome_mae, $identidade, $orgao_emissao, $uf_emissao, $dt_emissao, $cpf, $cartao_sus, $nis, $cep, $endereco, $numero, $bairro, $estado, $cidade, $fone1, $fone2, $email, $complemento, $cutis, $cabelo, $olhos, $estatura, $peso, $fator_rh, $sangue, $port_especial, $doador, $certidao, $dt_certidao, $termo, $livro, $folha, $cartorio, $uf_certidao, $cidade_certidao, $observacao));
    }

    public function remover() {
        $security = new securityHelper();
        $pessoa = new Pessoa_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($pessoa->remover($id));
    }

    public function search() {
        $security = new securityHelper();
        $pessoa = new Pessoa_Model();

        $pesquisa = $security->antiInjection(filter_input(INPUT_POST, "pesquisa"));
        $funcao = $security->antiInjection(filter_input(INPUT_POST, "funcao"));

        print_r($pessoa->search($pesquisa, $funcao));
    }

}
