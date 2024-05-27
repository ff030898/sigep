<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login_Model
 *
 * @author drc
 */
class Users_Model extends Model {

    public $_tabela = "users";

    public function getUsers() {
        return $this->read();
    }

    public function getUserID($id) {
        $result = $this->read("id = '{$id}'");
        return $result[0];
    }

    public function adicionar($nome, $email, $status, $restricao, $menu = null) {
        $security = new securityHelper();
        $permission = new Menu_User_Model();
        $email = strtoupper($email);
        $consulta = "email = '{$email}'";
        $result = $this->read($consulta);
        $nome = strtoupper($nome);
        if (count($result) == 0) {
            $recover_key = $security->Codifica(date("d-m_H:i:s"));
            $id = $this->insert(array(
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

            $log = new Log_Model();
            $log->gravarLog($this->_tabela, "Inserção", $nome);
            $settings = new Settings_Model();
            $email_settings = $settings->getNotify();

            $mensagem = '
                        <h2>Olá ' . $nome . '!</h2>
                        <p style="line-height: 32px;">Acabamos de criar seu acesso no sistema da ' . TITLE . ' .</p>
                        <p style="line-height: 32px; text-align: center;" align="center"">Para acessar o sistema basta clicar no botão abaixo.</p>
                            <p style="line-height: 32px; text-align: center;" align="center"><a href="' . URL_SITE . '/login/reset/key/' . $recover_key . '" style="color: white; display: inline-block; border-radius: 2px; text-decoration: none; line-height: 1px; background-color: #00ADEF; padding: 18px  55px; text-decoration: uppercase;" target="_blank">Resetar senha</a></p>
                        <p style="line-height: 32px;">Para manter sua conta segura, por favor, não encaminhe este e-mail para ninguém. Caso tenha alguma dúvida, entre em contato com o suporte do sistema.</p>
            ';

            $destinatarios = array($email => $nome);

            $E_mail = new Email_Model();
            $E_mail->enviar($destinatarios, 'Acesso ao sistema da CRRH Consultoria', $mensagem);


            return $id;
        } else {
            return false;
        }
    }

    public function editar($id, $nome, $email, $status, $restricao, $menu) {
        $security = new securityHelper();
        $permission = new Menu_User_Model();

        $log = new Log_Model();
        $recover_key = $security->Codifica(date("d-m_H:i:s"));

        $log->gravarLog($this->_tabela, "Alteração", $nome);

        $permission->clearPermission($id);
        foreach ($menu as $key => $value) {
            $permission->setPermission($id, $key);
        }


        return $this->update(array(
                    "name" => $nome,
                    "status" => $status,
                    "restricao" => $restricao,
                    "email" => $email,
                        ), "id = '{$id}'");
    }

    public function getName($id) {
        $result = $this->read("id = '{$id}'");
        return $result[0]["name"];
    }

    public function getInfoByKey($key) {
        $result = $this->read("recover_key='{$key}'");
        return $result[0];
    }

    public function altera_senha($id, $nome, $email, $password) {
        $log = new Log_Model();
        $security = new securityHelper();

        if ($password == "") {
            $result = $this->update(array(
                "name" => strtoupper($nome)
                    ), "id = '{$id}'");
        } else {
            $password = md5($security->antiInjection($password));
            $result = $this->update(array(
                "name" => strtoupper($nome),
                "password" => $password,
                    ), "id = '{$id}'");
        }

        if ($result) {
            $log->gravarLog($this->_tabela, "Alteração", "Altereu suas informações.");

            $mensagem = '
                        <h2>Olá ' . $nome . '!</h2>
                        <p style="line-height: 32px;">Você acabou de alterar as informações de Nome/Senha com sucesso!</p>
                        <p style="line-height: 32px;">Caso não tenha sido você, entre em contato com o suporte o mais rápido possível!</p>
            ';
            $destinatarios = array($email => $nome);
            $E_mail = new Email_Model();
            $E_mail->enviar($destinatarios, 'Senha do sistema alterada', $mensagem);

            return true;
        } else {
            $log->gravarLog($this->_tabela, "Alteração", "Tentou alterar mas sem sucesso.");

            $mensagem = '
                        <h2>Olá ' . $nome . '!</h2>
                        <p style="line-height: 32px;">Houve uma tentativa de alterar seu Nome/Senha sem sucesso!</p>
                        <p style="line-height: 32px;">Caso não tenha sido você, entre em contato com o suporte o mais rápido possível!</p>
            ';
            $destinatarios = array($email => $nome);
            $E_mail = new Email_Model();
            $E_mail->enviar($destinatarios, 'Tentativa de alterar a senha', $mensagem);

            return FALSE;
        }
    }

    public function valida($login, $password) {
        $restricao = new Restricoes_Model();
        $log = new Log_Model();

        $login = strtoupper($login);
        $resul = $this->read("email='{$login}' AND password='{$password}' AND status = '1'");
        if (count($resul) == 1) {
            $recover = $resul[0]['recover'];
            $id = $resul[0]['id'];
            $name = $resul[0]['name'];

            $id_restricao = $resul[0]['restricao'];

            $restri = $restricao->getRestricaoID($id_restricao);

            if ($this->checkRestricao($restri["seg"], $restri["ter"], $restri["qua"], $restri["qui"], $restri["sex"], $restri["sab"], $restri["dom"], $restri["hora_ini"], $restri["hora_fim"])) {

                if ($recover == "1") {
                    $this->update(array(
                        "recover" => "0",
                        "recover_key" => ""
                            ), "id = '{$id}'");
                }

                $_SESSION["add"] = "true";
                $_SESSION["user_id"] = $id;
                $_SESSION["user_name"] = $name;
                $_SESSION["nome_cliente"] = "CRRH CONSULTORIA | GERAL";
                $_SESSION["id_cliente"] = "0";

                $log->gravarLog($this->_tabela, "Login", $name);
                return true;
            } else {
                $_SESSION["user_name"] = $name;
                $_SESSION["nome_cliente"] = "CRRH CONSULTORIA | GERAL";
                $log->gravarLog($this->_tabela, "Tentativa de acesso fora da restricao", $login);
                session_destroy();
                return false;
            }
        } else {
            $log->gravarLog($this->_tabela, "Tentativa de acesso invalida", $login);
            session_destroy();
            return false;
        }
    }

    public function verifica($id) {
        $restricao = new Restricoes_Model();
        $resul = $this->read("id='{$id}' AND status = '1'");
        if (count($resul) == 1) {

            $id_restricao = $resul[0]['restricao'];

            $restri = $restricao->getRestricaoID($id_restricao);

            if ($this->checkRestricao($restri["seg"], $restri["ter"], $restri["qua"], $restri["qui"], $restri["sex"], $restri["sab"], $restri["dom"], $restri["hora_ini"], $restri["hora_fim"])) {
                return true;
            } else {
                session_destroy();
                return false;
            }
        } else {
            session_destroy();
            return false;
        }
    }

    private function checkRestricao($seg, $ter, $qua, $quin, $sex, $sab, $dom, $hora_ini, $hora_fim) {
        if (date("D") == "Mon" && $seg) {
            if (time() > strtotime($hora_ini) && time() < strtotime($hora_fim)) {
                return true;
            } else {
                return false;
            }
        } else if (date("D") == "Tue" && $ter) {
            if (time() > strtotime($hora_ini) && time() < strtotime($hora_fim)) {
                return true;
            } else {
                return false;
            }
        } else if (date("D") == "Wed" && $qua) {
            if (time() > strtotime($hora_ini) && time() < strtotime($hora_fim)) {
                return true;
            } else {
                return false;
            }
        } else if (date("D") == "Thu" && $quin) {
            if (time() > strtotime($hora_ini) && time() < strtotime($hora_fim)) {
                return true;
            } else {
                return false;
            }
        } else if (date("D") == "Fri" && $sex) {
            if (time() > strtotime($hora_ini) && time() < strtotime($hora_fim)) {
                return true;
            } else {
                return false;
            }
        } else if (date("D") == "Sat" && $sab) {
            if (time() > strtotime($hora_ini) && time() < strtotime($hora_fim)) {
                return true;
            } else {
                return false;
            }
        } else if (date("D") == "Sun" && $dom) {
            if (time() > strtotime($hora_ini) && time() < strtotime($hora_fim)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function sessao() {
        $add__ = NULL;
        if (!empty($_SESSION["add"])) {
            $add__ = $_SESSION["add"];
        }
        if ($add__ != "true") {
            return false;
        } else {
            return true;
        }
    }

    public function sair() {
        $name = $_SESSION["user_name"];
        $log = new Log_Model();
        $log->gravarLog($this->_tabela, "Logoff", $name);
        $_SESSION["add"] = "false";

        session_destroy();
        return false;
    }

    public function recuperar($email) {
        $security = new securityHelper();
        $log = new Log_Model();
        $email = strtoupper($email);
        $resul = $this->read("email='$email' AND status = '1'");
        if (count($resul) == 1) {
            $id = $resul[0]['id'];
            $recover_key = $security->Codifica(date("d-m_H:i:s"));
            $this->update(array(
                "recover" => "1",
                "recover_key" => $recover_key
                    ), "id = '{$id}'");

            $mensagem = '

                        <h2>Olá ' . $resul[0]['name'] . '!</h2>
                        <p style="line-height: 32px;">Você requisitou a troca de sua senha no sistema da ' . TITLE . '. Se foi você que solicitou, você pode definir uma nova senha.</p>
                        <p style="line-height: 32px; text-align: center;" align="center"><a href="' . URL_SITE . '/login/reset/key/' . $recover_key . '" style="color: white; display: inline-block; border-radius: 2px; text-decoration: none; line-height: 1px; background-color: #00ADEF; padding: 18px  55px; text-decoration: uppercase;" target="_blank">Resetar senha</a></p>
                        <p style="line-height: 32px;">Para manter sua conta segura, por favor, não encaminhe este e-mail para ninguém. Caso tenha alguma dúvida, entre em contato com o suporte do sistema.</p>
            ';


            $destinatarios = array($email => $resul[0]['name']);

            $E_mail = new Email_Model();
            $E_mail->enviar($destinatarios, 'Recuperar senha do sistema', $mensagem);

            $log->gravarLog($this->_tabela, "Locitado recuperação de senha", $email);
            return true;
        } else {
            $log->gravarLog($this->_tabela, "Tentativa de recuperacao de senha invalida", $email);
            return false;
        }
    }

    public function resetar($email, $recover_key, $password) {
        $security = new securityHelper();
        $log = new Log_Model();
        $email = strtoupper($email);
        $resul = $this->read("email='$email' AND recover_key = '$recover_key' AND recover = '1' AND status = '1'");

        if (count($resul) == 1) {
            $id = $resul[0]['id'];
            $password = md5($security->antiInjection($password));
            $this->update(array(
                "password" => $password,
                "recover" => 0,
                "recover_key" => ""
                    ), "id = '{$id}'");

            $mensagem = '
                        <h2>Olá ' . $resul[0]['name'] . '!</h2>
                        <p style="line-height: 32px;">Sua senha acabou de ser altera!</p>
                        <p style="line-height: 32px;">Caso não tenha sido você, entre em contato com o suporte o mais rápido possível!</p>
            ';

            $destinatarios = array($email => $resul[0]['name']);

            $E_mail = new Email_Model();
            $E_mail->enviar($destinatarios, 'Senha do sistema alterada', $mensagem);

            $log->gravarLog($this->_tabela, "Senha recuperada com sucesso", $email);

            return true;
        } else {
            $log->gravarLog($this->_tabela, "Tentativa de acesso invalida", $email);
            return false;
        }
    }

    public function remove($id) {
        $log = new Log_Model();
        if ($id != $_SESSION["user_id"]) {
            $result = $this->read("id = '{$id}'", NULL, NULL, NULL, "name");
            $user = $result[0]["name"];
            $log->gravarLog($this->_tabela, "Remoção", $user);
            return $this->delete("id = '{$id}'");
        } else {
            return FALSE;
        }
    }

    public function search_Usuarios($pesquisa, $funcao) {
        $result = $this->query("SELECT * FROM users WHERE (id LIKE '%{$pesquisa}%' OR name LIKE '%{$pesquisa}%'  OR login LIKE '%{$pesquisa}%') AND status = '1' ORDER BY name ASC");
        $mostra = null;
        $total = count($result);
        $mostra .= '<div style="float: left;">';
        $mostra .= '
            <div class="dataTable_wrapper">
                <table width="100%" class="table table-striped table-bordered table-hover" id="empresas_listas">
                    <tbody>
        ';
        for ($i = 0; $i <= $total - 1; $i++) {
            $mostra .= '
                <tr>
                    <td>';
            $mostra .= '<a href="javascript:' . $funcao . '(\'' . $result[$i]["id"] . '-' . $result[$i]["name"] . '\')">' . $result[$i]["id"] . '-' . $result[$i]["name"] . "</a>";
            $mostra .= '
                    </td>
                </tr>
            ';
        }

        $mostra = ($total == 0 ? "Não encontrado nenhum registro" : $mostra);

        $mostra .= '<div style = "float: right;">
                    <a href = "javascript:fechar_pesquisa()"><i class = "fa fa-window-close-o fa-lg"></i></a>
                    </div>';

        return $mostra;
    }

}
