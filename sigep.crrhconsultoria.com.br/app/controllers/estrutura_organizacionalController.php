<?php

class estrutura_organizacional extends Controller {

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

    public function index_action() {
        $this->verifica_cliente();

        $estrutura_organizacional = new Estrutura_Organizacional_Model();

        $dados["estrutura"] = $estrutura_organizacional->MontaEstrutura();

        $this->view('header');
        $this->view('menu');
        $this->view('content_estr_organizacional', $dados);
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();

        $estrutura_organizacional = new Estrutura_Organizacional_Model();

        $root = $security->antiInjection(filter_input(INPUT_POST, "root", FILTER_SANITIZE_NUMBER_INT));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));
        $ordem = $security->antiInjection(filter_input(INPUT_POST, "ordem", FILTER_SANITIZE_NUMBER_INT));

        print_r($estrutura_organizacional->adicionar($root, $descricao, $ordem));
    }

    public function editar() {
        $security = new securityHelper();

        $estrutura_organizacional = new Estrutura_Organizacional_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));
        $ordem = $security->antiInjection(filter_input(INPUT_POST, "ordem", FILTER_SANITIZE_NUMBER_INT));

        print_r($estrutura_organizacional->editar($id, $descricao, $ordem));
    }

    public function remover() {
        $security = new securityHelper();

        $estrutura_organizacional = new Estrutura_Organizacional_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT));

        print_r($estrutura_organizacional->remover($id));
    }

}
