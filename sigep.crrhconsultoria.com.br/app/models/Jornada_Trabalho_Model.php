<?php

class Jornada_Trabalho_Model extends Model {

    public $_tabela = "jornada_trabalho";

    public function __construct() {
        parent::__construct();
    }

    public function get($cond = "*") {

        if ($cond == "1") {
            $result = $this->read("status = '1'", null, null, "descricao ASC");
        } else {
            $result = $this->read(null, NULL, NULL, "descricao ASC");
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

    public function getByID($id_jornada) {
        $result = $this->read("id = '{$id_jornada}'");
        return $result[0];
    }

    public function adicionar($descricao, $horas) {

        $result = $this->read("descricao = '{$descricao}' AND horas = '{$horas}'");

        if (!count($result)) {

            $saida = $this->insert(array(
                "descricao" => $descricao,
                "horas" => $horas,
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

    public function editar($id_jornada, $descricao, $horas, $status) {

        $result = $this->read("(descricao = '{$descricao}' AND horas = '{$horas}') AND id != '{$id_jornada}'");

        if (!count($result)) {


            $saida = $this->update(array(
                "descricao" => $descricao,
                "horas" => $horas,
                "status" => $status,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_jornada}'");
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

    public function remover($id_jornada) {

        $result = $this->read("id = '{$id_jornada}'", NULL, NULL, NULL, "descricao");

        $saida = $this->delete("id = '{$id_jornada}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_jornada . "-" . $result[0]["descricao"]);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
