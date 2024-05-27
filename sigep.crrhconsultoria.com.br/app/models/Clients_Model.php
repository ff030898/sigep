<?php

class Clients_Model extends Model {

    public $_tabela = "clients";

    public function __construct() {
        parent::__construct();
    }

    public function getClients() {
        return $this->read("", null, null, "name ASC");
    }

    public function getClient($id) {
        $return = $this->read("id = '{$id}'");
        if (count($return)) {
            return $return[0];
        } else {
            return FALSE;
        }
    }

    public function getClientName($id) {
        if ($id != "0") {
            $return = $this->read("id = '{$id}' ");
            return $return[0]["social_razon"];
        } else {
            return "GERAL";
        }
    }

    public function getClientApelido($id) {
        $return = $this->read("id = '{$id}' ");
        return $return[0]["name"];
    }

    public function getEmpresa($page) {

        $result = $this->read('', 1, $page, "id ASC");
        if (count($result) >= 1) {
            return $result[0];
        } else {
            $result[0]["id"] = 1;
            return $result[0];
        }
    }

    public function getEmpresaID($id) {

        $result = $this->read("id = '{$id}' ");
        if (count($result) >= 1) {
            return $result[0];
        } else {
            $result[0]["id"] = 1;
            return $result[0];
        }
    }

    public function getTipoPessoa($pessoa) {
        $explode = explode('-', $pessoa);
        list($id_cliente) = $explode;

        $result = $this->read("id = '{$id_cliente}'", NULL, NULL, NULL, "nature");

        return $result[0]["nature"];
    }

    public function getQtdeFuncionarios() {

        $functionarios = new Funcionarios_Model();

        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->read("id = '{$id_cliente}'", null, null, null, "qtde_funcionario");

        $total_empresa = (count($result) ? $result[0]["qtde_funcionario"] : 0);
        $total_funcionarios = $functionarios->getTotalFuncionariosAtivosEmpresas();

        if ($total_empresa > 0) {
            return ($total_empresa > $total_funcionarios ? true : false);
        } else {
            return true;
        }
    }

