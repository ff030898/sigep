<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of homeController
 *
 * @author drc
 */
class home extends Controller {

    public function index_action() {
        $login = new Users_Model();
        $menu = new Menu_Model();

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

    public function logar() {
        $login = new Users_Model();
        echo $login->valida($_POST["login"], md5($_POST["password"]));
    }

}
