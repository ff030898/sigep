<?php

class usuarios extends Controller {

    public function index_action() {
        $user = new Users_Model();
        $dados["user"] = $user->getUsers();
        $this->view('header');
        $this->view('menu');
        $this->view('content_usuarios', $dados);
        $this->view('footer');
    }

    public function add() {
        $restricao = new Restricoes_Model();
        $menu = new Menu_Model();
        $dados["restricao"] = $restricao->getRestri();
        $dados["menu"] = $menu->estruturaMenu(0);
        $this->view('header');
        $this->view('menu');
        $this->view('content_usuarios_add', $dados);
        $this->view('footer');
    }

    public function edit() {
        $security = new securityHelper();
        $restricao = new Restricoes_Model();
        $user = new Users_Model();
        $menu = new Menu_Model();
        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $dados["menu"] = $menu->estruturaMenu($id);
        $dados["user"] = $user->getUserID($id);
        $dados["restricao"] = $restricao->getRestri();
        $this->view('header');
        $this->view('menu');
        $this->view('content_usuarios_edit', $dados);
        $this->view('footer');
    }

    public function alterar() {
        $user = new Users_Model();
        $dados["user"] = $user->getUserID($_SESSION["user_id"]);
        $this->view('header');
        $this->view('menu');
        $this->view('content_usuarios_altera', $dados);
        $this->view('footer');
    }

    public function altera_senha() {
        $security = new securityHelper();
        $usuarios = new Users_Model();
        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $nome = $security->antiInjection(filter_input(INPUT_POST, "nome"));
        $email = $security->antiInjection(filter_input(INPUT_POST, "email"));
        $password = $security->antiInjection(filter_input(INPUT_POST, "password"));

        print_r($usuarios->altera_senha($id, $nome, $email, $password));
    }

    public function adicionar() {
        $security = new securityHelper();
        $usuarios = new Users_Model();
        $nome = $security->antiInjection(filter_input(INPUT_POST, "nome"));
        $email = $security->antiInjection(filter_input(INPUT_POST, "email"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));
        $restricao = $security->antiInjection(filter_input(INPUT_POST, "restricao"));
        $menu = $_POST["menu"];
        print_r($usuarios->adicionar($nome, $email, $status, $restricao, $menu));
    }

    public function editar() {
        $security = new securityHelper();
        $usuarios = new Users_Model();
        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $nome = $security->antiInjection(filter_input(INPUT_POST, "nome"));
        $email = $security->antiInjection(filter_input(INPUT_POST, "email"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));
        $restricao = $security->antiInjection(filter_input(INPUT_POST, "restricao"));
        $menu = $_POST["menu"];
        $senha = $security->antiInjection(filter_input(INPUT_POST, "senha"));
        print_r($usuarios->editar($id, $nome, $email, $status, $restricao, $menu, $senha));
    }

    public function remove() {
        $security = new securityHelper();
        $user = new Users_Model();
        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));
        print_r($user->remove($id));
    }

    public function rel_acessos() {
        $this->view('header');
        $this->view('menu');
        $this->view('content_rel_acessos');
        $this->view('footer');
    }

    public function gerar() {
        $security = new securityHelper();
        $log = new Log_Model();

        $usuario = $security->antiInjection(filter_input(INPUT_POST, "usuario"));
        $data_ini = $security->antiInjection(filter_input(INPUT_POST, "data_ini"));
        $data_fim = $security->antiInjection(filter_input(INPUT_POST, "data_fim"));
        $saida = $security->antiInjection(filter_input(INPUT_POST, "saida"));


        if ($saida == 0) {
            $log->getLogPDF($usuario, $data_ini, $data_fim);
            print_r("OK-PDF");
        } else if ($saida == 1) {
            print_r($log->getLogTela($usuario, $data_ini, $data_fim));
        } else if ($saida == 2) {
            $log->getLogEXCEL($usuario, $data_ini, $data_fim);
            print_r("OK-EXCEL");
        }
    }

    public function search_acessos() {
        $security = new securityHelper();
        $log = new Log_Model();
        $pesquisa = $security->antiInjection(filter_input(INPUT_POST, "search"));
        print_r($log->getUsers($pesquisa));
    }

    public function search_usuario() {
        $security = new securityHelper();
        $usuarios = new Users_Model();

        $pesquisa = $security->antiInjection(filter_input(INPUT_POST, "pesquisa"));
        $funcao = $security->antiInjection(filter_input(INPUT_POST, "funcao"));

        print_r($usuarios->search_Usuarios($pesquisa, $funcao));
    }

}
