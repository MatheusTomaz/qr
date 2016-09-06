<?php
    session_start();
    require_once "../config/config.php";

    Class LoginModel{

        function buscarUsuario($campos, $tabela, $options=" "){
            $query = "SELECT $campos FROM $tabela $options";
            return mysql_query($query);
        }

    }
?>
