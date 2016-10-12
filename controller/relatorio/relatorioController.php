<?php

session_start();
require_once("../../model/relatorio/relatorioModel.php");
require_once('../../assets/tcpdf/tcpdf.php');

class RelatorioController{

    // function PDF(){
    //     // $config = new Config();
    // }
    // Load data
    function LoadData($idPalestra,$idEvento) {
        $modelRelatorio = new RelatorioModel();
        $data  = array();
        $pessoa = array();
        $participante = array();
        $j = 0;
        $row = $modelRelatorio->getRelatorio("*","participante_has_palestra as partPal, participante","WHERE partPal.participante_id = participante.id AND palestra_evento_id = ".$idEvento." AND palestra_id = ".$idPalestra." ORDER BY presenca DESC, nome");
        while($res = mysql_fetch_array($row)){
            $nome = utf8_decode(ucfirst($res["nome"]));
            $data[$j]["nome"] = $nome;
            $data[$j]["cpf"] = $res['cpf'];
            if($res["presenca"]==1){
                $data[$j]["presenca"] = "Presente";
            }else{
                $data[$j]["presenca"] = "Ausente";
            }

            $j++;
        }
        // $row = $modelRelatorio->getRelatorio("*","pessoas","WHERE evento_id = ".$idEvento);
        // while($res = mysql_fetch_array($row)){
        //     $nome = utf8_decode(ucfirst($res["nome"]));
        //     $data[$j]["nome"] = $nome;
        //     $data[$j]["cpf"] = $res['cpf'];
        //     $data[$j]["tipo"] = $res['grupo'];
        //     $j++;
        // }
        return $data;
    }

    function Header($pdf, $idPalestra, $idEvento) {
        $config = new Config();
        $modelRelatorio = new RelatorioModel();
        $row = $modelRelatorio->getRelatorio("*","evento", "WHERE id = ".$idEvento);
        $res = mysql_fetch_array($row);
        if(!empty($res['caminhoLogo'])){
            $pdf->Image($res['caminhoLogo'], 20, 15, 30);
        }
        $pdf->SetY(10);
        // $pdf->Image($config->getAssets("|img","logo.jpg"), 155, 15, 30);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetY(20);
        $pdf->Cell(80);
        $pdf->Cell(30, 10, utf8_decode($res['nome']), 0, 1, 'C');
        $pdf->SetY(30);
        $pdf->Cell(80);
        $pdf->SetFont('Arial', 'B', 12);
        $row = $modelRelatorio->getRelatorio("*","palestra", "WHERE id = ".$idPalestra);
        $res = mysql_fetch_array($row);
        $pdf->Cell(30, 10, utf8_decode($res['tipo']." - ".$res['nome']), 0, 0, 'C');
        $pdf->Ln(20);

    }

    // Page footer
    function Footer($pdf) {
        // Position at 1.5 cm from bottom
        $pdf->SetXY(190,0);
        // Arial italic 8
        $pdf->SetFont('Arial', 'I', 8);
        // Page number
        $pdf->Cell(0, 10, $pdf->PageNo(), 0, 0, 'C');
    }

    // Colored table
    function FancyTable($pdf, $data,$idPalestra,$idEvento) {
        // Colors, line width and bold font
        $modelRelatorio = new RelatorioModel();

        // Data
        $fill  = false;
        $cont  = 1;
        $contP = 0;
        foreach ($data as $row) {
            if($contP == 0){
               $this->gerarCabecalho($pdf,$idPalestra,$idEvento);
            }

            $contP++;
            if($contP == 25){
                $contP = 0;
            }

            $pdf->SetFillColor(224, 235, 255);
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetX(5);
            $pdf->Cell(10, 8, $cont, 'LR', 0, 'C', $fill);
            $pdf->Cell(100, 8, $row['nome'], 'LR', 0, 'L', $fill);
            $pdf->Cell(50, 8, "  ".$row['cpf'], 'LR', 0, 'C', $fill);
            $pdf->Cell(40, 8, $row['presenca'], 'LR', 0, 'C', $fill);
            $pdf->Ln();
            $fill = !$fill;
            $cont++;
        }
        // Closing line
        $pdf->SetX(5);
        $pdf->Cell(200, 0, '', 'T');
    }

    function gerarCabecalho($pdf,$idPalestra,$idEvento){
        $pdf->AddPage();
        $this->Header($pdf, $idPalestra,$idEvento);
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        $pdf->SetX(5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(5);
        $pdf->Cell(10, 8, "", 1, 0, 'C', true);
        $pdf->Cell(100, 8, "Nome", 1, 0, 'L', true);
        $pdf->Cell(50, 8, "CPF", 1, 0, 'C', true);
        $pdf->Cell(40, 8, utf8_decode("Situação"), 1, 0, 'C', true);
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetFont('Arial', '', 14);

    }

    function gerarRelatorio($idPalestra,$idEvento) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, null, false, 'UTF-8', false);

        $data = $this->LoadData($idPalestra,$idEvento);
        $this->FancyTable($pdf,$data,$idPalestra,$idEvento);
        $this->Footer($pdf);
        ob_clean();
        //Close and output PDF document

        $pdf->Output('example_050.pdf', 'I');
        // $this->Output('D', 'listadeparticipantes.pdf');

    }

    function gerarListaMembros() {

        $participante    = new participante();
        $participanteDAO = new participanteDAO();
        // Column headings
        $header = array(utf8_decode('Matrícula'), 'Nome');
        // Data loading
        $data = $participanteDAO->listaMembrosRel();
        $data = $this->LoadData($data);
        $this->AddPage();
        $this->SetFont('Arial', '', 14);
        $this->FancyTable($header, $data);
        $this->Output('D', 'listademembros.pdf');

    }

    function gerarListaPresenca($palestra) {
        $participante    = new participante();
        $participanteDAO = new participanteDAO();
        // Column headings
        $header = array(utf8_decode('Matrícula'), 'Nome', utf8_decode('Presença'));
        // Data loading
        $data = $participanteDAO->buscarListaRel("palestra".$palestra);
        $data = $this->LoadData($data, $palestra);
        // print_r($data);
        $this->AddPage();
        $this->SetFont('Arial', '', 14);
        $this->FancyTable($header, $data, $palestra);
        $this->Output('D', 'listadepresenca'.$palestra.'.pdf');
    }

}


?>
