<?php

class Indicadores_Quant_Tipo_Model extends Model {

    public $_tabela = "indicadores_quant_tipo";

    public function __construct() {
        parent::__construct();
    }

    public function get($cond = "*") {
        $id_cliente = $_SESSION["id_cliente"];

        if ($cond == "1") {
            $result = $this->read("status = '1'", null, null, "descricao ASC");
        } else {
            $result = $this->read(null, null, null, "descricao ASC");
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
        $result = $this->read("id = '{$id_indicadores}'");

        return $result[0];
    }

    public function adicionar($descricao) {

        $result = $this->read("descricao = '{$descricao}'");

        if (!count($result)) {

            $saida = $this->insert(array(
                "descricao" => $descricao,
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

    public function editar($id_indicadores, $descricao, $status) {

        $result = $this->read("(descricao = '{$descricao}') AND id != '{$id_indicadores}'");

        if (!count($result)) {
            $saida = $this->update(array(
                "descricao" => $descricao,
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

        $result = $this->read("id = '{$id_indicadores}'", NULL, NULL, NULL, "descricao");

        $saida = $this->delete("id = '{$id_indicadores}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_indicadores . "-" . $result[0]["descricao"]);
            return $saida;
        } else {
            return FALSE;
        }
    }

    public function monta_indicadores() {
        $result = $this->get();
        $indicadores_quant_sub = new Indicadores_Quant_Sub_Model();

        $array = [];
        foreach ($result as $value) {
            $array[$value["id"]] = $indicadores_quant_sub->get($value["id"]);
        }

        return $array;
    }

}
