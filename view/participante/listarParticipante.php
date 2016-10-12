<?
    session_start();
    require_once($_SESSION["participante"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $controller = new ParticipanteController();
    $config->verificarLogin("admin","user");
?>
<section class="conteudo participante cadastro">
    <div class="row">
        <?=$controller->listarParticipantes();?>
</section>

<?
    require_once($config->getMenuRodape());
?>
<?=$controller->gerarScript();?>
<?=$config->getAssets("js","participante/participante.js");?>
