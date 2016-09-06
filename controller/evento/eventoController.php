<?
    session_start();
    require_once($_SESSION["evento"]["model"]);

    Class EventoController{

        private $bean, $modelEvento, $alert, $caminho, $caminhoCracha, $caminhoLogo, $imagem1, $imagem2, $tipo1, $size1,
        $tipo2, $size2, $option, $logo, $timeInsert;

        function EventoController(){
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
                    $texto = "A imagem deve ter o formato JPEG ou PNG!";
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
                        if($razaoCabecalho >= 2.7){
                            $status1 = true;
                            $this->imagem1 = $_FILES['cabecalhoCrachaEvento']['tmp_name'];
                            $this->tipo1 = $_FILES['cabecalhoCrachaEvento']['type'];
                            $this->size1 = $_FILES['cabecalhoCrachaEvento']['size'];
                        }else{
                            $tipo = "danger";
                            $texto = "A razão da largura pela altura da imagem (razao = largura/altura) deve ser maior ou igual a 2.7!";
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
                        if($razaoRodape >= 2.3){
                            $status2 = true;
                            $this->imagem2 = $_FILES['rodapeCrachaEvento']['tmp_name'];
                            $this->tipo2 = $_FILES['rodapeCrachaEvento']['type'];
                            $this->size2 = $_FILES['rodapeCrachaEvento']['size'];
                        }else{
                            $tipo = "danger";
                            $texto .= "A razão da largura pela altura da imagem (razao = largura/altura) deve ser maior ou igual a 2.3!";
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

        function getIdEvento(){
            $row = $this->modelEvento->getUltimoEvento($_SESSION["idCliente"]);
            $row = mysql_fetch_array($row);
            $this->bean->setId($row[0]);
        }

        function listarEvento(){
            $model = new EventoModel();
            $row = $model->getEvento("*", "evento", "WHERE usuario_id = '{$_SESSION["idCliente"]}'");
            while($res = mysql_fetch_array($row)){
                $lista .=   "<div class='panel panel-default'>
                                <div class='panel-body'>
                                <span class='status-".(($res['status'] == 0) ? "aguardando'>" : "aberto'>")."<i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;&nbsp;</span><a data-toggle='collapse' data-parent='#accordion' href='#collapse".$res["id"]."
                                    '>".$res["nome"]."</a>
                                    <div class='pull-right'>
                                        <a href='".$_SESSION["palestra"]["view"]["cadastro"]."?id=".$res["id"]."'>
                                            <i class='fa fa-2x fa-plus-square' data-toggle='tooltip' data-placement='top' title='Adicionar palestras'></i>
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href='".$_SESSION["pessoas"]["view"]["cadastro"]."?id=".$res["id"]."'>
                                            <i class='fa fa-2x fa-group' data-toggle='tooltip' data-placement='top' title='Adicionar pessoas'></i>
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href='".$_SESSION["participante"]["view"]["cadastro"]."?id=".$res["id"]."'>
                                            <i class='fa fa-2x fa-user' data-toggle='tooltip' data-placement='top' title='Adicionar participantes'></i>
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
                                            <div class='col-xs-4 col-md-2'>
                                                <div class='thumbnail'>
                                                    <img src='".(isset($res["caminhoLogo"]) ? "../../assets/img/exemplo_logo.jpg" : $res["caminhoLogo"])."'>
                                                </div>
                                            </div>
                                            <div class='col-xs-8 col-md-4'>
                                                <b>Status:</b> <span class='status-".(($res['status'] == 0) ? "aguardando'>Aguardando aprovação" : "aberto'>Aberto")."</span>
                                            </div>
                                            <div class='col-xs-12 col-md-2'>
                                                <b>Palestras:</b> ".$res['qtdPalestra']."
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class='col-xs-12 col-md-12'>
                                                <button class='btn btn-default pull-right' onclick='#'>Ver Palestras</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";

            }
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
    }
?>
