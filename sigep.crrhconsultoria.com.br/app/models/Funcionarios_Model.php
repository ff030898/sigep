<?php

class Funcionarios_Model extends Model {

    public $_tabela = "funcionarios";

    public function __construct() {
        parent::__construct();
    }

    public function get($cond = "*") {
        $id_cliente = $_SESSION["id_cliente"];

        if ($cond == "1") {
            $result = $this->query("SELECT funcionarios.id, pessoa.nome, cargo.tipo, cargo.descricao, cargo.subtipo, funcionarios.dt_admissao, funcionarios.status FROM `funcionarios` INNER JOIN pessoa ON (pessoa.id = funcionarios.id_pessoa) INNER JOIN cargo ON (cargo.id = funcionarios.id_cargo) WHERE funcionarios.id_cliente = '{$id_cliente}' AND funcionarios.status = '1' ORDER BY pessoa.nome ASC ");
        } else {
            $result = $this->query("SELECT funcionarios.id, pessoa.nome, cargo.tipo, cargo.descricao, cargo.subtipo, funcionarios.dt_admissao, funcionarios.status FROM `funcionarios` INNER JOIN pessoa ON (pessoa.id = funcionarios.id_pessoa) INNER JOIN cargo ON (cargo.id = funcionarios.id_cargo) WHERE funcionarios.id_cliente = '{$id_cliente}' ORDER BY pessoa.nome ASC ");
        }


        foreach ($result as $key => $value) {
            if ($value["status"] == "1") {
                $result[$key]["status"] = "ATIVO";
            } else if ($value["status"] == "0") {
                $result[$key]["status"] = "INATIVO";
            } else if ($value["status"] == "3") {
                $result[$key]["status"] = "DESLIGADO";
            }

            if ($result[$key]["subtipo"] == "1") {
                $subtipo = "JUNIOR";
            } else if ($result[$key]["subtipo"] == "2") {
                $subtipo = "PLENO";
            } else if ($result[$key]["subtipo"] == "3") {
                $subtipo = "SENIOR";
            }

            $result[$key]["descricao_formatado"] = $result[$key]["descricao"] . " " . $subtipo;
        }

        return $result;
    }

    public function getByID($id_funcionario) {
        $pessoa = new Pessoa_Model();
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->read("id = '{$id_funcionario}' AND id_cliente = '{$id_cliente}'");
        $result[0]["pessoa"] = $result[0]["id_pessoa"] . "-" . $pessoa->getPessoaByID($result[0]["id_pessoa"])["nome"];
        $result[0]["dt_admissao"] = implode("/", array_reverse(explode("-", $result[0]["dt_admissao"])));
        $result[0]["data_ctps"] = implode("/", array_reverse(explode("-", $result[0]["data_ctps"])));
        $result[0]["salario"] = number_format($result[0]["salario"], 2, ',', '.');
        $result[0]["observacoes"] = str_replace("<br />", "\n", $result[0]["observacoes"]);
        return $result[0];
    }

    public function getTotalFuncionariosAtivosEmpresas() {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->query("SELECT COUNT(id) AS total FROM funcionarios WHERE id_cliente = '{$id_cliente}' AND status <= '2'");
        return (count($result) ? $result[0]["total"] : 0);
    }

    public function adicionar($pessoa, $id_cargo, $data_admissao, $tipo_funcionario, $cat_salarial, $id_jornada, $turno, $hora_ini, $hora_fim, $status, $ctps_num, $ctps_serie, $ctps_uf, $ctps_data, $pis, $salario, $observacao) {

        $id_cliente = $_SESSION["id_cliente"];

        $explode = explode('-', $pessoa);
        list($id_pessoa) = $explode;

        $result = $this->read("id_cliente = '{$id_cliente}' AND id_pessoa = '{$id_pessoa}' AND status = '1'");

        if (!count($result)) {

            $data_admissao = implode("-", array_reverse(explode("/", $data_admissao)));
            $ctps_data = implode("-", array_reverse(explode("/", $ctps_data)));
            $salario = str_replace(',', '.', str_replace('.', '', $salario));
            $observacao = str_replace("\n", '<br />', $observacao);

            $dt_prox_avaliacao = date("Y-m-d", strtotime("+60 days"));

            $saida = $this->insert(array(
                "id_cliente" => $id_cliente,
                "id_pessoa" => $id_pessoa,
                "id_cargo" => $id_cargo,
                "dt_admissao" => $data_admissao,
                "tipo_funcionario" => $tipo_funcionario,
                "cat_salarial" => $cat_salarial,
                "id_jornada_trabalho" => $id_jornada,
                "turno" => $turno,
                "hora_inicio" => $hora_ini,
                "hora_fim" => $hora_fim,
                "status" => $status,
                "num_ctps" => $ctps_num,
                "serie_ctps" => $ctps_serie,
                "uf_ctps" => $ctps_uf,
                "data_ctps" => $ctps_data,
                "pis" => $pis,
                "salario" => $salario,
                "observacoes" => $observacao,
                "data_avaliacao" => $dt_prox_avaliacao,
                "dt_ini" => date('Y-m-d'),
                "hora_ini" => date('H:i:s'),
                "user_ini" => $_SESSION["user_name"]
                    ), "sadsa");
            if (is_numeric($saida)) {
                $arr = array("status" => true);
                $this->log("Inserção", "Adicionado o Funcionário com Sucesso.");
            } else {
                $arr = array("status" => false, "message" => "Erro ao adicionar o registro.");
                $this->log("Inserção", "Erro ao adicionar Funcionário.");
            }
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Inserção", "Erro ao adicionar Funcionário pois já existe um igual.");
        }

        return json_encode($arr);
    }

