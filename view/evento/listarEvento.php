<?
    session_start();
    require_once($_SESSION["dashboard"]["controller"]);
    require_once($_SESSION["evento"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $eventoController = new EventoController();
    $config->verificarLogin("admin");
?>

<section class="conteudo dashboard cadastro">
<?=$eventoController->listarEventoCliente($_GET["id"]);?>
<?=$config->getAssets("js","evento/evento.js");?>
</section>
<?
    require_once($config->getMenuRodape());
?>
