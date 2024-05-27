<?php

class Estrutura_Organizacional_Model extends Model {

    public $_tabela = "estrutura_organizacional";
    private $_estrutura = NULL;

    public function __construct() {
        parent::__construct();
    }

    public function adicionar($root, $descricao, $ordem) {
        $id_cliente = $_SESSION["id_cliente"];

        if ($root == 0) {
            $tipo = $root + 1;
        } else {
            $res = $this->read("id = '{$root}'");
            $tipo = $res[0]["tipo"] + 1;
        }

        $result = $this->read("id_clients = '{$id_cliente}' AND root = '{$root}' AND tipo = '{$tipo}' AND descricao = '{$descricao}'");

        if (!count($result)) {
            $this->insert(array(
                "id_clients" => $id_cliente,
                "root" => $root,
                "tipo" => $tipo,
                "descricao" => $descricao,
                "ordem" => $ordem,
                "user_ini" => $_SESSION["user_name"]
            ));

            $arr = array("status" => true);
            $this->log("Inserção", "Adicionada a Descriao " . $descricao . " na Estrutura Organizacional com Sucesso.");
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Inserção", "Erro ao adicionar a Descriao " . $descricao . " na Estrutura Organizacional pois já existe um igual.");
        }

        return json_encode($arr);
    }

    public function editar($id, $descricao, $ordem) {
        $id_cliente = $_SESSION["id_cliente"];

        $res = $this->read("id = '{$id}'");

        $root = $res[0]["root"];
        $tipo = $res[0]["tipo"];

        $result = $this->read("id_clients = '{$id_cliente}' AND root = '{$root}' AND tipo = '{$tipo}' AND descricao = '{$descricao}' AND id != '{$id}'");

        if (!count($result)) {
            $this->update(array(
                "descricao" => $descricao,
                "ordem" => $ordem,
                "dt_time_update" => date("Y-m-d H:i:s"),
                "user_update" => $_SESSION["user_name"]
                    ), "id = $id");

            $arr = array("status" => true);
            $this->log("Alteração", "Alterada a Descriao " . $descricao . " na Estrutura Organizacional com Sucesso.");
        } else {
            $arr = array("status" => false, "message" => "Já existe uma cadastro com os dados informados.");
            $this->log("Alteração", "Erro ao alterar a Descriao " . $descricao . " na Estrutura Organizacional pois já existe um igual.");
        }

        return json_encode($arr);
    }

    public function remover($id) {
        $ret = $this->delete("id = '{$id}'");

        if ($ret) {
            $arr = array("status" => true);
            $this->log("Remoção", "Removido a Descriao ID " . $id . " na Estrutura Organizacional com Sucesso.");
        } else {
            $arr = array("status" => false, "message" => "Não foi possível remover essa Descrição da Unidade Organizacional.");
            $this->log("Remoção", "Erro ao remover a Descriao ID " . $id . " da Estrutura Organizacional.");
        }

        return json_encode($arr);
    }

    public function MontaEstrutura() {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("id_clients = '{$id_cliente}'", null, null, "ordem ASC, descricao ASC", "id, root, descricao, tipo, ordem");

        $arrayCategories = array();

        foreach ($result as $value) {
            $arrayCategories[$value['id']] = array("code" => $value["id"], "parent_id" => $value['root'], "name" => $value['descricao'], "tipo" => $value["tipo"], "ordem" => $value["ordem"]);
        }

        $this->CreateEstrutura($arrayCategories, 0);

        return $this->_estrutura;
    }

