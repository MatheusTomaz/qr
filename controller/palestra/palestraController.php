<?
    session_start();
    require_once($_SESSION["palestra"]["model"]);

    class PalestraController{

        private $alert, $eventoId, $bean, $modelPalestra;

        function PalestraController(){
            $this->bean = new PalestraBean();
            $this->modelPalestra = new PalestraModel();
            if(isset($_GET["id"])){
                $this->eventoId = $_GET["id"];
                $this->bean->setEventoId($_GET["id"]);
            }else if($_POST["eventoId"]){
                $this->eventoId = $_POST["eventoId"];
                $this->bean->setEventoId($_POST["eventoId"]);
            }
            if(isset($_POST["nomePalestra"])){
                $this->bean->setNome($_POST["nomePalestra"]);
                $this->bean->setTipoPalestra($_POST["tipoPalestra"]);
                $this->bean->setQtdParticipante($_POST["qtdParticipante"]);
                $this->cadastrarPalestra();
            }
            if(isset($_GET["excluir"])){
                $this->excluirPalestra($_GET["excluir"]);
            }
            $this->finalizarPalestra();
        }

        function cadastrarPalestra(){
            if($this->modelPalestra->setPalestra($this->bean)){
                $tipo = "success";
                $texto = "Palestra cadastrada com sucesso!";
            }else{
                $tipo = "danger";
                $texto = "Erro! Não foi possível cadastrar a palestra! Verifique se os campos estão preenchidos corretamente!";
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
        }

        function verificaPalestraAberta($id){
            $row = $this->modelPalestra->getPalestra("*","participante_has_palestra","WHERE palestra_id = ".$id." AND palestra_evento_id = ".$this->eventoId);
            if(mysql_num_rows($row)>0){
                $status = false;
                while($res = mysql_fetch_array($row)){
                    if($res["presenca"]==1){
                        $status = true;
                    }
                }
            }
            return $status;
        }

        function finalizarPalestra(){
            if(isset($_GET["fecharPalestra"])){
                if($this->modelPalestra->atualizarPalestra($_GET['fecharPalestra'],$this->eventoId)){
                    $tipo = "success";
                    $texto = "Palestra finalizada com sucesso!";
                }else{
                    $tipo = "danger";
                    $texto = "Não foi possível finalizar a palestra!";
                }
                $this->alert = $this->gerarAlert($tipo,$texto);
            }
        }

        function listarPalestras(){
            $row2 = $this->modelPalestra->getPalestra("*","evento","WHERE id = ".$this->eventoId);
            $nomeEvento = mysql_fetch_array($row2);
            $lista =   "<div class='col-xs-12'>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    Palestras Cadastradas ({$nomeEvento['nome']})";
            if($_SERVER["SCRIPT_NAME"] == "/sisqrcode/view/palestra/listarPalestra.php"){
                $lista .= "<div class='pull-right'>
                                <a href='".$_SESSION["palestra"]["view"]["cadastro"]."?id={$_GET['id']}'>
                                    <i class='fa fa-2x fa-plus' data-toggle='tooltip' data-placement='bottom' title='Cadastrar palestra'></i>
                                </a>
                            </div>";
            }
            $lista .= "</div>
                    </div>
                </div>
                <div class='col-xs-12'>
                    <div class='panel-group'>";
            $row = $this->modelPalestra->getPalestra("*","palestra","WHERE evento_id = ".$this->eventoId);
            if(mysql_num_rows($row) > 0){
                while($res = mysql_fetch_array($row)){
                    $qtdParticipante = $this->modelPalestra->getPalestra("*","participante_has_palestra","WHERE palestra_id = ".$res['id']);
                    $qtdParticipante = mysql_num_rows($qtdParticipante);
                    $lista .=   "<div class='panel panel-default'>
                                    <div class='panel-body'>
                                        <div class='row'>
                                            <div class='col-xs-5'>
                                                <a data-toggle='collapse' data-parent='#accordion' href='#collapse".$res["id"]."'>".$res["nome"]."</a>
                                            </div>
                                            <div class='col-xs-3'>
                                                {$res['tipo']}
                                            </div>
                                            <div class='col-xs-4'>
                                                ".(($res["status"]==0) ? "
                                                <div class='row'>
                                                    <div class='col-xs-3'>
                                                        <a href='".$_SESSION["pessoas"]["view"]["cadastro"]."?id=".$nomeEvento["id"]."'>
                                                                <i class='fa fa-2x fa-group' data-toggle='tooltip' data-placement='top' title='Adicionar pessoas'></i>
                                                        </a>
                                                    </div>
                                                    <div class='col-xs-3'>
                                                        <a href='".$_SESSION["participante"]["view"]["cadastro"]."?id=".$nomeEvento["id"]."'>
                                                            <i class='fa fa-2x fa-user' data-toggle='tooltip' data-placement='top' title='Adicionar participantes'></i>
                                                        </a>
                                                    </div>
                                                    <div class='col-xs-3'>
                                                        <a href='#'>
                                                            <i class='fa fa-2x fa-gear' data-toggle='tooltip' data-placement='top' title='Editar Palestra'></i>
                                                        </a>
                                                    </div>
                                                    ".($this->verificaPalestraAberta($res['id'])?"<div class='col-xs-3'></div>":"<div class='col-xs-3'>
                                                    <a href='#' onclick='excluir({$res['id']},\"" .$res['nome']."\");'>
                                                        <i class='fa fa-2x fa-close' data-toggle='tooltip' data-placement='top' title='Excluir palestra'></i>
                                                    </a></div>")."
                                                </div>":"<span class='label label-danger pull-right'>Finalizada</span>")."
                                            </div>
                                        </div>
                                    </div>
                                    <div id='collapse".$res["id"]."' class='panel-collapse collapse'>
                                        <div class='panel-body'>
                                            <div class='row'>
                                                <div class='col-xs-12'>
                                                    <b>Nº de participantes:</b> ".$qtdParticipante." / ".$res['qtdParticipante']."
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-xs-12'>
                                                    <div class='pull-right'>
                                                    ".(($res["status"]== 0 ) ? "
                                                        <button class='btn btn-default'  onclick=\"location.href='".$_SESSION["participante"]["view"]["listar"]."?id=".$this->eventoId."&palestra={$res['id']}'\">
                                                            Ver Participantes
                                                        </button>
                                                        <button class='btn btn-default' data-toggle='tooltip' data-placement='top' title='Finalizar palestra' onclick='fecharPalestra({$res['id']},{$this->eventoId},\"{$res['nome']}\")'>
                                                            <i class='fa fa-lock' ></i>
                                                        </button>":"<button class='btn btn-default' onclick='gerarRelatorio({$res['id']},{$this->eventoId},\"{$res['nome']}\")'>
                                                            Gerar Relatorio
                                                        </button>")."
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                }
            }else{
                $lista .=   "<div class='panel panel-default'>
                                <div class='panel-body text-center'>
                                    Nenhuma palestra cadastrada
                                </div>
                            </div>";
            }
            $lista .= "</div></div>";
            return $lista;
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

        function getEventoId(){
            return $this->eventoId;
        }


    }
?>
