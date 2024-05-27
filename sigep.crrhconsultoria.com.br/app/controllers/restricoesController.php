<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of restricoesController
 *
 * @author drc
 */
class restricoes extends Controller {

    public function index_action() {

        $restricoes = new Restricoes_Model();

        $page = $this->getParam("page");
        $id = $this->getParam("id");

        if (!isset($page)) {
            $page = 1;
        }

        if (!isset($id)) {
            $dados["restricao"] = $restricoes->getRestricao($page - 1);
            $dados["pagination"] = $restricoes->pagination($page, 1);
        } else {
            $dados["restricao"] = $restricoes->getRestricaoID($id);
            $dados["pagination"] = $restricoes->pagination($page, 1);
        }

        $this->view('header');
        $this->view('menu');
        $this->view('content_restricoes', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_restricoes_add');
        $this->view('footer');
    }

    public function edit() {

        $security = new securityHelper();
        $restricoes = new Restricoes_Model();

        $id = $security->antiInjection($this->getParam("id"));
        $dados["restricao"] = $restricoes->getRestricaoID($id);


        $this->view('header');
        $this->view('menu');
        $this->view('content_restricoes_edit', $dados);
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $restricoes = new Restricoes_Model();

        $nome = $security->antiInjection(filter_input(INPUT_POST, "nome"));
        $hora_ini = $security->antiInjection(filter_input(INPUT_POST, "hora_ini"));
        $hora_fim = $security->antiInjection(filter_input(INPUT_POST, "hora_fim"));
        $seg = $security->antiInjection(filter_input(INPUT_POST, "seg"));
        $ter = $security->antiInjection(filter_input(INPUT_POST, "ter"));
        $qua = $security->antiInjection(filter_input(INPUT_POST, "qua"));
        $qui = $security->antiInjection(filter_input(INPUT_POST, "qui"));
        $sex = $security->antiInjection(filter_input(INPUT_POST, "sex"));
        $sab = $security->antiInjection(filter_input(INPUT_POST, "sab"));
        $dom = $security->antiInjection(filter_input(INPUT_POST, "dom"));

        print_r($restricoes->adicionar($nome, $hora_ini, $hora_fim, $seg, $ter, $qua, $qui, $sex, $sab, $dom));
    }

    public function editar() {
        $security = new securityHelper();
        $restricoes = new Restricoes_Model();
        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $hora_ini = $security->antiInjection(filter_input(INPUT_POST, "hora_ini"));
        $hora_fim = $security->antiInjection(filter_input(INPUT_POST, "hora_fim"));
        $seg = $security->antiInjection(filter_input(INPUT_POST, "seg"));
        $ter = $security->antiInjection(filter_input(INPUT_POST, "ter"));
        $qua = $security->antiInjection(filter_input(INPUT_POST, "qua"));
        $qui = $security->antiInjection(filter_input(INPUT_POST, "qui"));
        $sex = $security->antiInjection(filter_input(INPUT_POST, "sex"));
        $sab = $security->antiInjection(filter_input(INPUT_POST, "sab"));
        $dom = $security->antiInjection(filter_input(INPUT_POST, "dom"));


        print_r($restricoes->editar($id, $hora_ini, $hora_fim, $seg, $ter, $qua, $qui, $sex, $sab, $dom));
    }

    public function remove() {
        $security = new securityHelper();
        $restricoes = new Restricoes_Model();
        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($restricoes->remove($id));
    }

    public function search() {
        $security = new securityHelper();
        $restricoes = new Restricoes_Model();

        $search = $security->antiInjection(filter_input(INPUT_POST, "search"));

        print_r($restricoes->search($search));
    }

}
