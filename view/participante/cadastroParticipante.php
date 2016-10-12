<?
    session_start();
    require_once("../../controller/participante/participanteController.php");
    $config = new Config();
    require_once($config->getMenu());
    $controller = new ParticipanteController();
    $config->verificarLogin("admin","user");
?>
<section class="conteudo participante cadastro">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cadastro de Participante
                </div>
            </div>
        </div>
        <?=$controller->getAlert();?>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="POST" action="cadastroParticipante.php" name='cadastro'>
                        <input type="hidden" value="<?=$controller->getId();?>" name="eventoId">
                        <?=$controller->listarPalestras();?>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel-title">
                                    Informações do participante
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8">
                                <div class="form-group">
                                    <label for="nomeParticipante">Nome</label>
                                    <input type="text" required="" class="form-control"  name="nomeParticipante" id="nomeParticipante" placeholder="Nome">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <label for="cpf">CPF</label>
                                    <input type="text" required class="form-control" onkeypress="return onlyNumber(event)" maxlength="14" name="cpf" id="cpf" placeholder="CPF">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-default">Cadastrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?=$controller->listarParticipantes();?>
</section>
<?=$config->getAssets("js","participante/participante.js");?>
<?=$config->getAssets("js","config.js");?>
<?
    require_once($config->getMenuRodape());
?>
<?=$controller->gerarScript();?>
