<?php
    $data = file_get_contents("php://input");
        // Cria um stdClass
    $objData = json_decode($data);
    // Como objData passa a ser um objeto, vamos capturar apenas o parametro que queremos
    // Conecta no banco
    // $host     = "bd-sisqrcode.mysql.uhserver.com";
    // $dbname   = "bd_sisqrcode";
    // $usuario  = "roboticajr";
    // $password = "EmpRobJr16@22@12";


    $host     = "localhost";
    $dbname   = "bd_sisqrcode";
    $usuario  = "root";
    $password = "graomestre10";


    $conexao  = mysql_connect($host, $usuario, $password) or die("Não foi possível conectar-se com o banco de dados");
    mysql_select_db($dbname) or die("Não foi possível conectar-se com o banco de dados");
    mysql_query("SET NAMES utf8", $conexao);
    $query = "SELECT * FROM participante WHERE cpf = '$objData->cpfParticipante' OR email = '$objData->emailParticipante'";
    $row = mysql_query($query);
    if(mysql_num_rows($row)>0){
        $json[] = array('cadastrou' => 'false', 'msg' => 'Você já possui cadastro!');
    }else{

        $query = "INSERT INTO participante (nome,cpf,email,senha,telefone,categoria,RG,nacionalidade,estado,cidade,nascimento)
                      VALUES ('{$objData->nomeParticipante}',
                             '{$objData->cpfParticipante}',
                             '{$objData->emailParticipante}',
                             '{$objData->senhaParticipante}',
                             '{$objData->telefone}',
                             '{$objData->categoria}',
                             '{$objData->rg}',
                             '{$objData->nacionalidade}',
                             '{$objData->estado}',
                             '{$objData->cidade}',
                             '{$objData->nascimento}')";
        $row = mysql_query($query);
        if($row){
            $query2 = "SELECT * FROM participante WHERE cpf = '$objData->cpfParticipante'";
            $row = mysql_query($query2);
            $data = mysql_fetch_array($row);
            $query = "INSERT INTO participante_has_evento (participante_id, evento_id)
                        VALUES ('{$data['id']}',
                                '{$objData->eventoId}')";
            $row = mysql_query($query);
            if ($row) {
                $json[] = array('cadastrou' => 'true', 'msg' => 'Você foi cadastrado com sucesso!');
            }else{
                $json[] = array('cadastrou' => 'false', 'msg' => 'Erro! Verifique se os campos estão preenchidos corretamente!');
            }
        }else{
            $json[] = array('cadastrou' => 'false', 'msg' => "Erro no Banco!");
        }


    }

    echo json_encode($json);
?>
