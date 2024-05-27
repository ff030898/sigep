<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Avaliacao_Qualitativas_Plano_Acao_Model
 *
 * @author drc
 */
class Avaliacao_Qualitativas_Plano_Acao_Model extends Model {

    public $_tabela = "avaliacao_qualitativas_plano_acao";

    public function __construct() {
        parent::__construct();
    }

    public function adicionar($id_avaliacao_indicadores_qualitatitva, $plano_acao, $evidencia, $gut, $participantes) {
        $id = $this->insert(array(
            "id_avaliacao_indicadores_qualitativa " => $id_avaliacao_indicadores_qualitatitva,
            "plano_acao" => $plano_acao,
            "evidencia" => $evidencia,
            "gut" => $gut,
            "participantes" => $participantes,
            "user_ini" => $_SESSION["user_name"]
                ), "id");

        if (filter_var($id, FILTER_VALIDATE_INT) && $id > 0) {
            $arr["status"] = true;
            $arr["message"] = "Plano de Ação salvo com sucesso!";
            $arr["id"] = $id;
        } else {
            $arr["status"] = false;
            $arr["message"] = "Erro ao salvar o Plano de Ação!";
        }

        return json_encode($arr);
    }

    public function editar($id_avaliacao_indicadores_qualitatitva, $id_plano_acao, $plano_acao, $evidencia, $gut, $participantes) {
        $ret = $this->update(array(
            "plano_acao" => $plano_acao,
            "evidencia" => $evidencia,
            "gut" => $gut,
            "participantes" => $participantes,
            "user_ini" => $_SESSION["user_name"]
                ), "id = '{$id_plano_acao}'");

        if (filter_var($ret, FILTER_VALIDATE_INT) && $ret > 0) {
            $arr["status"] = true;
            $arr["message"] = "Plano de Ação salvo com sucesso!";
            $arr["planos"] = $this->getByIdAvaliacaoIndicadoresQualitativas($id_avaliacao_indicadores_qualitatitva);
        } else {
            $arr["status"] = false;
            $arr["message"] = "Erro ao salvar o Plano de Ação!";
        }

        return json_encode($arr);
    }

    public function getByIdAvaliacaoIndicadoresQualitativas($id_avaliacao_indicadores_qualitatitva) {
        $result = $this->read("id_avaliacao_indicadores_qualitativa = '{$id_avaliacao_indicadores_qualitatitva}'", null, null, "dt_time_ini ASC");
        foreach ($result as $key => $value) {
            $result[$key]["status_nome"] = $this->statusNome($value["status"]);
        }
        return $result;
    }

    private function statusNome($status) {
        if ($status == 0) {
            return "NÃO INICIADO";
        } else if ($status == 1) {
            return "EM ANDAMENTO";
        } else if ($status == 2) {
            return "REALIZADO";
        } else if ($status == 3) {
            return "CANCELADO";
        }
    }

    public function getById($id_avaliaca_plano_acao) {
        $result = $this->read("id = '{$id_avaliaca_plano_acao}'");
        if (count($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    public function getByIdJson($id_avaliaca_plano_acao) {
        $result = $this->read("id = '{$id_avaliaca_plano_acao}'");
        if (count($result)) {
            $arr["status"] = true;
            $arr["plano"] = $result[0];
        } else {
            $arr["status"] = false;
            $arr["plano"] = $id_avaliaca_plano_acao;
        }

        return json_encode($arr);
    }

    public function remover($id_avaliacao_plano_acao) {


        $plano_acao = $this->getById($id_avaliacao_plano_acao);

        $ret = $this->delete("id = '{$id_avaliacao_plano_acao}'");

        if (filter_var($ret, FILTER_VALIDATE_INT) && $ret > 0) {
            $arr["status"] = true;
            $arr["message"] = "Plano de Ação REMOVIDO com sucesso!";
            $arr["planos"] = $this->getByIdAvaliacaoIndicadoresQualitativas($plano_acao["id_avaliacao_indicadores_qualitativa"]);
        } else {
            $arr["status"] = false;
            $arr["message"] = "Erro ao remover o Plano de Ação!";
        }

        return json_encode($arr);
    }

    public function validaResposta($id_avaliacao_indicadores_qualitativa) {
        return $this->total("id_avaliacao_indicadores_qualitativa = '{$id_avaliacao_indicadores_qualitativa}'");
    }

    public function atualizar_status_plano_acao($indicadore_qualitativos) {
        $test = true;

        foreach ($indicadore_qualitativos as $value) {
            $test = ($this->update(array("status" => $value["situacao"]), "id = '{$value["id_indicador"]}'") ? $test : false);
        }

        return true;
    }

}
