<?php

class indicadores_quant_tipo extends Controller {

    public function index_action() {
        $indicadores = new Indicadores_Quant_Tipo_Model();

        $dados["indicadores_quant"] = $indicadores->get();

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quant_tipo', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quant_tipo_add');
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $indicadores = new Indicadores_Quant_Tipo_Model();

        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));

        print_r($indicadores->adicionar($descricao));
    }

    public function edit() {
        $indicadores = new Indicadores_Quant_Tipo_Model();
        $security = new securityHelper();

        $dados["indicadores_quant"] = $indicadores->getByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quant_tipo_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $indicadores = new Indicadores_Quant_Tipo_Model();

        $id_indicadores = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($indicadores->editar($id_indicadores, $descricao, $status));
    }

    public function remover() {
        $security = new securityHelper();
        $indicadores = new Indicadores_Quant_Tipo_Model();

        $id_indicadores = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($indicadores->remover($id_indicadores));
    }

    public function sub() {
        $indicadores = new Indicadores_Quant_Tipo_Model();
        $indicadores_sub = new Indicadores_Quant_Sub_Model();
        $security = new securityHelper();

        $id_indicador = $security->antiInjection(filter_input(INPUT_POST, "id"));

        $dados["indicadores_quant"] = $indicadores->getByID($id_indicador);
        $dados["indicadores_sub"] = $indicadores_sub->get($id_indicador);

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quant_tipo_sub', $dados);
        $this->view('footer');
    }

    public function sub_add() {

        $indicadores = new Indicadores_Quant_Tipo_Model();
        $security = new securityHelper();

        $id_indicador = $security->antiInjection(filter_input(INPUT_POST, "id"));

        if ($id_indicador >= 1) {

            $dados["indicadores_quant"] = $indicadores->getByID($id_indicador);

            $this->view('header');
            $this->view('menu');
            $this->view('content_indicadores_quant_tipo_sub_add', $dados);
            $this->view('footer');
        } else {
            $this->index_action();
        }
    }

    public function sub_adicionar() {
        $security = new securityHelper();

        $id_indicador = $security->antiInjection(filter_input(INPUT_POST, "id"));

        if (filter_var($id_indicador, FILTER_VALIDATE_INT)) {
            $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));

            $indicador_sub = new Indicadores_Quant_Sub_Model();

            print_r($indicador_sub->adicionar($id_indicador, $descricao));
        } else {
            $this->index_action();
        }
    }

    public function sub_edit() {

        $indicadores = new Indicadores_Quant_Tipo_Model();
        $security = new securityHelper();

        $id_indicador = $security->antiInjection(filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT));
        $id_sub = $security->antiInjection(filter_input(INPUT_POST, "id_sub", FILTER_SANITIZE_NUMBER_INT));

        if ($id_indicador >= 1) {

            $indicador_sub = new Indicadores_Quant_Sub_Model();

            $dados["indicadores_quant"] = $indicadores->getByID($id_indicador);
            $dados["sub"] = $indicador_sub->getById($id_sub);

            $this->view('header');
            $this->view('menu');
            $this->view('content_indicadores_quant_tipo_sub_edit', $dados);
            $this->view('footer');
        } else {
            $this->index_action();
        }
    }

    public function sub_editar() {
        $security = new securityHelper();

        $id_indicador = $security->antiInjection(filter_input(INPUT_POST, "id"));

        if (filter_var($id_indicador, FILTER_VALIDATE_INT)) {
            $id_sub = $security->antiInjection(filter_input(INPUT_POST, "id_sub"));
            $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));
            $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

            $indicador_sub = new Indicadores_Quant_Sub_Model();

            print_r($indicador_sub->editar($id_indicador, $id_sub, $descricao, $status));
        } else {
            $this->index_action();
        }
    }

    public function remover_sub() {
        $security = new securityHelper();
        $indicador_sub = new Indicadores_Quant_Sub_Model();
        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($indicador_sub->remover($id));
    }

    public function negacao() {
        $indicadores = new Indicadores_Quant_Tipo_Model();
        $indicadores_sub = new Indicadores_Quant_Sub_Model();
        $indicadores_negacao = new Indicadores_Quant_Sub_Negacao_Model();
        $security = new securityHelper();

        $id_indicador = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $id_indicador_sub = $security->antiInjection(filter_input(INPUT_POST, "id_sub"));

        $dados["indicadores_quant"] = $indicadores->getByID($id_indicador);
        $dados["indicadores_sub"] = $indicadores_sub->getById($id_indicador_sub);
        $dados["indicadores_negacao"] = $indicadores_negacao->get($id_indicador_sub);

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quant_tipo_negacao', $dados);
        $this->view('footer');
    }

    public function negacao_add() {

        $indicadores = new Indicadores_Quant_Tipo_Model();
        $indicadores_sub = new Indicadores_Quant_Sub_Model();
        $security = new securityHelper();

        $id_indicador = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $id_indicador_sub = $security->antiInjection(filter_input(INPUT_POST, "id_sub"));

        if ($id_indicador >= 1) {

            $dados["indicadores_quant"] = $indicadores->getByID($id_indicador);
            $dados["indicadores_sub"] = $indicadores_sub->getById($id_indicador_sub);

            $this->view('header');
            $this->view('menu');
            $this->view('content_indicadores_quant_tipo_negacao_add', $dados);
            $this->view('footer');
        } else {
            $this->index_action();
        }
    }

    public function negacao_adicionar() {
        $security = new securityHelper();

        $id_indicador = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $id_sub = $security->antiInjection(filter_input(INPUT_POST, "id_sub"));

        if (filter_var($id_indicador, FILTER_VALIDATE_INT)) {
            $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));

            $indicador_sub = new Indicadores_Quant_Sub_Model();
            $indicador_negacao = new Indicadores_Quant_Sub_Negacao_Model();

            print_r($indicador_negacao->adicionar($id_sub, $descricao));
        } else {
            $this->index_action();
        }
    }

    public function negacao_edit() {

        $indicadores = new Indicadores_Quant_Tipo_Model();
        $security = new securityHelper();

        $id_indicador = $security->antiInjection(filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT));
        $id_sub = $security->antiInjection(filter_input(INPUT_POST, "id_sub", FILTER_SANITIZE_NUMBER_INT));
        $id_negacao = $security->antiInjection(filter_input(INPUT_POST, "id_negacao", FILTER_SANITIZE_NUMBER_INT));

        if ($id_indicador >= 1) {

            $indicadores = new Indicadores_Quant_Tipo_Model();
            $indicadores_sub = new Indicadores_Quant_Sub_Model();
            $indicador_negacao = new Indicadores_Quant_Sub_Negacao_Model();

            $dados["indicadores_quant"] = $indicadores->getByID($id_indicador);
            $dados["indicadores_sub"] = $indicadores_sub->getById($id_sub);
            $dados["indicadores_negacao"] = $indicador_negacao->getById($id_negacao);

            $this->view('header');
            $this->view('menu');
            $this->view('content_indicadores_quant_tipo_negacao_edit', $dados);
            $this->view('footer');
        } else {
            $this->index_action();
        }
    }

    public function negacao_editar() {
        $security = new securityHelper();

        $id_indicador = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $id_sub = $security->antiInjection(filter_input(INPUT_POST, "id_sub"));
        $id_negacao = $security->antiInjection(filter_input(INPUT_POST, "id_negacao"));

        if (filter_var($id_indicador, FILTER_VALIDATE_INT)) {
            $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));

            $indicador_negacao = new Indicadores_Quant_Sub_Negacao_Model();

            print_r($indicador_negacao->editar($id_negacao, $id_sub, $descricao));
        } else {
            $this->index_action();
        }
    }

    public function negacao_remover() {
        $security = new securityHelper();
        $indicadores_negacao = new Indicadores_Quant_Sub_Negacao_Model();
        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($indicadores_negacao->remover($id));
    }

}
