<?
    session_start();
    require_once("../../controller/participante/participanteController.php");
    $controllerParticipante = new ParticipanteController();
?>
<?=$controllerParticipante->listarParticipantesCliente($_GET['id']);?>
