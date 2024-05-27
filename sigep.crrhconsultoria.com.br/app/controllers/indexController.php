<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of indexController
 *
 * @author drc
 */
class index extends Controller {

    public function index_action() {
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
