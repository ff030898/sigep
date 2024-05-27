<?php

class clients_users extends Controller {

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

        $clients_user = new Clients_Users_Model();

        $dados["clients_users"] = $clients_user->getUsers();

        $this->view('header');
        $this->view('menu');
        $this->view('content_clients_users', $dados);
        $this->view('footer');
    }

    public function add() {
        $this->verifica_cliente();
        $restricao = new Restricoes_Model();
        $menu = new Clients_Menu_Model();
        $celula = new Celula_Model();

        $dados["menu"] = $menu->estruturaMenu(0);
        $dados["restricao"] = $restricao->getRestri();
        $dados["celula"] = $celula->estrutura_celula();

        $this->view('header');
        $this->view('menu');
        $this->view('content_clients_users_add', $dados);
        $this->view('footer');
    }

    public function edit() {
        $this->verifica_cliente();

        $restricao = new Restricoes_Model();
        $clients_user = new Clients_Users_Model();
        $security = new securityHelper();
        $menu = new Clients_Menu_Model();
        $celula = new Celula_Model();
        $clients_user_avaliacao = new Clients_Users_Avaliacao_Model();

        $id_user_cliente = $security->antiInjection(filter_input(INPUT_POST, 'id'));

        $dados["clients_users"] = $clients_user->getUserID($id_user_cliente);
        $dados["restricao"] = $restricao->getRestri();
        $dados["menu"] = $menu->estruturaMenu($id_user_cliente);
        $dados["celula"] = $celula->estrutura_celula();
        $dados["avaliacao"] = $clients_user_avaliacao->getByIdUser($id_user_cliente);

        $this->view('header');
        $this->view('menu');
        $this->view('content_clients_users_edit', $dados);
        $this->view('footer');
    }

    public function adicionar() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $clients_user = new Clients_Users_Model();

        $nome = $security->antiInjection(filter_input(INPUT_POST, "nome"));
        $email = $security->antiInjection(filter_input(INPUT_POST, "email"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));
        $restricao = $security->antiInjection(filter_input(INPUT_POST, "restricao"));

        $menu = $_POST["menu"];
        $avaliacao = $_POST["avaliacao"];

        print_r($clients_user->adicionar($nome, $email, $restricao, $status, $menu, $avaliacao));
    }

    public function editar() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $clients_user = new Clients_Users_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $nome = $security->antiInjection(filter_input(INPUT_POST, "nome"));
        $email = $security->antiInjection(filter_input(INPUT_POST, "email"));
        $status = $security->antiInjection(filter_input(INPUT_POST, "status"));
        $restricao = $security->antiInjection(filter_input(INPUT_POST, "restricao"));

        if (!isset($_POST["menu"])) {
            $menu = array();
        }

        $menu = (isset($_POST["menu"]) ? $_POST["menu"] : array());
        $avaliacao = (isset($_POST["avaliacao"]) ? $_POST["avaliacao"] : array());


        print_r($clients_user->editar($id, $nome, $email, $restricao, $status, $menu, $avaliacao));
    }

    public function remove() {
        $this->verifica_cliente();

        $security = new securityHelper();
        $clients_user = new Clients_Users_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($clients_user->remove($id));
    }

}
