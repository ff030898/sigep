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
class login extends Controller {

    public function index_action() {

    }

    public function logar() {

        $security = new securityHelper();
        $login = new Users_Model();
        print_r($login->valida($security->antiInjection(filter_input(INPUT_POST, "login")), md5($security->antiInjection(filter_input(INPUT_POST, "password")))));
    }

    public function sair() {
        $login = new Users_Model();
        $login->sair();
        $this->view('login');
    }

    public function recover() {
        $this->view('recover');
    }

    public function recuperar() {
        $security = new securityHelper();
        $login = new Users_Model();
        print_r($login->recuperar($security->antiInjection(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))));
    }

    public function reset() {
        $recover_key = $this->getParam('key');
        $login = new Users_Model();
        $dados = $login->getInfoByKey($recover_key);

        $this->view('reset', $dados);
    }

    public function resetar() {

        $security = new securityHelper();
        $login = new Users_Model();
        print_r($login->resetar(
                        $security->antiInjection(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)), $security->antiInjection(filter_input(INPUT_POST, "recover_key")), $security->antiInjection(filter_input(INPUT_POST, "password"))));
    }

}
