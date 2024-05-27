<?php

class Perfil_Fonte_Interna_Model extends Model {

    public $_tabela = "perfil_fonte_interna";

    public function __construct() {
        parent::__construct();
    }

    public function clear($id_cliente, $id_cargo, $id_perfil) {
        return $this->delete("id_cliente = '{$id_cliente}' AND id_cargo = '{$id_cargo}' AND id_perfil = '{$id_perfil}'");
    }

    public function adicionar($id_cliente, $id_cargo, $id_perfil, $cargo_interno, $tempo, $tipo) {
        $this->clear($id_cliente, $id_cargo, $id_perfil);
        if (is_array($cargo_interno)) {
            foreach ($cargo_interno as $key => $value) {
                $this->insert(array(
                    "id_cliente" => $id_cliente,
                    "id_cargo" => $id_cargo,
                    "id_perfil" => $id_perfil,
                    "id_cargo_interno" => $value,
                    "tempo" => $tempo[$key],
                    "tipo" => $tipo[$key],
                    "dt_ini" => date('Y-m-d'),
                    "hora_ini" => date('H:i:s'),
                    "user_ini" => $_SESSION["user_name"]
                ));
            }
        }
        return true;
    }

    public function get($id_cliente, $id_cargo, $id_perfil) {
        $result = $this->read("id_cliente = '{$id_cliente}' AND id_cargo = '{$id_cargo}' AND id_perfil = '{$id_perfil}'");

        return $result;
    }

}
