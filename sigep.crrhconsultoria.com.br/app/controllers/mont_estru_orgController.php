<?php

class mont_estru_org extends Controller {

    public function index_action() {

        $this->verifica_cliente();

        $mont_estru = new Mont_Estru_Org_Model();

        $dados["mont_estru_org"] = $mont_estru->getMontEstruOrg();

        $this->view('header');
        $this->view('menu');
        $this->view('content_mont_estru_org', $dados);
        $this->view('footer');
    }

    public function add() {

        $this->verifica_cliente();

        $estrutura = new Estrutura_Org_Model();
        $classificacao = new Class_Estrutura_Model();

        $dados["estrutura"] = $estrutura->getEstrutura(1);
        $dados["classificacao"] = $classificacao->getClassEstrutura(1);

        $this->view('header');
        $this->view('menu');
        $this->view('content_mont_estru_org_add', $dados);
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
        $mont_estru = new Mont_Estru_Org_Model();

        $estrutura_org = $security->antiInjection(filter_input(INPUT_POST, "estrutura_org"));
        $class_organizacional = $security->antiInjection(filter_input(INPUT_POST, "class_organizacional"));
        $posicao = $security->antiInjection(filter_input(INPUT_POST, "posicao"));

        print_r($mont_estru->adicionar($estrutura_org, $class_organizacional, $posicao));
    }

    public function edit() {

        $this->verifica_cliente();

        $mont_estru = new Mont_Estru_Org_Model();
        $security = new securityHelper();
        $estrutura = new Estrutura_Org_Model();
        $classificacao = new Class_Estrutura_Model();

        $dados["estrutura"] = $estrutura->getEstrutura(1);
        $dados["classificacao"] = $classificacao->getClassEstrutura(1);
        $dados["mont_estru_org"] = $mont_estru->getMontEstruOrgByID($security->antiInjection(filter_input(INPUT_POST, "id")));
        $this->view('header');
        $this->view('menu');
        $this->view('content_mont_estru_org_edit', $dados);
        $this->view('footer');
    }

    public function editar() {

        $this->verifica_cliente();

        $security = new securityHelper();
        $mont_estru = new Mont_Estru_Org_Model();

        $id_mont_estru = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $estrutura_org = $security->antiInjection(filter_input(INPUT_POST, "estrutura_org"));
        $class_organizacional = $security->antiInjection(filter_input(INPUT_POST, "class_organizacional"));
        $posicao = $security->antiInjection(filter_input(INPUT_POST, "posicao"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($mont_estru->editar($id_mont_estru, $estrutura_org, $class_organizacional, $posicao, $status));
    }

    public function remover() {

        $this->verifica_cliente();

        $security = new securityHelper();
        $mont_estru = new Mont_Estru_Org_Model();

        $id_mont_estru = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($mont_estru->remover($id_mont_estru));
    }

}
