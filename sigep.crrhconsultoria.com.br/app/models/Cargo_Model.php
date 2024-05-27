<?php

class Cargo_Model extends Model {

    public $_tabela = "cargo";

    public function __construct() {
        parent::__construct();
    }

    public function getCargo($cond = "*") {

        $id_cliente = $_SESSION["id_cliente"];

        if ($cond == "1") {
            $result = $this->read("status = '1' AND id_cliente = '{$id_cliente}'", null, null, "descricao ASC");
        } else if ($cond == "perfil") {
            $result = $this->query("SELECT cargo.* FROM `cargo` LEFT JOIN perfil_cargo ON (perfil_cargo.id_cargo = cargo.id) WHERE perfil_cargo.id IS NULL AND cargo.id_cliente = '{$id_cliente}' AND cargo.status = '1' ORDER BY cargo.descricao ASC ");
        } else {
            $result = $this->query("SELECT cargo.*, cbo.codigo, cbo.titulo FROM `cargo` INNER JOIN cbo ON (cbo.id = cargo.id_cbo) WHERE id_cliente = '{$id_cliente}' ORDER BY cargo.descricao ASC ");
        }



        foreach ($result as $key => $value) {
            if ($value["status"] == "1") {
                $result[$key]["status"] = "ATIVO";
            } else if ($value["status"] == "0") {
                $result[$key]["status"] = "INATIVO";
            }

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
            $result[$key]["ascencao"] = $result[$key]["descricao"] . " " . $subtipo;
            $result[$key]["descricao_formatado"] = $result[$key]["descricao"] . " " . $subtipo;
        }

        return $result;
    }

    public function getCargoByID($id_cargo) {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->query("SELECT cargo.*, cbo.codigo, cbo.titulo  FROM cargo INNER JOIN cbo ON (cbo.id = cargo.id_cbo) WHERE cargo.id = '{$id_cargo}' AND id_cliente = '{$id_cliente}' ORDER BY cargo.descricao ASC");

        $result[0]["id_ascencao"] = ($result[0]["id_ascencao"] ? $result[0]["id_ascencao"] : "0");
        $result[0]["sal_min"] = number_format($result[0]["sal_min"], 2, ',', '.');
        $result[0]["sal_max"] = number_format($result[0]["sal_max"], 2, ',', '.');

        if ($result[0]["tipo"] == "1") {
            $tipo = "AUXILIAR";
        } else if ($result[0]["tipo"] == "2") {
            $tipo = "ASSISTENTE";
        } else if ($result[0]["tipo"] == "3") {
            $tipo = "LIDER";
        } else if ($result[0]["tipo"] == "4") {
            $tipo = "SUPERVISOR";
        } else if ($result[0]["tipo"] == "5") {
            $tipo = "ANALISTA";
        } else if ($result[0]["tipo"] == "6") {
            $tipo = "COORDENADOR";
        } else if ($result[0]["tipo"] == "7") {
            $tipo = "GERENTE";
        } else if ($result[0]["tipo"] == "8") {
            $tipo = "SUPERINTENDENTE";
        } else if ($result[0]["tipo"] == "9") {
            $tipo = "DIRETOR";
        }

        $result[0]["observacoes"] = str_replace("<br />", "\n", $result[0]["observacoes"]);
        $result[0]["tipo_descricao"] = $tipo;

        return $result[0];
    }

    public function getNatByCargo($id_cargo) {
        $result = $this->read("id = '{$id_cargo}'", null, null, null, "id_nat_ocupacional");
        return $result[0]["id_nat_ocupacional"];
    }

    public function getDescricaoCargo($id_cargo) {
        $result = $this->read("id = '{$id_cargo}'", null, null, null, "tipo, descricao, subtipo");

        if ($result[0]["tipo"] == "1") {
            $tipo = "AUXILIAR";
        } else if ($result[0]["tipo"] == "2") {
            $tipo = "ASSISTENTE";
        } else if ($result[0]["tipo"] == "3") {
            $tipo = "LIDER";
        } else if ($result[0]["tipo"] == "4") {
            $tipo = "SUPERVISOR";
        } else if ($result[0]["tipo"] == "5") {
            $tipo = "ANALISTA";
        } else if ($result[0]["tipo"] == "6") {
            $tipo = "COORDENADOR";
        } else if ($result[0]["tipo"] == "7") {
            $tipo = "GERENTE";
        } else if ($result[0]["tipo"] == "8") {
            $tipo = "SUPERINTENDENTE";
        } else if ($result[0]["tipo"] == "9") {
            $tipo = "DIRETOR";
        }

        if ($result[0]["subtipo"] == "1") {
            $subtipo = "JUNIOR";
        } else if ($result[0]["subtipo"] == "2") {
            $subtipo = "PLENO";
        } else if ($result[0]["subtipo"] == "3") {
            $subtipo = "SENIOR";
        }

        return $result[0]["descricao"] . " " . $subtipo;
    }

