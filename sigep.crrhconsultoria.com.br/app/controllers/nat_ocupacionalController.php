<?php

class nat_ocupacional extends Controller {

    public function index_action() {
        $nat_ocupacional = new Nat_Ocupacional_Model();

        $dados["nat_ocupacional"] = $nat_ocupacional->getNat_Ocupacional();

        $this->view('header');
        $this->view('menu');
        $this->view('content_nat_ocupacional', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_nat_ocupacional_add');
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $nat_ocupacional = new Nat_Ocupacional_Model();

        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));

        print_r($nat_ocupacional->adicionar($descricao));
    }

    public function edit() {
        $nat_ocupacional = new Nat_Ocupacional_Model();
        $security = new securityHelper();

        $dados["nat_ocupacional"] = $nat_ocupacional->getNat_OcupacionalByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_nat_ocupacional_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $nat_ocupacional = new Nat_Ocupacional_Model();

        $id_nat_ocupacional = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($nat_ocupacional->editar($id_nat_ocupacional, $descricao, $status));
    }

    public function remover() {
        $security = new securityHelper();
        $nat_ocupacional = new Nat_Ocupacional_Model();

        $id_nat_ocupacional = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($nat_ocupacional->remover($id_nat_ocupacional));
    }

}
