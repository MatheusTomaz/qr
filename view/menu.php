<?
    require_once '../config/config.php';
    $config = new Config();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Robótica Jr.</title>
    <?=$config->getCss("bootstrap.min.css");?>
    <?=$config->getCss("font-awesome.min.css");?>
    <?=$config->getCss("menu.css");?>
</head>
<body>
    <section class="menu">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        <img alt="Brand" src="<?=$config->getLogoRobotica();?>">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Eventos <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Cadastrar Evento</a></li>
                                <li><a href="#">Listar Eventos</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Palestras<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Cadastrar Palestra</a></li>
                                <li><a href="#">Listar Palestras</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Pessoas <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Cadastrar Pessoa</a></li>
                                <li><a href="#">Listar Pessoas</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Participante <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Cadastrar Participantes</a></li>
                                <li><a href="#">Listar Participante</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Relatórios</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Usuario <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Configurações da conta</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Sair</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </section>

