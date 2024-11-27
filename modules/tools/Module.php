<?php

namespace app\modules\tools;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\tools\controllers';

    public function init()
    {
        parent::init();
        // Adicione configurações adicionais do módulo aqui, se necessário.
    }
}
