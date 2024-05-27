<?php

class escolaridade extends Controller {

    public function index_action() {
        $escolaridade = new Escolaridade_Model();

        $dados["escolaridade"] = $escolaridade->getEscolaridade();

        $this->view('header');
        $this->view('menu');
        $this->view('content_escolaridade', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_escolaridade_add');
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $escolaridade = new Escolaridade_Model();

        $code_esocial = $security->antiInjection(filter_input(INPUT_POST, "code_esocial"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));

        print_r($escolaridade->adicionar($code_esocial, $descricao));
    }

    public function edit() {
        $escolaridade = new Escolaridade_Model();
        $security = new securityHelper();

        $dados["escolaridade"] = $escolaridade->getEscolaridadeByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_escolaridade_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $escolaridade = new Escolaridade_Model();

        $id_escolaridade = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $code_esocial = $security->antiInjection(filter_input(INPUT_POST, "code_esocial"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($escolaridade->editar($id_escolaridade, $code_esocial, $descricao, $status));
    }

    public function remover() {
        $security = new securityHelper();
        $escolaridade = new Escolaridade_Model();

        $id_escolaridade = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($escolaridade->remover($id_escolaridade));
    }

}
