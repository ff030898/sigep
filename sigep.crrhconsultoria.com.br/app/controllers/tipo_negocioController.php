<?php

class tipo_negocio extends Controller {

    public function index_action() {
        $tipo_negocio = new Tipo_Negocio_Model();

        $dados["tipo_negocio"] = $tipo_negocio->getTipo_Negocio();

        $this->view('header');
        $this->view('menu');
        $this->view('content_tipo_negocio', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_tipo_negocio_add');
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $tipo_negocio = new Tipo_Negocio_Model();

        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));

        print_r($tipo_negocio->adicionar($descricao));
    }

    public function edit() {
        $tipo_negocio = new Tipo_Negocio_Model();
        $security = new securityHelper();

        $dados["tipo_negocio"] = $tipo_negocio->getTipo_NegocioByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_tipo_negocio_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $tipo_negocio = new Tipo_Negocio_Model();

        $id_tipo_negocio = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($tipo_negocio->editar($id_tipo_negocio, $descricao, $status));
    }

    public function remover() {
        $security = new securityHelper();
        $tipo_negocio = new Tipo_Negocio_Model();

        $id_tipo_negocio = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($tipo_negocio->remover($id_tipo_negocio));
    }

}
