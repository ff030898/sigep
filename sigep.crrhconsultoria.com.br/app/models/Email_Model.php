<?php

class Email_Model {

    private $__mail;
    private $_assunto;
    private $_mensagem;

    public function __construct() {
        $settings = new Settings_Model();
        $email_settings = $settings->getNotify();

        $this->__mail = new mailer(true);

        $this->__mail->IsSMTP();
        $this->__mail->Host = $email_settings['server'];
        $this->__mail->SMTPAuth = true;
        $this->__mail->Port = intval($email_settings['port']);
        $this->__mail->Username = $email_settings['email'];
        $this->__mail->Password = $email_settings['password'];
        $this->__mail->SetFrom($email_settings['email'], utf8_decode(TITLE));
    }

    public function setAssunto($assunto) {
        $this->_assunto = $assunto;
    }

    public function addDestinatario($email, $nome = "") {
        $this->__mail->AddAddress($email, $nome);
    }

    public function setMensagem($mensagem) {
        $this->_mensagem = $mensagem;
    }

    public function enviar($destinatarios, $assunto, $mensagem) {
        $conteudo = '
                <!DOCTYPE HTML>
                <html style="margin: 0 !important; padding: 0 !important; height: 100% !important; width: 100% !important;">
                    <head>
                        <title>' . TITLE . '</title>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    </head>
                    <body bgcolor="#FFF" style="margin: 0;margin: 0 !important;padding: 0 !important;height: 100% !important;">
                        <center style="width: 100%;">
                            <table  cellpadding="0" cellspacing="0" border="0"  style="border-collapse:collapse;">
                                <tr>
                                    <td>
                                        <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;">Contato CRRH Consultoria</div>
                                        <table align="center" width="600" class="email-container">
                                            <tr>
                                                <td style="padding: 5px 0; text-align: center;">&nbsp;</td>
                                            </tr>
                                        </table>
                                        <table cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#F7F7F7" width="601" class="email-container">
                                            <tr>
                                                <td class="full-width-image"><img src="cid:email_header.jpg" width="627" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 600px; height: auto;"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 40px; padding-right: 40px; padding-bottom: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; color: #555555; line-height: 30px">';
        $conteudo .= $mensagem;
        $conteudo .= '
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="full-width-image"><img src="cid:email_bottom.jpg" width="627" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 600px; height: auto;"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </body>
                </html>
            ';

        $this->__mail->Subject = $assunto;


        $this->__mail->AddEmbeddedImage('webfiles/img/email_header.jpg', "email_header.jpg");
        $this->__mail->AddEmbeddedImage('webfiles/img/email_bottom.jpg', "email_bottom.jpg");

        foreach ($destinatarios as $key => $value) {
            $this->__mail->AddAddress($key, $value);
        }

        $this->__mail->MsgHTML(utf8_decode($conteudo));

        $this->__mail->Send();
    }

}
