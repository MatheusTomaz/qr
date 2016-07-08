<?php

require_once 'conn.php';
require_once 'usuarioBean.php';
require_once 'usuarioDAO.php';
require_once 'config.php';

class loginController {

	public $msg;

	private $usuarioDAO, $usuario, $config;

	function loginController() {
        $this->config = new Config();
		$this->usuarioDAO = new usuarioDAO();
		$this->usuario    = new usuario();
		if ($_GET["sair"]==1) {
			session_destroy();
			header("Location: ".$this->config->getHome());
		}
		if (isset($_POST['submit'])) {
			$this->usuario->setLogin($_POST['login']);
			$this->usuario->setSenha($_POST['senha']);
			$this->fazerLogin();
		}
        $this->verificarLogin();
	}

	function fazerLogin() {
		$res = $this->usuarioDAO->recuperarUsuario($this->usuario->getLogin(), $this->usuario->getSenha());
		if (mysql_num_rows($res) > 0) {
			$_SESSION["login"] = $this->usuario->getLogin();
			header("Location: menu.php");
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
