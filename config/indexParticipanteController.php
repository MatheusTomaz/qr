<?php

// require_once 'conn.php';
require_once '../bean/participante/participanteBean.php';
require_once '../model/loginModel.php';
require_once 'config.php';

class loginParticipanteController {

    public $msg;

    private $modelUsuario, $usuario, $config;

    function loginParticipanteController() {
        $this->config = new Config();
        $this->modelUsuario = new LoginModel();
        $this->participante    = new ParticipanteBean();
        $this->fazerLogout();
        if (isset($_POST['email'])) {
            $this->participante->setEmail($_POST['email']);
            $this->participante->setSenha($_POST['senha']);
            $this->fazerLogin();
        }
        $this->config->verificarLogin("login");
    }

    function fazerLogout(){
        if(isset($_SESSION["login"])){
            if ($_GET["sair"]==1) {
                session_destroy();
                unset($_SESSION["email"]);
                unset($_SESSION["senha"]);
                header("Location: /sisqrcode/view/loginParticipante.php");
            }
        }

    }

    function fazerLogin() {
        $res = $this->modelUsuario->buscarUsuario("*", "participante", "WHERE email = '{$this->participante->getEmail()}' AND senha = '{$this->participante->getSenha()}'");
        if (mysql_num_rows($res) > 0) {
            $row = mysql_fetch_array($res);
            $_SESSION["idParticipante"] = $row["id"];
            $_SESSION["login"] = $this->participante->getEmail();
            $_SESSION["senha"] = $this->participante->getSenha();
            $_SESSION["nomeCliente"] = $row["nome"];
            $_SESSION["grupo"] = "part";
            // die("oi");
            if($_SESSION['grupo']=="admin"){
                header("Location: cliente/listarCliente.php");
            }else{

                header("Location: participante/dashboard.php");
            }
        } else {
            $this->msg = "<div class='panel msg-erro'>Login e/ou Senha incorretos!</div>";
        }
    }


}

?>
