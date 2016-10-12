<?
    session_start();
    require_once("../../model/evento/eventoModel.php");

    Class EventoController{

        private $bean, $modelEvento, $alert, $caminho, $caminhoCracha, $caminhoLogo, $imagem1, $imagem2, $tipo1, $size1,
        $tipo2, $size2, $option, $logo, $timeInsert;

        function EventoController(){
            // print_r($_SESSION);
            $this->timeInsert = round(microtime(true) * 1000);
            $this->caminho = "../../assets/img/";
            $this->bean = new EventoBean();
            $this->modelEvento = new EventoModel();
            if(isset($_POST["nomeEvento"])){
                $this->imagem1 = " ";
                $this->imagem2 = " ";
                if($this->verificarImagens()){
                    $this->bean->setUsuarioId($_SESSION["idCliente"]);
                    $this->bean->setNome($_POST["nomeEvento"]);
                    $this->bean->setQtdPalestra($_POST["qtdPalestra"]);
                    $this->bean->setTipoCracha($this->option);
                    $this->getIdEvento();
                    $this->cadastrarEvento();
                }
            }
            if(isset($_GET["excluir"])){
                $this->excluirEvento($_GET["excluir"]);
            }
            if(isset($_GET["aprovar"])){
                $this->aprovarEvento($_GET["aprovar"]);
            }
        }

        function cadastrarEvento(){
            $retorno = 6;
            $caminho = $this->caminho."crachas/cracha".$_SESSION["idCliente"].$this->timeInsert;
            $caminhoLogo = $this->caminho."logos/logo".$_SESSION["idCliente"].$this->timeInsert;
            if($_FILES["logoEvento"]["error"] != 4){
                $this->bean->setCaminhoLogo($caminhoLogo.".jpg");
                if(move_uploaded_file($this->logo, $this->bean->getCaminhoLogo())){
                    $retorno = 1;
                }else{
                    $retorno = 2;
                }
            }else{
                $retorno = 1;
            }

            if($retorno == 1){
                if($this->option == 1){
                    $this->bean->setCaminhoCracha($caminho.".jpg");
                    if(move_uploaded_file($this->imagem1, $this->bean->getCaminhoCracha1())){
                        if($this->modelEvento->setEvento($this->bean)){
                            $retorno = 1;
                        }else{
                            $retorno = 6;
                        }
                    }else{
                        $retorno = 3;
                    }
                }
                if($this->option == 2){
                    $this->bean->setCaminhoCracha($caminho."C.jpg", $caminho."R.jpg");
                    if(move_uploaded_file($this->imagem1, $this->bean->getCaminhoCracha1())){
                        if(move_uploaded_file($this->imagem2, $this->bean->getCaminhoCracha2())){
                            if($this->modelEvento->setEvento($this->bean)){
                                $retorno = 1;
                            }else{
                                $retorno = 6;
                            }
                        }else{
                            $retorno = 4;
                        }
                    }else{
                        $retorno = 5;
                    }
                }
                if($this->option == 3){
                    $this->bean->setCaminhoCracha(" ");
                    if($this->modelEvento->setEvento($this->bean)){
                        $retorno = 1;
                    }
                }
            }

            switch($retorno){
                case 1:
                    $tipo = "success";
                    $texto = "Evento cadastrado com sucesso! Aguarde a aprovação!";
                    break;
                case 2:
                    $tipo = "danger";
                    $texto = "Não foi possível fazer o Upload da imagem! (Logo)";
                    break;
                case 3:
                    $tipo = "success";
                    $texto = "Não foi possível fazer o Upload da imagem! (Crachá)";
                    break;
                case 4:
                    $tipo = "success";
                    $texto = "Não foi possível fazer o Upload da imagem! (Cabeçalho)";
                    break;
                case 5:
                    $tipo = "success";
                    $texto = "Não foi possível fazer o Upload da imagem! (Rodapé)";
                    break;
                case 6:
                    $tipo = "success";
                    $texto = "Erro ao cadastrar o evento! Verifique se os campos foram preenchidos corretamente!";
                    break;
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
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

        function verificarImagens(){
            if($_FILES["logoEvento"]["error"] != 4){
                $retorno = false;
                $campo = " (Campo Logo)";
                if($_FILES['logoEvento']['type'] == "image/jpeg"){
                    list($width, $height) = getimagesize($_FILES['logoEvento']['tmp_name']);
                    $razaoFoto = $width/$height;
                    if($_FILES['logoEvento']['size'] <= 4096000){
                        $this->logo = $_FILES['logoEvento']['tmp_name'];
                        $retorno = true;
                    }else{
                        $tipo = "danger";
                        $texto = "O tamanho da imagem é maior que 4MB!";
                    }
                }else{
                    $tipo = "danger";
                    $texto = "A imagem deve ter o formato JPEG!";
                }
                $this->alert = $this->gerarAlert($tipo,$texto.$campo);
                if(!$retorno){
                    return false;
                }
            }

            if($_POST["modeloCracha"] == "completo"){
                $this->option = 1;
                $campo = " (Campo Crachá)";
                if($_FILES['crachaEvento']['type'] == "image/jpeg"){
                    list($width, $height) = getimagesize($_FILES['crachaEvento']['tmp_name']);
                    $razaoFoto = $width/$height;
                    if($width >= 320 && $height >= 430){
                        if($razaoFoto >= 0.70 && $razaoFoto <= 0.75){
                            $this->imagem1 = $_FILES['crachaEvento']['tmp_name'];
                            $this->tipo1 = $_FILES['crachaEvento']['type'];
                            $this->size1 = $_FILES['crachaEvento']['size'];
                            return true;
                        }else{
                            $tipo = "danger";
                            $texto = "A razão da largura pela altura da imagem (razao = largura/altura) deve estar entre 0.70 e 0.75!";
                        }
                    }else{
                        $tipo = "danger";
                        $texto = "A imagem deve ter a altura maior ou igual a 430 pixels (height >= 430px) e a largura maior ou igual a 320 pixels (width >= 320px)!";
                    }
                }else{
                    $tipo = "danger";
                    $texto = "A imagem deve ter o formato JPEG ou PNG!";
                }
                $this->alert = $this->gerarAlert($tipo,$texto.$campo);
                return false;
            }

            if($_POST["modeloCracha"] == "dividido"){
                $status1 = false;
                $status2 = false;
                $this->option = 2;
                $campo = " (Campo Cabeçalho)";
                if($_FILES['cabecalhoCrachaEvento']['type'] == "image/jpeg"){
                    list($width, $height) = getimagesize($_FILES['cabecalhoCrachaEvento']['tmp_name']);
                    $razaoCabecalho = $width/$height;
                    if($width >= 320){
                        if($razaoCabecalho >= 2.59 && $razaoCabecalho <= 2.7){
                            $status1 = true;
                            $this->imagem1 = $_FILES['cabecalhoCrachaEvento']['tmp_name'];
                            $this->tipo1 = $_FILES['cabecalhoCrachaEvento']['type'];
                            $this->size1 = $_FILES['cabecalhoCrachaEvento']['size'];
                        }else{
                            $tipo = "danger";
                            $texto .= "A imagem deve ter as dimensões (120x320)px! (altura X largura)";
                        }
                    }else{
                        $tipo = "danger";
                        $texto = "A imagem deve ter a largura maior ou igual a 320 pixels (width >= 320px)!";
                    }
                }else{
                    $tipo = "danger";
                    $texto = "A imagem deve ter o formato JPEG ou PNG!";
                }
                $this->alert = $this->gerarAlert($tipo,$texto.$campo."\n");

                $campo = " (Campo Rodapé)";
                if($_FILES['rodapeCrachaEvento']['type'] == "image/jpeg"){
                    list($width, $height) = getimagesize($_FILES['rodapeCrachaEvento']['tmp_name']);
                    $razaoRodape = $width/$height;
                    if($width >= 320){
                        if($razaoRodape >= 2.25 && $razaoRodape <= 2.32){
                            $status2 = true;
                            $this->imagem2 = $_FILES['rodapeCrachaEvento']['tmp_name'];
                            $this->tipo2 = $_FILES['rodapeCrachaEvento']['type'];
                            $this->size2 = $_FILES['rodapeCrachaEvento']['size'];
                        }else{
                            $tipo = "danger";
                            $texto .= "A imagem deve ter as dimensões (140x320)px! (altura X largura)";
                        }
                    }else{
                        $tipo = "danger";
                        $texto .= "A imagem deve ter a largura maior ou igual a 320 pixels (width >= 320px)!";
                    }
                }else{
                    $tipo = "danger";
                    $texto .= "A imagem deve ter o formato JPEG ou PNG!";
                }
                $this->alert = $this->gerarAlert($tipo,$texto.$campo);
                if($status1 && $status2){
                    return true;
                }else{
                    return false;
                }
            }
            if($_POST["modeloCracha"] == "etiqueta"){
                $this->option = 3;
                return true;
            }
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
            if($status == "aguardando"){
                $row = $this->modelEvento->getEvento("*", "evento", "WHERE usuario_id = '{$_SESSION["idCliente"]}' AND status = 0 ORDER BY data_criado");
                while($res = mysql_fetch_array($row)){
                    $row2 = $this->modelEvento->getEvento("*", "palestra", "WHERE evento_id = '{$res["id"]}'");
                    $qtdPalestra = mysql_num_rows($row2);
                    $row2 = $this->modelEvento->getEvento("*", "participante", "WHERE evento_id = '{$res["id"]}'");
                    $qtdParticipante = mysql_num_rows($row2);
                    $row2 = $this->modelEvento->getEvento("*", "pessoas", "WHERE evento_id = '{$res["id"]}'");
                    $qtdPessoas = mysql_num_rows($row2);
                    $lista .= "<div class='panel panel-default'>
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
                                        </div>
                                    </div>
                                </div>
                            </div>";
                }
            }else {
                $row = $this->modelEvento->getEvento("*", "evento", "WHERE usuario_id = '{$_SESSION["idCliente"]}' AND status = 1 ORDER BY data_criado");
                while($res = mysql_fetch_array($row)){
                    $row2 = $this->modelEvento->getEvento("*", "palestra", "WHERE evento_id = '{$res["id"]}'");
                    $qtdPalestra = mysql_num_rows($row2);
                    $row2 = $this->modelEvento->getEvento("*", "participante", "WHERE evento_id = '{$res["id"]}'");
                    $qtdParticipante = mysql_num_rows($row2);
                    $row2 = $this->modelEvento->getEvento("*", "pessoas", "WHERE evento_id = '{$res["id"]}'");
                    $qtdPessoas = mysql_num_rows($row2);
                    if($status == "finalizado"){
                        if($this->verificaEventoFinalizado($res["id"])){
                            $lista .= "<div class='panel panel-default'>
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
                                        </div>
                                    </div>
                                </div>
                            </div>";
                        }
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

        function listarEventoCliente($id){
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
