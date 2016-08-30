<?
    session_start();
    require_once($_SESSION["usuario"]["controller"]);
    require_once($_SESSION["cliente"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $controllerUsuario = new UsuarioController();
    $controllerCliente = new ClienteController();
?>
<section class="conteudo cliente cadastro">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cadastro de Usuário
                </div>
            </div>
        </div>
        <?=$controllerUsuario->getAlert();?>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="usuarioCliente.php" method="POST">
                        <div class="row">
                            <input type="hidden" value="<?=$controllerCliente->getId();?>" name="clienteId">
                            <div class="col-xs-8">
                                <div class="form-group">
                                    <label for="nomeCliente">Cliente</label><br/>
                                    <span id="nomeCliente"><?=$controllerCliente->getNome();?></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <label for="grupoUsuario">Grupo</label><br/>
                                    <select name="grupoUsuario" id="grupoUsuario">
                                        <option value="user">user</option>
                                        <option value="admin">admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8">
                                <div class="form-group">
                                    <label for="loginUsuario">Login</label>
                                    <input type="text" required class="form-control" name="loginUsuario" id="loginUsuario" placeholder="Usuário" value="<?=$controllerUsuario->getLogin();?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <label for="senhaUsuario">Senha</label>
                                    <input type="password" required class="form-control" name="senhaUsuario" id="senhaUsuario" placeholder="Senha" value="<?=$controllerUsuario->getSenha();?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-default"><?=$controllerUsuario->getButton();?></button>
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
