<?php

class Parametros_Model extends Model {

    public $_tabela = "settings";

    public function __construct() {
        parent::__construct();
    }

    public function getParametros() {
        $result = $this->read(null, null, null, null, "title, notify_email, notify_server, notify_port, notify_password, url, url_portal");
        return $result[0];
    }

    public function edita($title, $url, $url_portal, $email, $servidor, $porta, $password) {

        $saida = $this->update(array(
            "title" => $title,
            "url" => $url,
            "url_portal" => $url_portal,
            "notify_email" => $email,
            "notify_server" => $servidor,
            "notify_port" => $porta,
            "notify_password" => $password,
            "dt_update" => date("Y-m-d"),
            "time_update" => date("h:i:s")
                ), "id = '1'");
        if ($saida) {
            $this->log("Alteração", "Parametros");
            return $saida;
        } else {
            return FALSE;
        }
    }

    public function getUrlPortal() {
        $result = $this->read(null, null, null, null, "url_portal");

        return $result[0]["url_portal"];
    }

}
