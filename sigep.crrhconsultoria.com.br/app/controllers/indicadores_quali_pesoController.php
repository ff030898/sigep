<?php

class indicadores_quali_peso extends Controller {

    public function index_action() {
        $indicadores = new Indicadores_Quali_Peso_Model();

        $dados["indicadores_quali_peso"] = $indicadores->get();

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quali_peso', $dados);
        $this->view('footer');
    }

    public function add() {
        $indicadores = new Indicadores_Quali_Model();

        $dados["indicadores"] = $indicadores->get(1);

        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quali_peso_add', $dados);
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $indicadores = new Indicadores_Quali_Peso_Model();

        $id_ind_qualitativo = $security->antiInjection(filter_input(INPUT_POST, "indicador_qualitativo"));
        $peso1 = $security->antiInjection(filter_input(INPUT_POST, "peso1"));
        $inter1 = $security->antiInjection(filter_input(INPUT_POST, "inter1"));
        $peso2 = $security->antiInjection(filter_input(INPUT_POST, "peso2"));
        $inter2 = $security->antiInjection(filter_input(INPUT_POST, "inter2"));
        $peso3 = $security->antiInjection(filter_input(INPUT_POST, "peso3"));
        $inter3 = $security->antiInjection(filter_input(INPUT_POST, "inter3"));
        $peso4 = $security->antiInjection(filter_input(INPUT_POST, "peso4"));

        print_r($indicadores->adicionar($id_ind_qualitativo, $peso1, $inter1, $peso2, $inter2, $peso3, $inter3, $peso4));
    }

    public function edit() {
        $indicadores = new Indicadores_Quali_Peso_Model();
        $indicadores_quali = new Indicadores_Quali_Model();
        $security = new securityHelper();

        $dados["indicadores"] = $indicadores_quali->get(1);
        $dados["indicadores_quali_peso"] = $indicadores->getByID($security->antiInjection(filter_input(INPUT_POST, "id")));


        $this->view('header');
        $this->view('menu');
        $this->view('content_indicadores_quali_peso_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $indicadores = new Indicadores_Quali_Peso_Model();

        $id_indicadores = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $id_ind_qualitativo = $security->antiInjection(filter_input(INPUT_POST, "indicador_qualitativo"));
        $peso1 = $security->antiInjection(filter_input(INPUT_POST, "peso1"));
        $inter1 = $security->antiInjection(filter_input(INPUT_POST, "inter1"));
        $peso2 = $security->antiInjection(filter_input(INPUT_POST, "peso2"));
        $inter2 = $security->antiInjection(filter_input(INPUT_POST, "inter2"));
        $peso3 = $security->antiInjection(filter_input(INPUT_POST, "peso3"));
        $inter3 = $security->antiInjection(filter_input(INPUT_POST, "inter3"));
        $peso4 = $security->antiInjection(filter_input(INPUT_POST, "peso4"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($indicadores->editar($id_indicadores, $id_ind_qualitativo, $peso1, $inter1, $peso2, $inter2, $peso3, $inter3, $peso4, $status));
    }

    public function remover() {
        $security = new securityHelper();
        $indicadores = new Indicadores_Quali_Peso_Model();

        $id_indicadores = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($indicadores->remover($id_indicadores));
    }

}
