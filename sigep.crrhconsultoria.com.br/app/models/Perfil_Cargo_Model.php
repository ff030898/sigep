<?php

class Perfil_Cargo_Model extends Model {

    public $_tabela = "perfil_cargo";

    public function __construct() {
        parent::__construct();
    }

    public function get($cond = "*") {
        $id_cliente = $_SESSION["id_cliente"];

        if ($cond == "1") {
            $result = $this->query("SELECT perfil_cargo.id, cargo.tipo, cargo.descricao, cargo.subtipo, perfil_cargo.status FROM `perfil_cargo` INNER JOIN cargo ON (cargo.id = perfil_cargo.id_cargo) WHERE perfil_cargo.id_cliente = '{$id_cliente}' AND status = '1' ORDER BY cargo.tipo ASC, cargo.descricao ASC, cargo.subtipo ASC ");
        } else {
            $result = $this->query("SELECT perfil_cargo.id, cargo.tipo, cargo.descricao, cargo.subtipo, perfil_cargo.status FROM `perfil_cargo` INNER JOIN cargo ON (cargo.id = perfil_cargo.id_cargo) WHERE perfil_cargo.id_cliente = '{$id_cliente}' ORDER BY cargo.tipo ASC, cargo.descricao ASC, cargo.subtipo ASC ");
        }

        foreach ($result as $key => $value) {
            if ($value["status"] == "1") {
                $result[$key]["status"] = "ATIVO";
            } else if ($value["status"] == "0") {
                $result[$key]["status"] = "INATIVO";
            }

            if ($result[$key]["tipo"] == "1") {
                $tipo = "AUXILIAR";
            } else if ($result[$key]["tipo"] == "2") {
                $tipo = "ASSISTENTE";
            } else if ($result[$key]["tipo"] == "3") {
                $tipo = "LIDER";
            } else if ($result[$key]["tipo"] == "4") {
                $tipo = "SUPERVISOR";
            } else if ($result[$key]["tipo"] == "5") {
                $tipo = "ANALISTA";
            } else if ($result[$key]["tipo"] == "6") {
                $tipo = "COORDENADOR";
            } else if ($result[$key]["tipo"] == "7") {
                $tipo = "GERENTE";
            } else if ($result[$key]["tipo"] == "8") {
                $tipo = "SUPERINTENDENTE";
            } else if ($result[$key]["tipo"] == "9") {
                $tipo = "DIRETOR";
            }

            if ($result[$key]["subtipo"] == "1") {
                $subtipo = "JUNIOR";
            } else if ($result[$key]["subtipo"] == "2") {
                $subtipo = "PLENO";
            } else if ($result[$key]["subtipo"] == "3") {
                $subtipo = "SENIOR";
            }

            $result[$key]["descricao_formatado"] = $result[$key]["descricao"] . " " . $subtipo;
        }

        return $result;
    }

    public function getById($id_perfil) {
        $id_cliente = $_SESSION["id_cliente"];

        $cargo = new Cargo_Model();
        $requisitos = new Perfil_Requisitos_Model();
        $fonte_interna = new Perfil_Fonte_Interna_Model();
        $perfil_cargo_indicadores = new Perfil_Cargo_Indicadores_Model();

        $result = $this->read("id = '{$id_perfil}' AND id_cliente = '{$id_cliente}'");

        $id_cargo = $result[0]["id_cargo"];

        $result[0]["descricao_formatado"] = $cargo->getDescricaoCargo($id_cargo);
        $result[0]["requisitos"] = $requisitos->get($id_cliente, $id_cargo, $id_perfil);
        $result[0]["fonte_interna"] = $fonte_interna->get($id_cliente, $id_cargo, $id_perfil);
        $result[0]["indicadores"] = $perfil_cargo_indicadores->get($id_cliente, $id_cargo, $id_perfil);

        return $result[0];
    }

    public function getIdByCargo($id_cliente, $id_cargo) {
        $result = $this->read("id_cliente = '{$id_cliente}' AND id_cargo = '{$id_cargo}'", null, null, null, "id");
        if (count($result)) {
            return $result[0]["id"];
        } else {
            return 0;
        }
    }