    public function editar($id_funcionario, $pessoa, $id_cargo, $data_admissao, $tipo_funcionario, $cat_salarial, $id_jornada, $turno, $hora_ini, $hora_fim, $status, $ctps_num, $ctps_serie, $ctps_uf, $ctps_data, $pis, $salario, $observacao) {

        $id_cliente = $_SESSION["id_cliente"];

        $explode = explode('-', $pessoa);
        list($id_pessoa) = $explode;

        $result = $this->read("(id_cliente = '{$id_cliente}' AND id_pessoa = '{$id_pessoa}' AND status = '1') AND id != '{$id_funcionario}'");

        if (!count($result)) {

            $data_admissao = implode("-", array_reverse(explode("/", $data_admissao)));
            $ctps_data = implode("-", array_reverse(explode("/", $ctps_data)));
            $salario = str_replace(',', '.', str_replace('.', '', $salario));
            $observacao = str_replace("\n", '<br />', $observacao);

            $saida = $this->update(array(
                "id_cliente" => $id_cliente,
                "id_pessoa" => $id_pessoa,
                "id_cargo" => $id_cargo,
                "dt_admissao" => $data_admissao,
                "tipo_funcionario" => $tipo_funcionario,
                "cat_salarial" => $cat_salarial,
                "id_jornada_trabalho" => $id_jornada,
                "turno" => $turno,
                "hora_inicio" => $hora_ini,
                "hora_fim" => $hora_fim,
                "status" => $status,
                "num_ctps" => $ctps_num,
                "serie_ctps" => $ctps_serie,
                "uf_ctps" => $ctps_uf,
                "data_ctps" => $ctps_data,
                "pis" => $pis,
                "salario" => $salario,
                "observacoes" => $observacao,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_funcionario}' AND id_cliente = '{$id_cliente}'");
            if (is_numeric($saida)) {
                $arr = array("status" => true);
                $this->log("Alteração", "Alterado o Funcionário com Sucesso.");
            } else {
                $arr = array("status" => false, "message" => "Erro ao editar o registro.");
                $this->log("Alteração", "Erro ao editar Funcionário.");
            }
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Alteração", "Erro ao editar Funcionário pois já existe um igual.");
        }

        return json_encode($arr);
    }

    public function remover($id_funcionario) {

        $saida = $this->delete("id = '{$id_funcionario}'");

        if (is_numeric($saida)) {
            $arr = array("status" => true, "message" => "Removido registro com Sucesso.");
            $this->log("Remoção", "Removido funcionário com sucesso.");
        } else {
            $arr = array("status" => false, "message" => "Erro ao remover o registro.");
            $this->log("Remoção", "Erro ao remover Funcionário.");
        }

        return json_encode($arr);
    }

    public function atualizaDataAvaliacao($id_funcionario) {
        $funcionario = $this->getByID($id_funcionario);

        $cargo = new Cargo_Model();

        $cg = $cargo->getCargoByID($funcionario["id_cargo"]);

        $meses = $cg["periodicidade_avaliacao"];

        $px_avaliacao = date("Y-m-d", strtotime("+" . $meses . " months"));


        $this->update(array("data_avaliacao" => $px_avaliacao), "id = '{$id_funcionario}'");
    }

    public function getDadosFuncionárioById($idFuncionario) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->query("SELECT funcionarios.id, pessoa.nome, cargo.descricao, cargo.tipo, cargo.subtipo, cbo.codigo FROM funcionarios INNER JOIN pessoa ON (pessoa.id = funcionarios.id_pessoa) INNER JOIN cargo ON (cargo.id = funcionarios.id_cargo) INNER JOIN cbo ON (cbo.id = cargo.id_cbo) WHERE funcionarios.id_cliente = '{$id_cliente}' and funcionarios.id = '{$idFuncionario}' ");

        foreach ($result as $key => $value) {

            if ($result[$key]["tipo"] == "1") {
                $tipo = "AUXILIAR";
            } else if ($result[$key]["tipo"] == "2") {
                $tipo = "ASSISTENTE";
            } else if ($result[$key]["tipo"] == "3") {
                $tipo = "LIDER";
            } else if ($result[$key]["tipo"] == "4") {
                $tipo = "SUPERVISOR";
            } else if ($result[$key]["tipo"] == "5") {
                $tipo = "ANALISTA";
            } else if ($result[$key]["tipo"] == "6") {
                $tipo = "COORDENADOR";
            } else if ($result[$key]["tipo"] == "7") {
                $tipo = "GERENTE";
            } else if ($result[$key]["tipo"] == "8") {
                $tipo = "SUPERINTENDENTE";
            } else if ($result[$key]["tipo"] == "9") {
                $tipo = "DIRETOR";
            }

            if ($result[$key]["subtipo"] == "1") {
                $subtipo = "JUNIOR";
            } else if ($result[$key]["subtipo"] == "2") {
                $subtipo = "PLENO";
            } else if ($result[$key]["subtipo"] == "3") {
                $subtipo = "SENIOR";
            }

            $result[$key]["tipo_descricao"] = $tipo;
            $result[$key]["descricao_formatado"] = $tipo . " DE " . $result[$key]["descricao"] . " " . $subtipo;
        }

        return $result[0];
    }

}
