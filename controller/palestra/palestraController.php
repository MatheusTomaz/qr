<?
    session_start();
    require_once("../../model/palestra/palestraModel.php");
    require_once("../../controller/relatorio/relatorioController.php");

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
            if(isset($_GET["excluirPalestra"])){
                $this->excluirPalestra($_GET["excluirPalestra"]);
            }
            if(isset($_GET["gerarRelatorio"])){
                $this->gerarRelatorio($_GET["gerarRelatorio"],$_GET["id"]);
            }
            $this->finalizarPalestra();
        }

        function verificaEventoAberto($id){
            $row = $this->modelPalestra->getPalestra("*","participante_has_palestra","WHERE palestra_evento_id = ".$id);
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

        function listarParticipantes(){
            $row2 = $this->modelPalestra->getPalestra("*","evento","WHERE id = ".$this->eventoId);
            $nomeEvento = mysql_fetch_array($row2);
            $lista =   "
                    <div class='row'>
                        <div class='col-xs-12'>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    Participantes ({$nomeEvento['nome']})
                                    <div class='pull-right'>
                                        <button class='btn btn-default' style='margin-top:-8px;' type='button' onclick='checkPart()'>
                                            Selecionar todos
                                        </button>
                                        <button class='btn btn-default' style='margin-top:-8px;' type='button' onclick='excluirAll(".$nomeEvento['id'].")' data-toggle='tooltip' data-placement='bottom' title='Excluir selecionados'>
                                            <i class='fa fa-trash'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>";
            if($_SERVER["SCRIPT_NAME"] == "/sisqrcode/view/participante/listarParticipante.php"){
                $lista .= $this->alert;
            }
            $lista .= " <div class='col-xs-12'>
                            <form name='formParticipantes'>
                                <div class='panel-group'>";
            $row = $this->modelPalestra->getPalestra("*","participante_has_evento","WHERE evento_id = ".$this->eventoId);
            if(mysql_num_rows($row) > 0){
                while($resposta = mysql_fetch_array($row)){
                    $row2 = $this->modelPalestra->getPalestra("*","participante","WHERE id = ".$resposta['participante_id']);
                    $res = mysql_fetch_array($row2);
                    $status = $this->verificaParticipantePresenca($res['id']);
                    $lista .=   "   <div class='panel panel-default'>
                                        <div class='panel-body'>
                                            <div class='row'>
                                                <div class='col-xs-1'>
                                                    <input type='checkbox'
                                                    ".($status ? "style='display:none'":"name='listaParticipante[]'").
                                                    "participanteController.php  id='palestrasParticipante{$res['id']}' value='{$res["id"]}'>
                                                </div>
                                                <div class='col-xs-5'>
                                                    <a data-toggle='collapse' data-parent='#accordion' href='#collapse".$res["id"]."'>".$res["nome"]."</a>
                                                </div>
                                                <div class='col-xs-3'>
                                                    <b>CPF</b>: ".$res["cpf"]."
                                                </div>
                                                <div class='col-xs-3 icones'>
                                                    <div class='pull-right'>
                                                        ".($status ? " ":"<a href='#' onclick='excluir({$res['id']},{$this->eventoId},\"" .$res['nome']."\")'>
                                                            <i class='fa fa-2x fa-close' data-toggle='tooltip' data-placement='top' title='Excluir participante'></i>
                                                        </a>")."
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id='collapse".$res["id"]."' class='panel-collapse collapse'>
                                            <div class='panel-body'>
                                                <b>Palestras do participante:</b>
                                                <ul class='list-group'>";
                        $palestraRow = $this->modelPalestra->getPalestra("*","participante_has_palestra","WHERE participante_id = ".$res['id']." AND palestra_evento_id = ".$this->eventoId);
                        while($palRow = mysql_fetch_array($palestraRow)){
                            $infoPalestraRow = $this->modelPalestra->getPalestra("*","palestra","WHERE id = ".$palRow['palestra_id']);
                            $infoPalestraRow = mysql_fetch_array($infoPalestraRow);
                            $lista .=   "           <li class='list-group-item'>
                                            ".$infoPalestraRow["nome"]."
                                                        <div class='pull-right'>
                                                            <span id='pt{$res['id']}' class='label label-".(($palRow["presenca"]==1) ? "success'>Presente" : "danger'>Ausente")."</span></td>
                                                        </div>
                                                    </li>";
                        }
                    $lista .=  "                </ul>
                                            </div>
                                        </div>
                                    </div>";
                }

            }else{
                $lista .=   "           <div class='panel panel-default'>
                                            <div class='panel-body text-center'>
                                                Nenhum participante cadastrado
                                            </div>
                                        </div>";
            }
            $lista .= "     </div>
                            </form>
                        </div>";
            return $lista;
        }

        function verificaParticipantePresenca($id){
            $row = $this->modelPalestra->getPalestra("*","participante_has_palestra","WHERE participante_id = ".$id." AND palestra_evento_id = ".$this->eventoId);
            if(mysql_num_rows($row)>0){
                $status = false;
                while($res = mysql_fetch_array($row)){
                    if($res["presenca"]==1){
                        $status = true;
                        break;
                    }
                }
            }
            return $status;
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

        function verificaEventoFinalizado($id){
            $row = $this->modelPalestra->getPalestra("*","palestra","WHERE evento_id = ".$id);
            $status = false;
            if(mysql_num_rows($row)>0){
                $status = true;
                while($res = mysql_fetch_array($row)){
                    if($res["status"]==0){
                        $status = false;
                    }
                }
            }
            return $status;
        }

        function verificaPalestraAberta($id,$evento = " "){
            $row = $this->modelPalestra->getPalestra("*","participante_has_palestra","WHERE palestra_id = ".$id." AND palestra_evento_id = ".$this->eventoId.$evento);
            $status = false;
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

        function listarPalestrasCliente($id){
            $lista = array();
            $verifica = true;
            $row = $this->modelPalestra->getPalestra("*","palestra","WHERE evento_id = ".$id." AND status = 0");
            if(mysql_num_rows($row)>0){
                while($res = mysql_fetch_array($row)){
                    if(!$this->verificaPalestraAberta($res['id'], $res['evento_id']) && $this->palestrasSParticipante($res['id'])){
                        $lista[] = $res;
                        // print_r($row);
                        $verifica = false;
                    }
                }
            }
            if($verifica){
                $lista[] = array('nome' => 'Sem Palestras', 'id' => 0);
            }

            $json = json_encode( $lista );
            echo $json;
        }

        function palestrasSParticipante($id){
            $qtdParticipante = $this->modelPalestra->getPalestra("*","participante_has_palestra","WHERE palestra_id = ".$id);
            $qtdParticipante = mysql_num_rows($qtdParticipante);
            if($qtdParticipante > 0){
                return true;
            }else{
                return false;
            }
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

        function excluirPalestra($id){
            $row = $this->modelPalestra->getPalestra("*","participante_has_palestra","WHERE palestra_id = ".$id);
            $row2 = $this->modelPalestra->getPalestra("*","palestra","WHERE id = ".$id." AND evento_id = ".$this->eventoId);
            $palestra = mysql_fetch_array($row2);
            $row2 = $this->modelPalestra->getPalestra("*","palestra","WHERE id = ".$id." AND evento_id = ".$this->eventoId);
            if($palestra["status"]==0){
                if(!$this->verificaPalestraAberta($id)){
                    if(mysql_num_rows($row2) > 0){
                        $res = $this->modelPalestra->removePalestra($id,$this->eventoId);
                        if($res){
                            $tipo = "success";
                            $texto = "Palestra removida com sucesso!";
                        }else{
                            $tipo = "danger";
                            $texto = "Não foi possível remover Palestra";
                        }
                    }else{
                        $tipo = "danger";
                        $texto = "Palestra não existe!";
                    }
                }else{
                    $tipo = "danger";
                    $texto = "Não é possível remover palestra!";
                }
            }else{
                $tipo = "danger";
                $texto = "Não é possível remover palestra finalizada!";
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
        }

        function titlePalestra(){
            $row2 = $this->modelPalestra->getPalestra("*","evento","WHERE id = ".$this->eventoId);
            $nomeEvento = mysql_fetch_array($row2);
            $lista =   "<div class='row'>
            <div class='col-xs-12'>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    <div class='row'>
                                        <div class='col-xs-8'>
                                            {$nomeEvento['nome']}
                                        </div>";

            if($_SERVER["SCRIPT_NAME"] == "/sisqrcode/view/palestra/listarPalestra.php"){
                if(isset($_SESSION['idParticipante'])){
                    $lista .=           "<div class='col-xs-4'>
                                            <div class='row'>
                                                <div class='col-xs-3'>

                                                </div>
                                                <div class='col-xs-3'>

                                                </div>
                                                <div class='col-xs-3'>
                                                    Presença
                                                </div>
                                            </div>
                                        </div>";
                }else if(!$this->verificaEventoFinalizado($this->eventoId)){
                    $lista .=           "<div class='col-xs-4'>
                                            <div class='row'>
                                                <div class='col-xs-4'>
                                                </div>
                                                <div class='col-xs-4'>
                                                    <a href='".$_SESSION["palestra"]["view"]["cadastro"]."?id=".$this->eventoId."'>
                                                        <i class='fa fa-2x fa-plus-square' data-toggle='tooltip' data-placement='top' title='Adicionar palestras'></i>
                                                    </a>
                                                </div>
                                                <div class='col-xs-4'>
                                                    <a href='".$_SESSION["pessoas"]["view"]["cadastro"]."?id=".$this->eventoId."'>
                                                        <i class='fa fa-2x fa-group' data-toggle='tooltip' data-placement='top' title='Adicionar pessoas'></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>";
                }
            }
// <div class='col-xs-4'>
//                                                     <a href='".$_SESSION["participante"]["view"]["cadastro"]."?id=".$this->eventoId."&tipo=todos'>
//                                                         <i class='fa fa-2x fa-user' data-toggle='tooltip' data-placement='top' title='Adicionar participantes'></i>
//                                                     </a>
//                                                 </div>
            $lista .=                "</div>
                                    </div>
                                </div>
                            </div>
                        </div>";
            return $lista;
        }

        function listarPalestras(){
            $row2 = $this->modelPalestra->getPalestra("*","evento","WHERE id = ".$this->eventoId);
            $nomeEvento = mysql_fetch_array($row2);
            $lista =   "<div class='row'>";

            if($_SERVER["SCRIPT_NAME"] == "/sisqrcode/view/palestra/listarPalestra.php"){
                $lista .= $this->alert;
            }
            $lista .= "<div class='col-xs-12'>
                    <div class='panel-group'>";
            $row = $this->modelPalestra->getPalestra("*","palestra","WHERE evento_id = ".$this->eventoId);
            if(mysql_num_rows($row) > 0){
                while($res = mysql_fetch_array($row)){
                    $qtdParticipante = $this->modelPalestra->getPalestra("*","participante_has_palestra","WHERE palestra_id = ".$res['id']);
                    $qtdParticipante = mysql_num_rows($qtdParticipante);
                    $presencaParticipante = $this->modelPalestra->getPalestra("*","participante_has_palestra","WHERE palestra_id = ".$res['id']." AND participante_id = ".$_SESSION['idParticipante']);
                    $presencaParticipante = ($presencaParticipante['presenca']? "<span class='label label-success'>Presente</span>":"<span class='label label-danger'>Ausente</span>");
                    $lista .=   "<div class='panel panel-default'>
                                    <div class='panel-body'>
                                        <div class='row'>
                                            <div class='col-xs-5'>
                                                <a data-toggle='collapse' data-parent='#accordion' href='#collapse".$res["id"]."'>".$res["nome"]."</a>
                                            </div>
                                            <div class='col-xs-3'>
                                                {$res['tipo']}
                                            </div>".(!isset($_SESSION['idParticipante'])?"
                                            <div class='col-xs-4 icones'>
                                                <div class='row'>
                                                    <div class='col-xs-3'>

                                                    </div>
                                                    <div class='col-xs-3'>

                                                    </div>
                                                    ".(($res["status"]==0) ? "

                                                    <div class='col-xs-3'>
                                                        <a href='#'>
                                                            <i class='fa fa-2x fa-gear' data-toggle='tooltip' data-placement='top' title='Editar Palestra'></i>
                                                        </a>
                                                    </div>
                                                    ".($this->verificaPalestraAberta($res['id'])?"
                                                        <div class='col-xs-3'>
                                                            <span class='label label-success'>Aberta</span>
                                                        </div>":"
                                                        <div class='col-xs-3'>
                                                            <a href='#' onclick='excluir({$res['id']},{$this->eventoId},\"" .$res['nome']."\");'>
                                                            <i class='fa fa-2x fa-close' data-toggle='tooltip' data-placement='top' title='Excluir palestra'></i>
                                                            </a>
                                                        </div>"):"<div class='col-xs-3'>
                                                <span class='label label-danger'>Finalizado</span></div>")."
                                                </div>
                                            </div>":"
                                            <div class='col-xs-4 icones'>
                                                <div class='row'>
                                                    <div class='col-xs-3'>

                                                    </div>
                                                    <div class='col-xs-3'>

                                                    </div>
                                                    <div class='col-xs-3'>
                                                        {$presencaParticipante}
                                                    </div>
                                                </div>
                                            </div>    ")."
                                        </div>
                                    </div>
                                    ".(!isset($_SESSION['idParticipante'])?"
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
                                    </div>":" ")."
                                </div>";
                }
            }else{
                $lista .=   "<div class='panel panel-default'>
                                <div class='panel-body text-center'>
                                    Nenhuma palestra cadastrada
                                </div>
                            </div>";
            }
            $lista .= "</div></div></div>";
            return $lista;
        }

        function gerarRelatorio($idPalestra,$idEvento){
            $relatorio = new RelatorioController();
            $relatorio->gerarRelatorio($idPalestra,$idEvento);
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