    public function adicionar($id_cargo, $elaboracao, $aprovacao, $qualificacao_basica, $sumario, $cargo_interno, $tempo, $tipo, $conceito, $advertencia, $fonte_externa, $indicador, $sub, $valor) {

        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("id_cargo = '{$id_cargo}' AND id_cliente = '{$id_cliente}'");

        if (!count($result)) {

            $id_perfil = $this->insert(array(
                "id_cliente" => $id_cliente,
                "id_cargo" => $id_cargo,
                "elaboracao" => $elaboracao,
                "aprovacao" => $aprovacao,
                "qualificacao_basica" => $qualificacao_basica,
                "sumario" => $sumario,
                "conceito_avaliacao" => $conceito,
                "advertencia" => $advertencia,
                "fonte_externa" => $fonte_externa,
                "dt_ini" => date('Y-m-d'),
                "hora_ini" => date('H:i:s'),
                "user_ini" => $_SESSION["user_name"]
                    ), "sadsa");
            if (is_numeric($id_perfil)) {
                $perfil_fonte_interna = new Perfil_Fonte_Interna_Model();
                $perfil_fonte_interna->adicionar($id_cliente, $id_cargo, $id_perfil, $cargo_interno, $tempo, $tipo);

                $perfil_cargo_indicadores = new Perfil_Cargo_Indicadores_Model();
                $perfil_cargo_indicadores->adicionar($id_cliente, $id_cargo, $id_perfil, $indicador, $sub, $valor);

                $this->log("Insersão", "ID Cliente: " . $id_cliente . " ID Cargo: " . $id_cargo . " ID Perfil: " . $id_perfil);
                return $id_perfil;
            } else {
                return FALSE;
            }
        } else {
            return false;
        }
    }

    public function editar($id_perfil, $id_cargo, $elaboracao, $aprovacao, $qualificacao_basica, $sumario, $cargo_interno, $tempo, $tipo, $conceito, $advertencia, $fonte_externa, $indicador, $sub, $valor) {

        $id_cliente = $_SESSION["id_cliente"];

        $this->update(array(
            "elaboracao" => $elaboracao,
            "aprovacao" => $aprovacao,
            "qualificacao_basica" => $qualificacao_basica,
            "sumario" => $sumario,
            "conceito_avaliacao" => $conceito,
            "advertencia" => $advertencia,
            "fonte_externa" => $fonte_externa,
            "dt_update" => date('Y-m-d'),
            "hora_update" => date('H:i:s'),
            "user_update" => $_SESSION["user_name"]
                ), "id = '{$id_perfil}'");

        if (is_numeric($id_perfil)) {

            $perfil_fonte_interna = new Perfil_Fonte_Interna_Model();
            $perfil_fonte_interna->adicionar($id_cliente, $id_cargo, $id_perfil, $cargo_interno, $tempo, $tipo);

            $perfil_cargo_indicadores = new Perfil_Cargo_Indicadores_Model();
            $perfil_cargo_indicadores->adicionar($id_cliente, $id_cargo, $id_perfil, $indicador, $sub, $valor);

            $this->log("Alteração", "ID Cliente: " . $id_cliente . " ID Cargo: " . $id_cargo . " ID Perfil: " . $id_perfil);
            return $id_perfil;
        } else {
            return FALSE;
        }
    }

    public function remover($id_perfil) {

        $saida = $this->delete("id = '{$id_perfil}'");

        if (is_numeric($saida)) {
            $this->log("Remoção", $id_perfil);
            return $saida;
        } else {
            return FALSE;
        }
    }

