<?php

class organograma extends Controller {

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

        $ambiente = new Ambiente_Model();

        $this->view('header');
        $this->view('menu');
        $this->view('content_organograma');
        $this->view('footer');
    }

}
