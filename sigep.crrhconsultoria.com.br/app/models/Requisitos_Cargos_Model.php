<?php

class Requisitos_Cargos_Model extends Model {

    public $_tabela = "requisitos_cargos";

    public function __construct() {
        parent::__construct();
    }

    public function clearRequisitos($id_cargo) {
        return $this->delete("id_cargo = '{$id_cargo}'");
    }

    public function setRequisitos($id_cargo, $id_requisito) {
        return $this->insert(array(
                    "id_cargo" => $id_cargo,
                    "id_requisito" => $id_requisito
        ));
    }

    public function checkRequisitos($id_cargo, $id_requisito) {
        $result = $this->read("id_cargo = '{$id_cargo}' AND id_requisito = '{$id_requisito}'");
        if (count($result) == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getByIdCargo($idCargo) {
        return $this->query("SELECT requisitos.descricao FROM `requisitos_cargos` INNER JOIN requisitos ON (requisitos.id = requisitos_cargos.id_requisito) WHERE requisitos_cargos.id_cargo = '{$idCargo}' AND requisitos.status = '1' ORDER BY requisitos.descricao ASC ");
    }

}
