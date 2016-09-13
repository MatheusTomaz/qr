<?
    session_start();
    require_once($_SESSION["pessoas"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $controller = new PessoasController();
?>
<section class="conteudo pessoas cadastro">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cadastro de Pessoas
                </div>
            </div>
        </div>
        <?=$controller->getAlert();?>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="POST" action="cadastroPessoas.php">
                        <input type="hidden" value="<?=$controller->getId();?>" name="eventoId">
                        <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <div class="form-group">
                                    <label for="grupoPessoa">Grupo</label><br/>
                                    <select name="grupoPessoa" required id="grupoPessoa">
                                        <option value="Organizador">Organizador</option>
                                        <option value="Palestrante">Palestrante</option>
                                        <option value="Patrocinador">Patrocinador</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label for="nomePessoa">Nome</label>
                                    <input type="text" class="form-control" required name="nomePessoa" id="nomePessoa" placeholder="Nome">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <div class="form-group">
                                    <label for="cpf">CPF</label>
                                    <input type="text" class="form-control" required name="cpf" id="cpf" placeholder="CPF">
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

        <?=$controller->listarPessoas();?>
</section>
<?=$config->getAssets("js","pessoas/pessoas.js");?>
<?
    require_once($config->getMenuRodape());
?>
