<?php
    $data = file_get_contents("php://input");
        // Cria um stdClass
    $objData = json_decode($data);
    // Como objData passa a ser um objeto, vamos capturar apenas o parametro que queremos
    // Conecta no banco
    $host     = "bd-sisqrcode.mysql.uhserver.com";
    $dbname   = "bd_sisqrcode";
    $usuario  = "roboticajr";
    $password = "EmpRobJr16@22@12";


    $conexao  = mysql_connect($host, $usuario, $password) or die("Não foi possível conectar-se com o banco de dados");
    mysql_select_db($dbname) or die("Não foi possível conectar-se com o banco de dados");
    mysql_query("SET NAMES utf8", $conexao);
    $query = "SELECT * FROM participante_has_palestra WHERE participante_id = '$objData->participante_id' AND palestra_id = '$objData->palestra_id'";
    $row = mysql_query($query);
    if($obj = mysql_fetch_array($row)){
        if($obj['presenca'] == 0){
            $presenca = 1;
        }else{
            $presenca = 0;
        }
    }
    // die(print_r($objData->login));
    // Prepara o select. Limito para 3 resultado, para não encher a tela de autoajuda
    $query = "UPDATE participante_has_palestra SET presenca = $presenca, data_criado = '".date("Y-m-d H:i:s",time())."' WHERE participante_id= '$objData->participante_id' AND palestra_id = '$objData->palestra_id'";
    $row = mysql_query($query);
    if ($row) {
        $json[] = array('atualizou' => 'true', 'presenca' => $presenca);
    }else{
        $json[] = array('atualizou' => 'false');
    }
    echo json_encode($json);
?>
