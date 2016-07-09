<?
    session_start();
    require_once($_SESSION["dashboard"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
?>
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
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    Evento 1
                    <div class="pull-right">
                        <a href="<?=$_SESSION["palestra"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-plus-square" data-toggle="tooltip" data-placement="top" title="Adicionar palestras"></i>
                        </a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?=$_SESSION["pessoas"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-group" data-toggle="tooltip" data-placement="top" title="Adicionar pessoas"></i>
                        </a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?=$_SESSION["participante"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-user" data-toggle="tooltip" data-placement="top" title="Adicionar participantes"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    Evento 2
                    <div class="pull-right">
                        <a href="<?=$_SESSION["palestra"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-plus-square" data-toggle="tooltip" data-placement="top" title="Adicionar palestras"></i>
                        </a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?=$_SESSION["pessoas"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-group" data-toggle="tooltip" data-placement="top" title="Adicionar pessoas"></i>
                        </a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?=$_SESSION["participante"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-user" data-toggle="tooltip" data-placement="top" title="Adicionar participantes"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    Evento 3
                    <div class="pull-right">
                        <a href="<?=$_SESSION["palestra"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-plus-square" data-toggle="tooltip" data-placement="top" title="Adicionar palestras"></i>
                        </a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?=$_SESSION["pessoas"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-group" data-toggle="tooltip" data-placement="top" title="Adicionar pessoas"></i>
                        </a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?=$_SESSION["participante"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-user" data-toggle="tooltip" data-placement="top" title="Adicionar participantes"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?
    require_once($config->getMenuRodape());
?>
