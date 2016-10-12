<?
    session_start();
    require_once("../../config/config.php");
    require_once("../../bean/cracha/crachaBean.php");

    Class RelatorioModel{

        function getRelatorio($campos, $tabela, $options=" "){
            $query = "SELECT $campos FROM $tabela $options";
            // die($query);
            return mysql_query($query);
        }
    }
?>
