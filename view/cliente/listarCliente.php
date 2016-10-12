<?
    session_start();
    require_once($_SESSION["cliente"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $controller = new ClienteController();
    $config->verificarLogin("admin");
?>
<section class="conteudo primeira-pagina cliente cadastro">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Clientes
                    <div class="pull-right">
                        <a href="<?=$_SESSION["cliente"]["view"]["cadastro"];?>">
                            <i class="fa fa-2x fa-plus" data-toggle="tooltip" data-placement="bottom" title="Cadastrar cliente"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?=$controller->getAlert();?>
    </div>
    <div id="accordion" class="panel-group">
        <?=$controller->listarCliente();?>
    </div>
</section>
<?=$config->getAssets("js","cliente/cliente.js");?>
<?
    require_once($config->getMenuRodape());
?>