    public function rel_tela($id_perfil) {

        $perfil = $this->getById($id_perfil);
        $cg = new Cargo_Model();
        $cbo = new CBO_Model();
        $indi_quantitativos = new Indicadores_Quant_Model();
        $requisitos = new Requisitos_Model();
        $perfil_requisitos = new Perfil_Requisitos_Model();
        $texto = new textoHelper();
        $per_req = $perfil_requisitos->getByID($perfil["id_cliente"], $perfil["id_cargo"], $id_perfil);
        $cargo = $cg->getCargoByID($perfil["id_cargo"]);
        $titulo_cbo = $cbo->getCBOByID($cargo["id_cbo"]);
        $nat_ocupacional = new Nat_Ocupacional_Model();
        $requisitos_cargo = new Requisitos_Cargos_Model();

        $perfil_cargo_indicadores = new Perfil_Cargo_Indicadores_Model();

        $descricao_detalhada = $perfil_cargo_indicadores->getbyTipo($perfil["id_cliente"], $perfil["id_cargo"], $id_perfil, 5);

        $html = '
                        <div class="col-lg-12">
                            <table class="table" style="border: solid 1px #000; width: 100%;">
                                <tr style="border: solid 1px #000;">
                                    <td style="border: solid 1px #000;" colspan="4">
                                        <table class="table">
                                        <tr>
                                            <td colspan="3" style="border: solid 1px #000;" >
                                                <label>Cargo: </label> ' . $perfil["descricao_formatado"] . '<br />
                                                <label>CBO: </label> ' . $titulo_cbo["codigo"] . "-" . strtoupper($titulo_cbo["titulo"]) . '<br />
                                                <label>Elaboração: </label> ' . $perfil["elaboracao"] . '<br />
                                                <label>Aprovação: </label> ' . $perfil["aprovacao"] . '
                                            </td>
                                            <td style="border: solid 1px #000;">
                                                <label>DC nº 01 - Rev. 00</label><br />
                                                <label>Data:</label> ' . date('d/m/Y') . '
                                            </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr style="border: solid 1px #000;">
                                    <td rowspan="3" style="border: solid 1px #000;" class="text-center"><label>CARACTERIZAÇÃO DO CARGO</label></td>
                                    <td style="border: solid 1px #000;" colspan="3">
                                        <label>CARREIRA ATUAL: </label> ' . $nat_ocupacional->getNat_OcupacionalByID($cargo["id_nat_ocupacional"])["descricao"] . '
                                        <label>CLASSE VERTICAL: </label> ' . $cargo["tipo_descricao"] . '&nbsp;&nbsp;&nbsp; <label>CÓDIGO: </label> ' . $cargo["tipo"] . '<br />
                                    </td>
                                </tr>
                                <tr style="border: solid 1px #000;">
                                    <td colspan="3" style="border: solid 1px #000;" class="text-justify ">
                                        <label>QUALIFICAÇÃO BÁSICA:</label><br />
                                        ' . $perfil["qualificacao_basica"] . '
                                    </td>
                                </tr>
                                <tr style="border: solid 1px #000;">
                                    <td colspan="3" style="border: solid 1px #000;" class="text-justify ">
                                        <label>SUMÁRIO DAS DESCRIÇÕES:</label><br />
                                        ' . $perfil["sumario"] . '
                                    </td>
                                </tr>
                            ';
        foreach ($this->monta_estrutura_perfil($id_perfil) as $value) {
            if ($value["id_indicadores_quant_tipo"] == 5) {
                $html .= '
                <tr style="border: solid 1px #000;">
                    <td style="border: solid 1px #000;" class="text-center" colspan="4"><b>COMPETÊNCIAS: ' . $value["descricao"] . '</b></td>
                </tr>
                ';
                foreach ($value["sub"] as $value_sub) {
                    $html .= '
                    <tr style="border: solid 1px #000;">
                    <td style="border: solid 1px #000;" class="text-center" colspan="4"><b>INDICADOR: ' . $value_sub["descricao"] . '</b></td>
                </tr>
                    <tr style="border: solid 1px #000;">
                        <td style="border: solid 1px #000;" class="text-center">
                            <label><b>' . $value_sub["descricao"] . '</b></label>
                        </td>
                        <td style="border: solid 1px #000;" class="text-justify" colspan="3">
                ';
                    $i = 1;
                    foreach ($value_sub["descricao_indicador"] as $value_indicador) {
                        $html .= $i . ". " . $value_indicador["descricao"] . "<br /><br />";
                        $i++;
                    }
                    $html .= '
                        </td>
                    </tr>
                ';
                }
            } else {
                $html .= '
                    <tr style="border: solid 1px #000;">
                        <td style="border: solid 1px #000;" class="text-center" colspan="4"><b>COMPETÊNCIAS: ' . $value["descricao"] . '</b></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table class="table" style="border: solid 1px #000; width: 100%;">
                                <tr>
                ';
                foreach ($value["sub"] as $value_sub) {

                    $html .= '
                        <td  style="border: solid 1px #000;" class="text-center"><label>INDICADOR: ' . $value_sub["descricao"] . '</label></td>
                    ';
                }
                $html .= '
                                </tr>
                                <tr>
                                    ';
                foreach ($value["sub"] as $value_sub) {
                    $html .= '
                        <td style="border-right: solid 1px #000; vertical-align: text-top;" class="text-justify ">
                        ';
                    $i = 1;
                    foreach ($value_sub["descricao_indicador"] as $value_indicador) {
                        $html .= $i . '. ' . $value_indicador["descricao"] . "<br /><br />";
                        $i++;
                    }

                    $html .= '
                        </td>
                    ';
                }
                $html .= '
                                </tr>
                            </table>
                        </td>
                    </tr>
                ';
            }
        }

        $html .= '
                                <tr style="border: solid 1px #000;">
                                    <td colspan="4" style="border: solid 1px #000;" class="text-center"><label>REQUISITOS DE RECRUTAMENTO E SELEÇÃO</label></td>
                                </tr>
                                <tr style="border: solid 1px #000;">
                                    <td style="border: solid 1px #000;" class="text-center">
                                        <label>CARACTERÍSTICAS EXIGIDAS</label>
                                    </td>
                                    <td style="border: solid 1px #000;" class="text-justify " colspan="3">
        ';
        $i = 1;
        foreach ($requisitos_cargo->getByIdCargo($perfil["id_cargo"]) as $value) {
            $html .= $i . ". " . $value["descricao"] . "<br />";
            $i++;
        }
//        foreach ($per_req as $key => $value) {
//            $r = $requisitos->getByID($value["id"]);
//            $html .= $i . ". " . $value["descricao"] . "<br />";
//            $i++;
//        }
        $html .= '
                                    </td>
                                </tr>
                                <tr style="border: solid 1px #000;">
                                    <td style="border: solid 1px #000;" class="text-center">
                                        <label>CRITÉRIOS PARA INGRESSO NO CARGO</label>
                                    </td>
                                    <td style="border: solid 1px #000;" class="text-justify " colspan="3">
                                        <label>FONTE INTERNA: </label>
        ';
        if (count($perfil["fonte_interna"])) {

            foreach ($perfil["fonte_interna"] as $key => $value) {

                $html .= "EXPERIÊNCIA MÍNIMA DE (";
                $html .= $value["tempo"] . ") ";
                $html .= $texto->convert_number_to_words($value["tempo"]);

                if ($value["tempo"] <= 1) {
                    if ($value["tipo"] == 1) {
                        $html .= " ANO NO CARGO DE ";
                    } else {
                        $html .= " MêS NO CARGO DE ";
                    }
                } else {
                    if ($value["tipo"] == 1) {
                        $html .= " ANOS NO CARGO DE ";
                    } else {
                        $html .= " MESES NO CARGO DE ";
                    }
                }


                $html .= $cg->getDescricaoCargo($value["id_cargo_interno"]) . ". ";
            }
        }

        $html .= " Ter atingido o conceito " . $perfil["conceito_avaliacao"] . " NA ÚLTIMA AVALIAÇÃO DE DESEMPENHO. NÃO TER ADVERTÊNCIA ";

        if ($perfil["advertencia"] == 1) {
            $html .= "GRAVE";
        } else {
            $html .= "LEVE";
        }
        $html .= " NOS ÚLTIMOS 90 DIAS. <br /><br />";

        $html .= "<label>FONTE EXTERNA: </label> " . $perfil["fonte_externa"] . "<br /><br />";

        $html .= "<label>QUALIFICAÇÃO BÁSICA: </label> " . $perfil["qualificacao_basica"] . "<br /><br />";

        $html .= '
                                    </td>
                                </tr>
                            </table>
                        </div>


        ';

        return $html;
    }

