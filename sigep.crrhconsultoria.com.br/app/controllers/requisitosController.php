<?php

class requisitos extends Controller {

    public function index_action() {
        $requisitos = new Requisitos_Model();

        $dados["requisitos"] = $requisitos->get();

        $this->view('header');
        $this->view('menu');
        $this->view('content_requisitos', $dados);
        $this->view('footer');
    }

    public function add() {

        $this->view('header');
        $this->view('menu');
        $this->view('content_requisitos_add');
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $requisitos = new Requisitos_Model();

        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $tipo = $security->antiInjection(filter_input(INPUT_POST, "tipo"));

        print_r($requisitos->adicionar($descricao, $tipo));
    }

    public function edit() {
        $requisitos = new Requisitos_Model();
        $security = new securityHelper();

        $dados["requisitos"] = $requisitos->getByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_requisitos_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $requisitos = new Requisitos_Model();

        $id_requisitos = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $tipo = $security->antiInjection(filter_input(INPUT_POST, "tipo"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($requisitos->editar($id_requisitos, $descricao, $tipo, $status));
    }

    public function remover() {
        $security = new securityHelper();
        $requisitos = new Requisitos_Model();

        $id_requisitos = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($requisitos->remover($id_requisitos));
    }

}
