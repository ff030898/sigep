<?php

class avaliacao extends Controller {

    public function __construct() {
        parent::__construct();
        $this->verifica_cliente();
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

    public function index_action() {

        $avaliacao = new Avaliacao_Model();

        $dados["funcionarios"] = $avaliacao->getFuncionarioAvaliacao();

        $this->view('header');
        $this->view('menu');
        $this->view('content_avaliacao', $dados);
        $this->view('footer');
    }

    public function avaliar() {
        $security = new securityHelper();
        $avaliacao = new Avaliacao_Model();

        $id_funcionario = $security->antiInjection(filter_input(INPUT_POST, "id_funcionario", FILTER_SANITIZE_NUMBER_INT));

        $dados["funcionario"] = $avaliacao->monta_avaliacao($id_funcionario);

        $this->view('header');
        $this->view('menu');
        $this->view('content_avaliacao_avaliar', $dados);
        $this->view('footer');
    }

    public function adicionar() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao = new Avaliacao_Model();

            $id_funcionario = $security->antiInjection(filter_input(INPUT_POST, "id_funcionario", FILTER_SANITIZE_NUMBER_INT));
            $id_pessoa = $security->antiInjection(filter_input(INPUT_POST, "id_pessoa", FILTER_SANITIZE_NUMBER_INT));
            $id_cargo = $security->antiInjection(filter_input(INPUT_POST, "id_cargo", FILTER_SANITIZE_NUMBER_INT));
            $id_perfil_cargo = $security->antiInjection(filter_input(INPUT_POST, "id_perfil_cargo", FILTER_SANITIZE_NUMBER_INT));
            $indicadores_quantitativos = filter_input(INPUT_POST, "indicadores_quantitativos", FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            $indicadore_qualitativos = filter_input(INPUT_POST, "indicadores_qualitativos", FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);

            print_r($avaliacao->adicionar($id_funcionario, $id_pessoa, $id_cargo, $id_perfil_cargo, $indicadores_quantitativos, $indicadore_qualitativos));
        } else {
            $this->index_action();
        }
    }

    public function dica() {
        $avaliacao = new Avaliacao_Model();

        $dados["funcionarios"] = $avaliacao->getFuncionariosDica();

        $this->view('header');
        $this->view('menu');
        $this->view('content_dica_funcionarios', $dados);
        $this->view('footer');
    }

    public function dica_funcionario() {
        $avaliacao = new Avaliacao_Model();
        $funcionarios = new Funcionarios_Model();
        $security = new securityHelper();
        $gdp = new Gdp_Model();


        $id_funcionario = $security->antiInjection(filter_input(INPUT_POST, "id_funcionario", FILTER_SANITIZE_NUMBER_INT));

        $dados["dica"] = $avaliacao->getDica($id_funcionario);
        $dados["funcionario"] = $funcionarios->getDadosFuncionárioById($id_funcionario);
        $dados["gdp"] = $gdp->getGdp();


        $this->view('header');
        $this->view('menu');
        $this->view('content_dica_relatorio', $dados);
        $this->view('footer');
    }

    public function comparativos_gdp() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_comparativos_gdp');
        $this->view('footer');
    }

    public function gerarComparativo() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao = new Avaliacao_Model();

            $dataIni = $security->antiInjection(filter_input(INPUT_POST, "dataIni", FILTER_SANITIZE_STRING));
            $dataFim = $security->antiInjection(filter_input(INPUT_POST, "dataFim", FILTER_SANITIZE_STRING));

            print_r($avaliacao->gerarComparativoGDP($dataIni, $dataFim));
        } else {
            $this->index_action();
        }
    }

}
