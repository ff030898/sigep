<?php

class Class_Estrutura_Model extends Model {

    public $_tabela = "class_estrutura";

    public function __construct() {
        parent::__construct();
    }

    public function getClassEstrutura($tipo = "*") {

        $id_cliente = $_SESSION["id_cliente"];

        $security = new securityHelper();

        if ($tipo == "1") {
            $result = $this->read("id_cliente = '{$id_cliente}' AND status  = '1'", NULL, NULL, "descricao ASC");
        } else {
            $result = $this->read("id_cliente = '{$id_cliente}'", NULL, NULL, "descricao ASC");
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

    public function getClassEstruturaByID($id_estrutura) {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("id = '{$id_estrutura}' AND id_cliente = '{$id_cliente}'");
        return $result[0];
    }

    public function adicionar($descricao) {

        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("descricao = '{$descricao}' AND id_cliente = '{$id_cliente}'");

        if (!count($result)) {

            $saida = $this->insert(array(
                "id_cliente" => $id_cliente,
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

    public function editar($id_estrutura, $descricao, $status) {

        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("(descricao = '{$descricao}' AND id_cliente = '{$id_cliente}') AND id != '{$id_estrutura}'");

        if (!count($result)) {


            $saida = $this->update(array(
                "descricao" => $descricao,
                "status" => $status,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_estrutura}'");
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

    public function remover($id_estrutura) {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("id = '{$id_estrutura}'", NULL, NULL, NULL, "descricao");

        $saida = $this->delete("id = '{$id_estrutura}' AND id_cliente = '{$id_cliente}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_estrutura . "-" . $result[0]["descricao"]);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
