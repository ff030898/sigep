<?php

class cargo extends Controller {

    public function index_action() {

        $this->verifica_cliente();

        $cargo = new Cargo_Model();

        $dados["cargo"] = $cargo->getCargo();

        $this->view('header');
        $this->view('menu');
        $this->view('content_cargo', $dados);
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

    public function add() {
        $this->verifica_cliente();

        $cargo = new Cargo_Model();
        $nat_ocupacional = new Nat_Ocupacional_Model();
        $requisitos = new Requisitos_Model();
        $ind_qual = new Indicadores_Quali_Model();
        $estrutura_organizacional = new Estrutura_Organizacional_Model();

        $dados["ascencao"] = $cargo->getCargo(1);
        $dados["nat_ocupacional"] = $nat_ocupacional->getNat_Ocupacional(1);
        $dados["requisitos"] = $requisitos->get(1);
        $dados["ind_qual"] = $ind_qual->get(1);

        $dados["estrutura"] = $estrutura_organizacional->MontaEstrutura2();

        $this->view('header');
        $this->view('menu');
        $this->view('content_cargo_add', $dados);
        $this->view('footer');
    }

    public function adicionar() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $cargo = new Cargo_Model();

        $tipo = $security->antiInjection(filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_NUMBER_INT));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));
        $subtipo = $security->antiInjection(filter_input(INPUT_POST, "subtipo", FILTER_SANITIZE_NUMBER_INT));
        $id_cbo = $security->antiInjection(filter_input(INPUT_POST, "cbo", FILTER_SANITIZE_STRING));
        $id_ascencao = $security->antiInjection(filter_input(INPUT_POST, "ascencao", FILTER_SANITIZE_NUMBER_INT));
        $id_nat_ocupacional = $security->antiInjection(filter_input(INPUT_POST, "nat_ocupacional", FILTER_SANITIZE_NUMBER_INT));
        $sal_min = $security->antiInjection(filter_input(INPUT_POST, "sal_min", FILTER_SANITIZE_STRING));
        $sal_max = $security->antiInjection(filter_input(INPUT_POST, "sal_max", FILTER_SANITIZE_STRING));
        $grau_min = $security->antiInjection(filter_input(INPUT_POST, "grau_min", FILTER_SANITIZE_NUMBER_INT));
        $per_avaliacao = $security->antiInjection(filter_input(INPUT_POST, "per_avaliacao", FILTER_SANITIZE_NUMBER_INT));
        $id_estrutura_organizacional = $security->antiInjection(filter_input(INPUT_POST, "id_estrutura_organizacional", FILTER_SANITIZE_NUMBER_INT));
        $requisitos = filter_input(INPUT_POST, "requisitos", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $habilidades = $security->antiInjection(filter_input(INPUT_POST, "habilidades", FILTER_SANITIZE_STRING));
        $observacoes = $security->antiInjection(filter_input(INPUT_POST, "observacoes", FILTER_SANITIZE_STRING));

        print_r($cargo->adicionar($tipo, $descricao, $subtipo, $id_cbo, $id_ascencao, $id_nat_ocupacional, $sal_min, $sal_max, $grau_min, $per_avaliacao, $id_estrutura_organizacional, $requisitos, $habilidades, $observacoes));
    }

    public function edit() {
        $this->verifica_cliente();

        $security = new securityHelper();

        $cargo = new Cargo_Model();
        $nat_ocupacional = new Nat_Ocupacional_Model();
        $requisitos = new Requisitos_Model();
        $cargo_indi_quali = new Cargo_Indi_Quali_Model();
        $estrutura_organizacional = new Estrutura_Organizacional_Model();

        $id_cargo = $security->antiInjection(filter_input(INPUT_POST, "id"));

        $dados["ascencao"] = $cargo->getCargo(1);
        $dados["nat_ocupacional"] = $nat_ocupacional->getNat_Ocupacional(1);
        $dados["requisitos"] = $requisitos->getByCargo($id_cargo);

        $dados["ind_qual"] = $cargo_indi_quali->get($id_cargo);
        $dados["estrutura"] = $estrutura_organizacional->MontaEstrutura2();

        $dados["cargo"] = $cargo->getCargoByID($id_cargo);



        $this->view('header');
        $this->view('menu');
        $this->view('content_cargo_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $cargo = new Cargo_Model();

        $id_cargo = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $tipo = $security->antiInjection(filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_NUMBER_INT));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));
        $subtipo = $security->antiInjection(filter_input(INPUT_POST, "subtipo", FILTER_SANITIZE_NUMBER_INT));
        $id_cbo = $security->antiInjection(filter_input(INPUT_POST, "cbo", FILTER_SANITIZE_STRING));
        $id_ascencao = $security->antiInjection(filter_input(INPUT_POST, "ascencao", FILTER_SANITIZE_NUMBER_INT));
        $id_nat_ocupacional = $security->antiInjection(filter_input(INPUT_POST, "nat_ocupacional", FILTER_SANITIZE_NUMBER_INT));
        $sal_min = $security->antiInjection(filter_input(INPUT_POST, "sal_min", FILTER_SANITIZE_STRING));
        $sal_max = $security->antiInjection(filter_input(INPUT_POST, "sal_max", FILTER_SANITIZE_STRING));
        $grau_min = $security->antiInjection(filter_input(INPUT_POST, "grau_min", FILTER_SANITIZE_NUMBER_INT));
        $per_avaliacao = $security->antiInjection(filter_input(INPUT_POST, "per_avaliacao", FILTER_SANITIZE_NUMBER_INT));
        $id_estrutura_organizacional = $security->antiInjection(filter_input(INPUT_POST, "id_estrutura_organizacional", FILTER_SANITIZE_NUMBER_INT));
        $requisitos = filter_input(INPUT_POST, "requisitos", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $habilidades = $security->antiInjection(filter_input(INPUT_POST, "habilidades", FILTER_SANITIZE_STRING));
        $observacoes = $security->antiInjection(filter_input(INPUT_POST, "observacoes", FILTER_SANITIZE_STRING));

        print_r($cargo->editar($id_cargo, $tipo, $descricao, $subtipo, $id_cbo, $id_ascencao, $id_nat_ocupacional, $sal_min, $sal_max, $grau_min, $per_avaliacao, $id_estrutura_organizacional, $requisitos, $habilidades, $observacoes));
    }

    public function remover() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $cargo = new Cargo_Model();

        $id_cargo = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($cargo->remover($id_cargo));
    }

    public function search_cargo() {
        $security = new securityHelper();
        $cargo = new Cargo_Model();

        $pesquisa = $security->antiInjection(filter_input(INPUT_POST, "pesquisa"));
        $funcao = $security->antiInjection(filter_input(INPUT_POST, "funcao"));

        print_r($cargo->search_cargo($pesquisa, $funcao));
    }

    public function get_ind_quant() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $ind_quant = new Indicadores_Quant_Model();

        $id_nat_ocupacional = $security->antiInjection(filter_input(INPUT_POST, "id_nat_ocupacional"));

        print_r($ind_quant->getByNatOcupacional($id_nat_ocupacional));
    }

    public function get_ind_quant_nat() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $cargo = new Cargo_Model();
        $ind_quant = new Indicadores_Quant_Model();

        $id_cargo = $security->antiInjection(filter_input(INPUT_POST, "id_cargo"));
        $id_nat_ocupacional = $cargo->getNatByCargo($id_cargo);

        print_r($ind_quant->getByNatOcupacional($id_nat_ocupacional));
    }

}
