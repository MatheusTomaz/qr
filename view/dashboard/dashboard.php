<?
    session_start();
    require_once($_SESSION["dashboard"]["controller"]);
    require_once($_SESSION["evento"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $eventoController = new EventoController();
?>
<!-- <?=$config->verificaPath()?>
<noscript>
    <?=$config->alertNoJS();?>
</noscript> -->
<section class="conteudo dashboard cadastro">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Eventos
                    <div class="pull-right">
                        <a href="<?=$_SESSION["evento"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-plus" data-toggle="tooltip" data-placement="top" title="Adicionar evento"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?=$eventoController->getAlert();?>
    </div>

    <div id="accordion" class="panel-group">
        <?=$eventoController->listarEvento();?>
    </div>
<?=$config->getAssets("js","evento/evento.js");?>
</section>
<?
    require_once($config->getMenuRodape());
?>
