<?php

class Perfil_Requisitos_Model extends Model {

    public $_tabela = "perfil_requisitos";

    public function __construct() {
        parent::__construct();
    }

    public function clear($id_cliente, $id_cargo, $id_perfil) {
        return $this->delete("id_cliente = '{$id_cliente}' AND id_cargo = '{$id_cargo}' AND id_perfil = '{$id_perfil}'");
    }

    public function adicionar($id_cliente, $id_cargo, $id_perfil, $requisitos) {
        $this->clear($id_cliente, $id_cargo, $id_perfil);

        foreach ($requisitos as $key => $value) {
            $this->insert(array(
                "id_cliente" => $id_cliente,
                "id_cargo" => $id_cargo,
                "id_perfil" => $id_perfil,
                "id_requisito" => $value,
                "dt_ini" => date('Y-m-d'),
                "hora_ini" => date('H:i:s'),
                "user_ini" => $_SESSION["user_name"]
            ));
        }
        return true;
    }

    public function get($id_cliente, $id_cargo, $id_perfil) {
        $result = $this->query("SELECT requisitos.descricao, requisitos.id, perfil_requisitos.id_perfil FROM perfil_requisitos RIGHT JOIN requisitos ON (perfil_requisitos.id_requisito = requisitos.id) WHERE perfil_requisitos.id_perfil IS NULL OR (perfil_requisitos.id_perfil = '{$id_perfil}' AND perfil_requisitos.id_cliente = '{$id_cliente}' AND perfil_requisitos.id_cargo = '{$id_cargo}') ORDER BY requisitos.descricao ASC ");

        $requisitos = "";
        foreach ($result as $key => $value) {
            if ($value["id_perfil"]) {
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

    public function getByID($id_cliente, $id_cargo, $id_perfil) {
        $result = $this->query("SELECT requisitos.descricao, requisitos.id, perfil_requisitos.id_perfil FROM perfil_requisitos RIGHT JOIN requisitos ON (perfil_requisitos.id_requisito = requisitos.id) WHERE perfil_requisitos.id_perfil IS NULL OR (perfil_requisitos.id_perfil = '{$id_perfil}' AND perfil_requisitos.id_cliente = '{$id_cliente}' AND perfil_requisitos.id_cargo = '{$id_cargo}') ORDER BY requisitos.descricao ASC ");

        return $result;
    }

}
