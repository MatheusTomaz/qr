<?
    session_start();
    require_once("../../controller/palestra/palestraController.php");
    $config = new Config();
    require_once($config->getMenu());
    $controllerPalestra = new PalestraController();
    $config->verificarLogin("admin","user", "part");
?>
<section class="conteudo palestra cadastro">
    <?=$controllerPalestra->titlePalestra();?>
    <div class="row">
        <ul  class="nav nav-pills nav-justified">
            <li class="col-xs-6 active">
                <a  href="#palestra" data-toggle="tab">
                    <span class="status-aberto"><i class="fa fa-circle"></i></span> Palestras
                </a>
            </li>
            <li class="col-xs-6">
                <a href="#participante" data-toggle="tab">
                    <span class="status-aguardando"><i class="fa fa-circle"></i></span> Participantes
                </a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="tab-content">
                <div class="tab-pane active" id="palestra">
                    <div id="accordion" class="panel-group">
                        <?=$controllerPalestra->listarPalestras();?>
                    </div>
                </div>
                <div class="tab-pane" id="participante">
                    <div id="accordion" class="panel-group">
                        <?=$controllerPalestra->listarParticipantes();?>
                    </div>
                </div>
            </div>
        </div>
</section>
<?
    require_once($config->getMenuRodape());
?>
<?=$config->getAssets("js","palestra/palestra.js");?>
