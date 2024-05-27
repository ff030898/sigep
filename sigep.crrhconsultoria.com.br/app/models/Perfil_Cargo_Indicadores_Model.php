<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Perfil_Cargo_Indicadores_Model
 *
 * @author drc
 */
class Perfil_Cargo_Indicadores_Model extends Model {

    public $_tabela = "perfil_cargo_indicadores";

    public function __construct() {
        parent::__construct();
    }

    public function get($id_cliente, $id_cargo, $id_perfil) {
        return $this->read("id_clients = '{$id_cliente}' AND id_cargo = '{$id_cargo}' AND id_perfil_cargo = '{$id_perfil}'", null, null, "id_clients ASC, id_perfil_cargo ASC, id_indicadores_quant_tipo ASC, id_indicadores_quant_sub ASC");
    }

    public function getbyTipo($id_cliente, $id_cargo, $id_perfil, $id_tipo_indicador) {
        return $this->read("id_clients = '{$id_cliente}' AND id_cargo = '{$id_cargo}' AND id_perfil_cargo = '{$id_perfil}' AND id_indicadores_quant_tipo = '{$id_tipo_indicador}'", null, null, "id_clients ASC, id_perfil_cargo ASC, id_indicadores_quant_tipo ASC, id_indicadores_quant_sub ASC");
    }

    private function clear($id_cliente, $id_cargo, $id_perfil) {
        return $this->delete("id_clients = '{$id_cliente}' AND id_cargo = '{$id_cargo}' AND id_perfil_cargo = '{$id_perfil}'");
    }

    public function adicionar($id_cliente, $id_cargo, $id_perfil, $indicadores, $sub, $valor) {

        $this->clear($id_cliente, $id_cargo, $id_perfil);

        foreach ($valor as $key => $value) {
            if ($value != "") {
                $this->insert(array(
                    "id_clients" => $id_cliente,
                    "id_cargo" => $id_cargo,
                    "id_perfil_cargo" => $id_perfil,
                    "id_indicadores_quant_tipo" => $indicadores[$key],
                    "id_indicadores_quant_sub" => $sub[$key],
                    "descricao" => $value
                        ), "id");
            }
        }
        return true;
    }

}