    public function search_cargo($pesquisa, $funcao) {

        $result = $this->query("SELECT * FROM cargo INNER JOIN cbo ON (cbo.id = cargo.id_cbo) WHERE (cargo.id LIKE '%{$pesquisa}%' OR cargo.id_cbo LIKE '%{$pesquisa}%'  OR cargo.descricao LIKE '%{$pesquisa}%') AND cargo.status = '1' ORDER BY cargo.descricao ASC");
        $mostra = null;
        $total = count($result);
        $mostra .= '<div style="float: left;">';
        $mostra .= '
            <div class="dataTable_wrapper">
                <table width="100%" class="table table-striped table-bordered table-hover" id="empresas_listas">
                    <tbody>
        ';
        for ($i = 0; $i <= $total - 1; $i++) {

            if ($result[$i]["tipo"] == "1") {
                $tipo = "AUXILIAR";
            } else if ($result[$i]["tipo"] == "2") {
                $tipo = "ASSISTENTE";
            } else if ($result[$i]["tipo"] == "3") {
                $tipo = "LIDER";
            } else if ($result[$i]["tipo"] == "4") {
                $tipo = "SUPERVISOR";
            } else if ($result[$i]["tipo"] == "5") {
                $tipo = "ANALISTA";
            } else if ($result[$i]["tipo"] == "6") {
                $tipo = "COORDENADOR";
            } else if ($result[$i]["tipo"] == "7") {
                $tipo = "GERENTE";
            } else if ($result[$i]["tipo"] == "8") {
                $tipo = "SUPERINTENDENTE";
            } else if ($result[$i]["tipo"] == "9") {
                $tipo = "DIRETOR";
            }

            if ($result[$i]["subtipo"] == "1") {
                $subtipo = "JUNIOR";
            } else if ($result[$i]["subtipo"] == "2") {
                $subtipo = "PLENO";
            } else if ($result[$i]["subtipo"] == "3") {
                $subtipo = "SENIOR";
            }

            $descricao = $tipo . " " . $result[$i]["descricao"] . " " . $subtipo;

            $mostra .= '
                <tr>
                    <td>';
            $mostra .= '<a href="javascript:' . $funcao . '(\'' . $result[$i]["tipo"] . '\',\'' . $result[$i]["descricao"] . '\',\'' . $result[$i]["id_cbo"] . '-' . $result[$i]["codigo"] . '-' . $result[$i]["titulo"] . '\')">' . $descricao . "</a>";
            $mostra .= '
                    </td>
                </tr>
            ';
        }

        $mostra = ($total == 0 ? "Não encontrado nenhum registro" : $mostra);

        $mostra .= '<div style = "float: right;">
                    <a href = "javascript:fechar_pesquisa_cargo()"><i class = "fa fa-window-close-o fa-lg"></i></a>
                    </div>';

        if ($total > 0) {
            return $mostra;
        } else {
            return null;
        }
    }

