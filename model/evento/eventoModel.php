<?
    session_start();
    require_once($_SESSION["config"]);
    require_once($_SESSION["evento"]["bean"]);

    Class EventoModel{

        private $idEvento;
        private $nomeEvento;
        private $status;
        private $qtdPalestra;
        private $caminhoLogo;
        private $caminhoCracha;

        function getEvento($campos, $tabela, $options=" "){
            $query = "SELECT $campos FROM $tabela $options";
            // die($query);
            return mysql_query($query);
        }

        function setEvento($values){
            $query = "INSERT INTO evento (nome,qtdPalestra,caminhoCracha,caminhoLogo,tipoCracha,usuario_id)
                      VALUES('{$values->getNome()}',
                             '{$values->getQtdPalestra()}',
                             '{$values->getCaminhoCracha1()}-{$values->getCaminhoCracha2()}',
                             '{$values->getCaminhoLogo()}',
                             '{$values->getTipoCracha()}',
                             '{$values->getUsuarioId()}')";
            return mysql_query($query);
        }

        function removeCliente($id){
            $query = "DELETE FROM evento WHERE id = $id";
            return mysql_query($query);
        }

        function getUltimoEvento($id){
            $query = "SELECT MAX(id) FROM evento WHERE usuario_id = $id";
            // die($query);
            return mysql_query($query);
        }

        function removeEvento($id){
            $query = "DELETE FROM evento WHERE id = $id";
            return mysql_query($query);
        }

    }
?>
