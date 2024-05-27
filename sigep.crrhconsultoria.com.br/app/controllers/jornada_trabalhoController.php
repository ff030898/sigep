<?php

class jornada_trabalho extends Controller {

    public function index_action() {


        $jornada_trabalho = new Jornada_Trabalho_Model();

        $dados["jornada_trabalho"] = $jornada_trabalho->get();

        $this->view('header');
        $this->view('menu');
        $this->view('content_jornada_trabalho', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_jornada_trabalho_add');
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $jornada_trabalho = new Jornada_Trabalho_Model();

        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $horas = $security->antiInjection(filter_input(INPUT_POST, "horas"));

        print_r($jornada_trabalho->adicionar($descricao, $horas));
    }

    public function edit() {
        $jornada_trabalho = new Jornada_Trabalho_Model();
        $security = new securityHelper();

        $dados["jornada_trabalho"] = $jornada_trabalho->getByID($security->antiInjection(filter_input(INPUT_POST, "id")));

        $this->view('header');
        $this->view('menu');
        $this->view('content_jornada_trabalho_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $jornada_trabalho = new Jornada_Trabalho_Model();

        $id_jornada_trabalho = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $descricao = $security->antiInjection(filter_input(INPUT_POST, "descricao"));
        $horas = $security->antiInjection(filter_input(INPUT_POST, "horas"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));

        print_r($jornada_trabalho->editar($id_jornada_trabalho, $descricao, $horas, $status));
    }

    public function remover() {
        $security = new securityHelper();
        $jornada_trabalho = new Jornada_Trabalho_Model();

        $id_jornada_trabalho = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($jornada_trabalho->remover($id_jornada_trabalho));
    }

}
