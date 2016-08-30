<?
    session_start();
    require_once($_SESSION["cliente"]["model"]);
    require_once($_SESSION["usuario"]["model"]);

    Class ClienteController{

        private $alert, $clienteId, $bean;

        function ClienteController(){
            $this->bean = new ClienteBean();
            if(isset($_POST["nomeCliente"])){
                $this->bean->setNome($_POST["nomeCliente"]);
                $this->bean->setCidade($_POST["cidadeCliente"]);
                $this->bean->setEstado($_POST["estadoCliente"]);
                $this->bean->setPais($_POST["paisCliente"]);
                $this->bean->setRua($_POST["ruaCliente"]);
                $this->bean->setNumeroCasa($_POST["numEndCliente"]);
                $this->bean->setBairro($_POST["bairroCliente"]);
                $this->bean->setComplemento($_POST["complementoCliente"]);
                $this->bean->setTelefone($_POST["telefoneCliente"]);
                $this->bean->setCnpj($_POST["cnpjCliente"]);
                // $bean->setObs($_POST["nomeCliente"]);
                $this->cadastrarCliente($this->bean);
            }
            if(isset($_GET["id"])){
                $this->clienteId = $_GET["id"];
                $this->bean->setId($_GET["id"]);
                $this->buscarCliente();
            }else if($_POST["clienteId"]){
                $this->clienteId = $_POST["clienteId"];
                $this->bean->setId($_POST["clienteId"]);
                $this->buscarCliente();
            }
            if(isset($_GET["excluir"])){
                $this->excluirCliente($_GET["excluir"]);
            }
        }

        function listarCliente(){
            $model = new ClienteModel();
            $row = $model->getCliente("*", "cliente");
            while($res = mysql_fetch_array($row)){
                $lista .= "<div class='col-xs-12'>
                    <div class='panel panel-default'>
                        <div class='panel-body'>".$res["nome"].
                            "<div class='pull-right'>
                                <a href='usuarioCliente.php?id=".$res["id"]."'>
                                    <i class='fa fa-2x fa-user' data-toggle='tooltip' data-placement='top' title='Cadastrar Usuário'></i>
                                </a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href='#'>
                                    <i class='fa fa-2x fa-gear' data-toggle='tooltip' data-placement='top' title='Configurações de conta'></i>
                                </a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href='#' onclick='excluir({$res['id']},\"" .$res['nome']."\");'>
                                    <i class='fa fa-2x fa-close' data-toggle='tooltip' data-placement='top' title='Excluir cliente'></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>";
            }
            return $lista;
        }

        function buscarCliente(){
            $model = new ClienteModel();
            $row = $model->getCliente("*","cliente","WHERE id = {$this->bean->getId()}");
            $res = mysql_fetch_array($row);
            // print_r($res);
            $this->bean->setNome($res["nome"]);
        }

        function cadastrarCliente($bean){
            $model = new ClienteModel();
            $res = $model->getCliente("*","endereco","WHERE cnpj LIKE {$this->bean->getCnpj()}");
            if(mysql_num_rows($res)<1){
                if($model->setEndereco($bean)){
                    $res = $model->getCliente("*","endereco","WHERE cnpj LIKE {$this->bean->getCnpj()}");
                    $row = mysql_fetch_array($res);
                    $bean->setEnderecoId($row["id"]);
                    if($model->setCliente($bean)){
                        $tipo = "success";
                        $texto = "Cliente inserido com sucesso!";
                    }else{
                        $tipo = "danger";
                        $texto = "Cliente não inserido!";
                    }
                }else{
                    $tipo = "danger";
                    $texto = "Endereço não inserido!";
                }
            }else{
                $tipo = "warning";
                $texto = "Cliente já cadastrado!";
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
        }

        function excluirCliente($id){
            $modelCliente = new ClienteModel();
            $modelUsuario = new UsuarioModel();
            $res = $modelUsuario->buscarUsuario("*","usuario","WHERE cliente_id = ".$id);
            if(mysql_num_rows($res)>0){
                if($modelUsuario->removeUsuario($id)){
                    $res = $modelCliente->getCliente("*","cliente","WHERE id = ".$id);
                    $row = mysql_fetch_array($res);
                    print_r($row["endereco_id"]);
                    if($modelCliente->removeCliente($id)){
                        if($modelCliente->removeEndereco($row["endereco_id"])){
                            $tipo = "success";
                            $texto = "Cliente excluído com sucesso!";
                        }else{
                            $tipo = "danger";
                            $texto = "Não foi possível excluir o cliente!(Endereço)";
                        }
                    }else{
                        $tipo = "danger";
                        $texto = "Não foi possível excluir o cliente!";
                    }
                }else{
                    $tipo = "danger";
                    $texto = "Não foi possível excluir o cliente!(Usuário)";
                }
            }else{
                $res = $modelCliente->getCliente("*","cliente","WHERE id = ".$id);
                $row = mysql_fetch_array($res);
                if($modelCliente->removeCliente($id)){
                    print($row["endereco_id"]);
                    if($modelCliente->removeEndereco($row["endereco_id"])){
                        $tipo = "success";
                        $texto = "Cliente excluído com sucesso!";
                    }else{
                        $tipo = "danger";
                        $texto = "Não foi possível excluir o cliente!(Endereço)";
                    }
                }else{
                    $tipo = "danger";
                    $texto = "Não foi possível excluir o cliente!";
                }
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

        function getNome(){
            return $this->bean->getNome();
        }

        function getCnpjCliente(){
            return $this->bean->getCnpjCliente();
        }

        function getId(){
            return $this->bean->getId();
        }

        function getAlert(){
            return $this->alert;
        }

    }
?>
