<?php
    session_start();
    require_once("../../config/config.php");
    require_once("../../bean/cliente/clienteBean.php");

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

        // function setCliente($values){
        //     $fabricaConexao = new FabricaConexao();
        //     $pdo = $fabricaConexao->getTransacao();

        //     $query1 = $pdo->query("INSERT INTO cliente (nome,endereco_id)
        //               VALUES('{$values->getNome()}',
        //                      '{$values->getEnderecoId()}')");
        //     $query2 = $pdo->query("INSERT INTO endereco (cidade,estado,pais,rua,numeroCasa,bairro,complemento,telefone,cnpj)
        //               VALUES ('{$values->getCidade()}',
        //                      '{$values->getEstado()}',
        //                      '{$values->getPais()}',
        //                      '{$values->getRua()}',
        //                      '{$values->getNumeroCasa()}',
        //                      '{$values->getBairro()}',
        //                      '{$values->getComplemento()}',
        //                      '{$values->getTelefone()}',
        //                      '{$values->getCnpj()}')");

        //     if(!$query1 || !$query2){
        //         $pdo->rollBack(); /* Desfaz a inserção na tabela de movimentos em caso de erro na query da tabela conta */
        //         $status = false;
        //     }else{
        //         $pdo->commit(); /* Se não houve erro nas querys, confirma os dados no banco */
        //         $status = true;
        //     }

        //     return $status;
        // }


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
