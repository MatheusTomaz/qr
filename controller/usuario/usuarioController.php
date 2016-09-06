<?
    session_start();
    require_once($_SESSION["usuario"]["model"]);

    Class UsuarioController{

        private $bean, $model, $button, $alterar;

        function UsuarioController(){
            $this->bean = new UsuarioBean();
            $this->model = new UsuarioModel();
            if(isset($_GET["id"])){
                $this->bean->setId($_GET["id"]);
            }else{
                $this->bean->setId($_POST["clienteId"]);
            }
            // print_r($_POST);
            $this->definirAcao();
            if(isset($_POST['usuarioCliente'])){
                $this->bean->setLogin($_POST['usuarioCliente']);
            }
        }

        function buscarUsuario(){
            $row = $this->model->buscarUsuario("*","usuario","WHERE cliente_id = {$this->bean->getId()}");
            $res = mysql_fetch_array($row);
            print $res["login"];
            if(mysql_num_rows($row)>0){
                $this->bean->setLogin($res["login"]);
                $this->bean->setSenha($res["senha"]);
                return true;
            }else{
                return false;
            }
        }

        function definirAcao(){
            if($this->buscarUsuario()){
                $this->button = "ALTERAR";
                $this->alterar = 1;
            }else{
                $this->button = "CADASTRAR";
                $this->alterar = 0;
            }
            // $bean->setObs($_POST["nomeCliente"]);
            if(isset($_POST["usuarioCliente"])){
                print "oi";
                $this->bean->setLogin($_POST["usuarioCliente"]);
                $this->bean->setSenha($_POST["senhaUsuario"]);
                $this->bean->setGrupo($_POST["grupoUsuario"]);
                if($this->alterar == 0){
                    $this->cadastrarUsuario();
                }else if($this->alterar == 1){
                    $this->alterarUsuario();
                }
            }
        }

        function cadastrarUsuario(){
            $res = $this->model->buscarUsuario("*","usuario","WHERE login LIKE '{$this->bean->getLogin()}'");
            if(mysql_num_rows($res)<1){
                if($this->model->cadastrarUsuario($this->bean)){
                    $tipo = "success";
                    $texto = "Usuário cadastrado com sucesso!";
                }else{
                    $tipo = "danger";
                    $texto = "Usuário não cadastrado!";
                }
            }else{
                $tipo = "warning";
                $texto = "Usuário já cadastrado!";
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
        }

        function alterarUsuario(){
            $res = $this->model->buscarUsuario("*","usuario","WHERE login LIKE '{$this->bean->getLogin()}'");
            $row = mysql_fetch_array($res);
            if(mysql_num_rows($res)<1 || (mysql_num_rows($res)>0  && ($this->bean->getLogin() == $row["login"]) && ($this->bean->getId() == $row["cliente_id"]))){
                if($this->model->alterarUsuario($this->bean)){
                    $tipo = "success";
                    $texto = "Usuário alterado com sucesso!";
                }else{
                    $tipo = "danger";
                    $texto = "Usuário não alterado!";
                }
            }else{
                $tipo = "warning";
                $texto = "Usuário já cadastrado!";
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
        }

        function gerarAlert($tipo,$texto){
            return "<div class='col-xs-12'><div class='alert alert-".$tipo."' role='alert'>
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                    ".$texto."</div></div>";
        }

        function getAlert(){
            return $this->alert;
        }

        function getLogin(){
            return $this->bean->getLogin();
        }

        function getSenha(){
            return $this->bean->getSenha();
        }

        function getButton(){
            return $this->button;
        }

    }
?>