    public function rel_tela2($id_perfil) {

        $perfil = $this->getById($id_perfil);
        $cg = new Cargo_Model();
        $cbo = new CBO_Model();
        $indi_quantitativos = new Indicadores_Quant_Model();
        $requisitos = new Requisitos_Model();
        $perfil_requisitos = new Perfil_Requisitos_Model();
        $texto = new textoHelper();
        $per_req = $perfil_requisitos->getByID($perfil["id_cliente"], $perfil["id_cargo"], $id_perfil);
        $cargo = $cg->getCargoByID($perfil["id_cargo"]);
        $titulo_cbo = $cbo->getCBOByID($cargo["id_cbo"]);
        $nat_ocupacional = new Nat_Ocupacional_Model();

        $perfil_cargo_indicadores = new Perfil_Cargo_Indicadores_Model();

        $descricao_detalhada = $perfil_cargo_indicadores->getbyTipo($perfil["id_cliente"], $perfil["id_cargo"], $id_perfil, 5);

        $html = '


<table border="1" width="100%" style="border-collapse: collapse;">
    <tr>
        <td colspan="3" class="text-center">
            <b>2 - COMPETÊNCIAS TÉCNICAS</b>
        </td>
    </tr>
    <tr>
        <td class="text-center">
            <b>NOÇÕES</b>
        </td>
        <td class="text-center">
            <b>CONHECIMENTOS</b>
        </td>
        <td class="text-center">
            <b>DOMÍNIO</b>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
    </tr>
</table>
<table border="1" width="100%" style="border-collapse: collapse;">
    <tr>
        <td colspan="4" class="text-center">
            <b>3 - COMPETÊNCIAS DE DESENVOLVIMENTO</b>
        </td>
    </tr>
    <tr>
        <td><b>FORMAÇÃO PROFISSIONAL</b></td>
        <td><b>APERFEIÇOAMENTO</b></td>
        <td><b>NORMATIVOS</b></td>
        <td><b>COMPORTAMENTAIS</b></td>
    </tr>
    <tr>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
    </tr>
</table>
<table border="1" width="100%" style="border-collapse: collapse;">
    <tr>
        <td colspan="4" class="text-center">
            <b>4 - COMPETÊNCIAS ORGANIZACIONAIS</b>
        </td>
    </tr>
    <tr>
        <td><b>RESULTADOS</b></td>
        <td><b>VALORES</b></td>
        <td><b>MERCADO IMAGEM</b></td>
        <td><b>PROCESSOS, CONFORMIDADE E QUALIDADE</b></td>
    </tr>
    <tr>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
        <td style="vertical-align: text-top;">
        ';
        $i = 1;

        $html .= '
        </td>
    </tr>
</table>
<table border="1" width="100%" style="border-collapse: collapse;">
    <tr>
        <td colspan="2" class="text-center">
            <b>COMPETÊNCIAS ORGANIZACIONAIS</b>
        </td>
    </tr>
    <tr>
        <td class="text-center" style="vertical-align: text-top;">
            <b>CARACTERÍSTICAS EXIGIDAS</b>
        </td>
        <td class="text-justify " style="vertical-align: text-top;">
        ';
        $i = 1;
        foreach ($per_req as $key => $value) {
            $r = $requisitos->getByID($value["id"]);
            $html .= "<b>" . $i . "</b>" . ". " . $r["descricao"] . "<br />";
            $i++;
        }
        $html .= '
        </td>
    </tr>
    <tr>
        <td class="text-center" style="vertical-align: text-top;">
            <b>CRITÉRIOS PARA INGRESSO NO CARGO</b>
        </td>
        <td class="text-justify " style="vertical-align: text-top;">
            <b>FONTE INTERNA: </b>
             ';
        if (count($perfil["fonte_interna"])) {

            foreach ($perfil["fonte_interna"] as $key => $value) {

                $html .= "EXPERIÊNCIA MÍNIMA DE (";
                $html .= $value["tempo"] . ") ";
                $html .= $texto->convert_number_to_words($value["tempo"]);

                if ($value["tempo"] <= 1) {
                    if ($value["tipo"] == 1) {
                        $html .= " ANO NO CARGO DE ";
                    } else {
                        $html .= " MêS NO CARGO DE ";
                    }
                } else {
                    if ($value["tipo"] == 1) {
                        $html .= " ANOS NO CARGO DE ";
                    } else {
                        $html .= " MESES NO CARGO DE ";
                    }
                }


                $html .= $cg->getDescricaoCargo($value["id_cargo_interno"]) . ". ";
            }
        }

        $html .= " Ter atingido o conceito " . $perfil["conceito_avaliacao"] . " NA ÚLTIMA AVALIAÇÃO DE DESEMPENHO. NÃO TER ADVERTÊNCIA ";

        if ($perfil["advertencia"] == 1) {
            $html .= "GRAVE";
        } else {
            $html .= "LEVE";
        }
        $html .= " NOS ÚLTIMOS 90 DIAS. <br /><br />";

        $html .= "<b>FONTE EXTERNA: </b> " . $perfil["fonte_externa"] . "<br /><br />";

        $html .= "<b>CONDIÇÕES GERAIS: </b><br /><br />";

        $html .= '
        </td>
    </tr>
</table>
        ';

        return $html;
    }

