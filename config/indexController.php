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
		if ($_GET["sair"]==1) {
			session_destroy();
			header("Location: /sisqrcode/view/login.php");
		}
		if (isset($_POST['login'])) {
			$this->usuario->setLogin($_POST['login']);
			$this->usuario->setSenha($_POST['senha']);
			$this->fazerLogin();
		}
  //       $this->verificarLogin();
        // $config = new Config();
        // if($_POST['login']=="root"){
        //     header("Location: dashboard/dashboard.php");
        // }
	}

	function fazerLogin() {
		$res = $this->modelUsuario->buscarUsuario("*", "usuario", "WHERE login = '{$this->usuario->getLogin()}' AND senha = '{$this->usuario->getSenha()}'");
        $row = mysql_fetch_array($res);
            $_SESSION["idCliente"] = $row["id"];
		if (mysql_num_rows($res) > 0) {
            $res = $this->modelUsuario->buscarUsuario("*", "cliente", "WHERE id = '{$row["cliente_id"]}'");
            $row = mysql_fetch_array($res);
			$_SESSION["login"] = $this->usuario->getLogin();
            $_SESSION["senha"] = $this->usuario->getSenha();
            $_SESSION["nomeCliente"] = $row["nome"];
			header("Location: dashboard/dashboard.php");
		} else {
			$this->msg = "<div class='panel msg-erro'>Login e/ou Senha incorretos!</div>";
		}
	}

	function verificarLogin() {
		if (!isset($_SESSION["login"])) {
            if($_SERVER['REQUEST_URI']!=($this->config->getHome()."index.php")){
                header('Location: index.php');
            }
        }else{
            if($_SERVER['REQUEST_URI']==($this->config->getHome()."index.php") || $_SERVER['REQUEST_URI']==($this->config->getHome())){
                header('Location: menu.php');
            }
		}
	}
}

?>
