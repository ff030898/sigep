<?php

class Clients_Users_Model extends Model {

    public $_tabela = "clients_users";

    public function getUsers() {
        $id_client = $_SESSION["id_cliente"];
        $result = $this->read("id_client = '{$id_client}'", null, null, "name ASC", "id, id_client, name, email, status");

        foreach ($result as $key => $value) {
            if ($result[$key]["status"] == 1) {
                $result[$key]["situacao"] = "ATIVO";
            } else if ($result[$key]["status"] == 0) {
                $result[$key]["situacao"] = "INATIVO";
            }
        }
        return $result;
    }

    public function getUserID($id) {
        $id_client = $_SESSION["id_cliente"];
        $result = $this->read("id_client = '{$id_client}' AND id = '{$id}'");
        return $result[0];
    }

    public function adicionar($nome, $email, $restricao, $status, $menu = null, $avaliacao = null) {

        $permission = new Clients_Menu_User_Model();
        $clientes_user_avaliacao = new Clients_Users_Avaliacao_Model();

        $id_client = $_SESSION["id_cliente"];

        $security = new securityHelper();
        $email = strtoupper($email);

        $result = $this->read("id_client = '{$id_client}' AND email = '{$email}'");

        if (!count($result)) {
            $nome = strtoupper($nome);

            $recover_key = $security->Codifica(date("d-m_H:i:s"));

            $id = $this->insert(array(
                "id_client" => $id_client,
                "name" => $nome,
                "email" => $email,
                "status" => $status,
                "restricao" => $restricao,
                "recover" => 1,
                "recover_key" => $recover_key,
                "password" => "wqefwifjwienjfwenwernfg"
                    ), "user_id_seq");

            foreach ($menu as $key2 => $value2) {
                $permission->setPermission($id, $key2);
            }

            $clientes_user_avaliacao->deleteByIdUser($id);
            foreach ($avaliacao as $key => $value) {
                $clientes_user_avaliacao->adicionar($id, $value);
            }

            $log = new Log_Model();

            $log->gravarLog($this->_tabela, "Inserção", $nome);

            $settings = new Settings_Model();

            $parametros = new Parametros_Model();

            $client = new Clients_Model();

            $mensagem = '
                        <h2>Olá ' . $nome . '!</h2>
                        <p style="line-height: 32px;">Acabamos de criar seu acesso no sistema da <b>' . $client->getClientName($id_client) . '</b> .</p>
                        <p style="line-height: 32px; text-align: center;" align="center"">Para acessar o sistema basta clicar no botão abaixo.</p>
                            <p style="line-height: 32px; text-align: center;" align="center"><a href="' . $parametros->getUrlPortal() . '/login/reset/key/' . $recover_key . '" style="color: white; display: inline-block; border-radius: 2px; text-decoration: none; line-height: 1px; background-color: #00ADEF; padding: 18px  55px; text-decoration: uppercase;" target="_blank">Resetar senha</a></p>
                        <p style="line-height: 32px;">Para manter sua conta segura, por favor, não encaminhe este e-mail para ninguém. Caso tenha alguma dúvida, entre em contato com o suporte do sistema.</p>
            ';

            $destinatarios = array($email => $nome);

            $E_mail = new Email_Model();
            $E_mail->enviar($destinatarios, 'Acesso ao sistema da ' . $client->getClientName($id_client), $mensagem);


            return $id;
        } else {
            return false;
        }
    }

    public function editar($id, $nome, $email, $restricao, $status, $menu = null, $avaliacao = null) {

        $permission = new Clients_Menu_User_Model();
        $clientes_user_avaliacao = new Clients_Users_Avaliacao_Model();

        $id_client = $_SESSION["id_cliente"];

        $result = $this->read("id_client = '{$id_client}' AND email = '{$email}' AND id != '{$id}'");

        if (!count($result)) {
            $ret = $this->update(array(
                "name" => $nome,
                "status" => $status,
                "restricao" => $restricao,
                "email" => $email,
                    ), "id = '{$id}'");



            $permission->clearPermission($id);
            foreach ($menu as $key => $value) {
                $permission->setPermission($id, $key);
            }

            $clientes_user_avaliacao->deleteByIdUser($id);
            foreach ($avaliacao as $key => $value) {
                $clientes_user_avaliacao->adicionar($id, $value);
            }

            $log = new Log_Model();
            $log->gravarLog($this->_tabela, "Alteração", $nome);

            $client = new Clients_Model();
            $parametros = new Parametros_Model();

            $mensagem = '
                        <h2>Olá ' . $nome . '!</h2>
                        <p style="line-height: 32px;">Acabamos de alterar seu usuário no sistema da <b>' . $client->getClientName($id_client) . '</b> .</p>
                        <p style="line-height: 32px; text-align: center;" align="center"">Para acessar o sistema basta clicar no botão abaixo.</p>
                            <p style="line-height: 32px; text-align: center;" align="center"><a href="' . $parametros->getUrlPortal() . '" style="color: white; display: inline-block; border-radius: 2px; text-decoration: none; line-height: 1px; background-color: #00ADEF; padding: 18px  55px; text-decoration: uppercase;" target="_blank">Acessar Sistema</a></p>
                        <p style="line-height: 32px;">Por gentileza, verifique seu acesso no sistema. Caso tenha alguma dúvida, entre em contato com o suporte do sistema.</p>
            ';

            $destinatarios = array($email => $nome);

            $E_mail = new Email_Model();
            $E_mail->enviar($destinatarios, 'Alterado conta no sistema da ' . $client->getClientName($id_client), $mensagem);

            return true;
        } else {
            return false;
        }
    }

    public function remove($id) {

        $id_client = $_SESSION["id_cliente"];

        $log = new Log_Model();
        if ($id != $_SESSION["user_id"]) {
            $result = $this->read("id_client = '{$id_client}' AND id = '{$id}'", NULL, NULL, NULL, "name");
            $user = $result[0]["name"];
            $log->gravarLog($this->_tabela, "Remoção", $user);
            return $this->delete("id_client = '{$id_client}' AND id = '{$id}'");
        } else {
            return FALSE;
        }
    }

}
