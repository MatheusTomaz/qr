<?
    session_start();
    require_once($_SESSION["config"]);
    require_once($_SESSION["palestra"]["bean"]);

    Class PalestraModel{

        function getPalestra($campos, $tabela, $options=" "){
            $query = "SELECT $campos FROM $tabela $options";
            // die($query);
            return mysql_query($query);
        }

        function setPalestra($values){
            $query = "INSERT INTO palestra (nome,qtdParticipante,tipo,evento_id)
                      VALUES('{$values->getNome()}',
                             '{$values->getQtdParticipante()}',
                             '{$values->getTipoPalestra()}',
                             '{$values->getEventoId()}')";
            return mysql_query($query);
        }

        function atualizarPalestra($id,$evento){
            $query = "UPDATE palestra SET status = 1 WHERE id = $id AND evento_id = $evento";
            return mysql_query($query);
        }

        function removePalestra($id){
            $query = "DELETE FROM palestra WHERE id = $id";
            return mysql_query($query);
        }

        function removeParticipante($id){
            $query = "DELETE FROM participante WHERE id = $id";
            return mysql_query($query);
        }
    }
?>
