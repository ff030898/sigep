<?php

class indicadores_quali_graduacao extends Controller {

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
        $indicadores = new Indicadores_Quali_Graduacao_Model();

        $dados["indicadores_quali_graduacao"] = $indicadores->get();

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quali_graduacao', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->verifica_cliente();
        $indicadores = new Indicadores_Quali_Model();

        $dados["indicadores"] = $indicadores->get(1);

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quali_graduacao_add', $dados);
        $this->view('footer');
    }

    public function adicionar() {
        $this->verifica_cliente();
        $security = new securityHelper();
        $indicadores = new Indicadores_Quali_Graduacao_Model();

        $id_ind_qualitativo = $security->antiInjection(filter_input(INPUT_POST, "indicador_qualitativo"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $descricao_resumida = $security->antiInjection(filter_input(INPUT_POST, "descricao_resumida"));
        $ordem_horizontal = $security->antiInjection(filter_input(INPUT_POST, "ordem_horizontal"));


        print_r($indicadores->adicionar($id_ind_qualitativo, $descricao, $descricao_resumida, $ordem_horizontal));
    }

    public function edit() {
        $this->verifica_cliente();
        $indicadores = new Indicadores_Quali_Graduacao_Model();
        $indicadores_quali = new Indicadores_Quali_Model();
        $security = new securityHelper();

        $dados["indicadores"] = $indicadores_quali->get(1);
        $dados["indicadores_quali_graduacao"] = $indicadores->getByID($security->antiInjection(filter_input(INPUT_POST, "id")));


        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quali_graduacao_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $this->verifica_cliente();
        $security = new securityHelper();
        $indicadores = new Indicadores_Quali_Graduacao_Model();

        $id_indicadores = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $id_ind_qualitativo = $security->antiInjection(filter_input(INPUT_POST, "indicador_qualitativo"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $descricao_resumida = $security->antiInjection(filter_input(INPUT_POST, "descricao_resumida"));
        $ordem_horizontal = $security->antiInjection(filter_input(INPUT_POST, "ordem_horizontal"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($indicadores->editar($id_indicadores, $id_ind_qualitativo, $descricao, $descricao_resumida, $ordem_horizontal, $status));
    }

    public function remover() {
        $this->verifica_cliente();
        $security = new securityHelper();
        $indicadores = new Indicadores_Quali_Graduacao_Model();

        $id_indicadores = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($indicadores->remover($id_indicadores));
    }

}
