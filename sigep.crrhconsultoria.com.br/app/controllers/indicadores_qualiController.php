<?php

class indicadores_quali extends Controller {

    public function index_action() {
        $indicadores = new Indicadores_Quali_Model();

        $dados["indicadores_quali"] = $indicadores->get();

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quali', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quali_add');
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $indicadores = new Indicadores_Quali_Model();

        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $conceito = $security->antiInjection(filter_input(INPUT_POST, "conceito"));

        print_r($indicadores->adicionar($descricao, $conceito));
    }

    public function edit() {
        $indicadores = new Indicadores_Quali_Model();
        $security = new securityHelper();

        $dados["indicadores_quali"] = $indicadores->getByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quali_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $indicadores = new Indicadores_Quali_Model();

        $id_indicadores = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $conceito = $security->antiInjection(filter_input(INPUT_POST, "conceito"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($indicadores->editar($id_indicadores, $descricao, $conceito, $status));
    }

    public function remover() {
        $security = new securityHelper();
        $indicadores = new Indicadores_Quali_Model();

        $id_indicadores = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($indicadores->remover($id_indicadores));
    }

}
