<?php

class Indicadores_Quali_Peso_Model extends Model {

    public $_tabela = "indicadores_quali_peso";

    public function __construct() {
        parent::__construct();
    }

    public function get($cond = "*") {

        if ($cond == "1") {
            $result = $this->query("SELECT indicadores_quali_peso.*, indicadores_quali.descricao AS indicador_quali FROM indicadores_quali_peso INNER JOIN indicadores_quali ON (indicadores_quali.id = indicadores_quali_peso.id_ind_qualitativo) WHERE indicadores_quali_peso.status='1' ORDER BY indicadores_quali.descricao ASC");
        } else {
            $result = $this->query("SELECT indicadores_quali_peso.*, indicadores_quali.descricao AS indicador_quali FROM indicadores_quali_peso INNER JOIN indicadores_quali ON (indicadores_quali.id = indicadores_quali_peso.id_ind_qualitativo) ORDER BY indicadores_quali.descricao ASC");
        }



        foreach ($result as $key => $value) {
            if ($value["status"] == "1") {
                $result[$key]["status"] = "ATIVO";
            } else if ($value["status"] == "0") {
                $result[$key]["status"] = "INATIVO";
            }

            $result[$key]["peso1"] = number_format($result[$key]["peso1"], 2, ',', '.');
            $result[$key]["inter1"] = number_format($result[$key]["inter1"], 2, ',', '.');
            $result[$key]["peso2"] = number_format($result[$key]["peso2"], 2, ',', '.');
            $result[$key]["inter2"] = number_format($result[$key]["inter2"], 2, ',', '.');
            $result[$key]["peso3"] = number_format($result[$key]["peso3"], 2, ',', '.');
            $result[$key]["inter3"] = number_format($result[$key]["inter3"], 2, ',', '.');
            $result[$key]["peso4"] = number_format($result[$key]["peso4"], 2, ',', '.');
        }

        return $result;
    }

    public function getByID($id_indicadores) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->read("id = '{$id_indicadores}'");

        foreach ($result as $key => $value) {
            $result[$key]["peso1"] = number_format($result[$key]["peso1"], 2, ',', '.');
            $result[$key]["inter1"] = number_format($result[$key]["inter1"], 2, ',', '.');
            $result[$key]["peso2"] = number_format($result[$key]["peso2"], 2, ',', '.');
            $result[$key]["inter2"] = number_format($result[$key]["inter2"], 2, ',', '.');
            $result[$key]["peso3"] = number_format($result[$key]["peso3"], 2, ',', '.');
            $result[$key]["inter3"] = number_format($result[$key]["inter3"], 2, ',', '.');
            $result[$key]["peso4"] = number_format($result[$key]["peso4"], 2, ',', '.');
        }

        return $result[0];
    }

    public function getPesoAvaliacao($id_indicadores_qualitativo, $id_indicadores_quali_graduacao, $intermediario) {
        $indicadores_qual_graduacao = new Indicadores_Quali_Graduacao_Model();
        $graduacao = $indicadores_qual_graduacao->getByID($id_indicadores_quali_graduacao);

        $peso_b = $graduacao["ordem_horizontal"];

        $result = $this->read("id_ind_qualitativo = '{$id_indicadores_qualitativo}'");

        $p = ($intermediario ? "inter" . $peso_b : "peso" . $peso_b);

        return $result[0][$p];
    }

    public function adicionar($id_ind_qualitativo, $peso1, $inter1, $peso2, $inter2, $peso3, $inter3, $peso4) {
        $result = $this->read("id_ind_qualitativo = '{$id_ind_qualitativo}'");

        if (!count($result)) {

            $peso1 = str_replace(',', '.', str_replace('.', '', $peso1));
            $inter1 = str_replace(',', '.', str_replace('.', '', $inter1));
            $peso2 = str_replace(',', '.', str_replace('.', '', $peso2));
            $inter2 = str_replace(',', '.', str_replace('.', '', $inter2));
            $peso3 = str_replace(',', '.', str_replace('.', '', $peso3));
            $inter3 = str_replace(',', '.', str_replace('.', '', $inter3));
            $peso4 = str_replace(',', '.', str_replace('.', '', $peso4));

            $saida = $this->insert(array(
                "id_ind_qualitativo" => $id_ind_qualitativo,
                "peso1" => $peso1,
                "inter1" => $inter1,
                "peso2" => $peso2,
                "inter2" => $inter2,
                "peso3" => $peso3,
                "inter3" => $inter3,
                "peso4" => $peso4,
                "dt_ini" => date('Y-m-d'),
                "hora_ini" => date('H:i:s'),
                "user_ini" => $_SESSION["user_name"]
                    ), "sadsa");
            if (is_numeric($saida)) {
                $this->log("Insersão", $id_ind_qualitativo);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function editar($id_indicadores, $id_ind_qualitativo, $peso1, $inter1, $peso2, $inter2, $peso3, $inter3, $peso4, $status) {
        $result = $this->read("(id_ind_qualitativo = '{$id_ind_qualitativo}') AND id != '{$id_indicadores}'");

        if (!count($result)) {
            $peso1 = str_replace(',', '.', str_replace('.', '', $peso1));
            $inter1 = str_replace(',', '.', str_replace('.', '', $inter1));
            $peso2 = str_replace(',', '.', str_replace('.', '', $peso2));
            $inter2 = str_replace(',', '.', str_replace('.', '', $inter2));
            $peso3 = str_replace(',', '.', str_replace('.', '', $peso3));
            $inter3 = str_replace(',', '.', str_replace('.', '', $inter3));
            $peso4 = str_replace(',', '.', str_replace('.', '', $peso4));

            $saida = $this->update(array(
                "id_ind_qualitativo" => $id_ind_qualitativo,
                "peso1" => $peso1,
                "inter1" => $inter1,
                "peso2" => $peso2,
                "inter2" => $inter2,
                "peso3" => $peso3,
                "inter3" => $inter3,
                "peso4" => $peso4,
                "status" => $status,
                "dt_update" => date('Y-m-d'),
                "hora_update" => date('H:i:s'),
                "user_update" => $_SESSION["user_name"]
                    ), "id = '{$id_indicadores}'");
            if (is_numeric($saida)) {
                $this->log("Alteração", $id_ind_qualitativo);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function remover($id_indicadores) {
        $saida = $this->delete("id = '{$id_indicadores}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_indicadores);
            return $saida;
        } else {
            return FALSE;
        }
    }

}
