<?php

class Clients_Users_Avaliacao_Model extends Model {

    public $_tabela = "clients_users_avaliacao";

    public function adicionar($id_user, $id_celula) {
        $this->insert(array(
            "id_cliente_user" => $id_user,
            "id_celula" => $id_celula
        ));
    }

    public function deleteByIdUser($id_user) {
        $this->delete("id_cliente_user = '{$id_user}'");
    }

    public function getByIdUser($id_user) {
        return $this->read("id_cliente_user = '{$id_user}'");
    }

}
