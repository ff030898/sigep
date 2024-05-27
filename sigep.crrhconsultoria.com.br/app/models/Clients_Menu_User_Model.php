<?php

class Clients_Menu_User_Model extends Model {

    public $_tabela = "clients_menu_user";

    public function checkPermission($id_menu, $id_user) {
        $result = $this->read("usuario = '{$id_user}' AND menu = '{$id_menu}'");
        if (count($result) == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function clearPermission($id_user) {
        return $this->delete("usuario = '{$id_user}'");
    }

    public function setPermission($id_user, $id_menu) {
        return $this->insert(array(
                    "usuario" => $id_user,
                    "menu" => $id_menu
        ));
    }

}
