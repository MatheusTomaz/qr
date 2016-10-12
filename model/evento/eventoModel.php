<?
    session_start();
    require_once("../../config/config.php");
    require_once("../../bean/evento/eventoBean.php");

    Class EventoModel{


        function getEvento($campos, $tabela, $options=" "){
            $query = "SELECT $campos FROM $tabela $options";
            // die($query);
            return mysql_query($query);
        }

        function setEvento($values){
            $query = "INSERT INTO evento (nome,caminhoCracha,caminhoLogo,tipoCracha,usuario_id)
                      VALUES('{$values->getNome()}',
                             '{$values->getCaminhoCracha1()}-{$values->getCaminhoCracha2()}',
                             '{$values->getCaminhoLogo()}',
                             '{$values->getTipoCracha()}',
                             '{$values->getUsuarioId()}')";
            return mysql_query($query);
        }

        function removeEvento($id){
            $erro = 0;
            $fabricaConexao = new FabricaConexao();
            $pdo = $fabricaConexao->getTransacao();

            $query1 = "DELETE FROM participante_has_palestra WHERE palestra_evento_id = $id";
            $query2 = "DELETE FROM palestra WHERE evento_id = $id";
            $query3 = "DELETE FROM pessoas WHERE evento_id = $id";
            $query4 = "DELETE FROM participante WHERE evento_id = $id";
            $query5 = "DELETE FROM evento WHERE id = $id";

            if (!$pdo->query($query1)) $erro++;
            if (!$pdo->query($query2)) $erro++;
            if (!$pdo->query($query3)) $erro++;
            if (!$pdo->query($query4)) $erro++;
            if (!$pdo->query($query5)) $erro++;

            if ($erro == 0){
                $pdo->commit();
                $status = true;
            } else {
                $pdo->rollback();
                $status = false;
            }

            return $status;
        }

        function aprovarEvento($id){
            $query ="UPDATE evento SET status = 1 WHERE id = $id";
            return mysql_query($query);
        }

        function getUltimoEvento($id){
            $query = "SELECT MAX(id) FROM evento WHERE usuario_id = $id";
            // die($query);
            return mysql_query($query);
        }

    }
?>
