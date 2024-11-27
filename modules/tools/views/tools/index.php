<?php

use yii\helpers\Url;

$this->title = 'Home';

?>

<!-- Cabeçalho -->
<div class="container mt-4">
    <div class="text-center mb-5">
        <h1 class="display-4 text-primary">Bem-vindo ao módulo <span class="font-weight-bold">ToolsCrom</span></h1>
        <p class="lead text-muted">Esta é a página inicial do módulo. Escolha uma das opções abaixo para começar.</p>
    </div>

    <!-- Cards com opções -->
    <div class="row mt-5 justify-content-center gap-4">
        <!-- Análise de SEO -->
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Análise de SEO</h5>
                    <p class="card-text">Melhore o SEO do seu site com ferramentas especializadas.</p>
                    <a href="<?= Url::to(['tools/analyse-seo']) ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-bar-chart-line"></i> Acessar
                    </a>
                </div>
            </div>
        </div>

        <!-- Pesquisa de Sites -->
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Pesquisa de Sites</h5>
                    <p class="card-text">Pesquise e analise sites com base em dados relevantes.</p>
                    <a href="<?= Url::to(['tools/search-list-sites']) ?>" class="btn btn-success btn-lg">
                        <i class="bi bi-search"></i> Acessar
                    </a>
                </div>
            </div>
        </div>

        <!-- RepoHub -->
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">RepoHub</h5>
                    <p class="card-text">Conecte-se a repositórios e controle seu código de forma eficiente.</p>
                    <a href="<?= Url::to(['tools/repo-hub']) ?>" class="btn btn-info btn-lg">
                        <i class="bi bi-code-slash"></i> Acessar
                    </a>
                </div>
            </div>
        </div>

        <!-- MindMap -->
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">MindMap</h5>
                    <p class="card-text">Crie e gerencie mapas mentais para organizar suas ideias.</p>
                    <a href="<?= Url::to(['tools/mind-map']) ?>" class="btn btn-warning btn-lg">
                        <i class="bi bi-bricks"></i> Acessar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclua os ícones do Bootstrap (se necessário) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">