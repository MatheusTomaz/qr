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
    $erro = 0;

    $conexao  = mysql_connect($host, $usuario, $password) or die("Não foi possível conectar-se com o banco de dados");
    mysql_select_db($dbname) or die("Não foi possível conectar-se com o banco de dados");
    mysql_query("SET NAMES utf8", $conexao);
    $query = "SELECT *
                FROM participante_has_palestra AS partPal, `participante`
                WHERE partPal.participante_id = participante.id
                AND cpf = '$objData->participante_cpf' AND palestra_id = '$objData->palestra_id'";
    $row = mysql_query($query);
    if(mysql_num_rows($row) > 0){
        if(!$row){
            $erro = 1;
        }else{
            $obj = mysql_fetch_array($row);
            $participante_id = $obj['id'];
            if($obj['presenca'] == 0){
                $presenca = 0;
            }else{
                $presenca = 1;
            }
            if($presenca == 1){
                //Participante já presente
                $erro = 2;
            }else{
                $query = "UPDATE participante_has_palestra SET presenca = 1, data_criado = '".date("Y-m-d H:i:s",time())."' WHERE participante_id= '$participante_id' AND palestra_id = '$objData->palestra_id'";
                $row = mysql_query($query);
            }
        }
    }else{
        $erro = 1;
    }

    if($erro == 0){
        $json[] = array('atualizou' => 'true');
    }else if($erro == 1){
        $json[] = array('atualizou' => 'erro1');
    }else if($erro == 2){
        $json[] = array('atualizou' => 'erro2');
    }
    echo json_encode($json);
?>
