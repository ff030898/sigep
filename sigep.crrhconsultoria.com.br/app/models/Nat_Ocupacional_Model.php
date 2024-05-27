<?php

class Nat_Ocupacional_Model extends Model {

    public $_tabela = "nat_ocupacional";

    public function __construct() {
        parent::__construct();
    }

    public function getNat_Ocupacional($cond = "*") {
        $security = new securityHelper();

        if ($cond == "1") {
            $result = $this->read("status = '1'", null, null, "descricao ASC");
        } else {
            $result = $this->read(NULL, NULL, NULL, "descricao ASC");
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

    public function getNat_OcupacionalByID($id_nat_ocupacional) {
        $result = $this->read("id = '{$id_nat_ocupacional}'");
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

    public function editar($id_nat_ocupacional, $descricao, $status) {

        $result = $this->read("(descricao = '{$descricao}') AND id != '{$id_nat_ocupacional}'");

        if (!count($result)) {


            $saida = $this->update(array(
                "descricao" => $descricao,
                "status" => $status,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_nat_ocupacional}'");
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

    public function remover($id_nat_ocupacional) {

        $result = $this->read("id = '{$id_nat_ocupacional}'", NULL, NULL, NULL, "descricao");

        $saida = $this->delete("id = '{$id_nat_ocupacional}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_nat_ocupacional . "-" . $result[0]["descricao"]);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
