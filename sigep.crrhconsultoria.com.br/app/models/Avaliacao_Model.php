<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Avaliacao_Model
 *
 * @author drc
 */
class Avaliacao_Model extends Model {

    public $_tabela = "avaliacao";

    public function __construct() {
        parent::__construct();
    }

    public function getFuncionarioAvaliacao() {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->query("SELECT DISTINCT funcionarios.id, pessoa.nome, cargo.tipo, cargo.descricao, cargo.subtipo, funcionarios.dt_admissao, DATE_FORMAT(funcionarios.data_avaliacao,'%d/%m/%Y') AS data_avaliacao, funcionarios.status FROM `funcionarios` INNER JOIN pessoa ON (pessoa.id = funcionarios.id_pessoa) INNER JOIN cargo ON (cargo.id = funcionarios.id_cargo) WHERE funcionarios.id_cliente = '{$id_cliente}' AND funcionarios.status = '1' AND (funcionarios.data_avaliacao IS NULL OR funcionarios.data_avaliacao >= DATE_ADD(CURRENT_DATE, INTERVAL -90 day)) ORDER BY funcionarios.data_avaliacao ASC, cargo.descricao ASC, pessoa.nome ASC  ");

        foreach ($result as $key => $value) {
            if ($value["status"] == "1") {
                $result[$key]["status"] = "ATIVO";
            } else if ($value["status"] == "0") {
                $result[$key]["status"] = "INATIVO";
            } else if ($value["status"] == "3") {
                $result[$key]["status"] = "DESLIGADO";
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

            $result[$key]["data_avaliacao"] = (is_null($result[$key]["data_avaliacao"]) ? "NOVATO" : $result[$key]["data_avaliacao"]);

            $result[$key]["descricao_formatado"] = $result[$key]["descricao"] . " " . $subtipo;
        }

        return $result;
    }

    public function monta_avaliacao($id_funcionario) {

        $perfil_cargo = new Perfil_Cargo_Model();

        $id_cliente = $_SESSION["id_cliente"];

        $pessoa = $this->query("SELECT pessoa.id AS id_pessoa, pessoa.nome, cargo.descricao AS cargo, funcionarios.id AS id_funcionario, funcionarios.id_cargo FROM funcionarios INNER JOIN pessoa ON (pessoa.id = funcionarios.id_pessoa) INNER JOIN cargo ON (cargo.id = funcionarios.id_cargo) WHERE funcionarios.id = '{$id_funcionario}' AND funcionarios.id_cliente = '{$id_cliente}'");

        $ret["dados"] = $pessoa[0];
        $id_cargo = $pessoa[0]["id_cargo"];

        $id_perfil = $perfil_cargo->getIdByCargo($id_cliente, $id_cargo);

        $indicadores = array();

        $indicadores_tipo = $this->query("SELECT DISTINCT id_clients, id_cargo, id_perfil_cargo, id_indicadores_quant_tipo, indicadores_quant_tipo.descricao FROM `perfil_cargo_indicadores` INNER JOIN indicadores_quant_tipo ON (indicadores_quant_tipo.id = perfil_cargo_indicadores.id_indicadores_quant_tipo) WHERE perfil_cargo_indicadores.id_clients = '{$id_cliente}' AND perfil_cargo_indicadores.id_cargo = '{$id_cargo}' AND perfil_cargo_indicadores.id_perfil_cargo = '{$id_perfil}' ORDER BY indicadores_quant_tipo.descricao ASC ");

        if (count($indicadores_tipo)) {
            $ret["dados"]["id_perfil_cargo"] = $indicadores_tipo[0]["id_perfil_cargo"];
        }

        foreach ($indicadores_tipo as $key => $value) {

            $sub = $this->query("SELECT DISTINCT perfil_cargo_indicadores.id_clients, perfil_cargo_indicadores.id_cargo, perfil_cargo_indicadores.id_perfil_cargo, perfil_cargo_indicadores.id_indicadores_quant_tipo, id_indicadores_quant_sub, indicadores_quant_sub.descricao FROM `perfil_cargo_indicadores` INNER JOIN indicadores_quant_sub ON (indicadores_quant_sub.id = perfil_cargo_indicadores.id_indicadores_quant_sub) WHERE perfil_cargo_indicadores.id_clients = '{$id_cliente}' AND perfil_cargo_indicadores.id_cargo = '{$id_cargo}' AND perfil_cargo_indicadores.id_perfil_cargo = '{$id_perfil}' AND perfil_cargo_indicadores.id_indicadores_quant_tipo = '{$value["id_indicadores_quant_tipo"]}' ORDER BY indicadores_quant_sub.descricao ASC ");

            foreach ($sub as $key2 => $value2) {

                $perfil_indicador = $this->query("SELECT * FROM `perfil_cargo_indicadores` WHERE perfil_cargo_indicadores.id_clients = '{$id_cliente}' AND perfil_cargo_indicadores.id_cargo = '{$id_cargo}' AND perfil_cargo_indicadores.id_perfil_cargo = '{$id_perfil}' AND perfil_cargo_indicadores.id_indicadores_quant_tipo = '{$value["id_indicadores_quant_tipo"]}' AND perfil_cargo_indicadores.id_indicadores_quant_sub = '{$value2["id_indicadores_quant_sub"]}' ORDER BY perfil_cargo_indicadores.id ASC ");

                $sub[$key2]["indicador"] = $perfil_indicador;

                $sub[$key2]["negacao"] = $this->query("SELECT * FROM indicadores_quant_sub_negacao WHERE id_indicadores_quant_sub = '{$value2["id_indicadores_quant_sub"]}' ORDER BY descricao ASC");

                $indicadores_tipo[$key]["sub"][] = $sub["$key2"];
            }




            $indicadores[] = $indicadores_tipo[$key];
        }


        $ret["indicadores"] = $indicadores;

        $habilidades = array();

        $result = $this->query("SELECT cargo_indi_quali.id, cargo_indi_quali.id_indicador, indicadores_quali.descricao, indicadores_quali.conceito, cargo.tipo FROM cargo_indi_quali INNER JOIN indicadores_quali ON (indicadores_quali.id = cargo_indi_quali.id_indicador) INNER JOIN cargo ON (cargo.id = cargo_indi_quali.id_cargo) WHERE cargo_indi_quali.id_cliente = '{$id_cliente}' AND cargo_indi_quali.id_cargo = '{$id_cargo}' ORDER BY cargo_indi_quali.posicao ASC  ");

        foreach ($result as $key => $value) {
            $result2 = $this->query("SELECT indicadores_quali_graduacao.id, indicadores_quali_graduacao.descricao FROM indicadores_quali_graduacao WHERE indicadores_quali_graduacao.id_ind_qualitativo = '{$value["id_indicador"]}' AND indicadores_quali_graduacao.id_cliente = '{$id_cliente}' ORDER BY indicadores_quali_graduacao.ordem_horizontal ASC ");

            if ($value["id_indicador"] == 10 && ($value["tipo"] == "1" || $value["tipo"] == "2" || $value["tipo"] == "5")) {
                unset($result[$key]);
            } else {


                if ($value["id_indicador"] == 8 && ($value["tipo"] != "1" && $value["tipo"] != "2" && $value["tipo"] != "5")) {
                    unset($result[$key]);
                } else {
                    $result[$key]["graduacao"] = $result2;
                }
            }
        }

        $ret["habilidades"] = $result;

        return $ret;
    }

    public function monta_pdi($id_avaliacao) {

        $id_cliente = $_SESSION["id_cliente"];
        $avaliacao_plano_acao = new Avaliacao_Quantitativas_Plano_Acao_Model();
        $avaliacaao_plano_acao_habilidades = new Avaliacao_Qualitativas_Plano_Acao_Model();
        $estrutura = new Estrutura_Organizacional_Model();

        $result = $this->query("SELECT avaliacao.id, avaliacao.id_funcionario, avaliacao.id_pessoa, avaliacao.id_cargo, avaliacao.id_perfil_cargo, avaliacao.id_user_avaliador, pessoa.nome, cargo.descricao AS cargo, cargo.subtipo, cargo.id_estrutura_organizacional FROM `avaliacao` INNER JOIN pessoa ON (pessoa.id = avaliacao.id_pessoa) INNER JOIN cargo ON (cargo.id = avaliacao.id_cargo) WHERE avaliacao.id = '{$id_avaliacao}' AND avaliacao.id_cliente = '{$id_cliente}'");

        foreach ($result as $key => $value) {

            if ($result[$key]["subtipo"] == "1") {
                $subtipo = "JUNIOR";
            } else if ($result[$key]["subtipo"] == "2") {
                $subtipo = "PLENO";
            } else if ($result[$key]["subtipo"] == "3") {
                $subtipo = "SENIOR";
            }

            $result[$key]["descricao_formatado"] = $result[$key]["cargo"] . " " . $subtipo;
        }


        $ret["dados"] = $result[0];

        $id_cargo = $result[0]["id_cargo"];
        $id_perfil_cargo = $result[0]["id_perfil_cargo"];

        $ret["estrutura"] = $estrutura->getEstruturaById($result[0]["id_estrutura_organizacional"]);

        $result = $this->query("SELECT DISTINCT id_clients, id_cargo, id_perfil_cargo, id_indicadores_quant_tipo, indicadores_quant_tipo.descricao FROM `perfil_cargo_indicadores` INNER JOIN indicadores_quant_tipo ON (indicadores_quant_tipo.id = perfil_cargo_indicadores.id_indicadores_quant_tipo) WHERE perfil_cargo_indicadores.id_clients = '{$id_cliente}' AND perfil_cargo_indicadores.id_cargo = '{$id_cargo}' AND perfil_cargo_indicadores.id_perfil_cargo = '{$id_perfil_cargo}' ORDER BY indicadores_quant_tipo.descricao ASC ");

        $indicadores_quantitativos = [];

        foreach ($result as $key => $value) {
            $result_respostas = $this->query("SELECT avaliacao_indicadores_quantitativos.id, avaliacao_indicadores_quantitativos.id_avaliavao, avaliacao_indicadores_quantitativos.id_indicadores_quant_tipo, avaliacao_indicadores_quantitativos.id_indicadores_quant_sub, avaliacao_indicadores_quantitativos.id_perfil_cargo_indicadores, avaliacao_indicadores_quantitativos.id_indicadores_quant_sub_negacao, avaliacao_indicadores_quantitativos.resposta, perfil_cargo_indicadores.descricao AS indicadores, indicadores_quant_sub_negacao.descricao AS negacao FROM `avaliacao_indicadores_quantitativos` INNER JOIN perfil_cargo_indicadores ON (perfil_cargo_indicadores.id = avaliacao_indicadores_quantitativos.id_perfil_cargo_indicadores) INNER JOIN indicadores_quant_sub_negacao ON (indicadores_quant_sub_negacao.id = avaliacao_indicadores_quantitativos.id_indicadores_quant_sub_negacao) WHERE avaliacao_indicadores_quantitativos.id_avaliavao = '{$id_avaliacao}' AND avaliacao_indicadores_quantitativos.resposta = '0' AND avaliacao_indicadores_quantitativos.id_indicadores_quant_tipo = '{$value["id_indicadores_quant_tipo"]}'");

            if (count($result_respostas) == 0) {
                unset($result[$key]);
            } else {

                foreach ($result_respostas as $key_plano_acao => $value_plano_acao) {
                    $result_respostas[$key_plano_acao]["plano_acao"] = $avaliacao_plano_acao->getByIdAvaliacaoIndicadoresQuantitativos($value_plano_acao["id"]);
                }

                $indicadores_quantitativos[$key] = $result[$key];
                $indicadores_quantitativos[$key]["indicadores"] = $result_respostas;
            }
        }

        $ret["indicadores_quantitativos"] = $indicadores_quantitativos;

        $result = $this->query("SELECT cargo_indi_quali.id, cargo_indi_quali.id_indicador, indicadores_quali.descricao, indicadores_quali.conceito, cargo.tipo FROM cargo_indi_quali INNER JOIN indicadores_quali ON (indicadores_quali.id = cargo_indi_quali.id_indicador) INNER JOIN cargo ON (cargo.id = cargo_indi_quali.id_cargo) WHERE cargo_indi_quali.id_cliente = '{$id_cliente}' AND cargo_indi_quali.id_cargo = '{$id_cargo}' ORDER BY cargo_indi_quali.posicao ASC  ");

        foreach ($result as $key => $value) {
            $result_habilidades = $this->query("SELECT avaliacao_indicadores_qualitativos.id, avaliacao_indicadores_qualitativos.id_avaliacao, avaliacao_indicadores_qualitativos.id_indicadores_quali, avaliacao_indicadores_qualitativos.id_indicadores_quali_graduacao, indicadores_quali_graduacao.ordem_horizontal, indicadores_quali_graduacao.descricao, avaliacao_indicadores_qualitativos.intermediario, CONCAT(indicadores_quali_graduacao.ordem_horizontal, avaliacao_indicadores_qualitativos.intermediario) AS con, avaliacao_indicadores_qualitativos.posicao, avaliacao_indicadores_qualitativos.peso FROM `avaliacao_indicadores_qualitativos` INNER JOIN indicadores_quali_graduacao ON (indicadores_quali_graduacao.id = avaliacao_indicadores_qualitativos.id_indicadores_quali_graduacao) WHERE avaliacao_indicadores_qualitativos.id_avaliacao = '{$id_avaliacao}' AND avaliacao_indicadores_qualitativos.id_indicadores_quali = '{$value["id_indicador"]}' AND indicadores_quali_graduacao.ordem_horizontal <= '2' AND CONCAT(indicadores_quali_graduacao.ordem_horizontal,avaliacao_indicadores_qualitativos.intermediario) <= '21' ORDER BY avaliacao_indicadores_qualitativos.posicao ASC ");

            if (!count($result_habilidades)) {
                unset($result[$key]);
            } else {
                $result[$key]["avaliacao"] = $result_habilidades[0];

                $result[$key]["plano_acao"] = $avaliacaao_plano_acao_habilidades->getByIdAvaliacaoIndicadoresQualitativas($result_habilidades[0]["id"]);
            }
        }

        $ret["indicadores_qualitativos"] = $result;

        return $ret;
    }

    public function adicionar($id_funcionario, $id_pessoa, $id_cargo, $id_perfil_cargo, $indicadores_quantitativos, $indicadore_qualitativos) {

        $id = $this->insert(array(
            "id_funcionario" => $id_funcionario,
            "id_pessoa" => $id_pessoa,
            "id_cargo" => $id_cargo,
            "id_perfil_cargo" => $id_perfil_cargo,
            "id_cliente" => $_SESSION["id_cliente"],
            "id_user_avaliador" => $_SESSION["user_id"],
            "data_avaliacao" => date("Y-m-d H:i:s")
                ), "id");

        if (filter_var($id, FILTER_VALIDATE_INT) && $id > 0) {

            $quantitativos = new Avaliacao_Indicadores_Quantitativos();
            $qualitativos = new Avaliacao_Indicadores_Qualitativos_Model();

            $sit_quant = $quantitativos->adicionar($id, $indicadores_quantitativos);
            $sit_quali = $qualitativos->adicionar($_SESSION["id_cliente"], $id_cargo, $id, $indicadore_qualitativos);

            if ($sit_quant && $sit_quali) {

                $funcionario = new Funcionarios_Model();
                $funcionario->atualizaDataAvaliacao($id_funcionario);

                $arr["status"] = true;
                $arr["message"] = "Avaliação salva com sucesso!";
            } else {
                $this->delete("id = '{$id}'");

                $arr["status"] = false;
                $arr["message"] = "Erro ao salvar a avaliação!";
            }
        } else {
            $arr["status"] = false;
            $arr["message"] = "Erro ao salvar a avaliação.";
        }

        $this->log("Inserção", $arr["message"]);

        return json_encode($arr);
    }

    public function setResultadoAvaliacaoQuantitativa($idAvaliacao, $resultado) {
        $this->update(array("resultado_quantitativo" => $resultado), "id = '{$idAvaliacao}'");
    }

    public function setResultadoAvaliacaoQualitativa($idAvaliacao, $resultado) {
        $this->update(array("resultado_qualitativa" => $resultado), "id = '{$idAvaliacao}'");
    }

    public function getAvaliacoes($status = 2) {
        $id_cliente = $_SESSION["id_cliente"];
        $users = new Users_Model();
        $result = $this->query("SELECT DISTINCT(pessoa.id), avaliacao.id, DATE_FORMAT(avaliacao.data_avaliacao, '%d/%m/%Y %H:%i:%s') AS data_avaliacao, pessoa.nome AS pessoa, cargo.descricao AS cargo, cargo.subtipo, users.name AS avaliador, avaliacao.id_user_validacao FROM `avaliacao` INNER JOIN pessoa ON (pessoa.id = avaliacao.id_pessoa) INNER JOIN cargo ON (cargo.id=avaliacao.id_cargo) INNER JOIN users ON (users.id = avaliacao.id_user_avaliador) WHERE avaliacao.pdi = '{$status}' AND avaliacao.id_cliente = '{$id_cliente}' ORDER BY avaliacao.id DESC ");

        foreach ($result as $key => $value) {

            if ($result[$key]["subtipo"] == "1") {
                $subtipo = "JUNIOR";
            } else if ($result[$key]["subtipo"] == "2") {
                $subtipo = "PLENO";
            } else if ($result[$key]["subtipo"] == "3") {
                $subtipo = "SENIOR";
            }

            $result[$key]["descricao_formatado"] = $result[$key]["cargo"] . " " . $subtipo;

            if ($value["id_user_validacao"])
                $result[$key]["user_validacao"] = $users->getName($value["id_user_validacao"]);
        }

        return $result;
    }

    public function getAvaliacoesPendentes() {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->query("SELECT avaliacao.id, DATE_FORMAT(avaliacao.data_avaliacao, '%d/%m/%Y %H:%i:%s') AS data_avaliacao, pessoa.nome AS pessoa, cargo.descricao AS cargo, cargo.subtipo, users.name AS avaliador FROM `avaliacao` INNER JOIN pessoa ON (pessoa.id = avaliacao.id_pessoa) INNER JOIN cargo ON (cargo.id=avaliacao.id_cargo) INNER JOIN users ON (users.id = avaliacao.id_user_avaliador) WHERE avaliacao.pdi = '0' AND avaliacao.id_cliente = '{$id_cliente}' ORDER BY avaliacao.data_avaliacao ASC ");

        foreach ($result as $key => $value) {

            if ($result[$key]["subtipo"] == "1") {
                $subtipo = "JUNIOR";
            } else if ($result[$key]["subtipo"] == "2") {
                $subtipo = "PLENO";
            } else if ($result[$key]["subtipo"] == "3") {
                $subtipo = "SENIOR";
            }

            $result[$key]["descricao_formatado"] = $result[$key]["cargo"] . " " . $subtipo;
        }

        return $result;
    }

    public function finalizar($id_avaliacao) {

        $ava_ind_quantitativos = new Avaliacao_Indicadores_Quantitativos();
        $ava_ind_qualitativos = new Avaliacao_Indicadores_Qualitativos_Model();

        if ($ava_ind_quantitativos->validaRespostaPlanoAcao($id_avaliacao) && $ava_ind_qualitativos->validaRespostaPlanoAcao($id_avaliacao)) {
            $ret = $this->update(array("pdi" => 1), "id = '{$id_avaliacao}'");

            if (filter_var($ret, FILTER_VALIDATE_INT) && $ret > 0) {
                $arr["status"] = true;
                $arr["message"] = "Plano de Ação salvo com sucesso!";
            } else {
                $arr["status"] = false;
                $arr["message"] = "Erro ao salvar o Plano de Ação!";
            }

            $this->log("Alteração", $arr["message"]);
        } else {
            $arr["status"] = false;
            $arr["message"] = "Todos os indicadores devem possuir pelo menos um plano de ação defino!";
        }





        return json_encode($arr);
    }

    public function concluir($id_avaliacao) {

        $ava_ind_quantitativos = new Avaliacao_Indicadores_Quantitativos();
        $ava_ind_qualitativos = new Avaliacao_Indicadores_Qualitativos_Model();

        if ($ava_ind_quantitativos->validaRespostaPlanoAcao($id_avaliacao) && $ava_ind_qualitativos->validaRespostaPlanoAcao($id_avaliacao)) {
            $ret = $this->update(array("pdi" => 3, "id_user_validacao" => $_SESSION["user_id"]), "id = '{$id_avaliacao}'");

            if (filter_var($ret, FILTER_VALIDATE_INT) && $ret > 0) {
                $arr["status"] = true;
                $arr["message"] = "Plano de Ação salvo com sucesso!";
            } else {
                $arr["status"] = false;
                $arr["message"] = "Erro ao salvar o Plano de Ação!";
            }

            $this->log("Alteração", $arr["message"]);
        } else {
            $arr["status"] = false;
            $arr["message"] = "Todos os indicadores devem possuir pelo menos um plano de ação defino!";
        }

        return json_encode($arr);
    }

    public function emitir_pdi($id_avaliacao) {

        $view_pdi = $this->monta_pdi($id_avaliacao);

        require_once $_SERVER["DOCUMENT_ROOT"] . '/system/helpers/vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'c',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 45,
            'margin_bottom' => 25,
            'orientation' => 'L'
        ]);