    public function adicionar($id_tipo_negocio, $name, $social_razon, $activity, $nature, $cnpj, $cpf, $inss, $ie, $im, $cnae, $address, $district, $provincy, $pobox, $number, $city, $cep, $complement, $phone1, $phone2, $phone3, $ramal1, $ramal2, $ramal3, $email, $site, $observacao, $pt_reference, $qtde_funcionarios) {

        $consulta = "name = '{$name}'";

        $consulta .= ($cnpj != null ? " OR cnpj = '{$cnpj}'" : null);

        $consulta .= ($cpf != null ? " OR cpf = '{$cpf}'" : null);

        $consulta .= ($ie != null ? " OR ie = '{$ie}'" : null);

        $consulta .= ($im != null ? " OR im = '{$im}'" : null);

        $consulta .= ($inss != 0 ? " OR inss = '{$inss}'" : null);

        $result = $this->read($consulta);

        if (count($result) == 0) {

            $explode = explode('-', $id_tipo_negocio);
            list($id_tipo_negocio) = $explode;

            $saida = $this->insert(array(
                "tipo_negocio" => $id_tipo_negocio,
                "name" => $name,
                "social_razon" => $social_razon,
                "dt_ini" => date('Y-m-d'),
                "time_ini" => date("H:i:s"),
                "activity" => $activity,
                "nature" => $nature,
                "cnpj" => $cnpj,
                "cpf" => $cpf,
                "inss" => $inss,
                "ie" => $ie,
                "im" => $im,
                "cnae" => $cnae,
                "address" => $address,
                "district" => $district,
                "provincy" => $provincy,
                "pobox" => $pobox,
                "number" => $number,
                "city" => $city,
                "cep" => $cep,
                "complement" => $complement,
                "phone1" => $phone1,
                "phone2" => $phone2,
                "phone3" => $phone3,
                "ramal1" => $ramal1,
                "ramal2" => $ramal2,
                "ramal3" => $ramal3,
                "email" => $email,
                "site" => $site,
                "observacao" => $observacao,
                "pt_reference" => $pt_reference,
                "qtde_funcionario" => $qtde_funcionarios
            ));
            if ($saida) {
                $this->log("Insersão", $social_razon);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return false;
        }
    }

    public function edita($id, $id_tipo_negocio, $name, $social_razon, $activity, $nature, $cnpj, $cpf, $inss, $ie, $im, $cnae, $address, $district, $provincy, $pobox, $number, $city, $cep, $complement, $phone1, $phone2, $phone3, $ramal1, $ramal2, $ramal3, $email, $site, $status, $observacao, $pt_reference, $qtde_funcionarios) {

        $consulta = "(name = '{$name}'";

        $consulta .= ($cnpj != null ? " OR cnpj = '{$cnpj}'" : null);

        $consulta .= ($cpf != null ? " OR cpf = '{$cpf}'" : null);

        $consulta .= ($ie != null ? " OR ie = '{$ie}'" : null);

        $consulta .= ($im != null ? " OR im = '{$im}'" : null);

        $consulta .= ($inss != 0 ? " OR inss = '{$inss}'" : null);
        $consulta .= ") AND id != '{$id}'";

        $result = $this->read($consulta);

        if (count($result) == 0) {

            $explode = explode('-', $id_tipo_negocio);
            list($id_tipo_negocio) = $explode;

            $saida = $this->update(array(
                "name" => $name,
                "tipo_negocio" => $id_tipo_negocio,
                "social_razon" => $social_razon,
                "dt_update" => date("Y-m-d"),
                "time_update" => date("H:i:s"),
                "activity" => $activity,
                "nature" => $nature,
                "cnpj" => $cnpj,
                "cpf" => $cpf,
                "inss" => $inss,
                "ie" => $ie,
                "im" => $im,
                "cnae" => $cnae,
                "address" => $address,
                "district" => $district,
                "provincy" => $provincy,
                "pobox" => $pobox,
                "number" => $number,
                "city" => $city,
                "cep" => $cep,
                "complement" => $complement,
                "phone1" => $phone1,
                "phone2" => $phone2,
                "phone3" => $phone3,
                "ramal1" => $ramal1,
                "ramal2" => $ramal2,
                "ramal3" => $ramal3,
                "email" => $email,
                "site" => $site,
                "status" => $status,
                "observacao" => $observacao,
                "pt_reference" => $pt_reference,
                "qtde_funcionario" => $qtde_funcionarios
                    ), "id = '{$id}' ");
            if ($saida) {
                $this->log("Alteração", $social_razon);
                return $saida;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function remove($id) {
        $result = $this->read("id = '{$id}' ");
        $social_razon = $result[0]["social_razon"];

        $saida = $this->delete("id = '{$id}' ");
        if ($saida) {
            $this->log("Remoção", $social_razon);
            return $saida;
        } else {
            return FALSE;
        }
    }

    public function trocar_cliente($id) {

        $_SESSION["nome_cliente"] = "CRRH CONSULTORIA | " . $this->getClientName($id);
        $_SESSION["id_cliente"] = $id;

        return true;
    }

    public function search_Empresas($pesquisa, $funcao) {
        $result = $this->query("SELECT DISTINCT id, name, social_razon, cnpj, cpf FROM clients WHERE id LIKE '%{$pesquisa}%' OR name LIKE '%{$pesquisa}%' OR social_razon LIKE '%{$pesquisa}%' OR cnpj LIKE '%{$pesquisa}%' OR cpf LIKE '%{$pesquisa}%' ORDER BY name ASC");
        $mostra = null;
        $total = count($result);
        $mostra .= '<div style="float: left;">';

        for ($i = 0; $i <= $total - 1; $i++) {
            $mostra .= '<a href="javascript:' . $funcao . '(\'' . $result[$i]["id"] . '-' . $result[$i]["name"] . '\')">' . $result[$i]["id"] . '-' . $result[$i]["name"] . "</a><br />";
        }

        $mostra = ($total == 0 ? "Não encontrado nenhum registro" : $mostra);

        $mostra .= '</div><div style="float: right;">
                        <a href="javascript:fechar_pesquisa()"><i class="fa fa-window-close-o fa-lg"></i>
</a>
                    </div>';

        return $mostra;
    }

    public function search_Empresas_Boletos($pesquisa, $funcao) {
        $result = $this->query("SELECT DISTINCT id, name, social_razon, cnpj, cpf FROM clients WHERE id LIKE '%{$pesquisa}%' OR name LIKE '%{$pesquisa}%' OR social_razon LIKE '%{$pesquisa}%' OR cnpj LIKE '%{$pesquisa}%' OR cpf LIKE '%{$pesquisa}%' ORDER BY name ASC");
        $mostra = null;
        $total = count($result);
        $mostra .= '<div style="float: left;">';

        for ($i = 0; $i <= $total - 1; $i++) {
            if ($result[$i]["cpf"] != "999.999.999-99" && $result[$i]["cnpj"] != "99.999.999/9999-99") {
                $mostra .= '<a href="javascript:' . $funcao . '(\'' . $result[$i]["id"] . '-' . $result[$i]["name"] . '\')">' . $result[$i]["id"] . '-' . $result[$i]["name"] . "</a><br />";
            }
        }

        $mostra = ($total == 0 ? "Não encontrado nenhum registro" : $mostra);

        $mostra .= '</div><div style="float: right;">
                        <a href="javascript:fechar_pesquisa()"><i class="fa fa-window-close-o fa-lg"></i>
</a>
                    </div>';

        return $mostra;
    }

    public function pagination($page = null, $reg_page, $modulo) {
        $pagination = null;
        $out = null;
        if ($page != null) {

            $adjacents = 2;
            $prevlabel = "Anterior";
            $nextlabel = "Próximo";

            $per_page = $reg_page;
            $total_results = $this->total();
            $total_pages = ceil($total_results / $per_page);
            $tpages = $total_pages;

//            $reload = "/" . $modulo . "/index/page/";
            $reload = "javascript:setpage(";

            $out = '<ul class="pagination">';
            if ($page == 1) {

            } elseif ($page == 2) {
                $out .= "<li><a href=\"" . $reload . "1)" . "\">" . $prevlabel . "</a>\n</li>";
            } elseif ($page == 3) {
                $out .= "<li><a href=\"" . $reload . $page . ")" . "\">" . $prevlabel . "</a>\n</li>";
            } else {
                $out .= "<li><a href=\"" . $reload . ($page - 1) . ")\">" . $prevlabel . "</a>\n</li>";
                $out .= "<li><a href=\"" . $reload . "1" . ")\">" . "1" . "</a>\n</li>";
            }
            $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
            $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
            for ($i = $pmin; $i <= $pmax; $i++) {
                if ($i == $page) {
                    $out .= "<li class=\"active\"><a href=''>" . $i . "</a></li>\n";
                } elseif ($i == 1) {
                    $out .= "<li><a href=\"" . $reload . ")\">" . $i . "</a>\n</li>";
                } else {
                    $out .= "<li><a href=\"" . $reload . $i . ")\">" . $i . "</a>\n</li>";
                }
            }

            if ($page < ($tpages - $adjacents)) {
//                $out.= "<li><a href=\"" . $reload . $tpages . "\">" . $tpages . "</a>\n</li>";
            }
// next

            if ($page == $total_pages - 1) {
                $out .= "<li><a href=\"" . $reload . ($page + 1) . ")\">" . $nextlabel . "</a>\n</li>";
            } else if ($page < $tpages) {
                $out .= "<li><a href=\"" . $reload . $tpages . ")\">" . $tpages . "</a>\n</li>";
                $out .= "<li><a href=\"" . $reload . ($page + 1) . ")\">" . $nextlabel . "</a>\n</li>";
            } else {

            }
            $out .= "</ul>";
        }

        return $out;
    }

    public function relEmpresa_simples_pdf($empresa, $situacao) {
        if ($situacao == 1) {
            $where = " status = '1'";
        } else if ($situacao == 0) {
            $where = " status = '0'";
        } else if ($situacao == 2) {
            $where = " status <= '2'";
        }
        if ($empresa != NULL) {
            $explode = explode('-', $empresa);
            list($id_empresa) = $explode;

            if (is_numeric($id_empresa)) {
                $where .= " AND id = '$id_empresa'";
            }
        }
        $where .= "  ";
        $result = $this->read($where, NULL, NULL, "social_razon ASC");

        $html = '
            <style>
                body{
                    background-color:#FFFFFF;
                    font-size: 10px;
                    font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;
                }
                .panel{
                    margin-bottom:20px;
                    background-color:#fff;
                    border:1px solid transparent;
                    border-radius:4px;
                    -webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);
                    box-shadow:0 1px 1px rgba(0,0,0,.05)
                }
                .panel-heading{
                    padding:10px 15px;
                    border-bottom:1px solid transparent;
                    border-top-left-radius:3px;
                    border-top-right-radius:3px
                }
                .panel-body{
                    padding: 2px;
                    margin: 2px;
                }
                .panel-title{
                    margin-top:0;
                    margin-bottom:0;
                    font-size:16px;
                    color:inherit
                }
                .panel-footer{
                    padding:10px 15px;
                    background-color:#f5f5f5;
                    border-top:1px solid #ddd;
                    border-bottom-right-radius:3px;
                    border-bottom-left-radius:3px
                }
                .panel-primary{
                    border-color:#337ab7
                }
                table{
                    border-spacing:0;
                    border-collapse:collapse
                }
                b{
                    font-weight: bold;
                }

                table{
                    width: 100%;
                }

                .rodape_esq{
                    text-align: left;
                    border-top: 1px solid #000
                }

                .rodape_dir{
                    text-align: right;
                    border-top: 1px solid #000
                }
                .th_v{
                    border-top: 1px solid #000;
                    border-bottom: 1px solid #000;
                }
                .td{
                    padding-top: 10px;
                    padding-bottom: 10px;
                    border-bottom: 1px solid #000;
                }
            </style>
            <htmlpageheader name="myHTMLHeader" style="display:none;">
                <table>
                    <tr>
                        <td align="center"><img src="' . $_SERVER["DOCUMENT_ROOT"] . '/webfiles/img/' . $_SESSION["logo_report"] . '" /></td>
                    </tr>
                    <tr>
                        <td align="center"><h3><b>EMPRESAS CADASTRADAS</b></h3></td>
                    </tr>
                </table>
            </htmlpageheader>
            <htmlpagefooter name="myHTMLFooter">
                <table>
                    <tbody>
                        <tr style="" >
                            <td class="rodape_esq" >LM SERGURANÇA & INFORMÁTICA<br />' . ' Nº ' . ' ' . ' ' . '</td>
                            <td style="text-align: right; border-top: 1px solid #000" >' . date("d/m/Y H:m:i") . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pág.: {PAGENO} - {nb}</td>
                            <td class="rodape_dir" >Tel. ' . '</td>
                        </tr>
                    </tbody>
                </table>
            </htmlpagefooter>
            <div>
                <sethtmlpageheader name="myHTMLHeader" value="on" show-this-page="1" />
                <sethtmlpagefooter name="myHTMLFooter" show-this-page="1" value="on" />
                <div>
                    <table>
                        <tbody>
                            <tr>
                                <td colspan="5"><center><b>Total de empresas: ' . count($result) . '</b></center></td>
                            </tr>
                            <tr>
                                <th style="width: 2%" class="th_v" >#</th>
                                <th style="width: 58%" class="th_v" >Empresa</th>
                                <th style="width: 15%" class="th_v">CNPJ/CPF</th>
                                <th style="width: 15%" class="th_v">Telefone</th>
                                <th style="width: 5%" class="th_v">Situação</th>
                            </tr>
                            ';
        $c = 1;
        foreach ($result as $key => $v) {
            if ($v["status"] == 1) {
                $v["status"] = "ATIVO";
            } else {
                $v["status"] = "INATIVO";
            }

            if ($v["nature"] == 1) {
                $v["nature"] = "P. Jurídica";
                $v["CIC"] = $v["cnpj"];
            } else {
                $v["nature"] = "P. Física";
                $v["CIC"] = $v["cpf"];
            }

            $html .= '
                <tr>
                    <td class="td">' . $c . '</td>
                    <td class="td">' . $v["social_razon"] . '</td>
                    <td class="td">' . $v["CIC"] . '</td>
                    <td class="td">' . $v["phone1"] . '</td>
                    <td class="td">' . $v["status"] . '</td>
                </tr>
            ';
            $c++;
        }
        $c--;

        $html .= '
                <tr>
                    <td colspan="5"><center><b>Total de empresas: ' . $c . '</b></center></td>
                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        ';

        $mpdf = new mPDF('c', 'A4', '', '', 2, 1, 33, 10, 1, 1);
        $stylesheet = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/webfiles/css/bootstrap.min.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $stylesheet = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/webfiles/css/jquery-ui.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $stylesheet = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/webfiles/css/metisMenu.min.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $stylesheet = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/webfiles/css/sb-admin-2.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $stylesheet = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/webfiles/css/font-awesome.min.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $stylesheet = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/webfiles/css/dataTables.bootstrap.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $stylesheet = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/webfiles/css/dataTables.responsive.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $stylesheet = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/webfiles/css/aba.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html);
        $mpdf->Output($_SERVER["DOCUMENT_ROOT"] . '/temp/Empresas Cadastradas.pdf', "F");
    }

    public function relEmpresa_detalhado_pdf($empresa, $situacao) {

        if ($situacao == 1) {
            $where = " status = '1'";
        } else if ($situacao == 0) {
            $where = " status = '0'";
        } else if ($situacao == 2) {
            $where = " status <= '2'";
        }
        if ($empresa != NULL) {
            $explode = explode('-', $empresa);
            list($id_empresa) = $explode;

            if (is_numeric($id_empresa)) {
                $where .= " AND id = '$id_empresa'";
            }
        }
        $where .= "  ";
        $result = $this->read($where, NULL, NULL, "social_razon ASC");

        $html = '
            <style>
                body{
                    background-color:#FFFFFF;
                    font-size: 10px;
                    font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;
                }
                .panel{
                    margin-bottom:20px;
                    background-color:#fff;
                    border:1px solid transparent;
                    border-radius:4px;
                    -webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);
                    box-shadow:0 1px 1px rgba(0,0,0,.05)
                }
                .panel-heading{
                    padding:10px 15px;
                    border-bottom:1px solid transparent;
                    border-top-left-radius:3px;
                    border-top-right-radius:3px
                }
                .panel-body{
                    padding: 2px;
                    margin: 2px;
                }
                .panel-title{
                    margin-top:0;
                    margin-bottom:0;
                    font-size:16px;
                    color:inherit
                }
                .panel-footer{
                    padding:10px 15px;
                    background-color:#f5f5f5;
                    border-top:1px solid #ddd;
                    border-bottom-right-radius:3px;
                    border-bottom-left-radius:3px
                }
                .panel-primary{
                    border-color:#337ab7
                }
                table{
                    border-spacing:0;
                    border-collapse:collapse
                }
                b{
                    font-weight: bold;
                }

                table{
                    width: 100%;
                }

                .rodape_esq{
                    text-align: left;
                    border-top: 1px solid #000
                }

                .rodape_dir{
                    text-align: right;
                    border-top: 1px solid #000
                }
                .th_v{
                    border-top: 1px solid #000;
                    border-bottom: 1px solid #000;
                }
                .td{
                    padding-top: 10px;
                    padding-bottom: 10px;
                    border-bottom: 1px solid #000;
                }
            </style>
            <htmlpageheader name="myHTMLHeader" style="display:none;">
                <table>
                    <tr>
                        <td align="center"><img src="' . $_SERVER["DOCUMENT_ROOT"] . '/webfiles/img/' . $_SESSION["logo_report"] . '" /></td>
                    </tr>
                    <tr>
                        <td align="center"><h3><b>EMPRESAS CADASTRADAS</b></h3></td>
                    </tr>
                </table>
            </htmlpageheader>
            <htmlpagefooter name="myHTMLFooter">
                <table>
                    <tbody>
                        <tr style="" >
                            <td class="rodape_esq" >LM SERGURANÇA & INFORMÁTICA<br /></td>
                            <td style="text-align: right; border-top: 1px solid #000" >' . date("d/m/Y H:m:i") . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pág.: {PAGENO} - {nb}</td>
                            <td class="rodape_dir" >Tel. </td>
                        </tr>
                    </tbody>
                </table>
            </htmlpagefooter>
            <div>
                <sethtmlpageheader name="myHTMLHeader" value="on" show-this-page="1" />
                <sethtmlpagefooter name="myHTMLFooter" show-this-page="1" value="on" />
                <div>
                    <table>
                        <tbody>
                        <tr>
                            <td colspan="3"><center><b>Total de empresas: ' . count($result) . '</b></center></td>
                        </tr>
                            ';
        foreach ($result as $key => $v) {
            if ($v["status"] == 1) {
                $v["status"] = "ATIVO";
            } else {
                $v["status"] = "INATIVO";
            }

            if ($v["nature"] == 1) {
                $v["nature"] = "P. Jurídica";
                $v["CIC"] = $v["cnpj"];
            } else {
                $v["nature"] = "P. Física";
                $v["CIC"] = $v["cpf"];
            }


            $html .= '
                <tr>
                    <td><b>ID</b>: ' . $v["id"] . '</td>
                    <td><b>Nome Abrev.</b>: ' . $v["name"] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Razão Social</b>: ' . $v["social_razon"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Ramo Atividade</b>: ' . $v["activity"] . '</td>
                </tr>
                <tr>
                    <td><b>Data Implantação</b>: ' . $v["dt_ini"] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td><b>Situação</b>: ' . $v["status"] . '</td>
                </tr>
                <tr>
                    <td><b>Natureza</b>: ' . $v["nature"] . '</td>
                    <td><b>CPF/CNPJ</b>: ' . $v["CIC"] . '</td>
                    <td><b>Insc. Estadual<b>: ' . $v["ie"] . '</td>
                </tr>
                <tr>
                    <td><b>INSS/CEI</b>: ' . $v["inss"] . '</td>
                    <td><b>Insc. Municipal</b>: ' . $v["im"] . '</td>
                    <td><b>CNAE</b>: ' . $v["cnae"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Endereço</b>: ' . $v["address"] . '</td>
                </tr>
                <tr>
                    <td><b>Número</b>: ' . $v["number"] . '</td>
                    <td><b>Bairro</b>: ' . $v["district"] . '</td>
                    <td><b>Cidade</b>: ' . $v["city"] . '</td>
                </tr>
                <tr>
                    <td><b>Estado</b>: ' . $v["provincy"] . '</td>
                    <td colspan="2"><b>CEP</b>: ' . $v["cep"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Complemento</b>: ' . $v["complement"] . '</td>
                </tr>
                <tr>
                    <td><b>Telefone 1</b>: ' . $v["phone1"] . '</td>
                    <td><b>Telefone 2</b>: ' . $v["phone2"] . '</td>
                    <td><b>Telefone 3</b>: ' . $v["phone3"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><b>E-mail</b>: ' . $v["email"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Site</b>: ' . $v["site"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><br /><br /></td>
                </tr>
            ';
        }
        $html .= '
            <tr>
                    <td colspan="5"><center><b>Total de empresas: ' . count($result) . '</b></center></td>
            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        ';
        $mpdf = new mPDF('c', 'A4', '', '', 2, 1, 33, 10, 1, 1);
        $mpdf->WriteHTML($html);
        $mpdf->Output($_SERVER["DOCUMENT_ROOT"] . '/temp/Empresas Cadastradas.pdf', "F");
    }

    public function relEmpresa_simples_excel($empresa, $situacao) {
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Diego Rodrigues Costa")
                ->setLastModifiedBy("Diego Rodrigues Costa")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E4');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getStyle('A:E')->getFont()->setSize(7);

        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        $objDrawing->setPath($_SERVER["DOCUMENT_ROOT"] . '/webfiles/img/' . $_SESSION["logo_report"]);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setResizeProportional(TRUE);
//        $objDrawing->setWidth(600);
//        $objDrawing->setHeight(70);
//        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


        $objPHPExcel->getActiveSheet()->mergeCells('A6:D7');
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A6', 'EMPRESAS CADASTRADAS');

        $objPHPExcel->getActiveSheet()->getStyle('A9:D9')->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A9', 'Empresa');

        $objPHPExcel->getActiveSheet()->getStyle('B9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('B9', 'CNPJ/CPF');

        $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('C9', 'Telefone');

        $objPHPExcel->getActiveSheet()->getStyle('E9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('E9', 'Situação');

        $styleThickBrownBorderOutline = array(
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
                    'color' => array('argb' => '000000'),
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A9:D9')->applyFromArray($styleThickBrownBorderOutline);

        if ($situacao == 1) {
            $where = " status = '1'";
        } else if ($situacao == 0) {
            $where = " status = '0'";
        } else if ($situacao == 2) {
            $where = " status <= '2'";
        }
        if ($empresa != NULL) {
            $explode = explode('-', $empresa);
            list($id_empresa) = $explode;

            if (is_numeric($id_empresa)) {
                $where .= " AND id = '$id_empresa'";
            }
        }
        $where .= "  ";

        $result = $this->read($where, NULL, NULL, "social_razon ASC");
        $i = 10;
        $p = 51;
        foreach ($result as $key => $v) {
            if ($v["status"] == 1) {
                $v["status"] = "ATIVO";
            } else {
                $v["status"] = "INATIVO";
            }
            if ($v["nature"] == 1) {
                $v["nature"] = "P. Jurídica";
                $v["CIC"] = $v["cnpj"];
            } else {
                $v["nature"] = "P. Física";
                $v["CIC"] = $v["cpf"];
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $v["social_razon"]);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $v["CIC"]);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $v["phone1"]);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $v["status"]);
            $i++;

            if ($i == $p) {
                $objPHPExcel->getActiveSheet()->getStyle('A51:D51')->getFont()->setSize(10);
                $objPHPExcel->getActiveSheet()->getStyle('A51')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->setCellValue('A51', 'Empresa');

                $objPHPExcel->getActiveSheet()->getStyle('B51')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->setCellValue('B51', 'CNPJ');

                $objPHPExcel->getActiveSheet()->getStyle('C51')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->setCellValue('C51', 'Telefone');

                $objPHPExcel->getActiveSheet()->getStyle('D51')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->setCellValue('D51', 'Situação');
                $objPHPExcel->getActiveSheet()->getStyle('A51:D51')->applyFromArray($styleThickBrownBorderOutline);
                $i++;
                $p = $p + 50;
            }
        }



//        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->setTitle('Empresas Cadastradas');
        $objPHPExcel->setActiveSheetIndex(0);
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename = "01simple.xlsx"');
//        header('Cache-Control: max-age = 0');
//        header('Cache-Control: max-age = 1');
//        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($_SERVER["DOCUMENT_ROOT"] . '/temp/Empresas Cadastradas.xlsx');
    }

    public function relEmpresa_detalhado_excel($empresa, $situacao) {
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Diego Rodrigues Costa")
                ->setLastModifiedBy("Diego Rodrigues Costa")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:D4');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getStyle('A:Z')->getFont()->setSize(7);

        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        $objDrawing->setPath($_SERVER["DOCUMENT_ROOT"] . '/webfiles/img/' . $_SESSION["logo_report"]);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setResizeProportional(TRUE);
        $objDrawing->setWidth(140);
//        $objDrawing->setHeight(70);
//        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


        $objPHPExcel->getActiveSheet()->mergeCells('A6:D7');
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A6', 'EMPRESAS CADASTRADAS');

        $objPHPExcel->getActiveSheet()->getStyle('A9:Z9')->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A9:Z9')->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->setCellValue('A9', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B9', 'Nome Abreviado');
        $objPHPExcel->getActiveSheet()->setCellValue('C9', 'Razão Social');
        $objPHPExcel->getActiveSheet()->setCellValue('D9', 'Ramo/Atividade');
        $objPHPExcel->getActiveSheet()->setCellValue('E9', 'Data Implantação');
        $objPHPExcel->getActiveSheet()->setCellValue('G9', 'Situação');
        $objPHPExcel->getActiveSheet()->setCellValue('H9', 'Natureza');
        $objPHPExcel->getActiveSheet()->setCellValue('I9', 'CPF/CNPJ');
        $objPHPExcel->getActiveSheet()->setCellValue('J9', 'Insc. Estadual');
        $objPHPExcel->getActiveSheet()->setCellValue('K9', 'Insc. Municipal');
        $objPHPExcel->getActiveSheet()->setCellValue('L9', 'Insc. INSS/CEI');
        $objPHPExcel->getActiveSheet()->setCellValue('M9', 'CNAE');
        $objPHPExcel->getActiveSheet()->setCellValue('N9', 'Endereço');
        $objPHPExcel->getActiveSheet()->setCellValue('O9', 'Número');
        $objPHPExcel->getActiveSheet()->setCellValue('P9', 'Bairro');
        $objPHPExcel->getActiveSheet()->setCellValue('Q9', 'Cidade');
        $objPHPExcel->getActiveSheet()->setCellValue('R9', 'Estado');
        $objPHPExcel->getActiveSheet()->setCellValue('S9', 'CEP');
        $objPHPExcel->getActiveSheet()->setCellValue('T9', 'Caixa Postal');
        $objPHPExcel->getActiveSheet()->setCellValue('U9', 'Complemento');
        $objPHPExcel->getActiveSheet()->setCellValue('V9', 'Telefone 1');
        $objPHPExcel->getActiveSheet()->setCellValue('W9', 'Telefone 2');
        $objPHPExcel->getActiveSheet()->setCellValue('X9', 'Telefone 3');
        $objPHPExcel->getActiveSheet()->setCellValue('Y9', 'Email');
        $objPHPExcel->getActiveSheet()->setCellValue('Z9', 'Site');

        $styleThickBrownBorderOutline = array(
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
                    'color' => array('argb' => '000000'),
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_DASHDOT,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A9:AA9')->applyFromArray($styleThickBrownBorderOutline);

        if ($situacao == 1) {
            $where = " status = '1'";
        } else if ($situacao == 0) {
            $where = " status = '0'";
        } else if ($situacao == 2) {
            $where = " status <= '2'";
        }
        if ($empresa != NULL) {
            $explode = explode('-', $empresa);
            list($id_empresa) = $explode;

            if (is_numeric($id_empresa)) {
                $where .= " AND id = '$id_empresa'";
            }
        }
        $where .= "  ";
        $result = $this->read($where, NULL, NULL, "social_razon ASC");
        $i = 10;
        $p = 51;
        foreach ($result as $key => $v) {
            if ($v["status"] == 1) {
                $v["status"] = "ATIVO";
            } else {
                $v["status"] = "INATIVO";
            }
            if ($v["nature"] == 1) {
                $v["nature"] = "P. Jurídica";
                $v["CIC"] = $v["cnpj"];
            } else {
                $v["nature"] = "P. Física";
                $v["CIC"] = $v["cpf"];
            }
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $v["id"]);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $v["name"]);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $v["social_razon"]);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $v["activity"]);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $v["dt_ini"]);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $v["status"]);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $v["nature"]);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $v["CIC"]);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $v["ie"]);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $v["im"]);
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $v["inss"]);
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $v["cnae"]);
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $v["address"]);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $v["number"]);
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $v["district"]);
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $v["city"]);
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $v["provincy"]);
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $v["cep"]);
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $v["pobox"]);
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $v["complement"]);
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $v["phone1"]);
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $v["phone2"]);
            $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $v["phone3"]);
            $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $v["email"]);
            $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $v["site"]);
            $i++;
        }
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->setTitle('Empresas Cadastradas');
        $objPHPExcel->setActiveSheetIndex(0);
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename = "01simple.xlsx"');
//        header('Cache-Control: max-age = 0');
//        header('Cache-Control: max-age = 1');
//        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($_SERVER["DOCUMENT_ROOT"] . '/temp/Empresas Cadastradas.xlsx');
    }

    public function relEmpresa_simples_tela($empresa, $situacao) {
        if ($situacao == 1) {
            $where = " status = '1' ";
        } else if ($situacao == 0) {
            $where = " status = '0' ";
        } else if ($situacao == 2) {
            $where = " status <= '2' ";
        }

        if ($empresa != NULL) {
            $explode = explode('-', $empresa);
            list($id_empresa) = $explode;

            if (is_numeric($id_empresa)) {
                $where .= " AND id = '$id_empresa'";
            }
        }

        $where .= "  ";

        $result = $this->read($where, NULL, NULL, "social_razon ASC");

        $html = '<div class="panel panel-default">
            <div class="panel-body" >
                <div class="row">
                    <div class="col-lg-12">
                      <table>
                        <tbody>
                            <tr>
                                <td colspan="5"><center><b>Total de empresas: ' . count($result) . '</b></center></td>
                            </tr>
                            <tr>
                                <th style="width: 2%">#</th>
                                <th style="width: 58%">Empresa</th>
                                <th style="width: 15%">CNPJ/CPF</th>
                                <th style="width: 15%">Telefone</th>
                                <th style="width: 5%">Situação</th>
                            </tr>
                            ';
        $c = 1;
        foreach ($result as $key => $v) {
            if ($v["status"] == 1) {
                $v["status"] = "ATIVO";
            } else {
                $v["status"] = "INATIVO";
            }
            if ($v["nature"] == 1) {
                $v["nature"] = "P. Jurídica";
                $v["CIC"] = $v["cnpj"];
            } else {
                $v["nature"] = "P. Física";
                $v["CIC"] = $v["cpf"];
            }

            $html .= '
                <tr>
                    <td>' . $c . '</td>
                    <td>' . $v["social_razon"] . '</td>
                    <td>' . $v["CIC"] . '</td>
                    <td>' . $v["phone1"] . '</td>
                    <td>' . $v["status"] . '</td>
                </tr>
            ';
            $c++;
        }
        $c--;
        $html .= '
                <tr>
                    <td colspan="5"><center><b>Total de empresas: ' . $c . '</b></center></td>
                </tr>
            ';
        $html .= '
                        </tbody>
                    </table>
                                        </div>
                </div>
            </div>
        </div>
        ';
        return $html;
    }

    public function relEmpresa_detalhado_tela($empresa, $situacao) {
        if ($situacao == 1) {
            $where = " status = '1'";
        } else if ($situacao == 0) {
            $where = " status = '0'";
        } else if ($situacao == 2) {
            $where = " status <= '2'";
        }
        if ($empresa != NULL) {
            $explode = explode('-', $empresa);
            list($id_empresa) = $explode;

            if (is_numeric($id_empresa)) {
                $where .= " AND id = '$id_empresa'";
            }
        }
        $where .= "  ";
        $result = $this->read($where, NULL, NULL, "social_razon ASC");

        $html = '<div class="panel panel-default">
            <div class="panel-body" >
                <div class="row">
                    <div class="col-lg-12">
                      <table>
                        <tbody>
                        <tr>
                            <td colspan="3"><center><b>Total de empresas: ' . count($result) . '</b></center></td>
                        </tr>
                            ';
        foreach ($result as $key => $v) {
            if ($v["status"] == 1) {
                $v["status"] = "ATIVO";
            } else {
                $v["status"] = "INATIVO";
            }

            if ($v["nature"] == 1) {
                $v["nature"] = "P. Jurídica";
                $v["CIC"] = $v["cnpj"];
            } else {
                $v["nature"] = "P. Física";
                $v["CIC"] = $v["cpf"];
            }
            $html .= '
                <tr>
                    <td><b>ID</b>: ' . $v["id"] . '</td>
                    <td><b>Nome Abrev.</b>: ' . $v["name"] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Razão Social</b>: ' . $v["social_razon"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Ramo Atividade</b>: ' . $v["activity"] . '</td>
                </tr>
                <tr>
                    <td><b>Data Implantação</b>: ' . $v["dt_ini"] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td><b>Situação</b>: ' . $v["status"] . '</td>
                </tr>
                <tr>
                    <td><b>Natureza</b>: ' . $v["nature"] . '</td>
                    <td><b>CPF/CNPJ</b>: ' . $v["CIC"] . '</td>
                    <td><b>Insc. Estadual<b>/: ' . $v["ie"] . '</td>
                </tr>
                <tr>
                    <td><b>INSS/CEI</b>: ' . $v["inss"] . '</td>
                    <td><b>Insc. Municipal</b>: ' . $v["im"] . '</td>
                    <td><b>CNAE</b>: ' . $v["cnae"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Endereço</b>: ' . $v["address"] . '</td>
                </tr>
                <tr>
                    <td><b>Número</b>: ' . $v["number"] . '</td>
                    <td><b>Bairro</b>: ' . $v["district"] . '</td>
                    <td><b>Cidade</b>: ' . $v["city"] . '</td>
                </tr>
                <tr>
                    <td><b>Estado</b>: ' . $v["provincy"] . '</td>
                    <td colspan="2"><b>CEP</b>: ' . $v["cep"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Complemento</b>: ' . $v["complement"] . '</td>
                </tr>
                <tr>
                    <td><b>Telefone 1</b>: ' . $v["phone1"] . '</td>
                    <td><b>Telefone 2</b>: ' . $v["phone2"] . '</td>
                    <td><b>Telefone 3</b>: ' . $v["phone3"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><b>E-mail</b>: ' . $v["email"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Site</b>: ' . $v["site"] . '</td>
                </tr>
                <tr>
                    <td colspan="3"><br /><br /></td>
                </tr>
            ';
        }
        $html .= '
            <tr>
                    <td colspan="5"><center><b>Total de empresas: ' . count($result) . '</b></center></td>
            </tr>
                        </tbody>
                    </table>
                                        </div>
                </div>
            </div>
        </div>
        ';
        return $html;
    }

}
