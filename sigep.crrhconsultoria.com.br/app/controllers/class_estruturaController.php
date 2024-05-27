<?php

class class_estrutura extends Controller {

    public function index_action() {

        $this->verifica_cliente();

        $estrutura = new Class_Estrutura_Model();

        $dados["class_estrutura"] = $estrutura->getClassEstrutura();

        $this->view('header');
        $this->view('menu');
        $this->view('content_class_estrutura', $dados);
        $this->view('footer');
    }

    public function add() {

        $this->verifica_cliente();

        $this->view('header');
        $this->view('menu');
        $this->view('content_class_estrutura_add');
        $this->view('footer');
    }

    private function verifica_cliente() {
        if ($_SESSION["id_cliente"] == "0") {

            $dados["data"] = "empresa";

            $this->view('header');
            $this->view('menu');
            $this->view('content_home', $dados);
            $this->view('footer');
            exit();
        }
    }

    public function adicionar() {

        $this->verifica_cliente();

        $security = new securityHelper();
        $estrutura = new Class_Estrutura_Model();

        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));

        print_r($estrutura->adicionar($descricao));
    }

    public function edit() {

        $this->verifica_cliente();

        $estrutura = new Class_Estrutura_Model();
        $security = new securityHelper();

        $dados["class_estrutura"] = $estrutura->getClassEstruturaByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_class_estrutura_edit', $dados);
        $this->view('footer');
    }

    public function editar() {

        $this->verifica_cliente();

        $security = new securityHelper();
        $estrutura = new Class_Estrutura_Model();

        $id_estrutura = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($estrutura->editar($id_estrutura, $descricao, $status));
    }

    public function remover() {

        $this->verifica_cliente();

        $security = new securityHelper();
        $estrutura = new Class_Estrutura_Model();

        $id_estrutura = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($estrutura->remover($id_estrutura));
    }

}
