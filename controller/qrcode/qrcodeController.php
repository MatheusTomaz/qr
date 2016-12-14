 <?php
require_once ('../../assets/tcpdf/tcpdf.php');
require_once ("../../model/cracha/crachaModel.php");
Class QRCodeController {

    private $cont,$somax,$somay,$conty;

    function LoadData($pessoas,$participantes) {
        $modelCracha = new CrachaModel();
        $data  = array();
        $pessoa = array();
        $participante = array();
        $j = 0;
        if(!empty($pessoas[0])){
            foreach($pessoas as $id){
                $row = $modelCracha->getCracha("*","pessoas","WHERE id = ".$id);
                $res = mysql_fetch_array($row);
                $nome = utf8_decode(ucfirst($res["nome"]));
                if(strlen($nome)>30){
                    $nome = explode(" ",$nome);
                    $cont = 0;
                    $flag = false;
                    foreach ($nome as $n) {
                        $cont++;
                        if(($nome[$cont]!="de" &&
                            $nome[$cont]!="do" &&
                            $nome[$cont]!="da" &&
                            $nome[$cont]!="e") && $flag == false){
                            $abbv = substr($nome[$cont],0,1);
                            $flag = true;
                        }
                    }
                    $nome = $nome[0]." ".$abbv.". ".$nome[$cont-1];
                }
                $data[$j]["nome"] = $nome;
                $data[$j]["cpf"] = $res['cpf'];
                $data[$j]["tipo"] = $res['grupo'];
                $j++;
            }
        }
        if(!empty($participantes[0])){
            foreach($participantes as $id){
                $row = $modelCracha->getCracha("*","participante","WHERE id = ".$id);
                $res = mysql_fetch_array($row);
                $nome = utf8_decode(ucfirst($res["nome"]));
                if(strlen($nome)>30){
                    $nome = explode(" ",$nome);
                    $cont = 0;
                    $flag = false;
                    foreach ($nome as $n) {
                        $cont++;
                        if(($nome[$cont]!="de" &&
                            $nome[$cont]!="do" &&
                            $nome[$cont]!="da" &&
                            $nome[$cont]!="e") && $flag == false){
                            $abbv = substr($nome[$cont],0,1);
                            $flag = true;
                        }
                    }
                    $nome = $nome[0]." ".$abbv.". ".$nome[$cont-1];
                }
                $data[$j]["nome"] = $nome;
                $data[$j]["cpf"] = $res['cpf'];
                $data[$j]["tipo"] = "Participante";
                $j++;
            }
        }
        return $data;
    }

    function gerarImgCracha($pdf,$evento,$row,$style){
        $config = new Config();
        if($this->cont == 5){
            $this->cont = 1;
            $pdf->AddPage();
        }

        if($this->cont==1) {
            $x = 10;
            $y = 10;
        }else if($this->cont==2){
            $x = 15+(0.74*125);
            $y = 10;
        }else if($this->cont==3){
            $x = 10;
            $y = 22.5+120;
        }else if($this->cont==4){
            $x = 15+(0.74*125);
            $y = 22.5+120;
        }

        if($evento->getTipoCracha() == 1){
            $pdf->Image($evento->getCaminhoCracha1(), $x, $y, 0.74*125, 125);
            $pdf->SetXY($x,$y+28);
            $pdf->Line($x, $y, $x+92.5, $y);
            $pdf->Line($x, $y, $x, $y+125);
            $pdf->Line($x, $y+125, $x+92.5, $y+125);
            $pdf->Line($x+92.5, $y+125, $x+92.5, $y);
            if($row["tipo"] != "Participante"){
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(92.5, 10, strtoupper($row["tipo"]), 0, 0, 'C');
                $pdf->Line($x+10, $y+40, $x+82.5, $y+40);
                $pdf->SetXY($x,$y+55);
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(92.5, 8, $row["nome"], 0, 0, 'C');
            }else{
                $pdf->Cell(92.5, 8, $row["nome"], 0, 0, 'C');
                $pdf->SetXY($x,$y+34);
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(92.5, 8, "QRCode por Robotica Jr.", 0, 0, 'C');
                $pdf->write2DBarcode($row["cpf"]."-".$row["nome"], 'QRCODE,H', $x+31, $y+40, 30, 30, $style, 'N');
                $pdf->SetXY($x,$y+68);
                $pdf->Cell(92.5, 8, "www.roboticajr.ufla.br", 0, 0, 'C');
                $pdf->SetXY($x,$y+74);
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(92.5, 10, $row["tipo"], 0, 0, 'C');
            }

            // $pdf->Image($config->getAssets("|img","logo.jpg"), $x+76, $y+70, 15, 13);

        }else if($evento->getTipoCracha() == 2){
            $pdf->Image($evento->getCaminhoCracha1(), $x, $y, 92.5, 92.5/2.66);
            $pdf->Image($evento->getCaminhoCracha2(), $x, $y+85, 92.5, 92.5/2.3);
            $pdf->SetXY($x,$y+35);
            $pdf->Line($x, $y, $x+92.5, $y);
            $pdf->Line($x, $y, $x, $y+125);
            $pdf->Line($x, $y+125, $x+92.5, $y+125);
            $pdf->Line($x+92.5, $y+125, $x+92.5, $y);
            if($row["tipo"] != "Participante"){
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(92.5, 10, strtoupper($row["tipo"]), 0, 0, 'C');
                $pdf->Line($x+10, $y+40, $x+82.5, $y+40);
                $pdf->SetXY($x,$y+55);
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(92.5, 8, $row["nome"], 0, 0, 'C');
            }else{
                $pdf->Cell(92.5, 8, $row["nome"], 0, 0, 'C');
                $pdf->SetXY($x,$y+41);
                $pdf->SetFont('Arial', '', 6);
                $pdf->Cell(92.5, 8, "QRCode por Robotica Jr.", 0, 0, 'C');
                $pdf->write2DBarcode($row["cpf"]."-".$row["nome"], 'QRCODE,H', $x+33.75, $y+47, 25, 25, $style, 'N');
                $pdf->SetXY($x,$y+70);
                $pdf->Cell(92.5, 8, "www.roboticajr.ufla.br", 0, 0, 'C');
                $pdf->SetXY($x,$y+74);
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(92.5, 10, $row["tipo"], 0, 0, 'C');
            }
            $pdf->Image($config->getAssets("|img","logo.jpg"), $x+76, $y+70, 15, 13);
            // $pdf->Image($evento->getCaminhoCracha2(), 30, 30, 35, 27);
        }else if($evento->getTipoCracha() == 3){
            if($this->cont<=3) {
                $x = $this->somax+6.5;
                $y = $this->somay+10;
                $this->somax = $this->somax+66;
            }
            if($this->cont==3){
                $this->conty++;
                $this->cont = 0;
                $this->somax = 0;
                $this->somay = $this->somay+37;
            }

            $pdf->Line($x, $y, $x+65, $y);
            $pdf->Line($x, $y, $x, $y+36);
            $pdf->Line($x, $y+36, $x+65, $y+36);
            $pdf->Line($x+65, $y+36, $x+65, $y);
            $pdf->SetXY($x,$y);
            if($row["tipo"] != "Participante"){
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(65, 10, strtoupper($row["tipo"]), 0, 0, 'C');
                $pdf->Line($x+10, $y+10, $x+55, $y+10);
                $pdf->SetXY($x,$y+16);
                $pdf->Cell(65, 8, $row["nome"], 0, 0, 'C');
            }else{
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(65, 8, $row["nome"], 0, 0, 'C');
                $pdf->SetFont('Arial', '', 5);
                $pdf->SetXY($x+26,$y+4.5);
                $pdf->Rotate(90,$x+32.5,$y+20.5);
                $pdf->Cell(20, 8, "QRCode por Robotica Jr.", 0, 0, 'C');
                $pdf->Rotate(-90,$x+32.5,$y+20.5);
                $pdf->write2DBarcode($row["cpf"]."-".$row["nome"], 'QRCODE,H', $x+22, $y+7, 21, 20, $style, 'N');
                $pdf->SetXY($x,$y+24.5);
                $pdf->SetFont('Arial', '', 6);
                $pdf->Cell(65, 8, "www.roboticajr.ufla.br", 0, 0, 'C');
                $pdf->SetXY($x,$y+27);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(65, 10, $row["tipo"], 0, 0, 'C');
            }

            // $pdf->SetY($y+27.5);
            // $pdf->SetX($x+23);
            $pdf->Image($config->getAssets("|img","logo.jpg"), $x+54, $y+27, 10, 8);
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetY($y+26);
            $pdf->SetX($x);
            if($this->conty == 7){
                $pdf->AddPage();
                $this->conty = 0;
                $this->cont = 0;
                $this->somay = 0;
                $this->somax = 0;
            }
        }
        $this->cont++;

        return $pdf;
    }

    function gerarCracha($evento,$pessoas,$participantes) {

        $data = $this->LoadData($pessoas, $participantes);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, null, false, 'UTF-8', false);

        // set font
        $pdf->SetFont('helvetica', '', 11);
        // Column headings
        // Data loading
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $style    = array(
            'border'        => 2,
            'vpadding'      => '0',
            'hpadding'      => '0',
            'fgcolor'       => array(0, 0, 0),
            'bgcolor'       => false, //array(255,255,255)
            'module_width'  => 1, // width of a single module in points
            'module_height' => 1// height of a single module in points
        );
        $this->cont = 1;
        $this->conty = 0;
        $this->somax = 0;
        $this->somay = 0;
        foreach ($data as $row) {
            $this->gerarImgCracha($pdf,$evento,$row,$style,$this->cont);
        }
        ob_clean();
        //Close and output PDF document

        $pdf->Output('example_050.pdf', 'I');
    }
}
?>
