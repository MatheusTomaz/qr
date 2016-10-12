<?
    require_once '../config/config.php';
    $config = new Config("login");
    require_once '../config/indexController.php';
    $login = new loginController();
?>
<?=$config->verificaPath()?>
<noscript>
    <?=$config->alertNoJS();?>
</noscript>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?=$config->getAssets("favicon","favicon.png");?>
    <title>Robótica Jr.</title>
    <?=$config->getAssets("css","bootstrap.min.css");?>
    <?=$config->getAssets("css","font-awesome.min.css");?>
    <?=$config->getAssets("css","login.css");?>
</head>
<body class="login">
    <div class="logo text-center">
        <?=$config->getAssets("img","brand.png");?>
    </div>
    <div class="col-md-offset-3 col-md-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" class="form-control" id="login" name="login" placeholder="Login">
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="senha">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
