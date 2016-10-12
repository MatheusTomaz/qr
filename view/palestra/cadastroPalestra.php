<?
    session_start();
    require_once($_SESSION["palestra"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $controllerPalestra = new PalestraController();
    $config->verificarLogin("admin","user");
?>
<section class="conteudo palestra cadastro">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cadastro de Palestra
                </div>
            </div>
        </div>
        <?=$controllerPalestra->getAlert();?>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="POST" action="cadastroPalestra.php">
                        <input type="hidden" value="<?=$controllerPalestra->getId();?>" name="eventoId">
                        <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <div class="form-group">
                                    <label for="tipoPalestra">Tipo</label><br/>
                                    <select name="tipoPalestra" required id="tipoPalestra">
                                        <option value="Palestra">Palestra</option>
                                        <option value="Minicurso">Minicurso</option>
                                        <option value="Mesa Redonda">Mesa Redonda</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label for="nomePalestra">Nome da Palestra</label>
                                    <input type="text" required class="form-control" name="nomePalestra" id="nomePalestra" placeholder="Nome da Palestra">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <div class="form-group">
                                    <label for="qtdParticipante">Nº de Participantes</label>
                                    <input type="number" min="1" required class="form-control" onkeypress="return onlyNumber(event)" name="qtdParticipante" id="qtdParticipante" placeholder="Nº de Participantes">
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
    </div>

        <?=$controllerPalestra->listarPalestras();?>
</section>
<?=$config->getAssets("js","config.js");?>
<?=$config->getAssets("js","palestra/palestra.js");?>
<?
    require_once($config->getMenuRodape());
?>