    public function rel_pdf($id_perfil) {

        $perfil = $this->getById($id_perfil);
        $cg = new Cargo_Model();
        $cbo = new CBO_Model();
        $indi_quantitativos = new Indicadores_Quant_Model();
        $requisitos = new Requisitos_Model();
        $perfil_requisitos = new Perfil_Requisitos_Model();
        $texto = new textoHelper();
        $per_req = $perfil_requisitos->getByID($perfil["id_cliente"], $perfil["id_cargo"], $id_perfil);
        $cargo = $cg->getCargoByID($perfil["id_cargo"]);
        $titulo_cbo = $cbo->getCBOByID($cargo["id_cbo"]);
        $nat_ocupacional = new Nat_Ocupacional_Model();

        $perfil_cargo_indicadores = new Perfil_Cargo_Indicadores_Model();

        $descricao_detalhada = $perfil_cargo_indicadores->getbyTipo($perfil["id_cliente"], $perfil["id_cargo"], $id_perfil, 5);

        $html = '
            <style>
                body{
                    background-color:#FFFFFF;
                    font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;
                }
                b{
                    font-weight: bold;
                }
                table tr td{
                    padding: 5px;
                }
                .header{
                    margin-bottom:300px;
                }
                .rodape_esq{
                    text-align: left;
                    border-top: 1px solid #000
                }
                .rodape_dir{
                    text-align: right;
                    border-top: 1px solid #000
                }
                .titulo{
                    background-color: #d1d1e0;
                    font-size: 20px;
                    font-weight: bold;
                }
            </style>
            <htmlpageheader name="myHTMLHeader" style="display:none;">
                <table id="header" style="width:100%;">
                    <tr>
                        <td rowspan="3" style="border: 1px solid #000;"><center><img src="' . getenv("DOCUMENT_ROOT") . '/webfiles/img/logo_report2.png" /></center></td>
                        <td colspan="9" style="text-align: center; font-weight: bold; border: 1px solid #000;"><h3>PERFIL DE CARGO</h3></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-weight: bold; border: 1px solid #000; text-align: left;">CARGO: ' . $perfil["descricao_formatado"] . '</td>
                        <td colspan="6" style="font-weight: bold; border: 1px solid #000; text-align: left;">CBO: ' . $titulo_cbo["codigo"] . "-" . strtoupper($titulo_cbo["titulo"]) . '</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-weight: bold; border: 1px solid #000; text-align: left;">ELABORAÇÃO: ' . $perfil["elaboracao"] . '</td>
                        <td colspan="6" style="font-weight: bold; border: 1px solid #000; text-align: left;">APROVAÇÃO:  ' . $perfil["aprovacao"] . '</td>
                    </tr>
                </table>
            </htmlpageheader>
            <htmlpagefooter name="myHTMLFooter">
                <table>
                            <tr>
                                <td style="width: 300px; text-align: left;">
                                    ' . TITLE . '<br />Desenvolvido por DEM Tecnologia
                                </td>
                                <td style="width: 300px; text-align: center">
                                    ' . date('d/m/Y H:i:s') . ' <br /> ' . $_SESSION["user_name"] . '
                                </td>
                                <td style="width: 300px; text-align: right">
                                       Pág.: {PAGENO} - {nb}
                                </td>
                            </tr>
                        </table>
            </htmlpagefooter>
            <div>
                <sethtmlpageheader name="myHTMLHeader" value="on" show-this-page="1" />
                <sethtmlpagefooter name="myHTMLFooter" show-this-page="1" value="on" />
                <div>

                <table border="1" width="100%" style="border-collapse: collapse;">
                    <tr>
                        <td colspan="4" class="text-center"><b>CARACTERIZAÇÃO DO CARGO</b></td>
                    </tr>
                    <tr>

                        <td colspan="4">
                            <b>CARREIRA ATUAL: </b> ' . $nat_ocupacional->getNat_OcupacionalByID($cargo["id_nat_ocupacional"])["descricao"] . '
                            <b>CLASSE VERTICAL: </b> ' . $cargo["tipo_descricao"] . '&nbsp;&nbsp;&nbsp; <b>CÓDIGO: </b> ' . $cargo["id_nat_ocupacional"] . '
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-justify ">
                            <b>QUALIFICAÇÃO BÁSICA:</b><br />
                            ' . $perfil["qualificacao_basica"] . '
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-justify">
                            <b>SUMÁRIO DAS DESCRIÇÕES:</b><br />
                            ' . $perfil["sumario"] . '
                        </td>
                    </tr>
                    ';
        foreach ($this->monta_estrutura_perfil($id_perfil) as $value) {
            if ($value["id_indicadores_quant_tipo"] == 5) {
                $html .= '
                <tr>
                    <td colspan="4" style="border: solid 1px #000;" class="text-center">
                        <b>COMPETÊNCIAS: ' . $value["descricao"] . '</b>
                    </td>
                </tr>

                ';
                foreach ($value["sub"] as $value_sub) {
                    $html .= '<tr style="border: solid 1px #000;">
                            <td style="border: solid 1px #000;" class="text-center" colspan="4"><b>INDICADOR: ' . $value_sub["descricao"] . '</b></td>
                        </tr>';
                    $i = 1;
                    foreach ($value_sub["descricao_indicador"] as $value_indicador) {
                        if ($i == 1) {
                            $html .= '
                                <tr>
                                    <td colspan="4" class="text-justify" style="border-top: solid 1px #000; border-bottom: none">' . $i . '. ' . $value_indicador["descricao"] . '<br /><br /></td>
                                </tr>
                            ';
                        } else if ($i == count($value_sub["descricao_indicador"])) {
                            $html .= '
                                <tr>
                                    <td colspan="4" class="text-justify" style="border-top: none; border-bottom: solid 1px #000">' . $i . '. ' . $value_indicador["descricao"] . '<br /><br /></td>
                                </tr>
                            ';
                        } else {
                            $html .= '
                                <tr>
                                    <td colspan="4" class="text-justify" style="border-top: none; border-bottom: none">' . $i . '. ' . $value_indicador["descricao"] . '<br /><br /></td>
                                </tr>
                            ';
                        }

                        $i++;
                    }
                }
            } else {
                foreach ($value["sub"] as $value_sub) {
                    $html .= '
                        <tr>
                            <td colspan="4" style="border: solid 1px #000;" class="text-center">
                                <b>COMPETÊNCIAS: ' . $value["descricao"] . '</b>
                            </td>
                        </tr>
                        <tr style="border: solid 1px #000;">
                            <td style="border: solid 1px #000;" class="text-center" colspan="4"><b>INDICADOR: ' . $value_sub["descricao"] . '</b></td>
                        </tr>
                    ';
                    $i = 1;
                    foreach ($value_sub["descricao_indicador"] as $value_indicador) {
                        if ($i == 1) {
                            $html .= '
                                <tr>
                                    <td colspan="4" class="text-justify" style="border-top: solid 1px #000; border-bottom: none">' . $i . '. ' . $value_indicador["descricao"] . '<br /><br /></td>
                                </tr>
                            ';
                        } else if ($i == count($value_sub["descricao_indicador"])) {
                            $html .= '
                                <tr>
                                    <td colspan="4" class="text-justify" style="border-top: none; border-bottom: solid 1px #000">' . $i . '. ' . $value_indicador["descricao"] . '<br /><br /></td>
                                </tr>
                            ';
                        } else {
                            $html .= '
                                <tr>
                                    <td colspan="4" class="text-justify" style="border-top: none; border-bottom: none">' . $i . '. ' . $value_indicador["descricao"] . '<br /><br /></td>
                                </tr>
                            ';
                        }

                        $i++;
                    }
                }
            }
        }




        $html .= '
            <tr style="border: solid 1px #000;">
                <td colspan="4" style="border: solid 1px #000;" class="text-center"><b>REQUISITOS DE RECRUTAMENTO E SELEÇÃO</b></td>
            </tr>
            <tr style="border: solid 1px #000;">
                <td colspan="4" style="border: solid 1px #000;" class="text-center"><b>CARACTERÍSTICAS EXIGIDAS</b></td>
            </tr>
        ';
        $i = 1;
        foreach ($per_req as $value) {
            $r = $requisitos->getByID($value["id"]);
            if ($i == 1) {
                $html .= '
                    <tr>
                        <td colspan="4" class="text-justify" style="border-top: solid 1px #000; border-bottom: none">' . $i . '. ' . $r["descricao"] . '<br /><br /></td>
                    </tr>
                ';
            } else if ($i == count($per_req)) {
                $html .= '
                    <tr>
                        <td colspan="4" class="text-justify" style="border-top: none; border-bottom: solid 1px #000">' . $i . '. ' . $r["descricao"] . '<br /><br /></td>
                    </tr>
                ';
            } else {
                $html .= '
                    <tr>
                        <td colspan="4" class="text-justify" style="border-top: none; border-bottom: none">' . $i . '. ' . $r["descricao"] . '<br /><br /></td>
                    </tr>
                ';
            }

            $i++;
        }
        $html .= '
            <tr style="border: solid 1px #000;">
                <td colspan="4" style="border: solid 1px #000;" class="text-center"><b>CRITÉRIOS PARA INGRESSO NO CARGO</b></td>
            </tr>
            <tr style="border: solid 1px #000;">
                <td colspan="4" style="border: solid 1px #000;" class="text-justify">
                    <b>FONTE INTERNA: </b>
        ';
        if (count($perfil["fonte_interna"])) {

            foreach ($perfil["fonte_interna"] as $key => $value) {

                $html .= "<br />EXPERIÊNCIA MÍNIMA DE (";
                $html .= $value["tempo"] . ") ";
                $html .= strtoupper($texto->convert_number_to_words($value["tempo"]));

                if ($value["tempo"] <= 1) {
                    if ($value["tipo"] == 1) {
                        $html .= " ANO NO CARGO DE ";
                    } else {
                        $html .= " MêS NO CARGO DE ";
                    }
                } else {
                    if ($value["tipo"] == 1) {
                        $html .= " ANOS NO CARGO DE ";
                    } else {
                        $html .= " MESES NO CARGO DE ";
                    }
                }


                $html .= $cg->getDescricaoCargo($value["id_cargo_interno"]) . ".";
            }
        }
        $html .= "<br /><br /> TER ATINGIDO CONCEITO " . $perfil["conceito_avaliacao"] . " NA ÚLTIMA AVALIAÇÃO DE DESEMPENHO. <br />NÃO TER ADVERTÊNCIA " . ($perfil["advertencia"] == 1 ? "GRAVE" : "LEVE") . " NOS ÚLTIMOS 90 DIAS. <br /><br />";

        $html .= "<b>FONTE EXTERNA: </b> " . $perfil["fonte_externa"] . "<br /><br />";

        $html .= "<b>QUALIFICAÇÃO BÁSICA: </b> " . $perfil["qualificacao_basica"] . "<br /><br />";
        $html .= '
                </td>
            </tr>
        </table>
        ';

        require_once $_SERVER["DOCUMENT_ROOT"] . '/system/helpers/vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'c',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 45,
            'margin_bottom' => 25,
            'orientation' => 'P'
        ]);

        if (file_exists('temp/PerfilCargo ' . $perfil["descricao_formatado"] . '.pdf')) {
            unlink('temp/PerfilCargo ' . $perfil["descricao_formatado"] . '.pdf');
        }


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
        $mpdf->Output('temp/PerfilCargo ' . $perfil["descricao_formatado"] . '.pdf');

        $arquivo = 'PerfilCargo ' . $perfil["descricao_formatado"] . '.pdf';
        $file = $_SERVER["DOCUMENT_ROOT"] . '/temp/PerfilCargo ' . $perfil["descricao_formatado"] . '.pdf';

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $arquivo . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);

        exit;
    }

    public function rel_excel($id_cargo) {

    }

    private function monta_estrutura_perfil($id_perfil_cargo) {
//        indicadores_quant_tipo
//            indicadores_quant_sub
//                perfil_cargo_indicadores

        $result = $this->query("SELECT  DISTINCT perfil_cargo_indicadores.id_indicadores_quant_tipo, indicadores_quant_tipo.descricao FROM perfil_cargo_indicadores INNER JOIN indicadores_quant_tipo ON (indicadores_quant_tipo.id = perfil_cargo_indicadores.id_indicadores_quant_tipo) WHERE perfil_cargo_indicadores.id_perfil_cargo = '{$id_perfil_cargo}' ORDER BY indicadores_quant_tipo.descricao ASC");

        foreach ($result as $key => $value) {
            $result_sub = $this->query("SELECT DISTINCT perfil_cargo_indicadores.id_indicadores_quant_sub, indicadores_quant_sub.descricao FROM perfil_cargo_indicadores INNER JOIN indicadores_quant_sub ON (indicadores_quant_sub.id = perfil_cargo_indicadores.id_indicadores_quant_sub) WHERE perfil_cargo_indicadores.id_perfil_cargo = '{$id_perfil_cargo}' AND perfil_cargo_indicadores.id_indicadores_quant_tipo = '{$value["id_indicadores_quant_tipo"]}' ORDER BY indicadores_quant_sub.id ASC ");

            foreach ($result_sub as $key_sub => $value_sub) {
                $result_desc = $this->query("SELECT DISTINCT perfil_cargo_indicadores.descricao FROM perfil_cargo_indicadores WHERE perfil_cargo_indicadores.id_perfil_cargo = '{$id_perfil_cargo}' AND perfil_cargo_indicadores.id_indicadores_quant_tipo = '{$value["id_indicadores_quant_tipo"]}' AND perfil_cargo_indicadores.id_indicadores_quant_sub = '{$value_sub["id_indicadores_quant_sub"]}' ORDER BY perfil_cargo_indicadores.id ASC ");

                $result_sub[$key_sub]["descricao_indicador"] = $result_desc;
            }
            $result[$key]["sub"] = $result_sub;
        }

        return $result;
    }

}
