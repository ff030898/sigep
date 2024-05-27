<?php

class parametros extends Controller {

    public function index_action() {
        $parametros = new Parametros_Model();
        $dados["parametros"] = $parametros->getParametros();
        $this->view('header');
        $this->view('menu');
        $this->view('content_parametros', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $parametros = new Parametros_Model();

        $title = $security->antiInjection(filter_input(INPUT_POST, "title"));
        $url = $security->antiInjection(filter_input(INPUT_POST, "url"));
        $url_portal = $security->antiInjection(filter_input(INPUT_POST, "url_portal"));
        $email = $security->antiInjection(filter_input(INPUT_POST, "email"));
        $servidor = $security->antiInjection(filter_input(INPUT_POST, "servidor"));
        $porta = $security->antiInjection(filter_input(INPUT_POST, "porta"));
        $password = $security->antiInjection(filter_input(INPUT_POST, "password"));

        print_r($parametros->edita($title, $url, $url_portal, $email, $servidor, $porta, $password));
    }

}
