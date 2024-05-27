<?php

class funcionarios extends Controller {

    public function index_action() {

        $this->verifica_cliente();

        $cliente = new Clients_Model();
        $funcionários = new Funcionarios_Model();

        $dados["funcionarios"] = $funcionários->get();
        $dados["adiciona"] = $cliente->getQtdeFuncionarios();

        $this->view('header');
        $this->view('menu');
        $this->view('content_funcionarios', $dados);
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

        $cliente = new Clients_Model();

        if ($cliente->getQtdeFuncionarios()) {
            $jornada = new Jornada_Trabalho_Model();
            $cargo = new Cargo_Model();


            $dados["jornada"] = $jornada->get(1);
            $dados["cargo"] = $cargo->getCargo(1);

            $this->view('header');
            $this->view('menu');
            $this->view('content_funcionarios_add', $dados);
            $this->view('footer');
        } else {
            $login = new Users_Model();
            if (!$login->sessao()) {
                $this->view('login');
            } else {

                $dados["data"] = "a";

                $this->view('header');
                $this->view('menu');
                $this->view('content_home', $dados);
                $this->view('footer');
            }
        }
    }

    public function adicionar() {
        $security = new securityHelper();
        $funcionarios = new Funcionarios_Model();

        $pessoa = $security->antiInjection(filter_input(INPUT_POST, "pessoa"));
        $id_cargo = $security->antiInjection(filter_input(INPUT_POST, "cargo"));
        $data_admissao = $security->antiInjection(filter_input(INPUT_POST, "data_admissao"));
        $tipo_funcionario = $security->antiInjection(filter_input(INPUT_POST, "tipo_funcionario"));
        $cat_salarial = $security->antiInjection(filter_input(INPUT_POST, "cat_salarial"));
        $id_jornada = $security->antiInjection(filter_input(INPUT_POST, "jornada"));
        $turno = $security->antiInjection(filter_input(INPUT_POST, "turno"));
        $hora_ini = $security->antiInjection(filter_input(INPUT_POST, "hora_ini"));
        $hora_fim = $security->antiInjection(filter_input(INPUT_POST, "hora_fim"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));
        $ctps_num = $security->antiInjection(filter_input(INPUT_POST, "ctps_num"));
        $ctps_serie = $security->antiInjection(filter_input(INPUT_POST, "ctps_serie"));
        $ctps_uf = $security->antiInjection(filter_input(INPUT_POST, "ctps_uf"));
        $ctps_data = $security->antiInjection(filter_input(INPUT_POST, "ctps_data"));
        $pis = $security->antiInjection(filter_input(INPUT_POST, "pis"));
        $salario = $security->antiInjection(filter_input(INPUT_POST, "salario"));
        $observacao = $security->antiInjection(filter_input(INPUT_POST, "observacao"));

        print_r($funcionarios->adicionar($pessoa, $id_cargo, $data_admissao, $tipo_funcionario, $cat_salarial, $id_jornada, $turno, $hora_ini, $hora_fim, $status, $ctps_num, $ctps_serie, $ctps_uf, $ctps_data, $pis, $salario, $observacao));
    }

    public function edit() {
        $funcionarios = new Funcionarios_Model();
        $security = new securityHelper();
        $jornada = new Jornada_Trabalho_Model();
        $cargo = new Cargo_Model();

        $dados["jornada"] = $jornada->get(1);
        $dados["funcionarios"] = $funcionarios->getByID($security->antiInjection(filter_input(INPUT_POST, "id")));
        $dados["cargo"] = $cargo->getCargo(1);

        $this->view('header');
        $this->view('menu');
        $this->view('content_funcionarios_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $funcionarios = new Funcionarios_Model();

        $id_funcionario = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $pessoa = $security->antiInjection(filter_input(INPUT_POST, "pessoa"));
        $id_cargo = $security->antiInjection(filter_input(INPUT_POST, "cargo"));
        $data_admissao = $security->antiInjection(filter_input(INPUT_POST, "data_admissao"));
        $tipo_funcionario = $security->antiInjection(filter_input(INPUT_POST, "tipo_funcionario"));
        $cat_salarial = $security->antiInjection(filter_input(INPUT_POST, "cat_salarial"));
        $id_jornada = $security->antiInjection(filter_input(INPUT_POST, "jornada"));
        $turno = $security->antiInjection(filter_input(INPUT_POST, "turno"));
        $hora_ini = $security->antiInjection(filter_input(INPUT_POST, "hora_ini"));
        $hora_fim = $security->antiInjection(filter_input(INPUT_POST, "hora_fim"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));
        $ctps_num = $security->antiInjection(filter_input(INPUT_POST, "ctps_num"));
        $ctps_serie = $security->antiInjection(filter_input(INPUT_POST, "ctps_serie"));
        $ctps_uf = $security->antiInjection(filter_input(INPUT_POST, "ctps_uf"));
        $ctps_data = $security->antiInjection(filter_input(INPUT_POST, "ctps_data"));
        $pis = $security->antiInjection(filter_input(INPUT_POST, "pis"));
        $salario = $security->antiInjection(filter_input(INPUT_POST, "salario"));
        $observacao = $security->antiInjection(filter_input(INPUT_POST, "observacao"));

        print_r($funcionarios->editar($id_funcionario, $pessoa, $id_cargo, $data_admissao, $tipo_funcionario, $cat_salarial, $id_jornada, $turno, $hora_ini, $hora_fim, $status, $ctps_num, $ctps_serie, $ctps_uf, $ctps_data, $pis, $salario, $observacao));
    }

    public function remover() {
        $security = new securityHelper();
        $funcionarios = new Funcionarios_Model();

        $id_funcionario = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($funcionarios->remover($id_funcionario));
    }

}