    public function adicionar($tipo, $descricao, $subtipo, $id_cbo, $id_ascencao, $id_nat_ocupacional, $sal_min, $sal_max, $grau_min, $per_avaliacao, $id_estrutura_organizacional, $requisitos, $habilidades, $observacoes) {

        $id_cliente = $_SESSION["id_cliente"];


        $result = $this->read("tipo = '{$tipo}' AND descricao = '{$descricao}' AND subtipo = '{$subtipo}' AND id_cliente = '{$id_cliente}'");

        if (!count($result)) {

            $explode = explode('-', $id_cbo);
            list($id_cbo) = $explode;

            $explode = explode('-', $id_nat_ocupacional);
            list($id_nat_ocupacional) = $explode;

            if ($id_ascencao == 0) {
                $id_ascencao = null;
            } else {
                $explode = explode('-', $id_ascencao);
                list($id_ascencao) = $explode;
            }

            $sal_min = str_replace(',', '.', str_replace('.', '', $sal_min));
            $sal_max = str_replace(',', '.', str_replace('.', '', $sal_max));

            $observacoes = str_replace("\n", '<br />', $observacoes);

            $id_cargo = $this->insert(array(
                "id_cliente" => $id_cliente,
                "id_cbo" => $id_cbo,
                "id_ascencao" => $id_ascencao,
                "id_nat_ocupacional" => $id_nat_ocupacional,
                "id_estrutura_organizacional" => $id_estrutura_organizacional,
                "tipo" => $tipo,
                "descricao" => $descricao,
                "subtipo" => $subtipo,
                "sal_min" => $sal_min,
                "sal_max" => $sal_max,
                "grau_min" => $grau_min,
                "periodicidade_avaliacao" => $per_avaliacao,
                "observacoes" => $observacoes,
                "dt_ini" => date('Y-m-d'),
                "hora_ini" => date('H:i:s'),
                "user_ini" => $_SESSION["user_name"]
                    ), "id");
            if (filter_var($id_cargo, FILTER_VALIDATE_INT)) {
                $requistos_cargos = new Requisitos_Cargos_Model();

                foreach ($requisitos as $value2) {
                    $requistos_cargos->setRequisitos($id_cargo, $value2);
                }

                $cargo_indi_quali = new Cargo_Indi_Quali_Model();
                $cargo_indi_quali->adicionar($id_cargo, $habilidades);

                $arr = array("status" => true);
                $this->log("Inserção", "Adicionada o Cargo " . $descricao . " com Sucesso.");
            } else {
                $arr = array("status" => false, "message" => "Erro ao adicionar o registro.");
                $this->log("Inserção", "Erro ao adicionar o Cargo " . $descricao);
            }
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Inserção", "Erro ao adicionar o Cargo " . $descricao . " pois já existe um igual.");
        }

        return json_encode($arr);
    }

    public function editar($id_cargo, $tipo, $descricao, $subtipo, $id_cbo, $id_ascencao, $id_nat_ocupacional, $sal_min, $sal_max, $grau_min, $per_avaliacao, $id_estrutura_organizacional, $requisitos, $habilidades, $observacoes) {

        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("(tipo = '{$tipo}' AND descricao = '{$descricao}' AND subtipo = '{$subtipo}' AND id_cliente = '{$id_cliente}') AND id != '{$id_cargo}'");

        if (!count($result)) {

            $explode = explode('-', $id_cbo);
            list($id_cbo) = $explode;

            $explode = explode('-', $id_nat_ocupacional);
            list($id_nat_ocupacional) = $explode;

            if ($id_ascencao == 0) {
                $id_ascencao = null;
            } else {
                $explode = explode('-', $id_ascencao);
                list($id_ascencao) = $explode;
            }

            $sal_min = str_replace(',', '.', str_replace('.', '', $sal_min));
            $sal_max = str_replace(',', '.', str_replace('.', '', $sal_max));

            $observacoes = str_replace("\n", '<br />', $observacoes);

            $saida = $this->update(array(
                "id_cbo" => $id_cbo,
                "id_ascencao" => $id_ascencao,
                "id_nat_ocupacional" => $id_nat_ocupacional,
                "id_estrutura_organizacional" => $id_estrutura_organizacional,
                "tipo" => $tipo,
                "descricao" => $descricao,
                "subtipo" => $subtipo,
                "sal_min" => $sal_min,
                "sal_max" => $sal_max,
                "grau_min" => $grau_min,
                "periodicidade_avaliacao" => $per_avaliacao,
                "observacoes" => $observacoes,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_cargo}'");
            if (is_numeric($saida)) {
                $requistos_cargos = new Requisitos_Cargos_Model();

                $requistos_cargos->clearRequisitos($id_cargo);

                foreach ($requisitos as $value2) {
                    $requistos_cargos->setRequisitos($id_cargo, $value2);
                }

                $cargo_indi_quali = new Cargo_Indi_Quali_Model();
                $cargo_indi_quali->adicionar($id_cargo, $habilidades);

                $arr = array("status" => true);
                $this->log("Alteração", "Alterado o Cargo " . $descricao . " com Sucesso.");
            } else {
                $arr = array("status" => false, "message" => "Erro ao alterar o registro.");
                $this->log("Alteração", "Erro ao alterar o Cargo " . $descricao);
            }
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Alteração", "Erro ao alterar o Cargo " . $descricao . " pois já existe um igual.");
        }

        return json_encode($arr);
    }

    public function remover($id_cargo) {

        $requistos_cargos = new Requisitos_Cargos_Model();
        $cargo_indi_quali = new Cargo_Indi_Quali_Model();

        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("id = '{$id_cargo}'", NULL, NULL, NULL, "descricao");

        $requistos_cargos->clearRequisitos($id_cargo);
        $cargo_indi_quali->remove($id_cargo);

        $saida = $this->delete("id = '{$id_cargo}' AND id_cliente = '{$id_cliente}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_cargo . "-" . $result[0]["descricao"]);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
