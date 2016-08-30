<?
    session_start();
    require_once($_SESSION["evento"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
?>
<section class="conteudo evento cadastro">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cadastro de Evento
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
                                    <label for="nomeCliente">Nome do Evento</label>
                                    <input type="text" required="" class="form-control" name="nomeEvento" id="nomeEvento" placeholder="Nome do Evento">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <label for="logoEvento">Logo</label>
                                    <input type="file" name="logoEvento" id="logoEvento">
                                    <p class="help-block">(.jpg, .png)(100x100)px</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8">
                                <div class="form-group">
                                    <label for="qtdPalestra">Quantidade de Palestras</label>
                                    <input type="number" min="1" pattern="^[0-9]" class="form-control" name="qtdParticipante" id="qtdPalestra" placeholder="Quantidade de Palestras" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <label for="crachaEvento">Crachá</label>
                                    <input type="file" required name="crachaEvento" id="crachaEvento">
                                    <p class="help-block">(.jpg, .png)(100x100)px</p>
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
