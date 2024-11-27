<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $exception Exception */

$this->title = 'Erro';

?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Ocorreu um erro ao processar sua solicitação.
    </p>

    <?php if (isset($exception)): ?>
        <div>
            <strong>Erro:</strong> <?= Html::encode($exception->getMessage()) ?>
        </div>
    <?php endif; ?>
</div>
