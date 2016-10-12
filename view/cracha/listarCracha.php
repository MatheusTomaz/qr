<?
    session_start();
    require_once($_SESSION["cracha"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $crachaController = new CrachaController();
    $config->verificarLogin("admin","user");
?>
<section class="conteudo dashboard cadastro">
    <?=$crachaController->getLista();?>
</section>
<?
    require_once($config->getMenuRodape());

?>
<?=$config->getAssets("js","cracha/cracha.js");?>
