<?
    session_start();
    require_once("../../controller/evento/eventoController.php");
    $config = new Config();
    require_once($config->getMenu());
    $eventoController = new EventoController();
    $config->verificarLogin("admin","user");
?>
<!-- <?=$config->verificaPath()?>
<noscript>
    <?=$config->alertNoJS();?>
</noscript> -->
<section class="conteudo dashboard primeira-pagina cadastro">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Eventos
                    <div class="pull-right">
                        <a href="<?=$_SESSION["evento"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-plus" data-toggle="tooltip" data-placement="bottom" title="Adicionar evento"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?=$eventoController->getAlert();?>
    </div>
    <div class="row">
        <ul  class="nav nav-pills nav-justified">
            <li class="col-xs-4 active">
                <a  href="#aberto" data-toggle="tab">
                    <span class="status-aberto"><i class="fa fa-circle"></i></span> Abertos
                </a>
            </li>
            <li class="col-xs-4">
                <a href="#aguardando" data-toggle="tab">
                    <span class="status-aguardando"><i class="fa fa-circle"></i></span> Aguardando aprovação
                </a>
            </li>
            <li class="col-xs-4">
                <a href="#finalizado" data-toggle="tab">
                    <span class="status-finalizado"><i class="fa fa-circle"></i></span> Finalizados
                </a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="tab-content">
                <div class="tab-pane active" id="aberto">
                    <div id="accordion" class="panel-group">
                        <?=$eventoController->listarEventoPorStatus("aberto");?>
                    </div>
                </div>
                <div class="tab-pane" id="aguardando">
                    <div id="accordion" class="panel-group">
                        <?=$eventoController->listarEventoPorStatus("aguardando");?>
                    </div>
                </div>
                <div class="tab-pane" id="finalizado">
                    <div id="accordion" class="panel-group">
                        <?=$eventoController->listarEventoPorStatus("finalizado");?>
                    </div>
                </div>
            </div>
        </div>
    </div>



</section>
<?
    require_once($config->getMenuRodape());
?>
<?=$config->getAssets("js","evento/evento.js");?>