    private function CreateEstrutura($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
        foreach ($array as $categoryId => $category) {
            if ($currentParent == $category['parent_id']) {
                if ($currLevel > $prevLevel)
                    $this->_estrutura .= ' <ul> ';

                if ($currLevel == $prevLevel)
                    $this->_estrutura .= " </li> ";


                if ($category["tipo"] != 5) {
                    $this->_estrutura .= '
                           <li id="' . $category['code'] . '" class="estrutura_organizacional"><small><b>' . $this->getNomeTipo($category["tipo"]) . '</b></small><br />'
                            . $category['name'] . '<br />'
                            . '<a href="#" onclick="javascript:adicionar(' . $category['code'] . ');"><i class="fa fa-plus-circle"></i></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                            . '<a href="#" onclick="javascript:editar(' . $category['code'] . ',\'' . $category["name"] . '\', ' . $category["ordem"] . ');"><i class="fa fa-pencil-square-o"></i></a>';
                } else {
                    $this->_estrutura .= '
                            <li id="' . $category['code'] . '" class="estrutura_organizacional"> <small><b>' . $this->getNomeTipo($category["tipo"]) . '</b></small><br />'
                            . $category['name'] . '<br />'
                            . '<a href="#" onclick="javascript:editar(' . $category['code'] . ',\'' . $category["name"] . '\', ' . $category["ordem"] . ');"><i class="fa fa-pencil-square-o"></i></a>';
                }

                if (!$this->verificaSubItem($category["code"])) {
                    $this->_estrutura .= ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="javascript:remover(' . $category['code'] . ');"><i class="fa fa-trash"></i></a>';
                }


                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }
                $currLevel++;
                $this->CreateEstrutura($array, $categoryId, $currLevel, $prevLevel);
                $currLevel--;
            }
        }

        if ($currLevel == $prevLevel)
            $this->_estrutura .= " </li>  </ul> ";
    }

    public function MontaEstrutura2() {
        $id_cliente = $_SESSION["id_cliente"];


        $result = $this->read("id_clients = '{$id_cliente}'", null, null, "ordem ASC, descricao ASC", "id, root, descricao, tipo");

        $arrayCategories = array();

        foreach ($result as $value) {
            $arrayCategories[$value['id']] = array("code" => $value["id"], "parent_id" => $value['root'], "name" => $value['descricao'], "tipo" => $value["tipo"]);
        }

        $this->CreateEstrutura2($arrayCategories, 0);

        return $this->_estrutura;
    }

    private function CreateEstrutura2($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
        foreach ($array as $categoryId => $category) {
            if ($currentParent == $category['parent_id']) {
                if ($currLevel > $prevLevel)
                    $this->_estrutura .= ' <ul> ';

                if ($currLevel == $prevLevel)
                    $this->_estrutura .= " </li> ";

                $this->_estrutura .= '
                           <li id="' . $category['code'] . '" class="estrutura_organizacional"> <small><b>' . $this->getNomeTipo($category["tipo"]) . '</b></small><br /> ' . $category['name'];

                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }
                $currLevel++;
                $this->CreateEstrutura2($array, $categoryId, $currLevel, $prevLevel);
                $currLevel--;
            }
        }

        if ($currLevel == $prevLevel)
            $this->_estrutura .= " </li>  </ul> ";
    }

    private function getNomeTipo($tipo) {
        if ($tipo == 1) {
            return "UNIDADE";
        } else if ($tipo == 2) {
            return "ÁREA";
        } else if ($tipo == 3) {
            return "SETOR";
        } else if ($tipo == 4) {
            return "AMBIENTE";
        } else if ($tipo == 5) {
            return "CÉLULA";
        }
    }

    private function verificaSubItem($id) {
        $result = $this->read("root = '{$id}'");
        if (count($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function getEstruturaById($id, $pos = 0, $estrutura = array()) {
        $result = $this->read("id = '{$id}'");

        $estrutura[$pos] = $this->getNomeTipo($result[0]["tipo"]) . ": " . $result[0]["descricao"] . " ";

        if ($result[0]["root"] >= 1)
            $estrutura[$pos] = $this->getEstruturaById($result[0]["root"], $pos++, $estrutura);

        $est = array_reverse($estrutura);

        $retorna = "";
        foreach ($est as $value) {
            $retorna .= $value;
        }
        return $retorna;
    }

}
