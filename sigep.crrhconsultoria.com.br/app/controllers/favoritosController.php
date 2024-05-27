<?php

class favoritos extends Controller {

    public function index_action() {
        $login = new Users_Model();
        $menu = new Menu_Model();
        $favoritos = new Menu_Favoritos_Model();

        if (!$login->sessao()) {
            $this->view('login');
        } else {
            $dados["menu"] = $favoritos->montaPainelFavoritos();
            $this->view('header');
            $this->view('menu');
            $this->view('content_favoritos', $dados);
            $this->view('footer');
        }
    }

    public function verifica() {
        $favoritos = new Menu_Favoritos_Model();
        $id_menu = $this->getParam("menu");
        $tipo = $this->getParam("tipo");

        $id_user = $favoritos->controleFavoritos($id_menu, $tipo);

        header('Location: /favoritos');
        exit;
    }

}
