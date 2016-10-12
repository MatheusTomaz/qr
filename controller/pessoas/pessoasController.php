<?
    session_start();
    require_once($_SESSION["pessoas"]["model"]);

    class PessoasController{

        private $alert, $modelPessoa, $bean, $eventoId;

        function PessoasController(){
            $this->bean = new PessoasBean();
            $this->modelPessoa = new PessoaModel();
            if(isset($_GET["id"])){
                $this->eventoId = $_GET["id"];
                $this->bean->setEventoId($_GET["id"]);
            }else if($_POST["eventoId"]){
                $this->eventoId = $_POST["eventoId"];
                $this->bean->setEventoId($_POST["eventoId"]);
            }
            if(isset($_POST["nomePessoa"])){
                $this->bean->setNome($_POST["nomePessoa"]);
                $this->bean->setGrupoPessoa($_POST["grupoPessoa"]);
                $this->bean->setCpfPessoa($_POST["cpf"]);
                $this->cadastrarPessoa();
            }
            if(isset($_GET["excluir"])){
                $this->excluirPessoa($_GET["excluir"]);
            }
            if(isset($_GET["excluirAll"])){
                $this->excluirTodasPessoas($_GET["excluirAll"]);
            }
        }

        function cadastrarPessoa(){
            $row = $this->modelPessoa->getPessoa("*","pessoas","WHERE cpf = ".$this->bean->getCpfPessoa()." AND evento_id = ".$this->eventoId);
            if(mysql_num_rows($row)<1){
                if($this->modelPessoa->setPessoa($this->bean)){
                    $tipo = "success";
                    $texto = "Pessoa cadastrada com sucesso!";
                }else{
                    $tipo = "danger";
                    $texto = "Erro ao cadastrar! Verifique se os campos estão preenchidos corretamente!";
                }
            }else{
                $tipo = "warning";
                $texto = "Pessoa já cadastrada!";
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
        }

        function listarPessoas(){
            $row = $this->modelPessoa->getPessoa("*","pessoas","WHERE evento_id = ".$this->eventoId);
            $row2 = $this->modelPessoa->getPessoa("*","evento","WHERE id = ".$this->eventoId);
            $nomeEvento = mysql_fetch_array($row2);
            $lista =   "<div class='col-xs-12'>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    <div class='row'>
                                        <div class='col-xs-12'>
                                            Pessoas ({$nomeEvento['nome']})

                                            <div class='pull-right'>
                                                <button class='btn btn-default' style='margin-top:-8px;' type='button' onclick='checkPart()'>
                                                    Selecionar todos
                                                </button>
                                                <button class='btn btn-default' style='margin-top:-8px;' type='button' onclick='excluirAll(".$nomeEvento['id'].")' data-toggle='tooltip' data-placement='bottom' title='Excluir selecionados'>
                                                    <i class='fa fa-trash'></i>
                                                </button>
                                    ";
            if($_SERVER["SCRIPT_NAME"] == "/sisqrcode/view/pessoas/listarPessoas.php"){
                $lista .= "&nbsp&nbsp&nbsp;<a href='".$_SESSION["pessoas"]["view"]["cadastro"]."?id={$_GET['id']}'>
                            <i class='fa fa-2x fa-plus' data-toggle='tooltip' data-placement='bottom' title='Cadastrar Pessoa'></i>
                        </a>";
            }
            $lista .= "         </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
            if($_SERVER["SCRIPT_NAME"] == "/sisqrcode/view/pessoas/listarPessoas.php"){
                $lista .= $this->alert;
            }
            $lista .= "<div class='col-xs-12'>
                        <form name='formParticipantes'>
                            <div class='panel-group'>";
            if(mysql_num_rows($row) > 0){
                while($res = mysql_fetch_array($row)){
                    $lista .=   "<div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div class='row'>
                                        <div class='col-xs-1'>
                                            <input type='checkbox' name='listaParticipante[]' id='palestrasParticipante{$res['id']}' value='{$res["id"]}'>
                                        </div>
                                        <div class='col-xs-5'>
                                            ".$res["nome"]."
                                        </div>
                                        <div class='col-xs-3'>
                                            <b>CPF: </b>".$res["cpf"]."
                                        </div>
                                        <div class='col-xs-2'>
                                            <b>Grupo: </b>".$res["grupo"]."
                                        </div>
                                        <div class='col-xs-1 icones'>
                                            <div class='pull-right'>
                                                <a href='#' onclick='excluir({$res['id']},{$res['evento_id']},\"" .$res['nome']."\");'>
                                                    <i class='fa fa-2x fa-close' data-toggle='tooltip' data-placement='top' title='Excluir Pessoa'></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                }
            }else{
                $lista .=   "<div class='panel panel-default'>
                                <div class='panel-body text-center'>
                                    Nenhuma pessoa cadastrada
                                </div>
                        </div>";
            }
            $lista .= "</div>
            </form>
        </div>";
            return $lista;
        }

        function excluirPessoa($id){
            $row = $this->modelPessoa->getPessoa("*","pessoas","WHERE id = ".$id." AND evento_id = ".$this->eventoId);
            if(mysql_num_rows($row)>0){
                if($this->modelPessoa->removePessoa($id)){
                    $tipo = "success";
                    $texto = "Pessoa excluída com sucesso!";
                }else{
                    $tipo = "danger";
                    $texto = "Não foi possível excluir!";
                }
            }else{
                $tipo = "warning";
                $texto = "Participante já excluído!";
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
        }

        function excluirTodasPessoas($id){
            $ids = explode(",",$id);
            foreach($ids as $id){
                $row = $this->modelPessoa->getPessoa("*","pessoas","WHERE id = ".$id);
                if(mysql_num_rows($row)>0){
                    if($this->modelPessoa->removePessoa($id)){
                        $tipo = "success";
                        $texto = "Pessoa excluída com sucesso!";
                    }else{
                        $tipo = "danger";
                        $texto = "Não foi possível excluir!";
                        break;
                    }
                }else{
                    $tipo = "warning";
                    $texto = "Participante já excluído!";
                    break;
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

        function getAlert(){
            return $this->alert;
        }

        function getId(){
            return $this->eventoId;
        }

    }
?>
