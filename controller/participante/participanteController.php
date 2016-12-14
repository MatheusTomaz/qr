<?
    session_start();
    require_once("../../model/participante/participanteModel.php");

    class ParticipanteController{

        private $alert, $modelParticipante, $bean, $eventoId;

        function ParticipanteController(){
            $this->bean = new ParticipanteBean();
            $this->modelParticipante = new ParticipanteModel();
            if(isset($_GET["id"])){
                $this->eventoId = $_GET["id"];
                $this->bean->setEventoId($_GET["id"]);
            }else if($_POST["eventoId"]){
                $this->eventoId = $_POST["eventoId"];
                $this->bean->setEventoId($_POST["eventoId"]);
            }
            if(isset($_POST["nomeParticipante"]) && $this->verificarCampos()){
                $this->bean->setNome($_POST["nomeParticipante"]);
                $this->bean->setCpf($_POST["cpf"]);
                $this->cadastrarParticipante();
            }
            if(isset($_GET["excluir"])){
                $this->excluirParticipante($_GET["excluir"]);
            }
            if(isset($_GET["excluirAll"])){
                $this->excluirTodosParticipantes($_GET["excluirAll"]);
            }
            if(isset($_GET["participante"])){
                $this->presencaParticipante($_GET["participante"],$_GET["id"],$_GET["palestra"]);
            }
        }

        function cadastrarParticipante(){
            $row = $this->modelParticipante->getParticipante("*","participante","WHERE cpf = ".$this->bean->getCpf()." AND evento_id = ".$this->eventoId);
            if(mysql_num_rows($row)>0){
                $tipo = "danger";
                $texto = "Participante já cadastrado no evento! Para cadastrar em outras palestras, exclua-o e o cadastre novamente!";
            }else{
                if($this->modelParticipante->setParticipante($this->bean)){
                    foreach ($_POST["palestrasParticipante"] as $palestras) {
                        $this->bean->setPalestraId($palestras);

                        $row = $this->modelParticipante->getParticipante("*","participante","WHERE cpf = ".$this->bean->getCpf()." AND evento_id = ".$this->eventoId);
                        $row = mysql_fetch_array($row);
                        $this->bean->setId($row['id']);
                        $palestra = $this->modelParticipante->getParticipante("*","palestra","WHERE id = ".$this->bean->getPalestraId());
                        $palestra = mysql_fetch_array($palestra);
                        $nPartPal = $this->modelParticipante->getParticipante("*","participante_has_palestra","WHERE palestra_id = ".$this->bean->getPalestraId());
                        $nPartPal = mysql_num_rows($nPartPal);
                        if($nPartPal < $palestra["qtdParticipante"]){
                            if($this->modelParticipante->setPalestraParticipante($this->bean)){
                                $tipo = "success";
                                $texto = "Participante cadastrado com sucesso!";
                            }else{
                                $tipo = "danger";
                                $texto = "Não foi possível cadastrar participante na palestra {$palestra['nome']}!";
                            }
                        }else{
                            $tipo = "danger";
                            $texto .= "\nNão há mais vaga na palestra ".$palestra['nome']."!";
                        }
                    }
                }else{
                    $tipo = "danger";
                        $texto = "Erro ao cadastrar! Verifique se os campos estão preenchidos corretamente!";
                }
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
        }

        function listarParticipantesCliente($id){
            $lista = array();
            $row = $this->modelParticipante->getParticipante("participante.id, participante.nome, participante.cpf, partPal.presenca","participante_has_palestra as partPal, participante","WHERE participante_id = id AND palestra_id = ".$id." ORDER BY participante.nome");
            while($res = mysql_fetch_array($row)){
                // die(print_r($row));
                $lista[] = $res;
                    // print_r($row);
            }

            $json = json_encode( $lista );
            echo $json;
        }

        function verificarCampos(){
            if(isset($_POST["palestrasParticipante"])){
                foreach ($_POST["palestrasParticipante"] as $palestras) {
                    if(!empty($palestras)){
                        return true;
                    }else{
                        $tipo = "danger";
                        $texto = "Selecione alguma palestra!";
                    }
                }
            }else{
                $tipo = "danger";
                $texto = "Selecione alguma palestra!";
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
            return false;
        }

        function verificaPalestraAberta($id){
            $row = $this->modelParticipante->getParticipante("*","participante_has_palestra","WHERE palestra_id = ".$id." AND palestra_evento_id = ".$this->eventoId);
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

        function presencaParticipante($id,$evento,$palestra){
            $this->modelParticipante->presencaParticipante($id,$evento,$palestra);
        }

        function listarPalestras(){
            $row = $this->modelParticipante->getParticipante("*","palestra","WHERE evento_id = ".$this->eventoId);
            $row2 = $this->modelParticipante->getParticipante("nome","evento","WHERE id = ".$this->eventoId);
            $nomeEvento = mysql_fetch_array($row2);
            $lista =   "<div class='row'>
                            <div class='col-xs-12'>
                                <div class='panel-title'>
                                    Palestras ({$nomeEvento['nome']})
                                    <div class='pull-right'>
                                        <button class='btn btn-default' type='button' onclick='check()'>
                                            Selecionar todas
                                        </button>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-xs-12'>
                                <b>Selecione as palestras em que o participante será inscrito:</b><br>

                                <div class='panel-group'>";
            $flag = true;
            if(mysql_num_rows($row) > 0 ){
                while($res = mysql_fetch_array($row)){
                    $qtdPartPal = $this->modelParticipante->getParticipante("*","participante_has_palestra","WHERE palestra_id = ".$res['id']);
                    $qtdPartPal = mysql_num_rows($qtdPartPal);
                    if($res["status"]==0){
                        if($qtdPartPal<$res["qtdParticipante"]){
                            $lista .=   "<div class='panel panel-default'>
                                        <div class='panel-body'>
                                            <div class='row'>
                                                <div class='col-xs-1'>
                                                    <input type='checkbox' name='palestrasParticipante[]' id='palestrasParticipante{$res['id']}' value='{$res["id"]}'>
                                                </div>
                                                <div class='col-xs-5'>
                                                    ".$res["nome"]."
                                                </div>
                                                <div class='col-xs-2'>
                                                    ".$res["tipo"]."
                                                </div>
                                                <div class='col-xs-4 text-right'>
                                                    <b>Nº de participantes</b>: ".$qtdPartPal." / ".$res["qtdParticipante"]."
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                        }else{
                            $lista .=   "<div class='panel panel-default'>
                                        <div class='panel-body bg-danger'>
                                            <div class='row'>
                                                <div class='col-xs-1'>

                                                </div>
                                                <div class='col-xs-6'>
                                                    ".$res["nome"]."
                                                </div>
                                                <div class='col-xs-5 text-right'>
                                                    <b>Sem vagas!</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                        }
                        $flag = false;
                    }
                }
            }
            if(mysql_num_rows($row)<1 || $flag){
                $lista .=   "<div class='row'>
                                <div class='col-xs-12'>
                                    <div class='panel panel-default'>
                                        <div class='panel-body text-center'>
                                            Nenhuma palestra cadastrada
                                        </div>
                                    </div>
                                </div>
                            </div>";
            }
            $lista .= "</div>
                    </div>
                </div>";
            return $lista;
        }

        function verificaParticipantePresenca($id){
            $row = $this->modelParticipante->getParticipante("*","participante_has_palestra","WHERE participante_id = ".$id." AND palestra_evento_id = ".$this->eventoId);
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

        function listarParticipantes(){
            $row2 = $this->modelParticipante->getParticipante("*","evento","WHERE id = ".$this->eventoId);
            $nomeEvento = mysql_fetch_array($row2);
            $lista =   "<div class='col-xs-12'>
                                <div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        Participantes ({$nomeEvento['nome']})";
            if(!isset($_GET["palestra"])){
                $lista .= "<div class='pull-right'>
                    <button class='btn btn-default' style='margin-top:-8px;' type='button' onclick='checkPart()'>
                        Selecionar todos
                    </button>
                    <button class='btn btn-default' style='margin-top:-8px;' type='button' onclick='excluirAll(".$nomeEvento['id'].")' data-toggle='tooltip' data-placement='bottom' title='Excluir selecionados'>
                        <i class='fa fa-trash'></i>
                    </button>";

                if($_SERVER["SCRIPT_NAME"] == "/sisqrcode/view/participante/listarParticipante.php"){
                    $lista .= "&nbsp&nbsp&nbsp;<a href='".$_SESSION["participante"]["view"]["cadastro"]."?id={$this->eventoId}'>
                                <i class='fa fa-2x fa-plus' data-toggle='tooltip' data-placement='bottom' title='Cadastrar Participante'></i>
                            </a>";
                }
                $lista .= "</div>";
            }
            $lista .= "     </div>
                        </div>
                    </div>";
            if($_SERVER["SCRIPT_NAME"] == "/sisqrcode/view/participante/listarParticipante.php"){
                $lista .= $this->alert;
            }
            $lista .= "<div class='col-xs-12'>
                            <form name='formParticipantes'>
                                <div class='panel-group'>";
            if(isset($_GET["palestra"])){
                $row = $this->modelParticipante->getParticipante("*","participante_has_palestra","INNER JOIN participante ON participante_has_palestra.participante_id = participante.id WHERE evento_id = ".$this->eventoId." AND palestra_id = ".$_GET["palestra"]." ORDER BY presenca DESC , nome");
                if(mysql_num_rows($row) > 0){
                    $lista .= "<table class='table table-hover'>
                                <thead>
                                    <th class='text-center'>Id</th>
                                    <th>Nome</th>
                                    <th class='text-center'>CPF</th>
                                    <th class='text-center'>Status</th>
                                </thead>
                                <tbody>";
                    while($res = mysql_fetch_array($row)){
                        $lista .=   "<tr>
                                        <th class='text-center'>".$res["id"]."</th>
                                        <td>".$res["nome"]."</td>
                                        <td class='text-center'>".$res["cpf"]."</td>
                                        <td class='text-center'><span id='pt{$res['id']}' class='label label-".(($res["presenca"]==1) ? "success'>Presente" : "danger'>Ausente")."</span></td>

                                    </tr>";
                    }
                    $lista .= "</tbody>
                    </table>";
                }else{
                    $lista .=   "<div class='panel panel-default'>
                                    <div class='panel-body text-center'>
                                        Nenhum participante cadastrado
                                    </div>
                                </div>";
                }
                $lista .= "</div></form>
                        </div>";
            }else{
                $row = $this->modelParticipante->getParticipante("*","participante_has_evento","WHERE evento_id = ".$this->eventoId);
                if(mysql_num_rows($row) > 0){
                    while($resposta = mysql_fetch_array($row)){
                        $row2 = $this->modelParticipante->getParticipante("*","participante","WHERE id = ".$resposta['participante_id']);
                        $res = mysql_fetch_array($row2);
                        $status = $this->verificaParticipantePresenca($res['id']);
                        $lista .=   "<div class='panel panel-default'>
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
                            $palestraRow = $this->modelParticipante->getParticipante("*","participante_has_palestra","WHERE participante_id = ".$res['id']." AND palestra_evento_id = ".$this->eventoId);
                            while($palRow = mysql_fetch_array($palestraRow)){
                                $infoPalestraRow = $this->modelParticipante->getParticipante("*","palestra","WHERE id = ".$palRow['palestra_id']);
                                $infoPalestraRow = mysql_fetch_array($infoPalestraRow);
                                $lista .=   "<li class='list-group-item'>
                                                ".$infoPalestraRow["nome"]."
                                                <div class='pull-right'>
                                                    <span id='pt{$res['id']}' class='label label-".(($palRow["presenca"]==1) ? "success'>Presente" : "danger'>Ausente")."</span></td>
                                                </div>
                                            </li>";
                            }
                            $lista .=  "</ul>
                                    </div>
                                </div>
                            </div>";
                    }

                }else{
                    $lista .=   "<div class='panel panel-default'>
                                    <div class='panel-body text-center'>
                                        Nenhum participante cadastrado
                                    </div>
                                </div>";
                }
                $lista .= "</form></div>
                        </div>";
            }
            return $lista;
        }

        function excluirParticipante($id){
            $row = $this->modelParticipante->getParticipante("*","participante_has_palestra","WHERE participante_id = ".$id);
            $row2 = $this->modelParticipante->getParticipante("*","participante","WHERE id = ".$id." AND evento_id = ".$this->eventoId);
            if(!$this->verificaParticipantePresenca($id)){
                if(mysql_num_rows($row)>0){
                    if($this->modelParticipante->removeParticipantePalestra($id,$this->eventoId)){
                        if(mysql_num_rows($row2)>0){
                            if($this->modelParticipante->removeParticipante($id,$this->eventoId)){
                                $tipo = "success";
                                $texto = "Participante excluído com sucesso!";
                            }else{
                                $tipo = "danger";
                                $texto = "Erro ao excluir o participante!(Palestras)";
                            }
                        }else{
                            $tipo = "success";
                            $texto = "Participante excluído com sucesso!";
                        }
                    }else{
                        $tipo = "danger";
                        $texto = "Erro ao excluir o participante!";
                    }
                }else{
                    if(mysql_num_rows($row2)>0){
                        if($this->modelParticipante->removeParticipante($id,$this->eventoId)){
                            $tipo = "success";
                            $texto = "Participante excluído com sucesso!";
                        }else{
                            $tipo = "danger";
                            $texto = "Erro ao excluir o participante!(Palestras)";
                        }
                    }else{
                        $tipo = "success";
                        $texto = "Participante excluído com sucesso!";
                    }
                }
            }else{
                $tipo = "danger";
                $texto = "Não é possível excluir participante!";
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
        }

        function excluirTodosParticipantes($id){
            $ids = explode(",",$id);
            foreach($ids as $id){
                $row = $this->modelParticipante->getParticipante("*","participante_has_palestra","WHERE participante_id = ".$id);
                $row2 = $this->modelParticipante->getParticipante("*","participante","WHERE id = ".$id);
                if(!$this->verificaParticipantePresenca($id)){
                    if(mysql_num_rows($row)>0){
                        if($this->modelParticipante->removeParticipantePalestra($id,$this->eventoId)){
                            if(mysql_num_rows($row2)>0){
                                if($this->modelParticipante->removeParticipante($id,$this->eventoId)){
                                    $tipo = "success";
                                    $texto = "Participante excluído com sucesso!";
                                }else{
                                    $tipo = "danger";
                                    $texto = "Erro ao excluir o participante!(Palestras)";
                                }
                            }else{
                                $tipo = "success";
                                $texto = "Participante excluído com sucesso!";
                            }
                        }else{
                            $tipo = "danger";
                            $texto = "Erro ao excluir o participante!";
                        }
                    }else{
                        if(mysql_num_rows($row2)>0){
                            if($this->modelParticipante->removeParticipante($id,$this->eventoId)){
                                $tipo = "success";
                                $texto = "Participante excluído com sucesso!";
                            }else{
                                $tipo = "danger";
                                $texto = "Erro ao excluir o participante!(Palestras)";
                                break;
                            }
                        }else{
                            $tipo = "success";
                            $texto = "Participante excluído com sucesso!";
                            break;
                        }
                    }
                }else{
                    $tipo = "danger";
                    $texto = "Não é possível excluir participante!";
                    break;
                }
            }
            $this->alert = $this->gerarAlert($tipo,$texto);
        }

        function gerarScript(){
            if(isset($_POST["palestrasParticipante"])){
                $script =   "<script type='text/javascript'>\n";
                foreach ($_POST["palestrasParticipante"] as $nPalestra) {
                    $script .= "$('#palestrasParticipante$nPalestra')[0].setAttribute('checked','checked');\n";
                }
                $script .=  "\n</script>";
                return $script;
            }
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
