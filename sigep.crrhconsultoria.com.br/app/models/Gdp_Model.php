<?php

class Gdp_Model extends Model {

    public $_tabela = "gdp";

    public function __construct() {
        parent::__construct();
    }

    public function getGdp() {
        $security = new securityHelper();
        $result = $this->read(NULL, NULL, NULL, "classificacao ASC");

        foreach ($result as $key => $value) {
            if ($value["status"] == "1") {
                $result[$key]["status"] = "ATIVO";
            } else if ($value["status"] == "0") {
                $result[$key]["status"] = "INATIVO";
            }

            $result[$key]["perc_min"] = str_replace(',', '.', $result[$key]["perc_min"]);
            $result[$key]["perc_max"] = str_replace(',', '.', $result[$key]["perc_max"]);
        }

        return $result;
    }

    public function getGdpByID($id_gdp) {
        $result = $this->read("id = '{$id_gdp}'");
        return $result[0];
    }

    public function adicionar($descricao, $classificacao, $perc_min, $perc_max) {

        $result = $this->read("descricao = '{$descricao}'");

        if (!count($result)) {

            $perc_min = str_replace(',', '.', $perc_min);
            $perc_max = str_replace(',', '.', $perc_max);

            $saida = $this->insert(array(
                "descricao" => $descricao,
                "classificacao" => $classificacao,
                "perc_min" => $perc_min,
                "perc_max" => $perc_max,
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

    public function editar($id_gdp, $descricao, $status, $classificacao, $perc_min, $perc_max) {

        $result = $this->read("(descricao = '{$descricao}') AND id != '{$id_gdp}'");

        if (!count($result)) {

            $perc_min = str_replace(',', '.', $perc_min);
            $perc_max = str_replace(',', '.', $perc_max);

            $saida = $this->update(array(
                "descricao" => $descricao,
                "classificacao" => $classificacao,
                "perc_min" => $perc_min,
                "perc_max" => $perc_max,
                "status" => $status,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_gdp}'");
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

    public function remover($id_gdp) {

        $result = $this->read("id = '{$id_gdp}'", NULL, NULL, NULL, "descricao");

        $saida = $this->delete("id = '{$id_gdp}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_gdp . "-" . $result[0]["descricao"]);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
