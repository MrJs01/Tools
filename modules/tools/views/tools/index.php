<?php

use yii\helpers\Url;

$this->title = 'Home';

?>

<h1>Bem-vindo ao módulo ToolsCrom</h1>
<p>Esta é a página inicial do módulo.</p>

<!-- list de paginas -->

<div class="row mt-5 gap-3">

    <div class="col-md-4">
        <a href="<?= Url::to(['tools/analyse-seo']) ?>" class="btn btn-primary btn-block">Análise de SEO</a>
    </div>
    <div class="col-md-4">
        <a href="<?= Url::to(['tools/search-list-sites']) ?>" class="btn btn-primary btn-block">Pesquisa de Sites</a>
    </div>
    <!-- repo-hub -->
    <div class="col-md-4">
        <a href="<?= Url::to(['tools/repo-hub']) ?>" class="btn btn-primary btn-block">RepoHub</a>
    </div>


</div>