<?
    session_start();
    require_once($_SESSION["config"]);
    require_once($_SESSION["pessoas"]["bean"]);

    Class PessoaModel{

        function getPessoa($campos, $tabela, $options=" "){
            $query = "SELECT $campos FROM $tabela $options";
            // echo($query);
            return mysql_query($query);
        }

        function setPessoa($values){
            $query = "INSERT INTO pessoas (nome,cpf,grupo,evento_id)
                      VALUES('{$values->getNome()}',
                             '{$values->getCpfPessoa()}',
                             '{$values->getGrupoPessoa()}',
                             '{$values->getEventoId()}')";
            return mysql_query($query);
        }

        function removePessoa($id){
            $query = "DELETE FROM pessoas WHERE id = $id";
            return mysql_query($query);
        }

    }

?>