        $html = '
            <style>
                body{
                    background-color:#FFFFFF;
                    font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;
                }
                b{
                    font-weight: bold;
                }
                table tr td {
                    padding: 5px;
                }

                table.comBordaSimples {
                    border-collapse: collapse; /* CSS2 */
                    background: #FFFFF0;
                }

                table.comBordaSimples td {
                    border: 1px solid black;
                }

                table.comBordaSimples th {
                    border: 1px solid black;
                    background: #F0FFF0;
                }

                .tabe{
                    font-size: 10px;
                }

                .header{
                    margin-top:600px;
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
                #ul {
                list-style: none;
                  display:inline;
                  background-color: #d1d1e0;
                }
                #li {
                list-style: none;
                  display:inline;
                  background-color: #d1d1e0;
                }
            </style>
            <htmlpageheader name="myHTMLHeader" style="display:none;">
                <table id="header" style="width:100%;">
                    <tr>
                        <td rowspan="3" style="border: 1px solid #000;"><img src="' . getenv("DOCUMENT_ROOT") . '/webfiles/img/logo_report2.png" /></td>
                        <td colspan="9" style="text-align: center; font-weight: bold; border: 1px solid #000;"><h3>RELATÓRIO DE PDI<br>PLANO DE DESENVOLVIMENTO INDIVIDUAL</h3></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-weight: bold; border: 1px solid #000; text-align: left;">COLABORADOR: ' . $view_pdi["dados"]["nome"] . '</td>
                        <td colspan="6" style="font-weight: bold; border: 1px solid #000; text-align: left;">' . $view_pdi["estrutura"] . '</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-weight: bold; border: 1px solid #000; text-align: left;">CARGO: ' . $view_pdi["dados"]["cargo"] . '</td>
                        <td colspan="6" style="font-weight: bold; border: 1px solid #000; text-align: left;">DATA: ' . date("d/m/Y H:i:s") . '</td>
                    </tr>
                </table>
            </htmlpageheader>
            <htmlpagefooter name="myHTMLFooter">
                <table style="width:100%;">
                            <tr>
                                <td style="width: 33%; text-align: left;">
                                    ' . TITLE . '<br />D&M Tecnologia da Informação
                                </td>
                                <td style="width: 33%; text-align: center">
                                  &nbsp;
                                </td>
                                <td style="width: 33%; text-align: right">
                                       Pág.: {PAGENO} - {nb}
                                </td>
                            </tr>
                        </table>
            </htmlpagefooter>
            <div>
                <sethtmlpageheader name="myHTMLHeader" value="on" show-this-page="1" />
                <sethtmlpagefooter name="myHTMLFooter" show-this-page="1" value="on" />
                    <div class="dataTable_wrapper">

                    ';
        if (!count($view_pdi["indicadores_quantitativos"])) {
            $html .= '
                <table width="100%" border="1" class="comBordasSimples">
                    <tr>
                        <td colspan="10" style="font-weight: bold; background-color: #4472C4; text-align: center; color: #FFFFFF;"><h4>COLABORADOR SEM APONTAMENTOS DE NÃO CONFORMIDADE PARA INDICADORES DE COMPETÊNCIA</h4></td>
                    </tr>
                </table>
            ';
        } else {
            foreach ($view_pdi["indicadores_quantitativos"] as $value) {
                $html .= '
                    <table width="100%" border="1" class="comBordasSimples">
                        <thead>
                            <tr>
                                <td colspan="5" style="font-weight: bold; background-color: #4472C4; text-align: center; color: #FFFFFF;"><h4>COMPETÊNCIA ' . $value["descricao"] . '</h4></td>
                                <td colspan="5" style="font-weight: bold; background-color: #4472C4; text-align: center; color: #FFFFFF;"><h4>MATRIZ DE MELHORIAS</h4></td>
                            </tr>
                            <tr>
                                <td colspan="4" style="font-weight: bold; background-color: #4472C4; text-align: center; color: #FFFFFF; width:315px;"><h5>NÃO CONFORMIDADES</h5></td>
                                <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF; width:109px;"><h5>ÍNDICE ALCANÇADO</h5></td>
                                <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF; width:175px;"><h5>PLANO DE AÇÃO</h5></td>
                                <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF; width:154px;"><h5>EVIDÊNCIAS DOS RESULTADOS / METAS</h5></td>
                                <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF; width:67px;"><h5>MATRIZ GUT</h5></td>
                                <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF; width:175px;"><h5>PARTICIPANTES / ÁREA</h5></td>
                                <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF; width:90px;"><h5>SITUAÇÃO</h5></td>
                            </tr>
                        </thead>
                        <tbody>
                    ';
                foreach ($value["indicadores"] as $value_indicadores) {

                    $total_l = count($value_indicadores["plano_acao"]);
                    $html .= '
                    <tr>
                        <td rowspan="' . $total_l . '" colspan="4" style="text-align: justify; font-size: 10px;">' . mb_strtoupper($value_indicadores["indicadores"], 'UTF-8') . '</td>
                        <td rowspan="' . $total_l . '" style="text-align: center; font-size: 10px;">' . mb_strtoupper($value_indicadores["negacao"], 'UTF-8') . '</td>
                            ';
                    $r = 0;
                    foreach ($value_indicadores["plano_acao"] as $value_plano_acao) {
                        if ($r > 0) {
                            $html .= '</tr><tr>';
                            $r = 0;
                        }
                        $html .= '
                        <td style="text-align: justify; font-size: 10px;">' . mb_strtoupper($value_plano_acao["plano_acao"], 'UTF-8') . '</td>
                        <td style="text-align: justify; font-size: 10px;">' . mb_strtoupper($value_plano_acao["evidencia"], 'UTF-8') . '</td>
                        <td style="text-align: center; font-size: 10px;">' . mb_strtoupper($value_plano_acao["gut"], 'UTF-8') . '</td>
                        <td style="text-align: center; font-size: 10px;">' . mb_strtoupper($value_plano_acao["participantes"], 'UTF-8') . '</td>
                        <td style="text-align: center; font-size: 10px;">' . $value_plano_acao["status_nome"] . '</td>
                    ';
                        $r++;
                    }
                    $html .= '
                    </tr>
                ';
                }
                $html .= '
                        </tbody>
                    </table>
                    <pagebreak>
                ';
            }
        }

        if (!count($view_pdi["indicadores_qualitativos"])) {
            $html .= '
                <table width="100%" border="1" class="comBordasSimples">
                    <tr>
                        <td colspan="10" style="font-weight: bold; background-color: #4472C4; text-align: center; color: #FFFFFF;"><h4>COLABORADOR SEM APONTAMENTOS DE NÃO CONFORMIDADE PARA INDICADORES DE HABILIDADES</h4></td>
                    </tr>
                </table>
            ';
        } else {
            $html .= '
                <table width="100%" border="1" class="comBordasSimples">
                    <thead>
                        <tr>
                            <td colspan="5" style="font-weight: bold; background-color: #4472C4; text-align: center; color: #FFFFFF;"><h4>HABILIDADES</h4></td>
                            <td colspan="5" style="font-weight: bold; background-color: #4472C4; text-align: center; color: #FFFFFF;"><h4>MATRIZ DE MELHORIAS</h4></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; background-color: #4472C4; text-align: center; color: #FFFFFF;"><h6>POSIÇÃO</h5></td>
                            <td colspan="3" style="font-weight: bold; background-color: #4472C4; text-align: center; color: #FFFFFF;"><h5>NÃO CONFORMIDADES</h5></td>
                            <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF;"><h5>ÍNDICE ALCANÇADO</h5></td>
                            <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF;"><h5>PLANO DE AÇÃO</h5></td>
                            <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF;"><h5>EVIDÊNCIAS DOS RESULTADOS / METAS</h5></td>
                            <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF;"><h5>MATRIZ GUT</h5></td>
                            <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF;"><h5>PARTICIPANTES / ÁREA</h5></td>
                            <td style="font-weight: bold;  background-color: #4472C4; text-align: center; color: #FFFFFF;"><h5>SITUAÇÃO</h5></td>
                        </tr>
                    </thead>
                    <tbody>
            ';
            foreach ($view_pdi["indicadores_qualitativos"] as $value) {
                $total_l = count($value["plano_acao"]);
                $html .= '
                <tr>
                    <td rowspan="' . $total_l . '" style="text-align: center; font-size: 10px;">' . mb_strtoupper($value["avaliacao"]["posicao"], 'UTF-8') . '</td>
                    <td rowspan="' . $total_l . '" colspan="3" style="text-align: justify; font-size: 10px;">' . mb_strtoupper($value["descricao"], 'UTF-8') . '</td>
                    <td rowspan="' . $total_l . '" style="text-align: center; font-size: 10px;">ORDEM HORIZONTAL ' . mb_strtoupper($value["avaliacao"]["ordem_horizontal"], 'UTF-8') . ' ' . ($value["avaliacao"]["intermediario"] ? "INTERMEDIÁRIO" : "") . '</td>
                        ';
                $r = 0;
                foreach ($value["plano_acao"] as $value_plano_acao) {
                    if ($r > 0) {
                        $html .= '</tr><tr>';
                        $r = 0;
                    }
                    $html .= '
                    <td style="text-align: justify; font-size: 10px;">' . mb_strtoupper($value_plano_acao["plano_acao"], 'UTF-8') . '</td>
                    <td style="text-align: justify; font-size: 10px;">' . mb_strtoupper($value_plano_acao["evidencia"], 'UTF-8') . '</td>
                    <td style="text-align: center; font-size: 10px;">' . mb_strtoupper($value_plano_acao["gut"], 'UTF-8') . '</td>
                    <td style="text-align: center; font-size: 10px;">' . mb_strtoupper($value_plano_acao["participantes"], 'UTF-8') . '</td>
                    <td style="text-align: center; font-size: 10px;">' . $value_plano_acao["status_nome"] . '</td>
                ';
                    $r++;
                }

                $html .= '

                </tr>
            ';
            }
            $html .= '
                    </tbody>
                </table>
            ';
        }


        if (file_exists("temp/PDI.pdf")) {
            unlink("temp/PDI.pdf");
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
        $mpdf->Output('temp/PDI.pdf');

        $arquivo = "PDI.pdf";
        $file = $_SERVER["DOCUMENT_ROOT"] . '/temp/PDI.pdf';

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

    public function monta_plano_acao_atualizar($id_avaliacao) {

        $id_cliente = $_SESSION["id_cliente"];
        $avaliacao_plano_acao = new Avaliacao_Quantitativas_Plano_Acao_Model();
        $avaliacaao_plano_acao_habilidades = new Avaliacao_Qualitativas_Plano_Acao_Model();
        $estrutura = new Estrutura_Organizacional_Model();

        $result = $this->query("SELECT avaliacao.id, avaliacao.id_funcionario, avaliacao.id_pessoa, avaliacao.id_cargo, avaliacao.id_perfil_cargo, avaliacao.id_user_avaliador, pessoa.nome, cargo.descricao AS cargo, cargo.subtipo, cargo.id_estrutura_organizacional FROM `avaliacao` INNER JOIN pessoa ON (pessoa.id = avaliacao.id_pessoa) INNER JOIN cargo ON (cargo.id = avaliacao.id_cargo) WHERE avaliacao.id = '{$id_avaliacao}' AND avaliacao.id_cliente = '{$id_cliente}'");

        foreach ($result as $key => $value) {

            if ($result[$key]["subtipo"] == "1") {
                $subtipo = "JUNIOR";
            } else if ($result[$key]["subtipo"] == "2") {
                $subtipo = "PLENO";
            } else if ($result[$key]["subtipo"] == "3") {
                $subtipo = "SENIOR";
            }

            $result[$key]["descricao_formatado"] = $result[$key]["cargo"] . " " . $subtipo;
        }


        $ret["dados"] = $result[0];

        $id_cargo = $result[0]["id_cargo"];
        $id_perfil_cargo = $result[0]["id_perfil_cargo"];

        $ret["estrutura"] = $estrutura->getEstruturaById($result[0]["id_estrutura_organizacional"]);

        $result = $this->query("SELECT DISTINCT id_clients, id_cargo, id_perfil_cargo, id_indicadores_quant_tipo, indicadores_quant_tipo.descricao FROM `perfil_cargo_indicadores` INNER JOIN indicadores_quant_tipo ON (indicadores_quant_tipo.id = perfil_cargo_indicadores.id_indicadores_quant_tipo) WHERE perfil_cargo_indicadores.id_clients = '{$id_cliente}' AND perfil_cargo_indicadores.id_cargo = '{$id_cargo}' AND perfil_cargo_indicadores.id_perfil_cargo = '{$id_perfil_cargo}' ORDER BY indicadores_quant_tipo.descricao ASC ");

        $indicadores_quantitativos = [];

        foreach ($result as $key => $value) {
            $result_respostas = $this->query("SELECT avaliacao_indicadores_quantitativos.id, avaliacao_indicadores_quantitativos.id_avaliavao, avaliacao_indicadores_quantitativos.id_indicadores_quant_tipo, avaliacao_indicadores_quantitativos.id_indicadores_quant_sub, avaliacao_indicadores_quantitativos.id_perfil_cargo_indicadores, avaliacao_indicadores_quantitativos.id_indicadores_quant_sub_negacao, avaliacao_indicadores_quantitativos.resposta, perfil_cargo_indicadores.descricao AS indicadores, indicadores_quant_sub_negacao.descricao AS negacao FROM `avaliacao_indicadores_quantitativos` INNER JOIN perfil_cargo_indicadores ON (perfil_cargo_indicadores.id = avaliacao_indicadores_quantitativos.id_perfil_cargo_indicadores) INNER JOIN indicadores_quant_sub_negacao ON (indicadores_quant_sub_negacao.id = avaliacao_indicadores_quantitativos.id_indicadores_quant_sub_negacao) WHERE avaliacao_indicadores_quantitativos.id_avaliavao = '{$id_avaliacao}' AND avaliacao_indicadores_quantitativos.resposta = '0' AND avaliacao_indicadores_quantitativos.id_indicadores_quant_tipo = '{$value["id_indicadores_quant_tipo"]}'");

            if (count($result_respostas) == 0) {
                unset($result[$key]);
            } else {

                foreach ($result_respostas as $key_plano_acao => $value_plano_acao) {
                    $result_respostas[$key_plano_acao]["plano_acao"] = $avaliacao_plano_acao->getByIdAvaliacaoIndicadoresQuantitativos($value_plano_acao["id"]);
                }

                $indicadores_quantitativos[$key] = $result[$key];
                $indicadores_quantitativos[$key]["indicadores"] = $result_respostas;
            }
        }

        $ret["indicadores_quantitativos"] = $indicadores_quantitativos;

        $result = $this->query("SELECT cargo_indi_quali.id, cargo_indi_quali.id_indicador, indicadores_quali.descricao, indicadores_quali.conceito, cargo.tipo FROM cargo_indi_quali INNER JOIN indicadores_quali ON (indicadores_quali.id = cargo_indi_quali.id_indicador) INNER JOIN cargo ON (cargo.id = cargo_indi_quali.id_cargo) WHERE cargo_indi_quali.id_cliente = '{$id_cliente}' AND cargo_indi_quali.id_cargo = '{$id_cargo}' ORDER BY cargo_indi_quali.posicao ASC  ");

        foreach ($result as $key => $value) {
            $result_habilidades = $this->query("SELECT avaliacao_indicadores_qualitativos.id, avaliacao_indicadores_qualitativos.id_avaliacao, avaliacao_indicadores_qualitativos.id_indicadores_quali, avaliacao_indicadores_qualitativos.id_indicadores_quali_graduacao, indicadores_quali_graduacao.ordem_horizontal, indicadores_quali_graduacao.descricao, avaliacao_indicadores_qualitativos.intermediario, CONCAT(indicadores_quali_graduacao.ordem_horizontal, avaliacao_indicadores_qualitativos.intermediario) AS con, avaliacao_indicadores_qualitativos.posicao, avaliacao_indicadores_qualitativos.peso FROM `avaliacao_indicadores_qualitativos` INNER JOIN indicadores_quali_graduacao ON (indicadores_quali_graduacao.id = avaliacao_indicadores_qualitativos.id_indicadores_quali_graduacao) WHERE avaliacao_indicadores_qualitativos.id_avaliacao = '{$id_avaliacao}' AND avaliacao_indicadores_qualitativos.id_indicadores_quali = '{$value["id_indicador"]}' AND indicadores_quali_graduacao.ordem_horizontal <= '2' AND CONCAT(indicadores_quali_graduacao.ordem_horizontal,avaliacao_indicadores_qualitativos.intermediario) <= '21' ORDER BY avaliacao_indicadores_qualitativos.posicao ASC ");

            if (!count($result_habilidades)) {
                unset($result[$key]);
            } else {
                $result[$key]["avaliacao"] = $result_habilidades[0];

                $result[$key]["plano_acao"] = $avaliacaao_plano_acao_habilidades->getByIdAvaliacaoIndicadoresQualitativas($result_habilidades[0]["id"]);
            }
        }

        $ret["indicadores_qualitativos"] = $result;

        return $ret;
    }

    public function atualizar_status_plano_acao($id_avaliacao, $indicadores_quantitativos, $indicadore_qualitativos) {
        $quantitativas = new Avaliacao_Quantitativas_Plano_Acao_Model();
        $qualitativos = new Avaliacao_Qualitativas_Plano_Acao_Model();

        $quant = $quantitativas->atualizar_status_plano_acao($indicadores_quantitativos);
        $quali = $qualitativos->atualizar_status_plano_acao($indicadore_qualitativos);

        if ($quant && $quali) {
            $arr["status"] = true;
            $arr["message"] = "Plano de Ação atualizado com Sucesso!";
        } else {
            $arr["status"] = false;
            $arr["message"] = "Erro ao atualiza o Plano de Ação!\n Quantitativos: " . ($quant ? "OK" : "Erro") . "\nQualitativos: " . ($quali ? "OK" : "Erro");
        }

        $this->log("Alteração", $arr["message"]);
        return json_encode($arr);
    }

    public function getFuncionariosDica() {
        $id_cliente = $_SESSION["id_cliente"];
        return $this->query("SELECT DISTINCT(avaliacao.id_funcionario), pessoa.nome, cargo.descricao AS cargo FROM `avaliacao` INNER JOIN pessoa ON (pessoa.id = avaliacao.id_pessoa) INNER JOIN cargo ON (cargo.id = avaliacao.id_cargo) WHERE avaliacao.id_cliente = '{$id_cliente}' ORDER BY pessoa.nome ASC ");
    }

    public function getDica($id_funcionario) {
        $id_cliente = $_SESSION["id_cliente"];
        $habilidades = new Indicadores_Quali_Model();

        $result = $this->query("SELECT COUNT(*) AS total, DATE_FORMAT(data_avaliacao,'%Y') AS ano FROM avaliacao WHERE id_funcionario = '{$id_funcionario}' AND id_cliente = '{$id_cliente}' GROUP BY DATE_FORMAT(data_avaliacao,'%Y') ORDER BY DATE_FORMAT(data_avaliacao,'%Y') DESC ");
        foreach ($result as $key => $value) {
            $r2 = $this->query("SELECT indicadores_quant_tipo.descricao AS competencia, COUNT(*) AS previsto, COUNT(CASE WHEN resposta=1 THEN 1 ELSE null END) AS realizado, avaliacao_indicadores_quantitativos.id_indicadores_quant_tipo FROM avaliacao INNER JOIN avaliacao_indicadores_quantitativos ON (avaliacao_indicadores_quantitativos.id_avaliavao = avaliacao.id) INNER JOIN indicadores_quant_tipo ON (indicadores_quant_tipo.id = avaliacao_indicadores_quantitativos.id_indicadores_quant_tipo) WHERE YEAR(data_avaliacao) = '{$value["ano"]}' AND id_cliente = '{$id_cliente}' AND id_funcionario = '{$id_funcionario}' GROUP BY avaliacao_indicadores_quantitativos.id_indicadores_quant_tipo ORDER BY indicadores_quant_tipo.descricao ASC ");
            $result[$key]["competencia"] = $r2;
        }

        $ret["quantitativo"] = $result;

        $result = $this->query("SELECT COUNT(*) AS total, DATE_FORMAT(data_avaliacao,'%Y') AS ano FROM avaliacao WHERE id_funcionario = '{$id_funcionario}' AND id_cliente = '{$id_cliente}' GROUP BY DATE_FORMAT(data_avaliacao,'%Y') ORDER BY DATE_FORMAT(data_avaliacao,'%Y') DESC ");
        foreach ($result as $key => $value) {
            $result[$key]["competencia"] = array();
            foreach ($habilidades->get() as $hab) {
                $r2 = $this->query("SELECT hab.habilidade, (SUM(hab.realizado)/COUNT(hab.realizado)) AS total FROM ( SELECT indicadores_quali.descricao AS habilidade, (CONCAT(indicadores_quali_graduacao.ordem_horizontal,avaliacao_indicadores_qualitativos.intermediario)/40)*100 AS realizado FROM `avaliacao_indicadores_qualitativos` INNER JOIN avaliacao ON (avaliacao.id = avaliacao_indicadores_qualitativos.id_avaliacao) INNER JOIN indicadores_quali ON (indicadores_quali.id = avaliacao_indicadores_qualitativos.id_indicadores_quali) INNER JOIN indicadores_quali_graduacao ON (indicadores_quali_graduacao.id = avaliacao_indicadores_qualitativos.id_indicadores_quali_graduacao) WHERE YEAR(avaliacao.data_avaliacao) = '{$value["ano"]}' AND avaliacao_indicadores_qualitativos.id_indicadores_quali = '{$hab["id"]}' ) AS hab WHERE hab.habilidade != '' ");
                if ($r2[0]["habilidade"] != "")
                    array_push($result[$key]["competencia"], $r2[0]);
            }
        }

        $ret["habilidade"] = $result;
        return $ret;
    }

    public function gerarComparativoGDP($dataIni, $dataFim) {
        $id_cliente = $_SESSION["id_cliente"];

        $retotno = array();

        $dt_ini = implode("-", array_reverse(explode("/", $dataIni))) . " 00:00:00";
        $dt_fim = implode("-", array_reverse(explode("/", $dataFim))) . " 23:59:59";

        $retorno["status"] = true;
        $retorno["qualiXquant"] = $this->gdpQualitativoQuantitativo($dt_ini, $dt_fim);

        $retorno["GdpXEscolaridade"] = $this->gdpEscolaridadeXGDP($dt_ini, $dt_fim);

        $retorno["gdpXsexo"] = $this->gdpSexo($dt_ini, $dt_fim);

        $retorno["gdpXsalario"] = $this->gdpFaixaSalarial($dt_ini, $dt_fim);

        $retorno["gdpXfaixaIdade"] = $this->gdpFaixaIdade($dt_ini, $dt_fim);

        $retorno["gdpXarea"] = $this->gdpPorArea($dt_ini, $dt_fim);
        $retorno["gdpXcargo"] = $this->gdpPorCargo($dt_ini, $dt_fim);
        $retorno["gdpXnat"] = $this->gdpPorNat($dt_ini, $dt_fim);
        $retorno["gdpxFuncionario"] = $this->gdpPorFuncionario($dt_ini, $dt_fim);

        return json_encode($retorno, JSON_NUMERIC_CHECK);
    }

    private function gdpQualitativoQuantitativo($dtIni, $dtFim) {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->read("id_cliente = '{$id_cliente}' AND data_avaliacao >= '{$dtIni}' AND data_avaliacao <= '{$dtFim}'", null, null, null, "SUM(resultado_quantitativo) AS resultado_quantitativo, SUM(resultado_qualitativa) AS resultado_qualitativa");
        return array("resultado_quantitativo" => number_format($result[0]["resultado_quantitativo"], 2), "resultado_qualitativa" => number_format($result[0]["resultado_qualitativa"], 2));
    }

    private function gdpEscolaridadeXGDP($dtIni, $dtFim) {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->query("SELECT COUNT(avaliacao.id) AS total, IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 90,'A',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 80,'B',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 70,'C',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 50,'D','E')))) AS gdp , pessoa.grau_instrucao FROM `avaliacao` INNER JOIN pessoa ON (pessoa.id = avaliacao.id_pessoa) WHERE avaliacao.id_cliente = '{$id_cliente}' AND avaliacao.data_avaliacao >= '{$dtIni}' AND avaliacao.data_avaliacao <= '$dtFim' GROUP BY gdp, pessoa.grau_instrucao ORDER BY pessoa.grau_instrucao ASC, gdp ASC ");

        $ar = array();

        $nat = "";
        $temp = array();
        foreach ($result as $value) {
            if ($nat == "") {
                $temp["nat"] = $value["grau_instrucao"];
                $temp[$value["gdp"]] = $value["total"];
                $nat = $value["grau_instrucao"];
            } else if ($nat == $value["grau_instrucao"]) {
                $temp[$value["gdp"]] = $value["total"];
            } else if ($nat != $value["grau_instrucao"]) {
                array_push($ar, $temp);
                $temp = array();
                $temp["nat"] = $value["grau_instrucao"];
                $temp[$value["gdp"]] = $value["total"];
                $nat = $value["grau_instrucao"];
            }
        }
        array_push($ar, $temp);

        $arr = array(
            array("Grau Instrução", "A", array("role" => "annotation"), "B", array("role" => "annotation"), "C", array("role" => "annotation"), "D", array("role" => "annotation"), "E", array("role" => "annotation")),
        );

        foreach ($ar as $key => $value) {
            if (!isset($value["A"]))
                $ar[$key]["A"] = 0;
            if (!isset($value["B"]))
                $ar[$key]["B"] = 0;
            if (!isset($value["C"]))
                $ar[$key]["C"] = 0;
            if (!isset($value["D"]))
                $ar[$key]["D"] = 0;
            if (!isset($value["E"]))
                $ar[$key]["E"] = 0;


            array_push($arr, array($this->getGrauInstrucao($value["nat"]), $ar[$key]["A"], "A " . $ar[$key]["A"], $ar[$key]["B"], "B " . $ar[$key]["B"], $ar[$key]["C"], "C " . $ar[$key]["C"], $ar[$key]["D"], "D " . $ar[$key]["D"], $ar[$key]["E"], "E " . $ar[$key]["E"]));
        }

        return $arr;
    }

    private function gdpSexo($dtIni, $dtFim) {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->query("SELECT COUNT(avaliacao.id) AS total, IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 90,'A',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 80,'B',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 70,'C',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 50,'D','E')))) AS gdp , pessoa.sexo FROM `avaliacao` INNER JOIN pessoa ON (pessoa.id = avaliacao.id_pessoa) WHERE avaliacao.id_cliente = '{$id_cliente}' AND avaliacao.data_avaliacao >= '{$dtIni}' AND avaliacao.data_avaliacao <= '{$dtFim}' GROUP BY gdp, pessoa.sexo ORDER BY pessoa.sexo ASC, gdp ASC ");

        $ar = array();

        $nat = "";
        $temp = array();
        foreach ($result as $value) {
            if ($nat == "") {
                $temp["nat"] = $value["sexo"];
                $temp[$value["gdp"]] = $value["total"];
                $nat = $value["sexo"];
            } else if ($nat == $value["sexo"]) {
                $temp[$value["gdp"]] = $value["total"];
            } else if ($nat != $value["sexo"]) {
                array_push($ar, $temp);
                $temp = array();
                $temp["nat"] = $value["sexo"];
                $temp[$value["gdp"]] = $value["total"];
                $nat = $value["sexo"];
            }
        }
        array_push($ar, $temp);

        $arr = array(
            array("Sexo", "A", array("role" => "annotation"), "B", array("role" => "annotation"), "C", array("role" => "annotation"), "D", array("role" => "annotation"), "E", array("role" => "annotation")),
        );

        foreach ($ar as $key => $value) {
            if (!isset($value["A"]))
                $ar[$key]["A"] = 0;
            if (!isset($value["B"]))
                $ar[$key]["B"] = 0;
            if (!isset($value["C"]))
                $ar[$key]["C"] = 0;
            if (!isset($value["D"]))
                $ar[$key]["D"] = 0;
            if (!isset($value["E"]))
                $ar[$key]["E"] = 0;


            array_push($arr, array($value["nat"], $ar[$key]["A"], "A " . $ar[$key]["A"], $ar[$key]["B"], "B " . $ar[$key]["B"], $ar[$key]["C"], "C " . $ar[$key]["C"], $ar[$key]["D"], "D " . $ar[$key]["D"], $ar[$key]["E"], "E " . $ar[$key]["E"]));
        }

        return $arr;
    }

    private function gdpFaixaSalarial($dtIni, $dtFim) {
        $id_cliente = $_SESSION["id_cliente"];

        $result = $this->query("SELECT COUNT(avaliacao.id) AS total, IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 90,'A',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 80,'B',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 70,'C',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 50,'D','E')))) AS gdp , funcionarios.salario FROM `avaliacao` INNER JOIN funcionarios ON (funcionarios.id = avaliacao.id_funcionario) WHERE avaliacao.id_cliente = '{$id_cliente}' AND avaliacao.data_avaliacao >= '{$dtIni}' AND avaliacao.data_avaliacao <= '{$dtFim}' GROUP BY gdp, funcionarios.salario ORDER BY funcionarios.salario ASC, gdp ASC ");

        $array = array();

        foreach ($result as $value) {
            if ($value["salario"] <= 1000)
                $array[0][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["salario"] <= 2000)
                $array[1][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["salario"] <= 3000)
                $array[2][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["salario"] <= 4000)
                $array[3][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["salario"] <= 5000)
                $array[4][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["salario"] <= 6000)
                $array[5][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["salario"] <= 7000)
                $array[6][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["salario"] <= 8000)
                $array[7][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["salario"] <= 9000)
                $array[8][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["salario"] <= 10000)
                $array[9][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["salario"] > 10000)
                $array[10][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
        }

        $arr = array(
            array("Faixa Salarial", "A", array("role" => "annotation"), "B", array("role" => "annotation"), "C", array("role" => "annotation"), "D", array("role" => "annotation"), "E", array("role" => "annotation")),
        );

        foreach ($array as $key => $value) {

            $a = (isset($value["A"]) ? $value["A"] : 0);
            $b = (isset($value["B"]) ? $value["B"] : 0);
            $c = (isset($value["C"]) ? $value["C"] : 0);
            $d = (isset($value["D"]) ? $value["D"] : 0);
            $e = (isset($value["E"]) ? $value["E"] : 0);

            if ($key == 0)
                array_push($arr, array("Até R$ 1000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 1)
                array_push($arr, array("Até R$ 2000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 2)
                array_push($arr, array("Até R$ 3000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 3)
                array_push($arr, array("Até R$ 4000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 4)
                array_push($arr, array("Até R$ 5000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 5)
                array_push($arr, array("Até R$ 6000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 6)
                array_push($arr, array("Até R$ 7000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 7)
                array_push($arr, array("Até R$ 8000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 8)
                array_push($arr, array("Até R$ 9000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 9)
                array_push($arr, array("Até R$ 10000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 10)
                array_push($arr, array("+ R$ 10000", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
        }




        return $arr;
    }

    private function gdpFaixaIdade($dtIni, $dtFim) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->query("SELECT COUNT(avaliacao.id) AS total, IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 90,'A',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 80,'B',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 70,'C',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 50,'D','E')))) AS gdp , YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(pessoa.dt_nascimento))) AS idade FROM `avaliacao` INNER JOIN pessoa ON (pessoa.id = avaliacao.id_pessoa) WHERE avaliacao.id_cliente = '{$id_cliente}' AND avaliacao.data_avaliacao >= '{$dtIni}' AND avaliacao.data_avaliacao <= '{$dtFim}' GROUP BY gdp, pessoa.dt_nascimento ORDER BY pessoa.dt_nascimento DESC, gdp ASC ");

        $arr = array(
            array("Faixa de Idade", "A", array("role" => "annotation"), "B", array("role" => "annotation"), "C", array("role" => "annotation"), "D", array("role" => "annotation"), "E", array("role" => "annotation")),
        );

        $array = array();
        foreach ($result as $value) {
            if ($value["idade"] <= 26)
                $array[0][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["idade"] <= 36)
                $array[1][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["idade"] <= 46)
                $array[2][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
            else if ($value["idade"] > 46)
                $array[3][$value["gdp"]] = (isset($array[0][$value["gdp"]]) ? $array[0][$value["gdp"]] + $value["total"] : $value["total"]);
        }

        foreach ($array as $key => $value) {

            $a = (isset($value["A"]) ? $value["A"] : 0);
            $b = (isset($value["B"]) ? $value["B"] : 0);
            $c = (isset($value["C"]) ? $value["C"] : 0);
            $d = (isset($value["D"]) ? $value["D"] : 0);
            $e = (isset($value["E"]) ? $value["E"] : 0);

            if ($key == 0)
                array_push($arr, array("Até 26", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 1)
                array_push($arr, array("Até 36", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 2)
                array_push($arr, array("Até 46", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
            else if ($key == 3)
                array_push($arr, array("+ 46", $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
        }



        return $arr;
    }

    private function gdpPorArea($dtIni, $dtFim) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->query("SELECT COUNT(avaliacao.id) AS total, IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 90,'A',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 80,'B',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 70,'C',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 50,'D','E')))) AS gdp, estrutura_organizacional.id, estrutura_organizacional.descricao FROM `avaliacao` INNER JOIN cargo ON (cargo.id = avaliacao.id_cargo) INNER JOIN estrutura_organizacional ON (estrutura_organizacional.id = cargo.id_estrutura_organizacional) WHERE avaliacao.id_cliente = '{$id_cliente}' AND avaliacao.data_avaliacao >= '{$dtIni}' AND avaliacao.data_avaliacao <= '{$dtFim}' GROUP BY gdp, estrutura_organizacional.descricao ORDER BY estrutura_organizacional.descricao ASC, gdp ASC ");

        $arr = array(
            array("Área", "A", array("role" => "annotation"), "B", array("role" => "annotation"), "C", array("role" => "annotation"), "D", array("role" => "annotation"), "E", array("role" => "annotation")),
        );

        $array = array();
        foreach ($result as $value) {
            $array[$value["descricao"]][$value["gdp"]] = $value["total"];
        }

        foreach ($array as $key => $value) {
            $a = (isset($value["A"]) ? $value["A"] : 0);
            $b = (isset($value["B"]) ? $value["B"] : 0);
            $c = (isset($value["C"]) ? $value["C"] : 0);
            $d = (isset($value["D"]) ? $value["D"] : 0);
            $e = (isset($value["E"]) ? $value["E"] : 0);

            array_push($arr, array($key, $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
        }

        return $arr;
    }

    private function gdpPorCargo($dtIni, $dtFim) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->query("SELECT COUNT(avaliacao.id) AS total, IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 90,'A',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 80,'B',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 70,'C',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 50,'D','E')))) AS gdp , cargo.descricao FROM `avaliacao` INNER JOIN funcionarios ON (funcionarios.id = avaliacao.id_funcionario) INNER JOIN cargo ON (cargo.id = funcionarios.id_cargo) WHERE avaliacao.id_cliente = '{$id_cliente}' AND avaliacao.data_avaliacao >= '{$dtIni}' AND avaliacao.data_avaliacao <= '{$dtFim}' GROUP BY gdp, cargo.descricao ORDER BY cargo.descricao ASC, gdp ASC  ");

        $arr = array(
            array("Cargo", "A", array("role" => "annotation"), "B", array("role" => "annotation"), "C", array("role" => "annotation"), "D", array("role" => "annotation"), "E", array("role" => "annotation")),
        );

        $array = array();
        foreach ($result as $value) {
            $array[$value["descricao"]][$value["gdp"]] = $value["total"];
        }

        foreach ($array as $key => $value) {
            $a = (isset($value["A"]) ? $value["A"] : 0);
            $b = (isset($value["B"]) ? $value["B"] : 0);
            $c = (isset($value["C"]) ? $value["C"] : 0);
            $d = (isset($value["D"]) ? $value["D"] : 0);
            $e = (isset($value["E"]) ? $value["E"] : 0);

            array_push($arr, array($key, $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
        }

        return $arr;
    }

    private function gdpPorNat($dtIni, $dtFim) {
        $id_cliente = $_SESSION["id_cliente"];
        $result = $this->query("SELECT COUNT(avaliacao.id) AS total, IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 90,'A',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 80,'B',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 70,'C',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 50,'D','E')))) AS gdp , nat_ocupacional.descricao FROM `avaliacao` INNER JOIN funcionarios ON (funcionarios.id = avaliacao.id_funcionario) INNER JOIN cargo ON (cargo.id = funcionarios.id_cargo) INNER JOIN nat_ocupacional ON (nat_ocupacional.id = cargo.id_nat_ocupacional) WHERE avaliacao.id_cliente = '{$id_cliente}' AND avaliacao.data_avaliacao >= '{$dtIni}' AND avaliacao.data_avaliacao <= '{$dtFim}' GROUP BY gdp, nat_ocupacional.descricao ORDER BY nat_ocupacional.descricao ASC, gdp ASC ");

        $arr = array(
            array("Natureza Ocupacional", "A", array("role" => "annotation"), "B", array("role" => "annotation"), "C", array("role" => "annotation"), "D", array("role" => "annotation"), "E", array("role" => "annotation")),
        );

        $array = array();
        foreach ($result as $value) {
            $array[$value["descricao"]][$value["gdp"]] = $value["total"];
        }

        foreach ($array as $key => $value) {
            $a = (isset($value["A"]) ? $value["A"] : 0);
            $b = (isset($value["B"]) ? $value["B"] : 0);
            $c = (isset($value["C"]) ? $value["C"] : 0);
            $d = (isset($value["D"]) ? $value["D"] : 0);
            $e = (isset($value["E"]) ? $value["E"] : 0);

            array_push($arr, array($key, $a, "A " . $a, $b, "B " . $b, $c, "C " . $c, $d, "D " . $d, $e, "E " . $e));
        }


        return $arr;
    }

    private function gdpPorFuncionario($dtIni, $dtFim) {
        $id_cliente = $_SESSION["id_cliente"];
        return $this->query("SELECT IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 90,'A',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 80,'B',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 70,'C',IF((avaliacao.resultado_quantitativo + avaliacao.resultado_qualitativa)/2 >= 50,'D','E')))) AS gdp,estrutura_organizacional.descricao, pessoa.nome, DATE_FORMAT(avaliacao.data_avaliacao, '%d/%m/%Y') AS data FROM `avaliacao` INNER JOIN cargo ON (cargo.id = avaliacao.id_cargo) INNER JOIN estrutura_organizacional ON (estrutura_organizacional.id = cargo.id_estrutura_organizacional) INNER JOIN pessoa ON (pessoa.id = avaliacao.id_pessoa) WHERE avaliacao.id_cliente = '{$id_cliente}' AND avaliacao.data_avaliacao >= '{$dtIni}' AND avaliacao.data_avaliacao <= '{$dtFim}' ORDER BY estrutura_organizacional.descricao ASC, pessoa.nome ASC ");
    }

    private function getGrauInstrucao($codeGrauInstrucao) {
        if ($codeGrauInstrucao == "1")
            return "Analfabeto";
        else if ($codeGrauInstrucao == "6")
            return "Primario Incompleto";
        else if ($codeGrauInstrucao == "11")
            return "Primario Completo";
        else if ($codeGrauInstrucao == "16")
            return "Ginasial Incompleto";
        else if ($codeGrauInstrucao == "21")
            return "Ginasial Completo";
        else if ($codeGrauInstrucao == "26")
            return "Colegial Incompleto";
        else if ($codeGrauInstrucao == "31")
            return "Colegial Completo";
        else if ($codeGrauInstrucao == "36")
            return "Superior Incompleto";
        else if ($codeGrauInstrucao == "41")
            return "Superior Completo";
        else
            return "Grau";
    }

}
