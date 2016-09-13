<?
    session_start();
    require_once($_SESSION["config"]);
    require_once($_SESSION["participante"]["bean"]);

    Class ParticipanteModel{

        function getParticipante($campos, $tabela, $options=" "){
            $query = "SELECT $campos FROM $tabela $options";
            // echo($query)."<br>";
            return mysql_query($query);
        }

        function setParticipante($values){
            $query = "INSERT INTO participante (nome,cpf,evento_id)
                      VALUES('{$values->getNome()}',
                             '{$values->getCpf()}',
                             '{$values->getEventoId()}')";
                             // echo($query);
            return mysql_query($query);
        }

        function setPalestraParticipante($values){
            $query = "INSERT INTO participante_has_palestra (participante_id,palestra_id,palestra_evento_id)
                      VALUES('{$values->getId()}',
                             '{$values->getPalestraId()}',
                             '{$values->getEventoId()}')";
                             // echo($query);
            return mysql_query($query);
        }

        function removeParticipante($id,$evento){
            $query = "DELETE FROM participante WHERE id = $id AND evento_id = $evento";
            return mysql_query($query);
        }

        function removeParticipantePalestra($id){
            $query = "DELETE FROM participante_has_palestra WHERE participante_id = $id";
            return mysql_query($query);
        }

    }

?>
