<?
    session_start();
    require_once($_SESSION["palestra"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
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
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form>
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="form-group">
                                    <label for="nomePalestra">Nome da Palestra</label>
                                    <input type="text" class="form-control" name="nomePalestra" id="nomePalestra" placeholder="Nome da Palestra">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <label for="qtdParticipante">Quantidade de Participantes</label>
                                    <input type="number" class="form-control" name="qtdParticipante" id="qtdParticipante" placeholder="Quantidade de Palestras">
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
</section>
<?
    require_once($config->getMenuRodape());
?>
