<?php

class cbo extends Controller {

    public function index_action() {
        $cbo = new CBO_Model();

        $dados["cbo"] = $cbo->getCBO();

        $this->view('header');
        $this->view('menu');
        $this->view('content_cbo', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_cbo_add');
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $cbo = new CBO_Model();

        $codigo = $security->antiInjection(filter_input(INPUT_POST, "codigo"));
        $titulo = $security->antiInjection(filter_input(INPUT_POST, "titulo"));

        print_r($cbo->adicionar($codigo, $titulo));
    }

    public function edit() {
        $cbo = new CBO_Model();
        $security = new securityHelper();

        $dados["cbo"] = $cbo->getCBOByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_cbo_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $cbo = new CBO_Model();

        $id_cbo = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $codigo = $security->antiInjection(filter_input(INPUT_POST, "codigo"));
        $titulo = $security->antiInjection(filter_input(INPUT_POST, "titulo"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($cbo->editar($id_cbo, $codigo, $titulo, $status));
    }

    public function remover() {
        $security = new securityHelper();
        $cbo = new CBO_Model();

        $id_cbo = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($cbo->remover($id_cbo));
    }

    public function search_cbo() {
        $security = new securityHelper();
        $cbo = new CBO_Model();

        $pesquisa = $security->antiInjection(filter_input(INPUT_POST, "pesquisa"));
        $funcao = $security->antiInjection(filter_input(INPUT_POST, "funcao"));

        print_r($cbo->search_cbo($pesquisa, $funcao));
    }

}
