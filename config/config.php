<?php
    class Config{

        private $home = "/erep/";
        private $imagem = "logo.jpg";
        private $nome = "EREP";

        function getHome(){
            return $this->home;
        }

        function getImagem(){
            return $this->imagem;
        }

        function getNome(){
            return $this->nome;
        }

        function getLogo(){
            return "<a href='{$this->home}'><img src='{$this->imagem}'></a>";
        }

    }
?>
