<?
    session_start();
    require_once($_SESSION["evento"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $controllerEvento = new EventoController();
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
        <?=$controllerEvento->getAlert();?>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="POST" action="cadastroEvento.php" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel-title">
                                    Informações Gerais
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8">
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
                            <div class="col-xs-12">
                                <div class="panel-title">
                                    Crachá
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-xs-12'>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel-title">
                                                    <input type='radio' onclick="selecionaModelo()" class="modeloCracha" required name='modeloCracha' id='modeloCracha' value='completo'/> Completo
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-md-4 text-center">
                                                <a href="#" class="thumbnail">
                                                    <?=$config->getAssets("img","modeloCompletoCracha.png");?>
                                                </a>
                                            </div>
                                            <div class="col-xs-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="crachaEvento">Crachá</label>
                                                    <input type="file" required name="crachaEvento" id="crachaEvento">
                                                    <p class="help-block">(.jpg, .png)(100x100)px</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-xs-12'>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel-title">
                                                    <input type='radio' onclick="selecionaModelo()" class="modeloCracha" required name='modeloCracha' id='modeloCracha' value='dividido'/> Dividido (Cabeçalho e Rodapé)
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-md-4 text-center">
                                                <a href="#" class="thumbnail">
                                                    <?=$config->getAssets("img","modeloDivididoCracha.png");?>
                                                </a>
                                            </div>
                                            <div class="col-xs-12 col-md-8">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="form-group">
                                                        <label for="cabecalhoCrachaEvento">Cabeçalho</label>
                                                        <input type="file" required name="cabecalhoCrachaEvento" id="cabecalhoCrachaEvento">
                                                        <p class="help-block">(.jpg, .png)(100x100)px</p>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="form-group">
                                                        <label for="rodapeCrachaEvento">Rodapé</label>
                                                        <input type="file" required name="rodapeCrachaEvento" id="rodapeCrachaEvento">
                                                        <p class="help-block">(.jpg, .png)(100x100)px</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-xs-12'>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel-title">
                                                    <input type='radio' onclick="selecionaModelo()" class="modeloCracha" required name='modeloCracha' id='modeloCracha' value='etiqueta'/> Etiqueta (Apenas QR Code)
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 text-center">
                                                (Alguma informação ou nenhuma)
                                            </div>
                                        </div>
                                    </div>
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
<?=$config->getAssets("js","evento/cadastroEvento.js");?>
<?
    require_once($config->getMenuRodape());
?>
