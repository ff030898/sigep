<?php

class CBO_Model extends Model {

    public $_tabela = "cbo";

    public function __construct() {
        parent::__construct();
    }

    public function getCBO() {
        $security = new securityHelper();

        $result = $this->read(NULL, NULL, NULL, "codigo ASC");

        foreach ($result as $key => $value) {
            if ($value["status"] == "1") {
                $result[$key]["status"] = "ATIVO";
            } else if ($value["status"] == "0") {
                $result[$key]["status"] = "INATIVO";
            }
        }

        return $result;
    }

    public function getCBOByID($id_cbo) {
        $result = $this->read("id = '{$id_cbo}'");
        return $result[0];
    }

    public function search_cbo($pesquisa, $funcao) {
        $result = $this->query("SELECT * FROM cbo WHERE (id LIKE '%{$pesquisa}%' OR codigo LIKE '%{$pesquisa}%'  OR titulo LIKE '%{$pesquisa}%') AND status = '1' ORDER BY codigo ASC");
        $mostra = null;
        $total = count($result);
        $mostra .= '<div style="float: left;">';
        $mostra .= '
            <div class="dataTable_wrapper">
                <table width="100%" class="table table-striped table-bordered table-hover" id="empresas_listas">
                    <tbody>
        ';
        for ($i = 0; $i <= $total - 1; $i++) {
            $mostra .= '
                <tr>
                    <td>';
            $mostra .= '<a href="javascript:' . $funcao . '(\'' . $result[$i]["id"] . '-' . $result[$i]["codigo"] . '-' . $result[$i]["titulo"] . '\')">' . $result[$i]["id"] . '-' . $result[$i]["codigo"] . '-' . $result[$i]["titulo"] . "</a>";
            $mostra .= '
                    </td>
                </tr>
            ';
        }

        $mostra = ($total == 0 ? "Não encontrado nenhum registro" : $mostra);

        $mostra .= '<div style = "float: right;">
                    <a href = "javascript:fechar_pesquisa()"><i class = "fa fa-window-close-o fa-lg"></i></a>
                    </div>';

        return $mostra;
    }

    public function adicionar($codigo, $titulo) {

        $result = $this->read("(codigo = '{$codigo}' || titulo = '{$titulo}')");

        if (!count($result)) {

            $saida = $this->insert(array(
                "codigo" => $codigo,
                "titulo" => $titulo,
                "dt_ini" => date('Y-m-d'),
                "hora_ini" => date('H:i:s'),
                "user_ini" => $_SESSION["user_name"]
                    ), "sadsa");
            if (is_numeric($saida)) {
                $this->log("Insersão", $titulo);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function editar($id_CBO, $codigo, $titulo, $status) {

        $result = $this->read("(codigo = '{$codigo}' || titulo = '{$titulo}') AND id != '{$id_CBO}'");

        if (!count($result)) {


            $saida = $this->update(array(
                "codigo" => $codigo,
                "titulo" => $titulo,
                "status" => $status,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_CBO}'");
            if (is_numeric($saida)) {
                $this->log("Alteração", $titulo);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function remover($id_CBO) {

        $result = $this->read("id = '{$id_CBO}'", NULL, NULL, NULL, "codigo");

        $saida = $this->delete("id = '{$id_CBO}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_CBO . "-" . $result[0]["codigo"]);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
