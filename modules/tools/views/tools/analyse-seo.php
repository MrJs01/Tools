<?php

use yii\helpers\Html;

$this->title = 'SEO Analyzer';

?>

<div class="container mt-5">
    <h1 class="text-center">Análise de SEO</h1>
    <p class="text-center">Digite uma URL abaixo para obter uma análise SEO.</p>

    <!-- Formulário -->
    <?= Html::beginForm(['tools/analyse-seo'], 'post', ['class' => 'mb-5']) ?>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="input-group">
                    <?= Html::input('url', 'url', $url, [
                        'class' => 'form-control',
                        'placeholder' => 'https://exemplo.com',
                        'required' => true,
                    ]) ?>
                    <?= Html::submitButton('Analisar', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    <?= Html::endForm() ?>

    <!-- Resultados da Análise -->
    <?php if ($seoData): ?>
        <div id="results">
            <h2>Resultados da Análise:</h2>
            <ul class="list-group">
                <li class="list-group-item"><strong>Título da Página:</strong> <?= Html::encode($seoData['title']) ?></li>
                <li class="list-group-item"><strong>Meta Descrição:</strong> <?= Html::encode($seoData['description']) ?></li>
                <li class="list-group-item"><strong>Quantidade de Links:</strong> <?= Html::encode($seoData['linkCount']) ?></li>
                <li class="list-group-item"><strong>Tamanho do Conteúdo:</strong> <?= Html::encode($seoData['contentLength']) ?> caracteres</li>
            </ul>
        </div>
    <?php elseif (\Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Html::encode(\Yii::$app->session->getFlash('error')) ?>
        </div>
    <?php endif; ?>
</div>
