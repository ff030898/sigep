<?php

class menu extends Controller {

    public function index_action() {
        $menu = new Menu_Model();
        $dados["menu"] = $menu->estruturaMenu2();
        $this->view('header');
        $this->view('menu');
        $this->view('content_menu', $dados);
        $this->view('footer');
    }

    public function add() {
        $menu = new Menu_Model();
        $dados["menu"] = $menu->estruturaMenu3();
        $this->view('header');
        $this->view('menu');
        $this->view('content_menu_add', $dados);
        $this->view('footer');
    }

    public function adicionar() {
        $security = new securityHelper();
        $menu = new Menu_Model();

        $name = $security->antiInjection(filter_input(INPUT_POST, "name"));
        $ordem = $security->antiInjection(filter_input(INPUT_POST, "ordem"));
        $link = $security->antiInjection(filter_input(INPUT_POST, "link"));
        $icone = $security->antiInjection(filter_input(INPUT_POST, "icone"));
        $n_interno = $security->antiInjection(filter_input(INPUT_POST, "n_interno"));
        $target = $security->antiInjection(filter_input(INPUT_POST, "target"));
        $root = $security->antiInjection(filter_input(INPUT_POST, "root"));

        print_r($menu->adicionar($name, $link, $icone, $n_interno, $target, $root, $ordem));
    }

    public function remove() {
        $security = new securityHelper();
        $menu = new Menu_Model();

        $id = $security->antiInjection(filter_input(INPUT_POST, "id"));

        print_r($menu->remove($id));
    }

    public function edit() {
        $menu = new Menu_Model();
        $security = new securityHelper();
        $dados["menu"] = $menu->estruturaMenu3();
        $dados["item"] = $menu->getMenu($security->antiInjection(filter_input(INPUT_POST, 'id')));
        $this->view('header');
        $this->view('menu');
        $this->view('content_menu_edit', $dados);
        $this->view('footer');
    }

    public function editar() {
        $security = new securityHelper();
        $menu = new Menu_Model();

        $code = $security->antiInjection(filter_input(INPUT_POST, "id"));
        $name = $security->antiInjection(filter_input(INPUT_POST, "name"));
        $ordem = $security->antiInjection(filter_input(INPUT_POST, "ordem"));
        $link = $security->antiInjection(filter_input(INPUT_POST, "link"));
        $icone = $security->antiInjection(filter_input(INPUT_POST, "icone"));
        $n_interno = $security->antiInjection(filter_input(INPUT_POST, "n_interno"));
        $target = $security->antiInjection(filter_input(INPUT_POST, "target"));
        $root = $security->antiInjection(filter_input(INPUT_POST, "root"));

        print_r($menu->editar($code, $name, $link, $icone, $n_interno, $target, $root, $ordem));
    }

}
