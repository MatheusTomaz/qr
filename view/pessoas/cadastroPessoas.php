<?
    session_start();
    require_once($_SESSION["pessoas"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
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
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form>
                        <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <div class="form-group">
                                    <label for="tipoEvento">Grupo</label><br/>
                                    <select name="tipoEvento" id="tipoEvento">
                                        <option value="#">Organizador</option>
                                        <option value="#">Palestrante</option>
                                        <option value="#">Patrocinador</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label for="nomePessoas">Nome</label>
                                    <input type="text" class="form-control" name="nomePessoas" id="nomePessoas" placeholder="Nome">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <div class="form-group">
                                    <label for="cpf">CPF</label>
                                    <input type="text" class="form-control" name="cpf" id="cpf" placeholder="CPF">
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
