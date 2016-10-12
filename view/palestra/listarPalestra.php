<?
    session_start();
    require_once("../../controller/palestra/palestraController.php");
    $config = new Config();
    require_once($config->getMenu());
    $controllerPalestra = new PalestraController();
    $config->verificarLogin("admin","user");
?>
<section class="conteudo palestra cadastro">
    <?=$controllerPalestra->listarPalestras();?>
</section>
<?
    require_once($config->getMenuRodape());
?>
<?=$config->getAssets("js","palestra/palestra.js");?>
