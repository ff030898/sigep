<?php

class Indicadores_Quali_Graduacao_Model extends Model {

    public $_tabela = "indicadores_quali_graduacao";

    public function __construct() {
        parent::__construct();
    }

    public function get($cond = "*") {
        $id_cliente = $_SESSION["id_cliente"];

        if ($cond == "1") {
            $result = $this->query("SELECT indicadores_quali_graduacao.*, indicadores_quali.descricao AS indicador_quali FROM indicadores_quali_graduacao INNER JOIN indicadores_quali ON (indicadores_quali.id = indicadores_quali_graduacao.id_ind_qualitativo) WHERE indicadores_quali_graduacao.status='1' AND id_cliente = '{$id_cliente}' ORDER BY indicadores_quali.descricao ASC, indicadores_quali_graduacao.ordem_horizontal ");
        } else {
            $result = $this->query("SELECT indicadores_quali_graduacao.*, indicadores_quali.descricao AS indicador_quali FROM indicadores_quali_graduacao INNER JOIN indicadores_quali ON (indicadores_quali.id = indicadores_quali_graduacao.id_ind_qualitativo) WHERE id_cliente = '{$id_cliente}' ORDER BY indicadores_quali.descricao ASC, indicadores_quali_graduacao.ordem_horizontal ");
        }



        foreach ($result as $key => $value) {
            if ($value["status"] == "1") {
                $result[$key]["status"] = "ATIVO";
            } else if ($value["status"] == "0") {
                $result[$key]["status"] = "INATIVO";
            }
        }

        return $result;
    }

    public function getByID($id_indicadores) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->read("id = '{$id_indicadores}' AND id_cliente = '{$id_cliente}'");
        return $result[0];
    }

    public function adicionar($id_ind_qualitativo, $descricao, $descricao_resumida, $ordem_horizontal) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->read("id_cliente = '{$id_cliente}' AND id_ind_qualitativo = '{$id_ind_qualitativo}' AND ordem_horizontal = '{$ordem_horizontal}'");

        if (!count($result)) {

            $descricao = str_replace("\n", '<br />', $descricao);

            $saida = $this->insert(array(
                "id_cliente" => $id_cliente,
                "id_ind_qualitativo" => $id_ind_qualitativo,
                "descricao" => $descricao,
                "descricao_resumida" => $descricao_resumida,
                "ordem_horizontal" => $ordem_horizontal,
                "dt_ini" => date('Y-m-d'),
                "hora_ini" => date('H:i:s'),
                "user_ini" => $_SESSION["user_name"]
                    ), "sadsa");
            if (is_numeric($saida)) {
                $this->log("Insersão", $descricao);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function editar($id_indicadores, $id_ind_qualitativo, $descricao, $descricao_resumida, $ordem_horizontal, $status) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->read("(id_cliente = '{$id_cliente}' AND id_ind_qualitativo = '{$id_ind_qualitativo}' AND ordem_horizontal = '{$ordem_horizontal}') AND id != '{$id_indicadores}'");

        if (!count($result)) {
            $descricao = str_replace("\n", '<br />', $descricao);

            $saida = $this->update(array(
                "id_cliente" => $id_cliente,
                "id_ind_qualitativo" => $id_ind_qualitativo,
                "descricao" => $descricao,
                "descricao_resumida" => $descricao_resumida,
                "ordem_horizontal" => $ordem_horizontal,
                "status" => $status,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_indicadores}'");
            if (is_numeric($saida)) {
                $this->log("Alteração", $descricao);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function remover($id_indicadores) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->read("id = '{$id_indicadores}'", NULL, NULL, NULL, "descricao");

        $saida = $this->delete("id = '{$id_indicadores}' AND id_cliente = '{$id_cliente}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_indicadores . "-" . $result[0]["descricao"]);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
