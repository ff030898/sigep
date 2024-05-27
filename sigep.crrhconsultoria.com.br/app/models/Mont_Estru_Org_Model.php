<?php

class Mont_Estru_Org_Model extends Model {

    public $_tabela = "mont_estru_org";

    public function __construct() {
        parent::__construct();
    }

    public function getMontEstruOrg() {

        $id_cliente = $_SESSION["id_cliente"];

        $security = new securityHelper();

        $result = $this->query("SELECT mont_estru_org.id, mont_estru_org.posicao, mont_estru_org.status, estrutura_org.descricao AS estrutura, class_estrutura.descricao AS classificacao FROM `mont_estru_org` INNER JOIN estrutura_org ON (estrutura_org.id = mont_estru_org.id_estrutura_org) INNER JOIN class_estrutura ON (class_estrutura.id = mont_estru_org.id_class_org) WHERE mont_estru_org.id_cliente = '{$id_cliente}' ORDER BY mont_estru_org.id ASC ");

        foreach ($result as $key => $value) {
            if ($value["status"] == "1") {
                $result[$key]["status"] = "ATIVO";
            } else if ($value["status"] == "0") {
                $result[$key]["status"] = "INATIVO";
            }
        }

        return $result;
    }

    public function getMontEstruOrgByID($id_mont_class_org) {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("id = '{$id_mont_class_org}' AND id_cliente = '{$id_cliente}'");
        return $result[0];
    }

    public function adicionar($estrutura_org, $class_org, $posicao) {

        $id_cliente = $_SESSION["id_cliente"];

        $explode = explode('-', $estrutura_org);
        list($id_estrutura_org) = $explode;

        $explode = explode('-', $class_org);
        list($id_class_org) = $explode;

        $result = $this->read("id_estrutura_org = '{$id_estrutura_org}' AND id_class_org = '{$id_class_org}' AND posicao = '{$posicao}' AND id_cliente = '{$id_cliente}'");

        if (!count($result)) {

            $saida = $this->insert(array(
                "id_cliente" => $id_cliente,
                "id_estrutura_org" => $id_estrutura_org,
                "id_class_org" => $id_class_org,
                "posicao" => $posicao,
                "dt_ini" => date('Y-m-d'),
                "hora_ini" => date('H:i:s'),
                "user_ini" => $_SESSION["user_name"]
                    ), "sadsa");
            if (is_numeric($saida)) {
                $this->log("Insersão", $saida);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function editar($id_mont_class_org, $estrutura_org, $class_org, $posicao, $status) {

        $id_cliente = $_SESSION["id_cliente"];

        $explode = explode('-', $estrutura_org);
        list($id_estrutura_org) = $explode;

        $explode = explode('-', $class_org);
        list($id_class_org) = $explode;

        $result = $this->read("(id_estrutura_org = '{$id_estrutura_org}' AND id_class_org = '{$id_class_org}' AND posicao = '{$posicao}' AND id_cliente = '{$id_cliente}') AND id != '{$id_mont_class_org}'");

        if (!count($result)) {


            $saida = $this->update(array(
                "id_estrutura_org" => $id_estrutura_org,
                "id_class_org" => $id_class_org,
                "posicao" => $posicao,
                "status" => $status,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_mont_class_org}'");
            if (is_numeric($saida)) {
                $this->log("Alteração", $saida);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function remover($id_mont_class_org) {
        $id_cliente = $_SESSION["id_cliente"];

        $saida = $this->delete("id = '{$id_mont_class_org}' AND id_cliente = '{$id_cliente}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_mont_class_org);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
