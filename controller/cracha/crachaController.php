<?
    session_start();
    require_once("../../model/cracha/crachaModel.php");
    require_once("../../bean/evento/eventoBean.php");
    require_once("../../controller/qrcode/qrcodeController.php");

    Class CrachaController{

        private $bean, $modelCracha, $alert, $lista;

        function CrachaController(){
            $this->bean = new CrachaBean();
            $this->modelCracha = new CrachaModel();
            if(isset($_GET['id'])){
                $this->listarPessoas($_GET['id']);
                $this->listarParticipantes($_GET['id']);
            }
            if(isset($_GET['crachaPessoas'])){
                $this->gerarCracha($_GET['crachaPessoas'],$_GET['crachaParticipantes']);
            }
        }

        function gerarCracha($pessoasId,$participanteId){
            $evento = new EventoBean();
            $qrcode = new QRCodeController();
            $pessoasId = explode(",",$pessoasId);
            $participanteId = explode(",",$participanteId);
            $row = $this->modelCracha->getCracha("*","evento","WHERE id =".$_GET['id']);
            $res = mysql_fetch_array($row);
            $evento->setTipoCracha($res['tipoCracha']);
            $caminho = explode("-",$res['caminhoCracha']);
            print_r($caminho);
            $evento->setCaminhoCracha($caminho[0],$caminho[1]);
            $qrcode->gerarCracha($evento,$pessoasId,$participanteId);
        }

        function listarPessoas($id){
            $row2 = $this->modelCracha->getCracha("nome","evento","WHERE id = ".$id);
            $nomeEvento = mysql_fetch_array($row2);
            $this->lista =   "<div class='col-xs-12'>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    <div class='row'>
                                        <div class='col-xs-12'>
                                            Crachás ({$nomeEvento['nome']})
                                            <div class='pull-right'>
                                                <button class='btn btn-default' style='margin-top:-8px;' type='button' onclick='gerarCracha(".$id.")' data-toggle='tooltip' data-placement='bottom'>
                                                    Gerar Crachás
                                                </button>
                                    ";
            $this->lista .= "         </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
            if($_SERVER["SCRIPT_NAME"] == "/sisqrcode/view/pessoas/listarPessoas.php"){
                $this->lista .= $this->alert;
            }

            $this->lista .= "
            <div class='col-xs-12'>
                <div class='panel panel-default'>
                    <div class='panel-body panel-cracha'>
                        <div class='panel panel-default'>
                            <div class='panel-body title-body'>
                                <b>Pessoas</b>
                                <div class='pull-right'>
                                    <button class='btn btn-default' style='margin-top:-8px;' type='button' onclick='check()'>
                                        Selecionar todos
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class='col-xs-12'>
                            <form name='formCrachaPessoas'>
                            <div class='panel-group'>";
            $row = $this->modelCracha->getCracha("*","pessoas", "WHERE evento_id = $id ORDER BY grupo, nome");
            if(mysql_num_rows($row) > 0){
                while($res = mysql_fetch_array($row)){
                    $this->lista .=   "<div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div class='row'>
                                        <div class='col-xs-1'>
                                            <input type='checkbox' name='listaTudo[]' id='tudo{$res['id']}' value='{$res["id"]}'>
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
                                    </div>
                                </div>
                            </div>";
                }
            }else{
                $this->lista .=   "<div class='panel panel-default'>
                                <div class='panel-body text-center'>
                                    Nenhuma pessoa cadastrada
                                </div>
                        </div>";
            }
            $this->lista .= "</div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>";
        }

        function listarParticipantes($id){
            $row2 = $this->modelCracha->getCracha("nome","evento","WHERE id = ".$id);
            $nomeEvento = mysql_fetch_array($row2);
            $this->lista .= "<div class='col-xs-12'>
                <div class='panel panel-default'>
                    <div class='panel-body panel-cracha'>
                        <div class='panel panel-default'>
                            <div class='panel-body title-body'>
                                <div class='row'>
                                    <div class='col-xs-8'>
                                        <b>Participantes</b>
                                    </div>
                                    <div class='col-xs-2'>
                                        Filtros: <a href='?id={$_GET['id']}&filtro=nome'>Nome</a> |
                                        <a href='?id={$_GET['id']}&filtro=identificacao'>Inscrição</a>
                                    </div>
                                    <div class='col-xs-2'>
                                        <button class='btn btn-default pull-right' style='margin-top:-8px;' type='button' onclick='checkPart()'>
                                            Selecionar todos
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-xs-12'>
                            <form name='formCrachaParticipantes'>
                            <div class='panel-group'>";
                            if($_GET['filtro']=='identificacao'){
                                $option = 'id';
                            }else{
                                $option = 'nome';
                            }
            $row = $this->modelCracha->getCracha("*","participante", "WHERE evento_id = $id ORDER BY $option");
            if(mysql_num_rows($row) > 0){
                while($res = mysql_fetch_array($row)){
                    $this->lista .=   "<div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div class='row'>
                                        <div class='col-xs-1'>
                                            <input type='checkbox' name='listaTudo[]' id='tudo{$res['id']}' value='{$res["id"]}'>
                                        </div>
                                        <div class='col-xs-8'>
                                            ".$res["nome"]."
                                        </div>
                                        <div class='col-xs-3'>
                                            <b>CPF: </b>".$res["cpf"]."
                                        </div>
                                    </div>
                                </div>
                            </div>";
                }
            }else{
                $this->lista .=   "<div class='panel panel-default'>
                                <div class='panel-body text-center'>
                                    Nenhum participante cadastrado
                                </div>
                        </div>";
            }
            $this->lista .= "</div>
                        </form>
                    </div>
                </div>
            </div>
            </div>";
        }

        function getLista(){
            return $this->lista;
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
    }
?>
