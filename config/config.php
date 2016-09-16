<?php

    require_once "conn.php";

    class Config{

        // private $home = "/erep/";
        // private $nome = "EREP";
        // private $path = "../../";
        private $pathConfig = "config/config.php";
        private $pathDashboard = "view/dashboard/dashboard.php";
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
            $_SESSION["pathDashboard"]=$this->path.$this->pathDashboard;
            $this->getPath("cliente");
            $this->getPath("evento");
            $this->getPath("palestra");
            $this->getPath("participante");
            $this->getPath("pessoas");
            $this->getPath("relatorios");
            $this->getPath("dashboard");
            $this->getPath("usuario");
        }

        function getPath($param){
            $path = $this->path;
            $_SESSION["$param"]["view"]["listar"]= $path."view/".$param."/listar".ucfirst($param).".php";
            $_SESSION["$param"]["view"]["cadastro"]= $path."view/".$param."/cadastro".ucfirst($param).".php";
            $_SESSION["$param"]["controller"]= $path."controller/".$param."/".$param."Controller.php";
            $_SESSION["$param"]["model"]= $path."model/".$param."/".$param."Model.php";
            $_SESSION["$param"]["bean"]= $path."bean/".$param."/".$param."Bean.php";
        }

        function verificaPath(){
            $script = "<script type='text/javascript' charset='utf-8'>
                        var a = parent.window.document.location;
                        if(a.pathname != '/sisqrcode/'){
                            window.location.href = '/sisqrcode/';
                        }
                        </script>";
            return $script;
        }

        function alertNoJS(){
            $script = "<style type='text/css'>.conteudo, .menu {display:none;}</style><div class='noscriptmsg'>";
            $script .= $this->gerarAlert('info','Para usar o RCode, habilite o JavaScript no seu Navegador');
            $script .= "</div>";
            return $script;
        }

        function gerarAlert($tipo,$texto){
            return "<div class='col-xs-12'><div class='alert alert-".$tipo."' role='alert'>
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                    ".$texto."</div></div>";
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
                $path = $this->path."assets/img/".$file;
                return '<link rel="icon" href="'.$path.'" />';
            }
        }

        function verificarLogin($param = "NULL") {
            if($param == "login"){
                if(isset($_SESSION["login"])){
                    header('Location: /sisqrcode/view/dashboard/dashboard.php');
                }
            }else{
                if (!isset($_SESSION["login"])) {
                    header('Location: /sisqrcode/view/login.php');
                }
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
