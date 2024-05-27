<?php

class Log_Model extends Model {

    public $_tabela = "log";

    public function gravarLog($tabela, $tipo, $operecao) {
        if (isset($_SESSION["user_name"])) {
            $user = $_SESSION["user_name"];
        } else {
            $user = "SISTEMA";
        }
        return $this->insert(array(
                    "data" => date("Y-m-d"),
                    "hora" => date("H:i:s"),
                    "modulo" => $tabela,
                    "tipo" => $tipo,
                    "usuario" => $user,
                    "operacao" => $operecao
        ));
    }

    public function getUsers($usuario) {
        $result = $this->query("SELECT DISTINCT usuario FROM log WHERE usuario LIKE '%{$usuario}%' ORDER BY usuario ASC");
        $mostra = null;
        $total = count($result);
        for ($i = 0; $i <= $total - 1; $i++) {
            $mostra .= '<a href="javascript:setPesquisa(\'' . $result[$i]["usuario"] . '\')">' . $result[$i]["usuario"] . "</a><br />";
        }
        return $mostra;
    }

    public function getLogTela2($usuario = NULL, $data_ini = NULL, $data_fim = NULL) {

        $dt_ini = ($data_ini != "" ? implode("-", array_reverse(explode("/", $data_ini))) : date("Y-m-d"));
        $dt_fim = ($data_fim != "" ? implode("-", array_reverse(explode("/", $data_fim))) : date("Y-m-d"));

        if ($usuario == "") {
            $result = $this->read("data >= '{$dt_ini}' AND data <= '{$dt_fim}'", NULL, NULL, "id DESC");
        } else {
            $result = $this->read("usuario = '{$usuario}' AND data >= '{$dt_ini}' AND data <= '{$dt_fim}'", NULL, NULL, "id DESC");
        }


        $html = '
            <table id="tabela" style="width:100%;">
                    <tr>
                        <td style="width:3%;">#</td>
                        <td style="width:20%;">Usuário</td>
                        <td style="width:10%;">Data</td>
                        <td style="width:9%;">Hora</td>
                        <td style="width:10%;">Módulo</td>
                        <td style="width:17%">Tipo</td>
                        <td style="width:30%;">Operação</td>
                    </tr>
                     ';
        $c = 1;
        foreach ($result as $key => $value) {
            $html .= '
                    <tr>
                        <td style="width:3%;">' . $c . '</td>
                        <td style="width:20%;">' . $value["usuario"] . '</td>
                        <td style="width:10%;">' . implode("/", array_reverse(explode("-", $value["data"]))) . '</td>
                        <td style="width:9%;">' . $value["hora"] . '</td>
                        <td style="width:10%;">' . $value["modulo"] . '</td>
                        <td style="width:17%">' . $value["tipo"] . '</td>
                        <td style="width:25%; word-wrap:break-word;">' . wordwrap($value["operacao"], 45, "<br />", TRUE) . '</td>
                    </tr>
                ';
            $c++;
        }

        $html .= '
                    </table>
        ';
        return $html;
    }

