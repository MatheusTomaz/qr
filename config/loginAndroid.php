<?php
    $data = file_get_contents("php://input");
        // Cria um stdClass
    $objData = json_decode($data);
    // Como objData passa a ser um objeto, vamos capturar apenas o parametro que queremos
    // Conecta no banco
    $host     = "localhost";
    $dbname   = "bd_sisqrcode";
    $usuario  = "root";
    $password = "graomestre10";

    $conexao  = mysql_connect($host, $usuario, $password) or die("Não foi possível conectar-se com o banco de dados");
    mysql_select_db($dbname) or die("Não foi possível conectar-se com o banco de dados");
    mysql_query("SET NAMES utf8", $conexao);
    // die(print_r($objData->login));
    // Prepara o select. Limito para 3 resultado, para não encher a tela de autoajuda
    $query = "SELECT usuario.id, usuario.cliente_id, cliente.nome, usuario.login, usuario.senha
                FROM usuario, cliente
                WHERE usuario.cliente_id = cliente.id
                AND login = '$objData->login'
                AND senha = '$objData->senha'";
    $row = mysql_query($query);
    if (mysql_num_rows($row)>0) {
        /* percorre os resultados */
        while ($obj = mysql_fetch_array($row)) {
            $json[] = array('id' => $obj['id'], 'login' => $obj['login'], 'nome' => $obj['nome'], 'senha' => $obj['senha'], 'cliente_id' => $obj['cliente_id']);
        }
    }else{
        $json[]= array('id' => 0, 'login' => NULL, 'nome' => NULL, 'senha' => 0, 'cliente_id' => 0);
    }
    echo json_encode($json);
?>
