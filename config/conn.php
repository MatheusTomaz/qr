<?php

// $host     = "forro.ufla.br";
// $dbname   = "bd_roboticajr";
// $usuario  = "admin_bdrobotica";
// $password = "BdKwy036";

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: http://url:8080");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

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

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $usuario, $password);

        if(!$pdo){
            die('Erro ao iniciar a conexão');
        }

        $pdo->beginTransaction();/* Inicia a transação */

        return $pdo;

    }

}

?>
