<?
    require_once '../../config/config.php';
    $config = new Config();
    $config->verificarLogin();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?=$config->getAssets("favicon","favicon.png");?>
    <title>Robótica Jr.</title>
    <?=$config->getAssets("css","bootstrap.min.css");?>
    <?=$config->getAssets("css","font-awesome.min.css");?>
    <?=$config->getAssets("css","menu.css");?>
</head>
<body>
    <section class="menu">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../dashboard/dashboard.php">
                        <?=$config->getAssets("img","brand.png");?>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="<?=$_SESSION["pathDashboard"];?>">Home</a></li>
                        <li><a href="<?=$_SESSION["cliente"]["view"]["listar"];?>">Clientes</a></li>
                        <li><a href="#">Presença</a></li>
                        <li><a href="#">Relatórios</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$_SESSION["nomeCliente"];?> <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Configurações da conta</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/sisqrcode/view/login.php?sair=1">Sair</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </section>

