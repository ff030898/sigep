<?php

class Pessoa_Model extends Model {

    public $_tabela = "pessoa";

    public function __construct() {
        parent::__construct();
    }

    public function getPessoa() {
        $result = $this->read(NULL, NULL, NULL, "nome ASC", "id, nome, cidade, fone1");
        return $result;
    }

    public function getPessoaByID($id_pessoa) {
        $result = $this->read("id = '{$id_pessoa}'");
        return $result[0];
    }

    public function adicionar($nome, $dt_nascimento, $sexo, $uf_nascimento, $naturalidade, $grau_instrucao, $estado_civil, $nome_pai, $nome_mae, $identidade, $orgao_emissao, $uf_emissao, $dt_emissao, $cpf, $cartao_sus, $nis, $cep, $endereco, $numero, $bairro, $estado, $cidade, $fone1, $fone2, $email, $complemento, $cutis, $cabelo, $olhos, $estatura, $peso, $fator_rh, $sangue, $port_especial, $doador, $certidao, $dt_certidao, $termo, $livro, $folha, $cartorio, $uf_certidao, $cidade_certidao, $observacao) {

        $result = $this->read("cpf = '{$cpf}' || identidade = '{$identidade}'");

        if (!count($result)) {

            $dt_nascimento = implode("-", array_reverse(explode("/", $dt_nascimento)));
            $dt_emissao = implode("-", array_reverse(explode("/", $dt_emissao)));
            $dt_certidao = implode("-", array_reverse(explode("/", $dt_certidao)));

            $saida = $this->insert(array(
                "nome" => $nome,
                "dt_nascimento" => $dt_nascimento,
                "sexo" => $sexo,
                "uf_nascimento" => $uf_nascimento,
                "naturalidade" => $naturalidade,
                "grau_instrucao" => $grau_instrucao,
                "estado_civil" => $estado_civil,
                "nome_pai" => $nome_pai,
                "nome_mae" => $nome_mae,
                "identidade" => $identidade,
                "orgao_emissao" => $orgao_emissao,
                "uf_emissao" => $uf_emissao,
                "dt_emissao" => $dt_emissao,
                "cpf" => $cpf,
                "cartao_sus" => $cartao_sus,
                "nis" => $nis,
                "cep" => $cep,
                "endereco" => $endereco,
                "numero" => $numero,
                "bairro" => $bairro,
                "estado" => $estado,
                "cidade" => $cidade,
                "fone1" => $fone1,
                "fone2" => $fone2,
                "email" => $email,
                "complemento" => $complemento,
                "cutis" => $cutis,
                "cabelo" => $cabelo,
                "olhos" => $olhos,
                "estatura" => $estatura,
                "peso" => $peso,
                "fator_rh" => $fator_rh,
                "tipo_sangue" => $sangue,
                "port_especial" => $port_especial,
                "doador_sangue" => $doador,
                "tipo_certidao" => $certidao,
                "dt_certidao" => $dt_certidao,
                "termo" => $termo,
                "livro" => $livro,
                "folha" => $folha,
                "cartorio" => $cartorio,
                "uf_certidao" => $uf_certidao,
                "cidade_certidao" => $cidade_certidao,
                "observacao" => $observacao,
                "status" => 1,
                "dt_ini" => date('Y-m-d'),
                "hora_ini" => date('H:i:s'),
                "user_ini" => $_SESSION["user_name"]
                    ), "sadsa");
            if (is_numeric($saida)) {
                $arr = array("status" => true);
                $this->log("Inserção", "Adicionada a Pessoa " . $nome . " com Sucesso.");
            } else {
                $arr = array("status" => false, "message" => "Erro ao adicionar o registro.");
                $this->log("Inserção", "Erro ao adicionar a Pessoa " . $nome . ".");
            }
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Inserção", "Erro ao adicionar a Pessoa " . $nome . " pois já existe um igual.");
        }

        return json_encode($arr);
    }