    public function getLogTela($usuario = NULL, $data_ini = NULL, $data_fim = NULL) {

        $dt_ini = ($data_ini != "" ? implode("-", array_reverse(explode("/", $data_ini))) : date("Y-m-d"));
        $dt_fim = ($data_fim != "" ? implode("-", array_reverse(explode("/", $data_fim))) : date("Y-m-d"));

        if ($usuario == "") {
            $result = $this->read("data >= '{$dt_ini}' AND data <= '{$dt_fim}'", NULL, NULL, "id DESC");
        } else {
            $result = $this->read("usuario = '{$usuario}' AND data >= '{$dt_ini}' AND data <= '{$dt_fim}'", NULL, NULL, "id DESC");
        }


        $html = '<div class="panel panel-default">
            <div class="panel-body" >
                <div class="row">
                    <div class="col-lg-12">
                      <table>
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Usuário</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Módulo</th>
                                <th>Tipo</th>
                                <th>Operação</th>
                            </tr>
                     ';
        $c = 1;
        foreach ($result as $key => $value) {
            $html .= '
                    <tr>
                        <td style="padding-right:20px">' . $c . '</td>
                        <td style="padding-right:20px">' . $value["usuario"] . '</td>
                        <td style="padding-right:20px">' . implode("/", array_reverse(explode("-", $value["data"]))) . '</td>
                        <td style="padding-right:20px">' . $value["hora"] . '</td>
                        <td style="padding-right:20px">' . $value["modulo"] . '</td>
                        <td style="padding-right:20px">' . $value["tipo"] . '</td>
                        <td style="padding-right:20px">' . $value["operacao"] . '</td>
                    </tr>
                ';
            $c++;
        }

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

    public function getLogPDF($usuario = NULL, $data_ini = NULL, $data_fim = NULL) {
        $html = '

<style type="text/css">

    table.page_header {width: 100%; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm;word-wrap:break-word; word-break: break-all;table-layout: fixed; }
    table.page_footer {width: 100%; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm; font-size: 9px;word-wrap:break-word; word-break: break-all;table-layout: fixed;}
    div.note {border: solid 1mm #DDDDDD;background-color: #EEEEEE; padding: 2mm; border-radius: 2mm; width: 100%; }
    ul.main { width: 95%; list-style-type: square; }
    ul.main li { padding-bottom: 2mm; }
    h1 { text-align: center; font-size: 20mm}
    h3 { text-align: center; font-size: 14mm}
    #tabela{
        font-size:9px;
    }

</style>

<page backtop="28mm" backbottom="14mm" backleft="1mm" backright="1mm" style="font-size: 10pt">
    <page_header>
        <table class="page_header">
            <tr>
                <td style="width: 100%; text-align: center">
                    <img src="' . $_SERVER["DOCUMENT_ROOT"] . '/webfiles/img/logo_report.png" ><br />
                    RELATÓRIO DE ACESSO
                </td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 33%; text-align: left;">
                    ' . TITLE . '<br />Desenvolvido por DEM Tecnologia
                </td>
                <td style="width: 34%; text-align: center">
                    ' . date('d/m/Y H:i:s') . ' <br /> ' . $_SESSION["user_id"] . '-' . $_SESSION["user_name"] . '
                </td>
                <td style="width: 33%; text-align: right">
                    page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>
    </page_footer>

    <br>
';

        $html .= $this->getLogTela2($usuario, $data_ini, $data_fim);

        $html .= '</page>';

        $html2pdf = new html2pdf('P', 'A4', 'pt', true, 'UTF-8', array(1, 1, 1, 1));

        $html2pdf->writeHTML($html);
        $html2pdf->Output($_SERVER["DOCUMENT_ROOT"] . '/temp/Acessos.pdf', 'F');
    }

    public function getLogPDF2($usuario = NULL, $data_ini = NULL, $data_fim = NULL) {
        $html = '
<style>
body{
background-color:#FFFFFF;
font-size: 10px;
font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;
}
.panel{
margin-bottom:20px;
background-color:#fff;
border:1px solid transparent;
border-radius:4px;
-webkit-box-shadow:0 1px 1px rgba(0, 0, 0, .05);
box-shadow:0 1px 1px rgba(0, 0, 0, .05)
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
<htmlpageheader name = "myHTMLHeader" style = "display:none;">
<table>

<tr>
<td align = "center"><img src = "' . $_SERVER["DOCUMENT_ROOT"] . '/webfiles/img/1_logo_report.jpg" /></td>
</tr>
<tr>
<td align = "center"><h3><b>RELATÓRIO DE ACESSOS</b></h3></td>
</tr>
</table>
</htmlpageheader>
<htmlpagefooter name = "myHTMLFooter">
<table>
<tbody>
<tr style = "" >
<td class = "rodape_esq" >CRRH CONSULTORIA<br /></td>
<td style = "text-align: right; border-top: 1px solid #000" >' . date("d/m/Y") . '&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
Pág.: {PAGENO}</td>
<td class = "rodape_dir" ></td>
</tr>
</tbody>
</table>
</htmlpagefooter>
<div>
<sethtmlpageheader name = "myHTMLHeader" value = "on" show-this-page = "1" />
<sethtmlpagefooter name = "myHTMLFooter" show-this-page = "1" value = "on" />
<div>
';
        $html .= $this->getLogTela($usuario, $data_ini, $data_fim);


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
        $mpdf->Output($_SERVER["DOCUMENT_ROOT"] . '/temp/Acessos.pdf', "F");
    }

    public function getLogEXCEL($usuario = NULL, $data_ini = NULL, $data_fim = NULL) {
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Diego Rodrigues Costa")
                ->setLastModifiedBy("Diego Rodrigues Costa")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G4');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getStyle('A:G')->getFont()->setSize(7);

        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        $objDrawing->setPath($_SERVER["DOCUMENT_ROOT"] . '/webfiles/img/logo_report.png');
        $objDrawing->setCoordinates('A1');
        $objDrawing->setResizeProportional(TRUE);
//        $objDrawing->setWidth(600);
//        $objDrawing->setHeight(70);
//        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        $objPHPExcel->getActiveSheet()->mergeCells('A6:G7');
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A6', 'RELATÓRIO DE ACESSOS');

        $objPHPExcel->getActiveSheet()->getStyle('A9:G9')->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A9', '#');

        $objPHPExcel->getActiveSheet()->getStyle('B9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('B9', 'Usuário');

        $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('C9', 'Data');

        $objPHPExcel->getActiveSheet()->getStyle('D9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('D9', 'Hora');

        $objPHPExcel->getActiveSheet()->getStyle('E9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('E9', 'Módulo');

        $objPHPExcel->getActiveSheet()->getStyle('F9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('F9', 'Tipo');

        $objPHPExcel->getActiveSheet()->getStyle('G9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValue('G9', 'Operação');

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
        $objPHPExcel->getActiveSheet()->getStyle('A9:G9')->applyFromArray($styleThickBrownBorderOutline);



        $dt_ini = ($data_ini != "" ? implode("-", array_reverse(explode("/", $data_ini))) : date("Y-m-d"));
        $dt_fim = ($data_fim != "" ? implode("-", array_reverse(explode("/", $data_fim))) : date("Y-m-d"));

        if ($usuario == "") {
            $result = $this->read("data >= '{$dt_ini}' AND data <= '{$dt_fim}'", NULL, NULL, "id DESC");
        } else {
            $result = $this->read("usuario = '{$usuario}' AND data >= '{$dt_ini}' AND data <= '{$dt_fim}'", NULL, NULL, "id DESC");
        }

        $c = 1;
        $i = 10;
        foreach ($result as $key => $value) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $c);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $value["usuario"]);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, implode("/", array_reverse(explode("-", $value["data"]))));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $value["hora"]);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $value["modulo"]);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $value["tipo"]);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $value["operacao"]);
            $c++;
            $i++;
        }



        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->setTitle('Empresas Cadastradas');
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($_SERVER["DOCUMENT_ROOT"] . '/temp/Acessos.xlsx');
    }

}
