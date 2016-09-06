<?php
    session_start();
    require_once($_SESSION["config"]);
    require_once($_SESSION["cliente"]["bean"]);

    Class ClienteModel{

        function getCliente($campos, $tabela, $options=" "){
            $query = "SELECT $campos FROM $tabela $options";
            // die($query);
            return mysql_query($query);
        }

        function setCliente($values){
            $query = "INSERT INTO cliente (nome,endereco_id)
                      VALUES('{$values->getNome()}',
                             '{$values->getEnderecoId()}')";
                             // die($query);
            return mysql_query($query);
        }

        function removeCliente($id){
            $query = "DELETE FROM cliente WHERE id = $id";
            return mysql_query($query);
        }

        function removeEndereco($id){
            $query = "DELETE FROM endereco WHERE id = $id";
            // die($query);
            return mysql_query($query);
        }

        function setEndereco($values){
            $query = "INSERT INTO endereco (cidade,estado,pais,rua,numeroCasa,bairro,complemento,telefone,cnpj)
                      VALUES ('{$values->getCidade()}',
                             '{$values->getEstado()}',
                             '{$values->getPais()}',
                             '{$values->getRua()}',
                             '{$values->getNumeroCasa()}',
                             '{$values->getBairro()}',
                             '{$values->getComplemento()}',
                             '{$values->getTelefone()}',
                             '{$values->getCnpj()}')";
                             // die($query);
            return mysql_query($query);
        }

    }
?>
