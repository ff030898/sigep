<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Indicadores_Quali_Sub_Model
 *
 * @author drc
 */
class Indicadores_Quant_Sub_Model extends Model {

    public $_tabela = 'indicadores_quant_sub';

    public function __construct() {
        parent::__construct();
    }

    public function get($id_indicadores_quant_tipo) {
        $result = $this->read("id_indicadores_quant_tipo = '{$id_indicadores_quant_tipo}'", null, null, "descricao ASC", "id, id_indicadores_quant_tipo, descricao, status");

        foreach ($result as $key => $value) {
            $result[$key]["status_nome"] = $this->getStatusNome($value["status"]);
        }

        return $result;
    }

    public function getById($id_sub) {
        $result = $this->read("id = '$id_sub'");
        return $result[0];
    }

    private function getStatusNome($status) {
        if ($status == "1") {
            return "Ativo";
        } else if ($status == "0") {
            return "Inativo";
        } else {
            return $status;
        }
    }

    public function adicionar($id_indicadores_quant_tipo, $descricao) {
        $result = $this->read("id_indicadores_quant_tipo = '{$id_indicadores_quant_tipo}' AND descricao = '{$descricao}'");

        if (!count($result)) {
            $id = $this->insert(array(
                "id_indicadores_quant_tipo" => $id_indicadores_quant_tipo,
                "descricao" => $descricao,
                "status" => 1,
                "user_ini" => $_SESSION["user_name"]
                    ), "id");
            if (filter_var($id, FILTER_VALIDATE_INT)) {
                $this->log("Inserção", "Adicionada a Sub Categoria " . $descricao . " para o Indicador Quantitativo ID " . $id_indicadores_quant_tipo);
                $arr = array("status" => true, "message" => "Adicionado com sucesso.");
            } else {
                $arr = array("status" => false, "message" => "Erro ao adicionar o registro!\nProcure o administrador de sistema.");
                $this->log("Inserção", "Erro ao adicionar a Sub Categoria " . $descricao . " para o Indicador Quantitativo ID " . $id_indicadores_quant_tipo);
            }
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Inserção", "Erro ao adicionar a Sub Categoria " . $descricao . " para o Indicador Quantitativo ID " . $id_indicadores_quant_tipo . " pois já existe um igual.");
        }

        return json_encode($arr);
    }

    public function editar($id_indicadores_quant_tipo, $id_sub, $descricao, $status) {
        $result = $this->read("id_indicadores_quant_tipo = '{$id_indicadores_quant_tipo}' AND descricao = '{$descricao}' AND id != '{$id_sub}'");

        if (!count($result)) {
            $id = $this->update(array(
                "descricao" => $descricao,
                "status" => $status,
                "dt_hora_update" => date("Y-m-d H:i:s"),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_sub}'");
            if (filter_var($id, FILTER_VALIDATE_INT)) {
                $this->log("Alteração", "Alterada a Sub Categoria " . $descricao . " para o Indicador Quantitativo ID " . $id_indicadores_quant_tipo);
                $arr = array("status" => true, "message" => "Alterado com sucesso.");
            } else {
                $arr = array("status" => false, "message" => "Erro ao alterar o registro!\nProcure o administrador de sistema.");
                $this->log("Alteração", "Erro ao alterar a Sub Categoria " . $descricao . " para o Indicador Quantitativo ID " . $id_indicadores_quant_tipo);
            }
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Alteração", "Erro ao alterar a Sub Categoria " . $descricao . " para o Indicador Quantitativo ID " . $id_indicadores_quant_tipo . " pois já existe um igual.");
        }

        return json_encode($arr);
    }

    public function remover($id) {
        $ret = $this->delete("id = '{$id}'");

        if (filter_var($ret, FILTER_VALIDATE_INT)) {
            $this->log("Remoção", "Removido o Sub Indicador de Quantitativo ID " . $id);
            $arr = array("status" => true, "message" => "Removido com sucesso.");
        } else {
            $arr = array("status" => false, "message" => "Erro ao Remover o registro!\nProcure o administrador de sistema.");
            $this->log("Remoção", "Erro ao remover a Sub Categoria " . $id);
        }

        return json_encode($arr);
    }

}
