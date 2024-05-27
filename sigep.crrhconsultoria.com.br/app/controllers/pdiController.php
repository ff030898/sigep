<?php

class pdi extends Controller {

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

        $dados["funcionarios"] = $avaliacao->getAvaliacoesPendentes();

        $this->view('header');
        $this->view('menu');
        $this->view('content_pdi_lista', $dados);
        $this->view('footer');
    }

    public function plano_acao() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao = new Avaliacao_Model();

            $id_avaliacao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao", FILTER_SANITIZE_NUMBER_INT));

            $dados["pdi"] = $avaliacao->monta_pdi($id_avaliacao);

            $this->view('header');
            $this->view('menu');
            $this->view('content_pdi_plano_acao', $dados);
            $this->view('footer');
        } else {
            $this->index_action();
        }
    }

    public function adicionar_plano_acao() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao_plano_acao = new Avaliacao_Quantitativas_Plano_Acao_Model();

            $id_avaliacao_indicadores_quantitativa = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao_quantitativa", FILTER_SANITIZE_NUMBER_INT));
            $plano_acao = $security->antiInjection(filter_input(INPUT_POST, "plano_acao", FILTER_SANITIZE_STRING));
            $evidencia = $security->antiInjection(filter_input(INPUT_POST, "evidencia", FILTER_SANITIZE_STRING));
            $gut = $security->antiInjection(filter_input(INPUT_POST, "gut", FILTER_SANITIZE_STRING));
            $participantes = $security->antiInjection(filter_input(INPUT_POST, "participantes", FILTER_SANITIZE_STRING));


            print_r($avaliacao_plano_acao->adicionar($id_avaliacao_indicadores_quantitativa, $plano_acao, $evidencia, $gut, $participantes));
        } else {
            $this->index_action();
        }
    }

    public function editar_plano_acao() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao_plano_acao = new Avaliacao_Quantitativas_Plano_Acao_Model();

            $id_avaliacao_indicadores_quantitativa = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao_quantitativa", FILTER_SANITIZE_NUMBER_INT));
            $id_plano_acao = $security->antiInjection(filter_input(INPUT_POST, "id_plano_acao", FILTER_SANITIZE_NUMBER_INT));
            $plano_acao = $security->antiInjection(filter_input(INPUT_POST, "plano_acao", FILTER_SANITIZE_STRING));
            $evidencia = $security->antiInjection(filter_input(INPUT_POST, "evidencia", FILTER_SANITIZE_STRING));
            $gut = $security->antiInjection(filter_input(INPUT_POST, "gut", FILTER_SANITIZE_STRING));
            $participantes = $security->antiInjection(filter_input(INPUT_POST, "participantes", FILTER_SANITIZE_STRING));

            print_r($avaliacao_plano_acao->editar($id_avaliacao_indicadores_quantitativa, $id_plano_acao, $plano_acao, $evidencia, $gut, $participantes));
        } else {
            $this->index_action();
        }
    }

    public function remover_plano_acao() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao_plano_acao = new Avaliacao_Quantitativas_Plano_Acao_Model();

            $id_avaliacao_plano_acao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao_plano_acao", FILTER_SANITIZE_NUMBER_INT));

            print_r($avaliacao_plano_acao->remover($id_avaliacao_plano_acao));
        } else {
            $this->index_action();
        }
    }

    public function adicionar_plano_acao_habilidade() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao_plano_acao = new Avaliacao_Qualitativas_Plano_Acao_Model();

            $id_avaliacao_indicadores_qualitativas = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao_qualitativa", FILTER_SANITIZE_NUMBER_INT));
            $plano_acao = $security->antiInjection(filter_input(INPUT_POST, "plano_acao", FILTER_SANITIZE_STRING));
            $evidencia = $security->antiInjection(filter_input(INPUT_POST, "evidencia", FILTER_SANITIZE_STRING));
            $gut = $security->antiInjection(filter_input(INPUT_POST, "gut", FILTER_SANITIZE_STRING));
            $participantes = $security->antiInjection(filter_input(INPUT_POST, "participantes", FILTER_SANITIZE_STRING));


            print_r($avaliacao_plano_acao->adicionar($id_avaliacao_indicadores_qualitativas, $plano_acao, $evidencia, $gut, $participantes));
        } else {
            $this->index_action();
        }
    }

    public function editar_plano_acao_habilidade() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao_plano_acao = new Avaliacao_Qualitativas_Plano_Acao_Model();

            $id_avaliacao_indicadores_qualitativas = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao_qualitativa", FILTER_SANITIZE_NUMBER_INT));
            $id_plano_acao = $security->antiInjection(filter_input(INPUT_POST, "id_plano_acao", FILTER_SANITIZE_NUMBER_INT));
            $plano_acao = $security->antiInjection(filter_input(INPUT_POST, "plano_acao", FILTER_SANITIZE_STRING));
            $evidencia = $security->antiInjection(filter_input(INPUT_POST, "evidencia", FILTER_SANITIZE_STRING));
            $gut = $security->antiInjection(filter_input(INPUT_POST, "gut", FILTER_SANITIZE_STRING));
            $participantes = $security->antiInjection(filter_input(INPUT_POST, "participantes", FILTER_SANITIZE_STRING));


            print_r($avaliacao_plano_acao->editar($id_avaliacao_indicadores_qualitativas, $id_plano_acao, $plano_acao, $evidencia, $gut, $participantes));
        } else {
            $this->index_action();
        }
    }

    public function remover_plano_acao_habilidade() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao_plano_acao = new Avaliacao_Qualitativas_Plano_Acao_Model();

            $id_avaliacao_plano_acao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao_plano_acao", FILTER_SANITIZE_NUMBER_INT));

            print_r($avaliacao_plano_acao->remover($id_avaliacao_plano_acao));
        } else {
            $this->index_action();
        }
    }

    public function finalizar() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliaca = new Avaliacao_Model();

            $id_avaliacao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao", FILTER_SANITIZE_NUMBER_INT));

            print_r($avaliaca->finalizar($id_avaliacao));
        } else {
            $this->index_action();
        }
    }

    public function emitir() {
        $avaliacao = new Avaliacao_Model();

        $dados["funcionarios"] = $avaliacao->getAvaliacoes(3);

        $this->view('header');
        $this->view('menu');
        $this->view('content_pdi_emitir', $dados);
        $this->view('footer');
    }

    public function concluir_lista() {
        $avaliacao = new Avaliacao_Model();

        $dados["funcionarios"] = $avaliacao->getAvaliacoes(1);

        $this->view('header');
        $this->view('menu');
        $this->view('content_pdi_concluir_lista', $dados);
        $this->view('footer');
    }

    public function abre_plano() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao = new Avaliacao_Model();

            $id_avaliacao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao", FILTER_SANITIZE_NUMBER_INT));

            $dados["pdi"] = $avaliacao->monta_pdi($id_avaliacao);

            $this->view('header');
            $this->view('menu');
            $this->view('content_pdi_plano_acao_validacao', $dados);
            $this->view('footer');
        } else {
            $this->index_action();
        }
    }

    public function concluir() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliaca = new Avaliacao_Model();

            $id_avaliacao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao", FILTER_SANITIZE_NUMBER_INT));

            print_r($avaliaca->concluir($id_avaliacao));
        } else {
            $this->index_action();
        }
    }

    public function emitir_pdi() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliaca = new Avaliacao_Model();

            $id_avaliacao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao", FILTER_SANITIZE_NUMBER_INT));

            $avaliaca->emitir_pdi($id_avaliacao);
        } else {
            $this->index_action();
        }
    }

    public function busca_plano_acao() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $plano_acao = new Avaliacao_Quantitativas_Plano_Acao_Model();

            $id_avaliaca_plano_acao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao_plano_acao", FILTER_SANITIZE_NUMBER_INT));

            print_r($plano_acao->getByIdJson($id_avaliaca_plano_acao));
        } else {
            $this->index_action();
        }
    }

    public function busca_plano_acao_habilidades() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $plano_acao = new Avaliacao_Qualitativas_Plano_Acao_Model();

            $id_avaliaca_plano_acao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao_plano_acao", FILTER_SANITIZE_NUMBER_INT));

            print_r($plano_acao->getByIdJson($id_avaliaca_plano_acao));
        } else {
            $this->index_action();
        }
    }

    public function atualizar_plano_acao() {
        $avaliacao = new Avaliacao_Model();

        $dados["funcionarios"] = $avaliacao->getAvaliacoes(3);

        $this->view('header');
        $this->view('menu');
        $this->view('content_plano_acao_lista', $dados);
        $this->view('footer');
    }

    public function atualizar_plano_lista() {
        $avaliacao = new Avaliacao_Model();

        $dados["funcionarios"] = $avaliacao->getAvaliacoes(3);

        $this->view('header');
        $this->view('menu');
        $this->view('content_atualizar_plano_lista', $dados);
        $this->view('footer');
    }

    public function atualizar_pdi() {
        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao = new Avaliacao_Model();

            $id_avaliacao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao", FILTER_SANITIZE_NUMBER_INT));

            $dados["pdi"] = $avaliacao->monta_plano_acao_atualizar($id_avaliacao);

            $this->view('header');
            $this->view('menu');
            $this->view('content_atualizar_plano', $dados);
            $this->view('footer');
        } else {
            $this->index_action();
        }
    }

    public function atualizar_status_plano_acao() {

        if (getenv('REQUEST_METHOD') === 'POST') {
            $security = new securityHelper();
            $avaliacao = new Avaliacao_Model();

            $id_avaliacao = $security->antiInjection(filter_input(INPUT_POST, "id_avaliacao", FILTER_SANITIZE_NUMBER_INT));
            $indicadores_quantitativos = filter_input(INPUT_POST, "indicadores_quantitativos", FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
            $indicadore_qualitativos = filter_input(INPUT_POST, "indicadores_qualitativos", FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);

            print_r($avaliacao->atualizar_status_plano_acao($id_avaliacao, $indicadores_quantitativos, $indicadore_qualitativos));
        } else {
            $this->index_action();
        }
    }

}
