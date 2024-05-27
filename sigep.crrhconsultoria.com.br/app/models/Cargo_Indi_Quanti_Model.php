<?php

class Cargo_Indi_Quanti_Model extends Model {

    public $_tabela = "cargo_indi_quanti";

    public function __construct() {
        parent::__construct();
    }

    public function adicionar($id_cargo, $indicadores) {

        $id_cliente = $_SESSION["id_cliente"];
        $this->remove($id_cargo);
        $explode = explode(',', $indicadores);
        foreach ($explode as $key => $value) {

            $result = $this->read("id_cliente = '{$id_cliente}' AND id_cargo = '{$id_cargo}' AND id_indicador = '{$value}'");

            if (!count($result)) {
                $saida = $this->insert(array(
                    "id_cliente" => $id_cliente,
                    "id_cargo" => $id_cargo,
                    "id_indicador" => $value,
                    "posicao" => $key + 1
                ));
            }
        }

        return true;
    }

    public function get($id_cargo) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->query("SELECT indicadores_quant.descricao, indicadores_quant.id FROM `cargo_indi_quanti` INNER JOIN indicadores_quant ON (indicadores_quant.id = cargo_indi_quanti.id_indicador) WHERE cargo_indi_quanti.id_cliente = '{$id_cliente}' AND cargo_indi_quanti.id_cargo = '{$id_cargo}' ORDER BY cargo_indi_quanti.posicao ASC ");
        return $result;
    }

    public function remove($id_cargo) {
        return $this->delete("id_cargo = '{$id_cargo}'");
    }

}
