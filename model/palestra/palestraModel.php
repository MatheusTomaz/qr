<?
    session_start();
    require_once("../../config/config.php");
    require_once("../../bean/palestra/palestraBean.php");

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

        function removePalestra($id, $evento){
            $fabricaConexao = new FabricaConexao();
            $pdo = $fabricaConexao->getTransacao();

            $query1 = $pdo->query("DELETE FROM participante_has_palestra WHERE palestra_evento_id = $evento AND palestra_id = $id");
            $query2 = $pdo->query("DELETE FROM palestra WHERE id = $id AND evento_id = $evento");

            if(!$query1 || !$query2){
                $pdo->rollBack(); /* Desfaz a inserção na tabela de movimentos em caso de erro na query da tabela conta */
                $status = false;
            }else{
                $pdo->commit(); /* Se não houve erro nas querys, confirma os dados no banco */
                $status = true;
            }

            return $status;
        }

        function removeParticipante($id){
            $query = "DELETE FROM participante WHERE id = $id";
            return mysql_query($query);
        }
    }
?>
