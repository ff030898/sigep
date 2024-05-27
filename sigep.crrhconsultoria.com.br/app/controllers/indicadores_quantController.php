<?php

class indicadores_quant extends Controller {

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
        $indicadores = new Indicadores_Quant_Model();

        $dados["indicadores_quant"] = $indicadores->get();

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quant', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->verifica_cliente();
        $indicador_tipo = new Indicadores_Quant_Tipo_Model();
        $nat_ocupacional = new Nat_Ocupacional_Model();

        $dados["indicador_tipo"] = $indicador_tipo->get(1);
        $dados["nat_ocupacional"] = $nat_ocupacional->getNat_Ocupacional(1);

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quant_add', $dados);
        $this->view('footer');
    }

    public function adicionar() {
        $this->verifica_cliente();
        $security = new securityHelper();
        $indicadores = new Indicadores_Quant_Model();

        $id_tipo_indicador = $security->antiInjection(filter_input(INPUT_POST, "tipo_indicador"));
        $id_nat_ocupacional = $security->antiInjection(filter_input(INPUT_POST, "nat_ocupacional"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));

        print_r($indicadores->adicionar($id_tipo_indicador, $id_nat_ocupacional, $descricao));
    }

    public function edit() {
        $this->verifica_cliente();
        $indicadores = new Indicadores_Quant_Model();
        $security = new securityHelper();
        $indicador_tipo = new Indicadores_Quant_Tipo_Model();
        $nat_ocupacional = new Nat_Ocupacional_Model();

        $dados["indicador_tipo"] = $indicador_tipo->get(1);
        $dados["nat_ocupacional"] = $nat_ocupacional->getNat_Ocupacional(1);

        $dados["indicadores_quant"] = $indicadores->getByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quant_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $this->verifica_cliente();
        $security = new securityHelper();
        $indicadores = new Indicadores_Quant_Model();

        $id_indicadores = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $id_tipo_indicador = $security->antiInjection(filter_input(INPUT_POST, "tipo_indicador"));
        $id_nat_ocupacional = $security->antiInjection(filter_input(INPUT_POST, "nat_ocupacional"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($indicadores->editar($id_indicadores, $id_tipo_indicador, $id_nat_ocupacional, $descricao, $status));
    }

    public function remover() {
        $this->verifica_cliente();
        $security = new securityHelper();
        $indicadores = new Indicadores_Quant_Model();

        $id_indicadores = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($indicadores->remover($id_indicadores));
    }

}
