<?
    session_start();
    require_once("../../controller/evento/eventoController.php");
    $config = new Config();
    $eventoController = new EventoController();
    // $config->verificarLogin("user");
?>
<?=$eventoController->buscarEventos($_GET["id"]);?>
