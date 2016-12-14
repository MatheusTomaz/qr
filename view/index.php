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
<body class="loginParticipante">
    <div class="logo text-center">
        <?=$config->getAssets("img","brand.png");?>
    </div>
    <div class="col-md-offset-3 col-md-3 col-xs-12">
        <button class="panel panel-default" onclick="window.location.href='login.php'">Organização</button>
    </div>
    <div class="col-md-3 col-xs-12">
        <button class="panel panel-default" onclick="window.location.href='loginParticipante.php'">Participante</button>
    </div>
</body>
</html>
