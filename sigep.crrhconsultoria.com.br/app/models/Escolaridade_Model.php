<?php

class Escolaridade_Model extends Model {

    public $_tabela = "escolaridade";

    public function __construct() {
        parent::__construct();
    }

    public function getEscolaridade() {
        $security = new securityHelper();
        $result = $this->read(NULL, NULL, NULL, "code_esocial ASC");

        foreach ($result as $key => $value) {
            if ($value["status"] == "1") {
                $result[$key]["status"] = "ATIVO";
            } else if ($value["status"] == "0") {
                $result[$key]["status"] = "INATIVO";
            }
        }

        return $result;
    }

    public function getEscolaridadeByID($id_escolaridade) {
        $result = $this->read("id = '{$id_escolaridade}'");
        return $result[0];
    }

    public function adicionar($code_esocial, $descricao) {

        $result = $this->read("code_esocial = '{$code_esocial}' || descricao = '{$descricao}'");

        if (!count($result)) {

            $saida = $this->insert(array(
                "code_esocial" => $code_esocial,
                "descricao" => $descricao,
                "dt_ini" => date('Y-m-d'),
                "hora_ini" => date('H:i:s'),
                "user_ini" => $_SESSION["user_name"]
                    ), "sadsa");
            if (is_numeric($saida)) {
                $this->log("Insersão", $code_esocial . "-" . $descricao);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function editar($id_escolaridade, $code_esocial, $descricao, $status) {

        $result = $this->read("(code_esocial = '{$code_esocial}' || descricao = '{$descricao}') AND id != '{$id_escolaridade}'");

        if (!count($result)) {


            $saida = $this->update(array(
                "code_esocial" => $code_esocial,
                "descricao" => $descricao,
                "status" => $status,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_escolaridade}'");
            if (is_numeric($saida)) {
                $this->log("Alteração", $code_esocial . "-" . $descricao);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function remover($id_escolaridade) {

        $result = $this->read("id = '{$id_escolaridade}'", NULL, NULL, NULL, "descricao");

        $saida = $this->delete("id = '{$id_escolaridade}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_escolaridade . "-" . $result[0]["descricao"]);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
