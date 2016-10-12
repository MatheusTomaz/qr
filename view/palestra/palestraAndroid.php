<?
    session_start();
    require_once("../../controller/palestra/palestraController.php");
    $controllerPalestra = new PalestraController();
?>
<?=$controllerPalestra->listarPalestrasCliente($_GET['id']);?>