    public function editar($id_pessoa, $status, $nome, $dt_nascimento, $sexo, $uf_nascimento, $naturalidade, $grau_instrucao, $estado_civil, $nome_pai, $nome_mae, $identidade, $orgao_emissao, $uf_emissao, $dt_emissao, $cpf, $cartao_sus, $nis, $cep, $endereco, $numero, $bairro, $estado, $cidade, $fone1, $fone2, $email, $complemento, $cutis, $cabelo, $olhos, $estatura, $peso, $fator_rh, $sangue, $port_especial, $doador, $certidao, $dt_certidao, $termo, $livro, $folha, $cartorio, $uf_certidao, $cidade_certidao, $observacao) {

        $result = $this->read("(cpf = '{$cpf}' || identidade = '{$identidade}') AND id != '{$id_pessoa}'");

        if (!count($result)) {

            $dt_nascimento = implode("-", array_reverse(explode("/", $dt_nascimento)));
            $dt_emissao = implode("-", array_reverse(explode("/", $dt_emissao)));
            $dt_certidao = implode("-", array_reverse(explode("/", $dt_certidao)));

            $saida = $this->update(array(
                "status" => $status,
                "nome" => $nome,
                "dt_nascimento" => $dt_nascimento,
                "sexo" => $sexo,
                "uf_nascimento" => $uf_nascimento,
                "naturalidade" => $naturalidade,
                "grau_instrucao" => $grau_instrucao,
                "estado_civil" => $estado_civil,
                "nome_pai" => $nome_pai,
                "nome_mae" => $nome_mae,
                "identidade" => $identidade,
                "orgao_emissao" => $orgao_emissao,
                "uf_emissao" => $uf_emissao,
                "dt_emissao" => $dt_emissao,
                "cpf" => $cpf,
                "cartao_sus" => $cartao_sus,
                "nis" => $nis,
                "cep" => $cep,
                "endereco" => $endereco,
                "numero" => $numero,
                "bairro" => $bairro,
                "estado" => $estado,
                "cidade" => $cidade,
                "fone1" => $fone1,
                "fone2" => $fone2,
                "email" => $email,
                "complemento" => $complemento,
                "cutis" => $cutis,
                "cabelo" => $cabelo,
                "olhos" => $olhos,
                "estatura" => $estatura,
                "peso" => $peso,
                "fator_rh" => $fator_rh,
                "tipo_sangue" => $sangue,
                "port_especial" => $port_especial,
                "doador_sangue" => $doador,
                "tipo_certidao" => $certidao,
                "dt_certidao" => $dt_certidao,
                "termo" => $termo,
                "livro" => $livro,
                "folha" => $folha,
                "cartorio" => $cartorio,
                "uf_certidao" => $uf_certidao,
                "cidade_certidao" => $cidade_certidao,
                "observacao" => $observacao,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_pessoa}'");
            if (is_numeric($saida)) {
                $arr = array("status" => true);
                $this->log("Alteração", "Alterada a Pessoa " . $nome . " com Sucesso.");
            } else {
                $arr = array("status" => false, "message" => "Erro ao alterar o registro.");
                $this->log("Inserção", "Erro ao alterar a Pessoa " . $nome . ".");
            }
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Inserção", "Erro ao alterar a Pessoa " . $nome . " pois já existe um igual.");
        }
        return json_encode($arr);
    }

    public function remover($id_pessoa) {

        $result = $this->read("id = '{$id_pessoa}'", NULL, NULL, NULL, "nome");

        $saida = $this->delete("id = '{$id_pessoa}'");

        if (is_numeric($saida)) {
            $arr = array("status" => true);
            $this->log("Remoção", "Removido a Pessoa " . $id_pessoa . " com Sucesso.");
        } else {
            $arr = array("status" => false, "message" => "Erro ao remover o registro.");
            $this->log("Remoção", "Erro ao remover a Pessoa " . $id_pessoa . ".");
        }
        return json_encode($arr);
    }

    public function search($pesquisa, $funcao) {
        $result = $this->query("SELECT pessoa.id, pessoa.nome, pessoa.cpf FROM `pessoa` WHERE (pessoa.id LIKE '%{$pesquisa}%' OR pessoa.nome LIKE '%{$pesquisa}%' OR pessoa.cpf LIKE '%{$pesquisa}%') AND pessoa.status = '1' ORDER BY pessoa.nome ASC ");

        $mostra = null;
        $total = count($result);
        $mostra .= '<div style="float: left;">';
        $mostra .= '
            <div class="dataTable_wrapper">
                <table width="100%" class="table table-striped table-bordered table-hover" id="empresas_listas">
                    <tbody>
                        <tr>
                            <td>Nome</td>
                            <td>Cpf</td>
                        </tr>
                    </tbocy>
        ';
        for ($i = 0; $i <= $total - 1; $i++) {

            $mostra .= '<tr>';
            $mostra .= '<td><a href="javascript:' . $funcao . '(\'' . $result[$i]["id"] . "-" . $result[$i]["nome"] . '\',\'' . $result[$i]["nome"] . '\')">' . $result[$i]["nome"] . "</a></td>";
            $mostra .= '<td><a href="javascript:' . $funcao . '(\'' . $result[$i]["id"] . "-" . $result[$i]["nome"] . '\',\'' . $result[$i]["nome"] . '\')">' . $result[$i]["cpf"] . "</a></td>";
            $mostra .= '</tr>';
        }

        $mostra = ($total == 0 ? "Não encontrado nenhum registro" : $mostra);

        $mostra .= '<div style = "float: right;">
            <a href = "javascript:fechar_pesquisa_pessoa()"><i class = "fa fa-window-close-o fa-lg"></i></a>
            </div>';

        if ($total > 0) {
            return $mostra;
        } else {
            return null;
        }
    }

}
