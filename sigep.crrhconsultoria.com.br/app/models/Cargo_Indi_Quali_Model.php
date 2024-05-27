<?php

class Cargo_Indi_Quali_Model extends Model {

    public $_tabela = "cargo_indi_quali";

    public function __construct() {
        parent::__construct();
    }

    public function adicionar($id_cargo, $indicadores) {

        $id_cliente = $_SESSION["id_cliente"];
        $this->remove($id_cargo);
        $explode = explode(',', $indicadores);
        foreach ($explode as $key => $value) {
            $saida = $this->insert(array(
                "id_cliente" => $id_cliente,
                "id_cargo" => $id_cargo,
                "id_indicador" => $value,
                "posicao" => $key + 1
            ));
        }

        return true;
    }

    public function get($id_cargo) {
        $result = $this->query("SELECT indicadores_quali.descricao, indicadores_quali.id AS id_indicador_mesmo, cargo_indi_quali.* FROM indicadores_quali LEFT OUTER JOIN cargo_indi_quali ON (indicadores_quali.id = cargo_indi_quali.id_indicador) WHERE cargo_indi_quali.id_cargo is null OR (cargo_indi_quali.id_cargo = '{$id_cargo}' OR cargo_indi_quali.id_cargo is null) ORDER BY -cargo_indi_quali.id DESC, cargo_indi_quali.posicao desc ");
        return $result;
    }

    public function getPosicao($id_cliente, $id_cargo, $id_indicador) {
        $result = $this->read("id_cliente = '{$id_cliente}' AND id_cargo = '{$id_cargo}' AND id_indicador = '{$id_indicador}'", null, null, null, "posicao");
        if (count($result)) {
            return $result[0]["posicao"];
        } else {
            return 0;
        }
    }

    public function remove($id_cargo) {
        return $this->delete("id_cargo = '{$id_cargo}'");
    }

}
