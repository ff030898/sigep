<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of indicadores_quant_sub_negacao_Model
 *
 * @author drc
 */
class Indicadores_Quant_Sub_Negacao_Model extends Model {

    public $_tabela = "indicadores_quant_sub_negacao";

    public function __construct() {
        parent::__construct();
    }

    public function get($id_indicador_quant_sub) {
        return $this->read("id_indicadores_quant_sub = '{$id_indicador_quant_sub}'", null, null, "descricao ASC", "id, id_indicadores_quant_sub, descricao");
    }

    public function getById($id_negacao) {
        $result = $this->read("id = '{$id_negacao}'");
        return $result[0];
    }

    public function adicionar($id_indicador_quant_sub, $descricao) {
        $result = $this->read("id_indicadores_quant_sub = '{$id_indicador_quant_sub}' AND descricao = '{$descricao}'");

        if (!count($result)) {
            $id = $this->insert(array(
                "id_indicadores_quant_sub" => $id_indicador_quant_sub,
                "descricao" => $descricao
                    ), "id");

            if (filter_var($id, FILTER_VALIDATE_INT) && $id > 0) {
                $arr = array("status" => true);
                $this->log("Inserção", "Adicionada o registro " . $descricao . " com Sucesso.");
            } else {
                $arr = array("status" => false, "message" => "Erro ao criar o novo registro.");
                $this->log("Inserção", "Erro ao adicionar " . $descricao . ".");
            }
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Inserção", "Erro ao adicionar " . $descricao . " pois já existe um igual.");
        }
        return json_encode($arr);
    }

    public function editar($id, $id_indicador_quant_sub, $descricao) {
        $result = $this->read("id_indicadores_quant_sub = '{$id_indicador_quant_sub}' AND descricao = '{$descricao}' AND id != '{$id}'");

        if (!count($result)) {
            $ret = $this->update(array(
                "descricao" => $descricao
                    ), "id = '{$id}'");

            if (filter_var($ret, FILTER_VALIDATE_INT) && $id > 0) {
                $arr = array("status" => true);
                $this->log("Alteração", "Alterado o registro " . $descricao . " com Sucesso.");
            } else {
                $arr = array("status" => false, "message" => "Erro ao alterar o registro.");
                $this->log("Alteração", "Erro ao alterar " . $descricao . ".");
            }
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Alteração", "Erro ao alterar " . $descricao . " pois já existe um igual.");
        }
        return json_encode($arr);
    }

    public function remover($id) {
        $ret = $this->delete("id = '{$id}'");

        if (filter_var($ret, FILTER_VALIDATE_INT) && $id > 0) {
            $arr = array("status" => true);
            $this->log("Remoção", "Removido o registro " . $id . " com Sucesso.");
        } else {
            $arr = array("status" => false, "message" => "Erro ao alterar o registro.");
            $this->log("Remoção", "Erro ao remover " . $id . ".");
        }

        return json_encode($arr);
    }

}
