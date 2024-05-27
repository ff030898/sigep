<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Restricoes_Model
 *
 * @author drc
 */
class Restricoes_Model extends Model {

    public $_tabela = "restricoes";

    public function adicionar($nome, $hora_ini, $hora_fim, $seg = 0, $ter = 0, $qua = 0, $qui = 0, $sex = 0, $sab = 0, $dom = 0) {

        $result = $this->read("nome = '{$nome}'");
        if (count($result) == 0) {
            $this->log("Inserção", $nome);
            return $this->insert(array(
                        "nome" => $nome,
                        "hora_ini" => $hora_ini,
                        "hora_fim" => $hora_fim,
                        "seg" => $seg,
                        "ter" => $ter,
                        "qua" => $qua,
                        "qui" => $qui,
                        "sex" => $sex,
                        "sab" => $sab,
                        "dom" => $dom
            ));
        }
    }

    public function editar($id, $hora_ini, $hora_fim, $seg = 0, $ter = 0, $qua = 0, $qui = 0, $sex = 0, $sab = 0, $dom = 0) {

        $result = $this->read("id = '{$id}'", NULL, NULL, NULL, "nome");
        $nome = $result[0]["nome"];
        $this->log("Alteração", $nome);
        return $this->update(array(
                    "hora_ini" => $hora_ini,
                    "hora_fim" => $hora_fim,
                    "seg" => $seg,
                    "ter" => $ter,
                    "qua" => $qua,
                    "qui" => $qui,
                    "sex" => $sex,
                    "sab" => $sab,
                    "dom" => $dom
                        ), "id = '{$id}'");
    }

    public function pagination($page = null, $reg_page) {
        $pagination = null;
        if ($page != null) {

            $adjacents = 2;
            $prevlabel = "Anterior";
            $nextlabel = "Próximo";

            $per_page = $reg_page;
            $total_results = $this->total();
            $total_pages = ceil($total_results / $per_page);
            $tpages = $total_pages;

            $reload = "/restricoes/index/page/";

            $out = '<ul class="pagination">';
            if ($page == 1) {

            } elseif ($page == 2) {
                $out.="<li><a href=\"" . $reload . "\">" . $prevlabel . "</a>\n</li>";
            } elseif ($page == 3) {
                $out.="<li><a href=\"" . $reload . "\">" . $prevlabel . "</a>\n</li>";
            } else {
                $out.="<li><a href=\"" . $reload . ($page - 1) . "\">" . $prevlabel . "</a>\n</li>";
                $out.="<li><a href=\"" . $reload . "1" . "\">" . "1" . "</a>\n</li>";
            }
            $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
            $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
            for ($i = $pmin; $i <= $pmax; $i++) {
                if ($i == $page) {
                    $out.= "<li class=\"active\"><a href=''>" . $i . "</a></li>\n";
                } elseif ($i == 1) {
                    $out.= "<li><a href=\"" . $reload . "\">" . $i . "</a>\n</li>";
                } else {
                    $out.= "<li><a href=\"" . $reload . $i . "\">" . $i . "</a>\n</li>";
                }
            }

            if ($page < ($tpages - $adjacents)) {
//                $out.= "<li><a href=\"" . $reload . $tpages . "\">" . $tpages . "</a>\n</li>";
            }
            // next

            if ($page == $total_pages - 1) {
                $out.= "<li><a href=\"" . $reload . ($page + 1) . "\">" . $nextlabel . "</a>\n</li>";
            } else if ($page < $tpages) {
                $out.= "<li><a href=\"" . $reload . $tpages . "\">" . $tpages . "</a>\n</li>";
                $out.= "<li><a href=\"" . $reload . ($page + 1) . "\">" . $nextlabel . "</a>\n</li>";
            } else {

            }
            $out.= "</ul>";
        }

        return $out;
    }

    public function getRestricaoID($id) {

        $result = $this->read("id = '{$id}'");

        return $result[0];
    }

    public function getRestricao($page) {
        $result = $this->read(NULL, 1, $page, "id ASC");

        return $result[0];
    }

    public function getRestri() {
        $result = $this->read(NULL, NULL, NULL, "nome ASC");

        return $result;
    }

    public function remove($id) {
        $result = $this->read("id = '{$id}'", NULL, NULL, NULL, "nome");
        $nome = $result[0]["nome"];
        $this->log("Remoção", $nome);
        return $this->delete("id = '{$id}'");
    }

    public function search($search) {
        $mostra = null;
        $result = $this->search_restricao($search);
        foreach ($result as $key => $value) {
            $mostra .= '<a href="/restricoes/index/id/' . $value["id"] . '">' . $value["nome"] . "</a><br />";
        }

        return $mostra;
    }

}
