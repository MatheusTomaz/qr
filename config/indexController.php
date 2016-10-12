<?php

// require_once 'conn.php';
require_once '../bean/usuario/usuarioBean.php';
require_once '../model/loginModel.php';
require_once 'config.php';

class loginController {

	public $msg;

	private $modelUsuario, $usuario, $config;

	function loginController() {
        $this->config = new Config();
		$this->modelUsuario = new LoginModel();
		$this->usuario    = new UsuarioBean();
        $this->fazerLogout();
		if (isset($_POST['login'])) {
			$this->usuario->setLogin($_POST['login']);
			$this->usuario->setSenha($_POST['senha']);
			$this->fazerLogin();
		}
        $this->config->verificarLogin("login");
	}

    function fazerLogout(){
        if(isset($_SESSION["login"])){
            if ($_GET["sair"]==1) {
                session_destroy();
                unset($_SESSION["login"]);
                unset($_SESSION["senha"]);
                header("Location: /sisqrcode/view/login.php");
            }
        }

    }

	function fazerLogin() {
		$res = $this->modelUsuario->buscarUsuario("*", "usuario", "WHERE login = '{$this->usuario->getLogin()}' AND senha = '{$this->usuario->getSenha()}'");
        $row = mysql_fetch_array($res);
            $_SESSION["idCliente"] = $row["id"];
            $_SESSION["grupo"] = $row["grupo"];
		if (mysql_num_rows($res) > 0) {
            $res = $this->modelUsuario->buscarUsuario("*", "cliente", "WHERE id = '{$row["cliente_id"]}'");
            $row = mysql_fetch_array($res);
			$_SESSION["login"] = $this->usuario->getLogin();
            $_SESSION["senha"] = $this->usuario->getSenha();
            $_SESSION["nomeCliente"] = $row["nome"];
            if($_SESSION['grupo']=="admin"){
                header("Location: cliente/listarCliente.php");
            }else{
		    	header("Location: dashboard/dashboard.php");
            }
		} else {
			$this->msg = "<div class='panel msg-erro'>Login e/ou Senha incorretos!</div>";
		}
	}


}

?>
