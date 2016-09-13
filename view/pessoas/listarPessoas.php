<?
    session_start();
    require_once($_SESSION["pessoas"]["controller"]);
    $config = new Config();
    require_once($config->getMenu());
    $controller = new PessoasController();
?>
<section class="conteudo pessoas cadastro">
    <div class="row">
        <?=$controller->listarPessoas();?>
</section>
<?=$config->getAssets("js","pessoas/pessoas.js");?>
<?
    require_once($config->getMenuRodape());
?>
