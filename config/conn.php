<?php

// $host     = "forro.ufla.br";
// $dbname   = "bd_roboticajr";
// $usuario  = "admin_bdrobotica";
// $password = "BdKwy036";

$host     = "localhost";
$dbname   = "bd_sisqrcode";
$usuario  = "root";
$password = "graomestre10";

$conexao  = mysql_connect($host, $usuario, $password) or die("Não foi possível conectar-se com o banco de dados");
mysql_select_db($dbname) or die("Não foi possível conectar-se com o banco de dados");
mysql_query("SET NAMES utf8", $conexao);

Class FabricaConexao{

    function getTransacao(){
        $host     = "localhost";
        $dbname   = "bd_sisqrcode";
        $usuario  = "root";
        $password = "graomestre10";
        $transacao = mysqli_connect($host, $usuario, $password, $dbname);
        mysqli_autocommit($transacao, FALSE);
        return $transacao;
    }
}

?>
