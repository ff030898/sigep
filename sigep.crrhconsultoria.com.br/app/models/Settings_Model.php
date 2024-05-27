<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Produtos_Model
 *
 * @author drc
 */
class Settings_Model extends Model {

    public $_tabela = "settings";

    public function getSiteTitle() {
        return $this->read(null, null, null, null, "title, url");
    }

    public function getNotify() {
        $resul = $this->read("id='1'");
        return array(
            'server' => $resul[0]['notify_server'],
            'email' => $resul[0]['notify_email'],
            'password' => $resul[0]['notify_password'],
            'port' => $resul[0]['notify_port'],
            'url' => $resul[0]['url'],
            'url_portal' => $resul[0]['url_portal']
        );
    }

}
