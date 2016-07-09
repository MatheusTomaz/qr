<?php

    class Config{

        // private $home = "/erep/";
        // private $nome = "EREP";
        // private $path = "../../";
        private $pathConfig = "config/config.php";
        private $menu = "../menu.php";
        private $menuRodape = "../menu_rodape.php";

        function Config($page = NULL){
            session_start();
            if($page == "login"){
                $this->path = "/sisqrcode/";
            }else{
                $this->path = "../../";
            }
            $_SESSION["config"]=$this->path.$this->pathConfig;
            $this->getPath("cliente");
            $this->getPath("evento");
            $this->getPath("palestra");
            $this->getPath("participante");
            $this->getPath("pessoas");
            $this->getPath("relatorios");

        }

        function getPath($param){
            $path = $this->path;
            $_SESSION["$param"]["controller"]= $path."controller/".$param."/".$param."Controller.php";
            $_SESSION["$param"]["model"]= $path."model/".$param."/".$param."Model.php";
        }

        function getAssets($type,$file){
            $path = $this->path."assets/".$type."/".$file;
            if($type == "css"){
                return '<link href="'.$path.'" rel="stylesheet">';
            }
            if($type == "js"){
                return '<script type="text/javascript" src="'.$path.'"></script>';
            }
            if($type == "img"){
                return '<img src="'.$path.'">';
            }
            if($type == "favicon"){
                $path = $this->path.$this->assets."img/".$file;
                return '<link rel="icon" href="'.$path.'" />';
            }
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

        function getFavicon(){
            return $this->path.$this->favicon;
        }

        function getNome(){
            return $this->nome;
        }

        function getLogo(){
            return "<a href='{$this->home}'><img src='{$this->imagem}'></a>";
        }

    }
?>
