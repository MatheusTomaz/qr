<?
    session_start();
    require_once($_SESSION["config"]);
    require_once($_SESSION["palestra"]["bean"]);

    Class PalestraModel{

        private $transacao;

        function PalestraModel(){
            $fabricaConexao = new FabricaConexao();
            $this->transacao = $fabricaConexao->getTransacao();
        }

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
            $erro = 0;
            $query1 = "DELETE FROM participante_has_palestra WHERE palestra_id = $id AND palestra_evento_id = $evento";
            $query2 = "DELETE FROM palestra WHERE id = $id AND evento_id = $evento";
            if (!mysqli_query($this->transacao, $query1)) $erro++;
            if (!mysqli_query($this->transacao, $query2)) $erro++;
            if ($erro == 0){
                mysqli_commit($this->transacao);
                return true;
            } else {
                mysqli_rollback($this->transacao);
                return false;
            }
        }

        function removeParticipante($id){
            $query = "DELETE FROM participante WHERE id = $id";
            return mysql_query($query);
        }
    }
?>
