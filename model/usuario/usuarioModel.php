<?php
    session_start();
    require_once($_SESSION["config"]);
    require_once($_SESSION["usuario"]["bean"]);

    Class UsuarioModel{

        function cadastrarUsuario($values){
            $query = "INSERT INTO usuario (cliente_id,login,senha,grupo)
                      VALUES('{$values->getId()}',
                             '{$values->getLogin()}',
                             '{$values->getSenha()}',
                             '{$values->getGrupo()}')";
                             // die($query);
            return mysql_query($query);
        }

        function removeUsuario($id){
            $query = "DELETE FROM usuario WHERE cliente_id = $id";
            return mysql_query($query);
        }

        function alterarUsuario($values){
            $query = "UPDATE usuario
                      SET `login` = '{$values->getLogin()}', senha = '{$values->getSenha()}'
                      WHERE cliente_id = '{$values->getId()}'";
                             // die($query);
            return mysql_query($query);
        }

        function buscarUsuario($campos, $tabela, $options=" "){
            $query = "SELECT $campos FROM $tabela $options";
            // die($query);
            return mysql_query($query);
        }

    }
?>
