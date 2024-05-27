<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Avaliacao_Indicadores_Qualitativos_Model
 *
 * @author drc
 */
class Avaliacao_Indicadores_Qualitativos_Model extends Model {

    public $_tabela = 'avaliacao_indicadores_qualitativos';

    public function __construct() {
        parent::__construct();
    }

    public function adicionar($id_cliente, $id_cargo, $id_avaliacao, $indicadores_qualitativos) {
        $test = true;

        $cargo_indi_quali = new Cargo_Indi_Quali_Model();
        $indicadores_peso = new Indicadores_Quali_Peso_Model();

        foreach ($indicadores_qualitativos as $value) {
            $posicao = 0;
            $peso = 0;

            $posicao = $cargo_indi_quali->getPosicao($id_cliente, $id_cargo, $value["id_indicadores_quali"]);
            $peso = $indicadores_peso->getPesoAvaliacao($value["id_indicadores_quali"], $value["id_indicadores_quali_graduacao"], $value["intermediario"]);

            $id = $this->insert(array(
                "id_avaliacao" => $id_avaliacao,
                "id_indicadores_quali" => $value["id_indicadores_quali"],
                "id_indicadores_quali_graduacao" => $value["id_indicadores_quali_graduacao"],
                "intermediario" => $value["intermediario"],
                "posicao" => $posicao,
                "peso" => $peso
                    ), "id");

            $test = (filter_var($id, FILTER_VALIDATE_INT) && $id > 0 ? $test : false);
        }

        $this->defineResultado($id_avaliacao);

        return $test;
    }

    public function validaRespostaPlanoAcao($id_avaliacao) {
        $plano_acao = new Avaliacao_Qualitativas_Plano_Acao_Model();

        $result = $this->query("SELECT avaliacao_indicadores_qualitativos.id  FROM `avaliacao_indicadores_qualitativos` INNER JOIN indicadores_quali_graduacao ON (indicadores_quali_graduacao.id = avaliacao_indicadores_qualitativos.id_indicadores_quali_graduacao) WHERE avaliacao_indicadores_qualitativos.id_avaliacao = '{$id_avaliacao}' AND indicadores_quali_graduacao.ordem_horizontal <= '3' AND CONCAT(indicadores_quali_graduacao.ordem_horizontal,avaliacao_indicadores_qualitativos.intermediario) <= '21' ORDER BY avaliacao_indicadores_qualitativos.posicao ASC ");

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

    private function defineResultado($idAvaliacao) {
        try {
            $avaliacao = new Avaliacao_Model();

            $result = $this->query("SELECT (SUM(hab.realizado)/COUNT(hab.realizado)) AS total FROM ( SELECT indicadores_quali.descricao AS habilidade, (CONCAT(indicadores_quali_graduacao.ordem_horizontal,avaliacao_indicadores_qualitativos.intermediario)/40)*100 AS realizado FROM `avaliacao_indicadores_qualitativos` INNER JOIN avaliacao ON (avaliacao.id = avaliacao_indicadores_qualitativos.id_avaliacao) INNER JOIN indicadores_quali ON (indicadores_quali.id = avaliacao_indicadores_qualitativos.id_indicadores_quali) INNER JOIN indicadores_quali_graduacao ON (indicadores_quali_graduacao.id = avaliacao_indicadores_qualitativos.id_indicadores_quali_graduacao) WHERE avaliacao.id = '{$idAvaliacao}' ) AS hab WHERE hab.habilidade != '' ");

            if (count($result))
                $avaliacao->setResultadoAvaliacaoQualitativa($idAvaliacao, $result[0]["total"]);
        } catch (Exception $ex) {

        }
    }

}
