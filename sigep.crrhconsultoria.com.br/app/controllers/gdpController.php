<?php

class gdp extends Controller {

    public function index_action() {
        $gdp = new Gdp_Model();

        $dados["gdp"] = $gdp->getGdp();

        $this->view('header');
        $this->view('menu');
        $this->view('content_gdp', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_gdp_add');
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $gdp = new Gdp_Model();

        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $classificacao = $security->antiInjection(filter_input(INPUT_POST, "classificacao"));
        $perc_min = $security->antiInjection(filter_input(INPUT_POST, "perc_min"));
        $perc_max = $security->antiInjection(filter_input(INPUT_POST, "perc_max"));

        print_r($gdp->adicionar($descricao, $classificacao, $perc_min, $perc_max));
    }

    public function edit() {
        $gdp = new Gdp_Model();
        $security = new securityHelper();

        $dados["gdp"] = $gdp->getGdpByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_gdp_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $gdp = new Gdp_Model();

        $id_gdp = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $classificacao = $security->antiInjection(filter_input(INPUT_POST, "classificacao"));
        $perc_min = $security->antiInjection(filter_input(INPUT_POST, "perc_min"));
        $perc_max = $security->antiInjection(filter_input(INPUT_POST, "perc_max"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($gdp->editar($id_gdp, $descricao, $status, $classificacao, $perc_min, $perc_max));
    }

    public function remover() {
        $security = new securityHelper();
        $gdp = new Gdp_Model();

        $id_gdp = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($gdp->remover($id_gdp));
    }

}
