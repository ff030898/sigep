<?php

class perfil_cargo extends Controller {

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

        $perfil_cargo = new Perfil_Cargo_Model();

        $dados["perfil_cargo"] = $perfil_cargo->get();

        $this->view('header');
        $this->view('menu');
        $this->view('content_perfil_cargo', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->verifica_cliente();

        $cargo = new Cargo_Model();
        $requisitos = new Requisitos_Model();
        $indicadores_quant_tipo = new Indicadores_Quant_Tipo_Model();

        $dados["cargo"] = $cargo->getCargo("perfil");
        $dados["fonte_interna"] = $cargo->getCargo(1);
        $dados["requisitos"] = $requisitos->get(1);

        $dados["indicadores"] = $indicadores_quant_tipo->get(1);
        $dados["sub"] = $indicadores_quant_tipo->monta_indicadores();

        $this->view('header');
        $this->view('menu');
        $this->view('content_perfil_cargo_add', $dados);
        $this->view('footer');
    }

    public function adicionar() {

        $this->verifica_cliente();

        $security = new securityHelper();
        $peril_cargo = new Perfil_Cargo_Model();

        $id_cargo = $security->antiInjection(filter_input(INPUT_POST, "id_cargo"));
        $elaboracao = $security->antiInjection(filter_input(INPUT_POST, "elaboracao"));
        $aprovacao = $security->antiInjection(filter_input(INPUT_POST, "aprovacao"));
        $qualificacao_basica = $security->antiInjection(filter_input(INPUT_POST, "qualificacao_basica"));
        $sumario = $security->antiInjection(filter_input(INPUT_POST, "sumario"));
        $cargo_interno = filter_input(INPUT_POST, "cargo_interno", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $tempo = filter_input(INPUT_POST, "tempo", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $tipo = filter_input(INPUT_POST, "tipo", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $conceito = $security->antiInjection(filter_input(INPUT_POST, "conceito"));
        $advertencia = $security->antiInjection(filter_input(INPUT_POST, "advertencia"));
        $fonte_externa = $security->antiInjection(filter_input(INPUT_POST, "fonte_externa"));
        $indicador = filter_input(INPUT_POST, "indicador", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $sub = filter_input(INPUT_POST, "sub", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $valor = filter_input(INPUT_POST, "valor", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        print_r($peril_cargo->adicionar($id_cargo, $elaboracao, $aprovacao, $qualificacao_basica, $sumario, $cargo_interno, $tempo, $tipo, $conceito, $advertencia, $fonte_externa, $indicador, $sub, $valor));
    }

    public function editar() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $peril_cargo = new Perfil_Cargo_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $id_cargo = $security->antiInjection(filter_input(INPUT_POST, "id_cargo"));
        $elaboracao = $security->antiInjection(filter_input(INPUT_POST, "elaboracao"));
        $aprovacao = $security->antiInjection(filter_input(INPUT_POST, "aprovacao"));
        $qualificacao_basica = $security->antiInjection(filter_input(INPUT_POST, "qualificacao_basica"));
        $sumario = $security->antiInjection(filter_input(INPUT_POST, "sumario"));
        $cargo_interno = filter_input(INPUT_POST, "cargo_interno", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $tempo = filter_input(INPUT_POST, "tempo", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $tipo = filter_input(INPUT_POST, "tipo", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $conceito = $security->antiInjection(filter_input(INPUT_POST, "conceito"));
        $advertencia = $security->antiInjection(filter_input(INPUT_POST, "advertencia"));
        $fonte_externa = $security->antiInjection(filter_input(INPUT_POST, "fonte_externa"));
        $indicador = filter_input(INPUT_POST, "indicador", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $sub = filter_input(INPUT_POST, "sub", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $valor = filter_input(INPUT_POST, "valor", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        print_r($peril_cargo->editar($id, $id_cargo, $elaboracao, $aprovacao, $qualificacao_basica, $sumario, $cargo_interno, $tempo, $tipo, $conceito, $advertencia, $fonte_externa, $indicador, $sub, $valor));
    }

    public function edit() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $peril_cargo = new Perfil_Cargo_Model();
        $indicadores_quant_tipo = new Indicadores_Quant_Tipo_Model();
        $cargo = new Cargo_Model();

        $id_perfil = $security->antiInjection(filter_input(INPUT_POST, "id"));

        $dados["cargo"] = $cargo->getCargo(1);
        $dados["perfil"] = $peril_cargo->getById($id_perfil);

        $dados["indicadores"] = $indicadores_quant_tipo->get(1);
        $dados["sub"] = $indicadores_quant_tipo->monta_indicadores();

        $this->view('header');
        $this->view('menu');
        $this->view('content_perfil_cargo_edit', $dados);
        $this->view('footer');
    }

    public function remover() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $perfil_cargo = new Perfil_Cargo_Model();

        $id_perfil = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($perfil_cargo->remover($id_perfil));
    }

    public function rel() {
        $this->verifica_cliente();

        $peril_cargo = new Perfil_Cargo_Model();
        $dados["perfil"] = $peril_cargo->get();

        $this->view('header');
        $this->view('menu');
        $this->view('content_perfil_cargo_rel', $dados);
        $this->view('footer');
    }

    public function gerar_relatorio() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $peril_cargo = new Perfil_Cargo_Model();

        $id_perfil = $security->antiInjection(filter_input(INPUT_POST, "id_perfil"));
        $saida = $security->antiInjection(filter_input(INPUT_POST, "saida"));

        if ($saida == 0) {
            $peril_cargo->rel_pdf($id_perfil);
        } else if ($saida == 1) {
            print_r($peril_cargo->rel_tela($id_perfil));
        } else if ($saida == 2) {
            $peril_cargo->rel_excel($id_perfil);
        }
    }

}
