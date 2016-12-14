<?
    session_start();
    require_once("../../model/evento/eventoModel.php");

    Class EventoParticipanteController{

        private $bean, $modelEvento, $alert, $caminho, $caminhoCracha, $caminhoLogo, $imagem1, $imagem2, $tipo1, $size1,
        $tipo2, $size2, $option, $logo, $timeInsert;

        function EventoParticipanteController(){
            // print_r($_SESSION);
            $this->timeInsert = round(microtime(true) * 1000);
            $this->caminho = "../../assets/img/";
            $this->bean = new EventoBean();
            $this->modelEvento = new EventoModel();
            if(isset($_GET["excluir"])){
                $this->excluirEvento($_GET["excluir"]);
            }
            if(isset($_GET["aprovar"])){
                $this->aprovarEvento($_GET["aprovar"]);
            }
        }

        function verificaEventoAberto($id){
            $row = $this->modelEvento->getEvento("*","participante_has_palestra","WHERE palestra_evento_id = ".$id);
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

        function buscarEventos($id){
            $lista = array();
            $verifica = true;
            $row = $this->modelEvento->getEvento("*","evento","WHERE usuario_id = ".$id." AND status = 1");
            if(mysql_num_rows($row)>0){
                while($res = mysql_fetch_array($row)){
                    // die(print_r($row));
                    if(!$this->verificaEventoFinalizado($res['id'])){
                        $lista[] = $res;
                        $verifica = false;
                        // print_r($row);
                    }
                }
            }
            if($verifica){
                $lista[] = array('nome' => 'Sem eventos', 'id' => 0);
            }

            $json = json_encode( $lista );
            echo $json;
        }

        function verificaEventoFinalizado($id){
            $row = $this->modelEvento->getEvento("*","palestra","WHERE evento_id = ".$id);
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

        function excluirEvento($id){
            $tipo = "danger";
            $texto = "Não é possível excluir o evento";
            if(!$this->verificaEventoAberto($id)){
                if($this->modelEvento->removeEvento($id)){
                    $tipo = "success";
                    $texto = "Evento excluído com sucesso!";
                }
            }
            $this->alert = $this->gerarAlert($tipo, $texto);
        }

        function getIdEvento(){
            $row = $this->modelEvento->getUltimoEvento($_SESSION["idCliente"]);
            $row = mysql_fetch_array($row);
            $this->bean->setId($row[0]);
        }

        function listarEventoPorStatus($status){
            $row = $this->modelEvento->getEvento("*", "participante_has_evento", "WHERE participante_id = '{$_SESSION["idParticipante"]}' ORDER BY data_criado");
            while($res = mysql_fetch_array($row)){
                $row2 = $this->modelEvento->getEvento("*", "evento", "WHERE id = '{$res["evento_id"]}'");
                $evento = mysql_fetch_array($row2);
                $organizador = $this->modelEvento->getEvento("*", "usuario", "WHERE id = '{$evento["usuario_id"]}'");
                $organizador = mysql_fetch_array($organizador);
                $organizador = $this->modelEvento->getEvento("*", "cliente", "WHERE id = '{$organizador["cliente_id"]}'");
                $organizador = mysql_fetch_array($organizador);
                $organizador = $organizador['nome'];
                $qtdPalestra = mysql_num_rows($row2);
                $row2 = $this->modelEvento->getEvento("*", "participante_has_evento", "WHERE evento_id = '{$res["id"]}'");
                $qtdParticipante = mysql_num_rows($row2);
                $row2 = $this->modelEvento->getEvento("*", "pessoas", "WHERE evento_id = '{$res["id"]}'");
                $qtdPessoas = mysql_num_rows($row2);
                // print_r($evento);
                if($status == "finalizado"){
                    if($this->verificaEventoFinalizado($res["id"])){
                        $lista .= "<div class='panel panel-default'>
                            <div class='panel-body'>
                                <div class='row'>
                                    <div class='col-xs-8'>
                                        <span class='status-finalizado'><i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;&nbsp;</span><a data-toggle='collapse' data-parent='#accordion' href='#collapse".$evento["id"]."
                                        '>".$evento["nome"]."</a>
                                    </div>
                                    <div class='col-xs-4 icones'>
                                        <div class='row'>
                                            <div class='col-xs-2'></div>
                                            <div class='col-xs-2'></div>
                                            <div class='col-xs-2'></div>
                                            <div class='col-xs-2'></div>
                                            <div class='col-xs-2'></div>
                                            <div class='col-xs-2'>
                                                <a href='#'>
                                                    <i class='fa fa-2x fa-gear' data-toggle='tooltip' data-placement='top' title='Editar evento'></i>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id='collapse".$res["id"]."' class='panel-collapse collapse'>
                                <div class='panel-body'>
                                    <div class='row'>
                                        <div class='col-xs-4 col-md-2'>
                                            <div class='thumbnail'>
                                                <img src='".(empty($res["caminhoLogo"]) ? "../../assets/img/exemplo_logo.jpg" : $res["caminhoLogo"])."'>
                                            </div>
                                        </div>
                                        <div class='col-xs-8 col-md-4'>
                                            <b>Status:</b> <span class='status-finalizado'>Finalizado</span>
                                        </div>
                                        <div class='col-xs-12 col-md-2'>
                                            <b>Organizador:</b> ".$organizador."<br><br>
                                        </div>
                                        <div class='col-xs-12 col-md-2'></div>

                                        <div class='col-xs-12 col-md-2'>
                                            <b>Palestras:</b> ".$qtdPalestra."<br><br>
                                        </div>

                                    </div>
                                    <div class='row'>
                                        <div class='col-xs-12 col-md-12'>
                                            <div class='pull-right'>
                                                <button class='btn btn-default' onclick=\"location.href='".$_SESSION["palestra"]["view"]["listar"]."?id=".$res['id']."'\">Ver Evento</button>
                                                <!---- <button class='btn btn-default' onclick=\"location.href='".$_SESSION["cracha"]["view"]["listar"]."?id=".$res['id']."'\">Ver Relatório Geral</button> ----!>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                }else{
                    if(!$this->verificaEventoFinalizado($res["id"])){
                        $lista .= "<div class='panel panel-default'>
                            <div class='panel-body'>
                                <div class='row'>
                                    <div class='col-xs-8'>
                                        <span class='status-aberto'><i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;&nbsp;</span><a data-toggle='collapse' data-parent='#accordion' href='#collapse".$evento["id"]."
                                        '>".$evento["nome"]."</a>
                                    </div>
                                    <div class='col-xs-4 icones'>
                                        <div class='row'>
                                            <div class='col-xs-2'></div>
                                            <div class='col-xs-2'></div>
                                            <div class='col-xs-2'></div>
                                            <div class='col-xs-2'></div>
                                            <div class='col-xs-2'>
                                                <a href='#'>
                                                    <i class='fa fa-2x fa-gear' data-toggle='tooltip' data-placement='top' title='Editar evento'></i>
                                                </a>
                                            </div>
                                            <div class='col-xs-2'>
                                                <a href='#' ".($this->verificaEventoAberto($res['id']) ? "style='display:none'":"")." onclick='excluir({$res['id']},\"" .$res['nome']."\");'>
                                                    <i class='fa fa-2x fa-close' data-toggle='tooltip' data-placement='top' title='Excluir evento'></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id='collapse".$evento["id"]."' class='panel-collapse collapse'>
                                <div class='panel-body'>
                                    <div class='row'>
                                        <div class='col-xs-4 col-md-2'>
                                            <div class='thumbnail'>
                                                <img src='".(empty($res["caminhoLogo"]) ? "../../assets/img/exemplo_logo.jpg" : $res["caminhoLogo"])."'>
                                            </div>
                                        </div>
                                        <div class='col-xs-8 col-md-4'>
                                            <b>Status:</b> <span class='status-aberto'>Aberto</span>
                                        </div>
                                        <div class='col-xs-12 col-md-2'>
                                            <b>Organizador:</b> ".$organizador."<br><br>
                                        </div>
                                        <div class='col-xs-12 col-md-2'>
                                        </div>
                                        <div class='col-xs-12 col-md-2'>
                                            <b>Palestras:</b> ".$qtdPalestra."<br><br>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-xs-12 col-md-12'>
                                            <div class='pull-right'>
                                                <button class='btn btn-default' onclick=\"location.href='".$_SESSION["palestra"]["view"]["listar"]."?id=".$evento['id']."'\">Ver Evento</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                }
            }
            return $lista;
        }

        function listarEvento(){
            $row = $this->modelEvento->getEvento("*", "evento", "WHERE usuario_id = '{$_SESSION["idCliente"]}' ORDER BY data_criado");
            while($res = mysql_fetch_array($row)){
                $row2 = $this->modelEvento->getEvento("*", "palestra", "WHERE evento_id = '{$res["id"]}'");
                $qtdPalestra = mysql_num_rows($row2);
                $row2 = $this->modelEvento->getEvento("*", "participante", "WHERE evento_id = '{$res["id"]}'");
                $qtdParticipante = mysql_num_rows($row2);
                $row2 = $this->modelEvento->getEvento("*", "pessoas", "WHERE evento_id = '{$res["id"]}'");
                $qtdPessoas = mysql_num_rows($row2);
                if($this->verificaEventoFinalizado($res["id"])){
                    $lista .=   "<div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div class='row'>
                                        <div class='col-xs-8'>
                                            <span class='status-finalizado'><i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;&nbsp;</span><a data-toggle='collapse' data-parent='#accordion' href='#collapse".$res["id"]."
                                            '>".$res["nome"]."</a>
                                        </div>
                                        <div class='col-xs-4 icones'>
                                            <div class='row'>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'>
                                                    <a href='#'>
                                                        <i class='fa fa-2x fa-gear' data-toggle='tooltip' data-placement='top' title='Editar evento'></i>
                                                    </a>
                                                </div>
                                                <div class='col-xs-2'>
                                                    <span class='label label-danger' data-toggle='tooltip' data-placement='top' title='Finalizado'>&nbsp;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id='collapse".$res["id"]."' class='panel-collapse collapse'>
                                    <div class='panel-body'>
                                        <div class='row'>
                                            <div class='col-xs-4 col-md-2'>
                                                <div class='thumbnail'>
                                                    <img src='".(empty($res["caminhoLogo"]) ? "../../assets/img/exemplo_logo.jpg" : $res["caminhoLogo"])."'>
                                                </div>
                                            </div>
                                            <div class='col-xs-8 col-md-4'>
                                                <b>Status:</b> <span class='status-finalizado'>Finalizado</span>
                                            </div>
                                            <div class='col-xs-12 col-md-2'></div>
                                            <div class='col-xs-12 col-md-2'></div>
                                            <div class='col-xs-12 col-md-2'>
                                                <b>Palestras:</b> ".$qtdPalestra."<br><br>
                                            </div>

                                        </div>
                                        <div class='row'>
                                            <div class='col-xs-12 col-md-12'>
                                                <div class='pull-right'>
                                                    <button class='btn btn-default' onclick=\"location.href='".$_SESSION["palestra"]["view"]["listar"]."?id=".$res['id']."'\">Ver Evento</button>
                                                    <!---- <button class='btn btn-default' onclick=\"location.href='".$_SESSION["cracha"]["view"]["listar"]."?id=".$res['id']."'\">Gerar Relatório Geral</button> ----!>
                                                </div>
                                            </div>
                                        </div>";
                }else if($res['status'] == 1){
                    $lista .=   "<div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div class='row'>
                                        <div class='col-xs-8'>
                                            <span class='status-aberto'><i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;&nbsp;</span><a data-toggle='collapse' data-parent='#accordion' href='#collapse".$res["id"]."
                                            '>".$res["nome"]."</a>
                                        </div>
                                        <div class='col-xs-4 icones'>
                                            <div class='row'>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'>
                                                    <a href='#'>
                                                        <i class='fa fa-2x fa-gear' data-toggle='tooltip' data-placement='top' title='Editar evento'></i>
                                                    </a>
                                                </div>
                                                <div class='col-xs-2'>
                                                    <a href='#' ".($this->verificaEventoAberto($res['id']) ? "style='display:none'":"")." onclick='excluir({$res['id']},\"" .$res['nome']."\");'>
                                                        <i class='fa fa-2x fa-close' data-toggle='tooltip' data-placement='top' title='Excluir evento'></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id='collapse".$res["id"]."' class='panel-collapse collapse'>
                                    <div class='panel-body'>
                                        <div class='row'>
                                            <div class='col-xs-4 col-md-2'>
                                                <div class='thumbnail'>
                                                    <img src='".(empty($res["caminhoLogo"]) ? "../../assets/img/exemplo_logo.jpg" : $res["caminhoLogo"])."'>
                                                </div>
                                            </div>
                                            <div class='col-xs-8 col-md-4'>
                                                <b>Status:</b> <span class='status-aberto'>Aberto</span>
                                            </div>
                                            <div class='col-xs-12 col-md-2'>
                                                <b>Palestras:</b> ".$qtdPalestra."<br><br>
                                            </div>
                                            <div class='col-xs-12 col-md-2'>
                                                <b>Pessoas:</b> ".$qtdPessoas."<br><br>
                                            </div>
                                            <div class='col-xs-12 col-md-2'>
                                                <b>Participantes:</b> ".$qtdParticipante."<br><br>
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class='col-xs-12 col-md-12'>
                                                <div class='pull-right'>
                                                    <button class='btn btn-default' onclick=\"location.href='".$_SESSION["palestra"]["view"]["listar"]."?id=".$res['id']."'\">Ver Evento</button>
                                                    <button class='btn btn-default' onclick=\"location.href='".$_SESSION["cracha"]["view"]["listar"]."?id=".$res['id']."'\">Gerar Crachás</button>
                                                </div>
                                            </div>
                                        </div>";
                }else{
                    $lista .=   "<div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div class='row'>
                                        <div class='col-xs-8'>
                                            <span class='status-aguardando'><i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;&nbsp;</span><a data-toggle='collapse' data-parent='#accordion' href='#collapse".$res["id"]."
                                                '>".$res["nome"]."</a>
                                        </div>
                                        <div class='col-xs-4 icones'>
                                            <div class='row'>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'></div>
                                                <div class='col-xs-2'>
                                                    <a href='#'>
                                                        <i class='fa fa-2x fa-gear' data-toggle='tooltip' data-placement='top' title='Editar evento'></i>
                                                    </a>
                                                </div>
                                                <div class='col-xs-2'>
                                                    <a href='#' onclick='excluir({$res['id']},\"" .$res['nome']."\");'>
                                                        <i class='fa fa-2x fa-close' data-toggle='tooltip' data-placement='top' title='Excluir evento'></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id='collapse".$res["id"]."' class='panel-collapse collapse'>
                                    <div class='panel-body'>
                                        <div class='row'>
                                            <div class='col-xs-4 col-md-2'>
                                                <div class='thumbnail'>
                                                    <img src='".(empty($res["caminhoLogo"]) ? "../../assets/img/exemplo_logo.jpg" : $res["caminhoLogo"])."'>
                                                </div>
                                            </div>
                                            <div class='col-xs-8 col-md-4'>
                                                <b>Status:</b> <span class='status-aguardando'>Aguardando aprovação</span>
                                            </div>
                                        </div>";
                                        if($_SESSION["grupo"]=="admin"){
                                            $lista .= "<div class='row'>
                                            <div class='col-xs-12'>
                                                <button type='button' class='btn btn-default pull-right' onclick='aprovarEvento({$res['id']});'>Aprovar</button>
                                            </div>
                                        </div>";
                                    }
                }
                $lista .= "</div>
                                </div>
                            </div>";

            }
            return $lista;
        }

        function listarEventoParticipante($id){
            $nomeCliente = $this->modelEvento->getEvento("*", "cliente", "WHERE id = '$id'");
            $nomeCliente = mysql_fetch_array($nomeCliente);
            $row = $this->modelEvento->getEvento("*", "usuario", "WHERE cliente_id = '$id'");
            $res = mysql_fetch_array($row);
            $row = $this->modelEvento->getEvento("*", "evento", "WHERE usuario_id = {$res['id']} ORDER BY data_criado");
            $lista = "<div class='row'>
                        <div class='col-xs-12'>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    Eventos - {$nomeCliente['nome']}
                                </div>
                            </div>
                        </div>"
                        .$this->alert.
                    "</div>
                    <div id='accordion' class='panel-group'>";
            while($res = mysql_fetch_array($row)){
                $row2 = $this->modelEvento->getEvento("*", "palestra", "WHERE evento_id = '{$res["id"]}'");
                $qtdPalestra = mysql_num_rows($row2);
                $row2 = $this->modelEvento->getEvento("*", "participante", "WHERE evento_id = '{$res["id"]}'");
                $qtdParticipante = mysql_num_rows($row2);
                $row2 = $this->modelEvento->getEvento("*", "pessoas", "WHERE evento_id = '{$res["id"]}'");
                $qtdPessoas = mysql_num_rows($row2);
                if($this->verificaEventoFinalizado($res["id"])){
                    $status = "finalizado";
                    $textoStatus = "Finalizado";
                }else if ($res['status'] == 1){
                    $status = "aberto";
                    $textoStatus = "Aberto";
                }else{
                    $status = "aguardando";
                    $textoStatus = "Aguardando aprovação";
                }
                $lista .=   "<div class='panel panel-default'>
                            <div class='panel-body'>
                                <div class='row'>
                                    <div class='col-xs-8'>
                                        <span class='status-$status'><i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;&nbsp;</span><a data-toggle='collapse' data-parent='#accordion' href='#collapse".$res["id"]."
                                        '>".$res["nome"]."</a>
                                    </div>
                                    ".(($res['status']==0 && !$this->verificaEventoFinalizado($res['id']))?"
                                    <div class='col-xs-4'>
                                        <div class='pull-right'>
                                            <button type='button' class='btn btn-default pull-right' onclick='aprovarEvento({$res['id']},{$_GET['id']});'>Aprovar</button>
                                        </div>
                                    </div>":"")
                                    ."
                                </div>
                            </div>
                            <div id='collapse".$res["id"]."' class='panel-collapse collapse'>
                                <div class='panel-body'>
                                    <div class='row'>
                                        <div class='col-xs-4 col-md-2'>
                                            <div class='thumbnail'>
                                                <img src='".(empty($res["caminhoLogo"]) ? "../../assets/img/exemplo_logo.jpg" : $res["caminhoLogo"])."'>
                                            </div>
                                        </div>
                                        <div class='col-xs-8 col-md-4'>
                                            <b>Status:</b> <span class='status-$status'>$textoStatus</span>
                                        </div>
                                        <div class='col-xs-12 col-md-2'>
                                            <b>Palestras:</b> ".$qtdPalestra."<br><br>
                                        </div>
                                        <div class='col-xs-12 col-md-2'>
                                            <b>Pessoas:</b> ".$qtdPessoas."<br><br>
                                        </div>
                                        <div class='col-xs-12 col-md-2'>
                                            <b>Participantes:</b> ".$qtdParticipante."<br><br>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>";

            }
            $lista .= "</div>";
            return $lista;
        }

        function aprovarEvento($id){
            $tipo = "danger";
            $texto = "Não foi possível aprovar o evento";
            if($_SESSION["grupo"]=="admin"){
                if($this->modelEvento->aprovarEvento($id)){
                    $tipo = "success";
                    $texto = "Evento aprovado com sucesso!";
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
    }
?>
