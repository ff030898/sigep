<?php

class Indicadores_Quali_Model extends Model {

    public $_tabela = "indicadores_quali";

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

        $result[0]["conceito"] = str_replace("<br />", "\n", $result[0]["conceito"]);

        return $result[0];
    }

    public function getQuant() {
        $result = $this->read();
        return count($result);
    }

    public function adicionar($descricao, $conceito) {

        $result = $this->read("descricao = '{$descricao}'");

        if (!count($result)) {

            $conceito = str_replace("\n", '<br />', $conceito);

            $saida = $this->insert(array(
                "descricao" => $descricao,
                "conceito" => $conceito,
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

    public function editar($id_indicadores, $descricao, $conceito, $status) {

        $result = $this->read("(descricao = '{$descricao}') AND id != '{$id_indicadores}'");

        if (!count($result)) {
            $conceito = str_replace("\n", '<br />', $conceito);

            $saida = $this->update(array(
                "descricao" => $descricao,
                "conceito" => $conceito,
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
