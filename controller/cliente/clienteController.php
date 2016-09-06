<?
    session_start();
    require_once($_SESSION["cliente"]["model"]);
    require_once($_SESSION["usuario"]["model"]);

    Class ClienteController{

        private $alert, $clienteId, $bean, $modelCliente;

        function ClienteController(){
            $this->bean = new ClienteBean();
            $this->modelCliente = new ClienteModel();
            $this->modelUsuario = new UsuarioModel();
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
                $this->cadastrarCliente();
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
            $row = $this->modelCliente->getCliente("*", "cliente");
            while($res = mysql_fetch_array($row)){
                $endereco = $this->modelCliente->getCliente("*","endereco","WHERE id = '{$res["endereco_id"]}'");
                $endereco = mysql_fetch_array($endereco);
                $lista .=   "<div class='panel panel-default'>
                                <div class='panel-body'>
                                    <a data-toggle='collapse' data-parent='#accordion' href='#collapse".$res["id"]."
                                    '>".$res["nome"]."</a>
                                    <div class='pull-right'>
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
                                <div id='collapse".$res["id"]."' class='panel-collapse collapse'>
                                    <div class='panel-body'>
                                        <div class='row'>
                                            <div class='col-md-2 col-xs-12'>
                                                <b>CNPJ:</b> ".$endereco["cnpj"]."
                                            </div>
                                            <div class='col-md-2 col-xs-12'>
                                                <b>País:</b> ".$endereco["pais"]."
                                            </div>
                                            <div class='col-md-5 col-xs-12'>
                                                <b>Cidade:</b> ".$endereco["cidade"]." - ".$endereco["estado"]."
                                            </div>
                                            <div class='col-md-3 col-xs-12'>
                                                <b>Bairro:</b> ".$endereco["bairro"]."
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class='col-md-9 col-xs-12'>
                                                <b>Endereço:</b> ".$endereco["rua"].", ".$endereco["numeroCasa"]."
                                                 - ".$endereco["complemento"]."
                                            </div>
                                            <div class='col-md-3 col-xs-12'>
                                                <b>Telefone:</b> ".$endereco["telefone"]."
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class='col-md-9 col-xs-12'>
                                                <b>Eventos:</b> ".$this->buscarQtdEventoCliente($res["id"])."
                                            </div>
                                            <div class='col-md-3 col-xs-12'>
                                                <button class='btn btn-default pull-right' onclick='#'>Ver Eventos</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            return $lista;
        }

        function buscarQtdEventoCliente($id){
            $res = $this->modelUsuario->buscarUsuario("*","usuario","WHERE cliente_id = ".$id);
            if(mysql_num_rows($res)<1){
                return 0;
            }
            $row = mysql_fetch_array($res);
            $res = $row = $this->modelCliente->getCliente("*","evento","WHERE usuario_id = ".$row["id"]);
            return mysql_num_rows($res);
        }

        function buscarCliente(){
            $row = $this->modelCliente->getCliente("*","cliente","WHERE id = {$this->bean->getId()}");
            $res = mysql_fetch_array($row);
            // print_r($res);
            $this->bean->setNome($res["nome"]);
        }

        function cadastrarCliente(){
            $res = $this->modelCliente->getCliente("*","endereco","WHERE cnpj LIKE {$this->bean->getCnpj()}");
            if(mysql_num_rows($res)<1){
                if($this->modelCliente->setEndereco($this->bean)){
                    $res = $this->modelCliente->getCliente("*","endereco","WHERE cnpj LIKE {$this->bean->getCnpj()}");
                    $row = mysql_fetch_array($res);
                    $this->bean->setEnderecoId($row["id"]);
                    if($this->modelCliente->setCliente($this->bean)){
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
            $res = $this->modelUsuario->buscarUsuario("*","usuario","WHERE cliente_id = ".$id);
            if(mysql_num_rows($res)>0){
                if($this->modelUsuario->removeUsuario($id)){
                    $res = $this->modelCliente->getCliente("*","cliente","WHERE id = ".$id);
                    $row = mysql_fetch_array($res);
                    print_r($row["endereco_id"]);
                    if($this->modelCliente->removeCliente($id)){
                        if($this->modelCliente->removeEndereco($row["endereco_id"])){
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
                $res = $this->modelCliente->getCliente("*","cliente","WHERE id = ".$id);
                $row = mysql_fetch_array($res);
                if($this->modelCliente->removeCliente($id)){
                    print($row["endereco_id"]);
                    if($this->modelCliente->removeEndereco($row["endereco_id"])){
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
