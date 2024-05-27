<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Avaliacao_Indicadores_Quantitativos
 *
 * @author drc
 */
class Avaliacao_Indicadores_Quantitativos extends Model {

    public $_tabela = 'avaliacao_indicadores_quantitativos';

    public function __construct() {
        parent::__construct();
    }

    public function adicionar($id_avaliacao, $indicadores_quantitativos) {
        $test = true;

        foreach ($indicadores_quantitativos as $value) {

            $sub_negacao = ($value["id_indicadores_quant_sub_negacao"] > 0 ? $value["id_indicadores_quant_sub_negacao"] : "");

            $id = $this->insert(array(
                "id_avaliavao" => $id_avaliacao,
                "id_indicadores_quant_tipo" => $value["id_indicadores_quant_tipo"],
                "id_indicadores_quant_sub" => $value["id_indicadores_quant_sub"],
                "id_perfil_cargo_indicadores" => $value["id_perfil_cargo_indicadores"],
                "id_indicadores_quant_sub_negacao" => $sub_negacao,
                "resposta" => $value["resposta"]
                    ), "id");
            $test = (filter_var($id, FILTER_VALIDATE_INT) && $id > 0 ? $test : false);
        }

        $this->defineResultado($id_avaliacao);

        return $test;
    }

    private function defineResultado($idAvaliacao) {
        try {
            $avaliacao = new Avaliacao_Model();
            $result = $this->read("id_avaliavao = '{$idAvaliacao}'", null, null, null, "(COUNT(IF(resposta=1,1,NULL)) / COUNT(id))*100 AS total");

            $avaliacao->setResultadoAvaliacaoQuantitativa($idAvaliacao, $result[0]["total"]);
        } catch (Exception $ex) {

        }
    }

    public function validaRespostaPlanoAcao($id_avaliacao) {
        $plano_acao = new Avaliacao_Quantitativas_Plano_Acao_Model();

        $result = $this->read("id_avaliavao = '{$id_avaliacao}' AND resposta = '0'");

        if (!count($result)) {
            return true;
        } else {
            $test = true;
            foreach ($result as $value) {
                $test = $plano_acao->validaResposta($value["id"]) ? $test : false;
            }
            return $test;
        }
    }

}
