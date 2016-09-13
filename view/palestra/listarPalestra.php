<?
    session_start();
    require_once($_SESSION["palestra"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $controllerPalestra = new PalestraController();
?>
<section class="conteudo palestra cadastro">
    <?=$controllerPalestra->listarPalestras();?>
</section>
<?
    require_once($config->getMenuRodape());
?>
<?=$config->getAssets("js","palestra/palestra.js");?>
