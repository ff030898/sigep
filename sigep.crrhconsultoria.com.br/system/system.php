<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of system
 *
 * @author drc
 */
class System {

    private $_url;
    private $_explode;
    public $_controller;
    public $_action;
    public $_params;

    public function __construct() {
        $this->setUrl();
        $this->setExplode();
        $this->setController();
        $this->setAction();
        $this->setParams();
    }

    private function setUrl() {
        $security = new securityHelper();
        $_GET["url"] = (isset($_GET["url"]) ? $security->antiInjection($_GET["url"]) : 'index/index_action');
        $this->_url = $security->antiInjection($_GET["url"]);
    }

    private function setExplode() {
        $this->_explode = explode('/', $this->_url);
    }

    private function setController() {
        $this->_controller = $this->_explode[0];
    }

    private function setAction() {
        $ac = (!isset($this->_explode[1]) || $this->_explode[1] == null || $this->_explode[1] == "index" ? "index_action" : $this->_explode[1]);
        $this->_action = $ac;
    }

    private function setParams() {
        $ind = null;
        $value = null;
        unset($this->_explode[0], $this->_explode[1]);
        if (end($this->_explode) == "") {
            array_pop($this->_explode);
        }

        $i = 0;

        if (!empty($this->_explode)) {
            foreach ($this->_explode as $val) {
                if ($i % 2 == 0) {
                    $ind[] = $val;
                } else {
                    $value[] = $val;
                }
                $i++;
            }
        } else {
            $ind = array();
            $value = array();
        }

        if (is_array($value)) {
            if (count($ind) > count($value)) {
                array_pop($ind);
            } else if (count($ind) < count($value)) {
                array_pop($value);
            }
        }



        if (!empty($ind) && !empty($value)) {
            $this->_params = array_combine($ind, $value);
        } else {
            $this->_params = array();
        }
    }

    public function getParam($name = null) {
        if (!empty($name)) {
            if (!empty($this->_params[$name])) {
                return $this->_params[$name];
            } else {
                return null;
            }
        } else {
            return $this->_params;
        }
    }

    public function run() {
        $usuario = new Users_Model();
        $add__ = NULL;
        if (isset($_SESSION["user_id"])) {
            if (!$usuario->verifica($_SESSION["user_id"])) {
                $usuario->sair();
            }
        }
        $controller_path = CONTROLLERS . $this->_controller . "Controller.php";
        if (!empty($_SESSION["add"])) {
            $add__ = $_SESSION["add"];
        }

        if (!file_exists($controller_path) || (!$add__ && $this->_controller != "login")) {
            $this->_controller = "index";
            $controller_path = CONTROLLERS . "indexController.php";
        }

        require_once $controller_path;
        $app = new $this->_controller();

        if (!method_exists($app, $this->_action)) {
            $this->_action = "index_action";
        }

        $action = $this->_action;
        $app->$action();
    }

}
