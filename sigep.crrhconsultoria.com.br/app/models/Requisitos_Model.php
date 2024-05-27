<?php

class Requisitos_Model extends Model {

    public $_tabela = "requisitos";

    public function __construct() {
        parent::__construct();
    }

    public function get($cond = "*") {

        if ($cond == "1") {
            $result = $this->query("SELECT * FROM `requisitos` WHERE status = '1' ORDER BY descricao ASC ");
        } else {
            $result = $this->query("SELECT * FROM `requisitos` ORDER BY descricao ASC ");
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

    public function getByID($id_requisitos) {
        $result = $this->read("id = '{$id_requisitos}'");
        return $result[0];
    }

    public function getByCargo($id_cargo) {
        $requisitos_cargos = new Requisitos_Cargos_Model();


        $result = $this->read(null, null, null, "descricao ASC");

        $requisitos = "";
        foreach ($result as $key => $value) {
            if ($requisitos_cargos->checkRequisitos($id_cargo, $value["id"])) {
                $requisitos .= '
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked id="chkRequ' . $value["id"] . '" name="chkRequ' . $value["id"] . '">
                        <label class="form-check-label" for="defaultCheck1">
                          ' . $value["descricao"] . '
                        </label>
                    </div>
                ';
            } else {
                $requisitos .= '
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="chkRequ' . $value["id"] . '" name="chkRequ' . $value["id"] . '">
                        <label class="form-check-label" for="defaultCheck1">
                          ' . $value["descricao"] . '
                        </label>
                    </div>
                ';
            }
        }
        return $requisitos;
    }

    public function adicionar($descricao, $tipo) {

        $result = $this->read("descricao = '{$descricao}'");

        if (!count($result)) {

            $saida = $this->insert(array(
                "descricao" => $descricao,
                "tipo" => $tipo,
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

    public function editar($id_requisitos, $descricao, $tipo, $status) {

        $result = $this->read("(descricao = '{$descricao}') AND id != '{$id_requisitos}'");

        if (!count($result)) {


            $saida = $this->update(array(
                "descricao" => $descricao,
                "tipo" => $tipo,
                "status" => $status,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_requisitos}'");
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

    public function remover($id_requisitos) {

        $result = $this->read("id = '{$id_requisitos}'", NULL, NULL, NULL, "descricao");

        $saida = $this->delete("id = '{$id_requisitos}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_requisitos . "-" . $result[0]["descricao"]);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
