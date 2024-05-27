<?php

class Indicadores_Quant_Model extends Model {

    public $_tabela = "indicadores_quant";

    public function __construct() {
        parent::__construct();
    }

    public function get($cond = "*") {
        $id_cliente = $_SESSION["id_cliente"];

        if ($cond == "1") {
            $result = $this->query("SELECT indicadores_quant.id, indicadores_quant_tipo.descricao AS tipo_indicador, nat_ocupacional.descricao AS nat_ocupacional, indicadores_quant.id_cliente, indicadores_quant.id_tipo_indicador, indicadores_quant.id_nat_ocupacional, indicadores_quant.descricao, indicadores_quant.status FROM `indicadores_quant`INNER JOIN indicadores_quant_tipo ON (indicadores_quant_tipo.id = indicadores_quant.id_tipo_indicador) INNER JOIN nat_ocupacional ON (nat_ocupacional.id = indicadores_quant.id_nat_ocupacional) WHERE indicadores_quant.id_cliente = '{$id_cliente}' AND indicadores_quant.status = '1' ORDER BY indicadores_quant_tipo.descricao ASC, nat_ocupacional.descricao ASC, indicadores_quant.descricao ASC ");
        } else {
            $result = $this->query("SELECT indicadores_quant.id, indicadores_quant_tipo.descricao AS tipo_indicador, nat_ocupacional.descricao AS nat_ocupacional, indicadores_quant.id_cliente, indicadores_quant.id_tipo_indicador, indicadores_quant.id_nat_ocupacional, indicadores_quant.descricao, indicadores_quant.status FROM `indicadores_quant`INNER JOIN indicadores_quant_tipo ON (indicadores_quant_tipo.id = indicadores_quant.id_tipo_indicador) INNER JOIN nat_ocupacional ON (nat_ocupacional.id = indicadores_quant.id_nat_ocupacional) WHERE indicadores_quant.id_cliente = '{$id_cliente}' ORDER BY indicadores_quant_tipo.descricao ASC, nat_ocupacional.descricao ASC, indicadores_quant.descricao ASC ");
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

    public function getByNatOcupacional($id_nat_ocupacional) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->read("id_cliente = '{$id_cliente}' AND id_nat_ocupacional = '{$id_nat_ocupacional}' AND status = '1'", null, null, "descricao ASC", "id, descricao");

        $select = '<option selected value="">Selecione</option>';
        foreach ($result as $key => $value) {
            $select .= '<option value="' . $result[$key]["id"] . '">' . $result[$key]["descricao"] . '</option>';
        }

        return $select;
    }

    public function adicionar($id_tipo_indicador, $id_nat_ocupacional, $descricao) {

        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("descricao = '{$descricao}' AND id_tipo_indicador = '{$id_tipo_indicador}' AND id_cliente = '{$id_cliente}'");

        if (!count($result)) {

            $saida = $this->insert(array(
                "id_cliente" => $id_cliente,
                "id_tipo_indicador" => $id_tipo_indicador,
                "id_nat_ocupacional" => $id_nat_ocupacional,
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

    public function editar($id_indicadores, $id_tipo_indicador, $id_nat_ocupacional, $descricao, $status) {

        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("(descricao = '{$descricao}' AND id_tipo_indicador = '{$id_tipo_indicador}' AND id_cliente = '{$id_cliente}') AND id != '{$id_indicadores}'");

        if (!count($result)) {
            $saida = $this->update(array(
                "id_tipo_indicador" => $id_tipo_indicador,
                "id_nat_ocupacional" => $id_nat_ocupacional,
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

}
