<?php
    class Config{

        private $assets = "../assets/";
        private $home = "/erep/";
        private $logoRobotica = "../assets/img/logo.png";
        private $nome = "EREP";
        private $menu = "menu.php";
        private $menuRodape = "menu_rodape.php";

        function getCss($file){
            $path = $this->assets."css/".$file;
            return '<link href="'.$path.'" rel="stylesheet">';
        }

        function getJs($file){
            $path = $this->assets."js/".$file;
            return '<script type="text/javascript" src="'.$path.'"></script>';
        }

        function getHome(){
            return $this->home;
        }

        function getMenu(){
            return $this->menu;
        }

        function getMenuRodape(){
            return $this->menuRodape;
        }

        function getLogoRobotica(){
            return $this->logoRobotica;
        }

        function getNome(){
            return $this->nome;
        }

        function getLogo(){
            return "<a href='{$this->home}'><img src='{$this->imagem}'></a>";
        }

    }
?>
