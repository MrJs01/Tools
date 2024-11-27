<?php

namespace app\modules\tools\controllers;

use yii\web\Controller;

class ToolsController extends Controller
{
    private $layout_default = '@app/modules/tools/views/layout/main-tools';
    public $enableCsrfValidation = false;

    // setar layout em todos os actions
    public function init()
    {

        parent::init();

        // Define o layout padrão para o controlador
        $this->layout = $this->layout_default;

        // Redireciona erros para a ação personalizada
        \Yii::$app->errorHandler->errorAction = 'tools/tools/error';
    }
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Certifique-se de que o CSRF está validando adequadamente
        if ($this->enableCsrfValidation && !\Yii::$app->request->validateCsrfToken()) {
            throw new \yii\web\BadRequestHttpException('CSRF token validation failed.');
        }

        return true;
    }

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            return $this->render('error', [
                'exception' => $exception,
            ]);
        }

        return $this->render('error', [
            'message' => 'Ocorreu um erro desconhecido.',
        ]);
    }



    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest()
    {
        return "ok";
    }



    public function actionAnalyseSeo()
    {
        $seoData = null;
        $url = \Yii::$app->request->post('url', null);

        if ($url) {
            try {
                // Obter o conteúdo da URL
                $html = @file_get_contents($url);

                if (!$html) {
                    \Yii::$app->session->setFlash('error', 'Não foi possível acessar a URL. Verifique se ela está correta.');
                    return $this->render('analyse-seo', [
                        'seoData' => null,
                        'url' => $url,
                    ]);
                }

                // Carregar o HTML no DOMDocument
                $doc = new \DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML($html);
                libxml_clear_errors();

                // Extrair título
                $title = $doc->getElementsByTagName('title')->item(0)->textContent ?? 'Título não encontrado';

                // Extrair meta description
                $metaDescription = '';
                foreach ($doc->getElementsByTagName('meta') as $meta) {
                    // Verifica se o meta é um DOMElement antes de chamar o getAttribute
                    if ($meta instanceof \DOMElement && strtolower($meta->getAttribute('name')) === 'description') {
                        $metaDescription = $meta->getAttribute('content');
                        break;
                    }
                }

                // Contar links
                $linkCount = $doc->getElementsByTagName('a')->length;

                // Tamanho do conteúdo
                $contentLength = strlen(strip_tags($html));

                // Preparar os dados de SEO
                $seoData = [
                    'title' => $title,
                    'description' => $metaDescription ?: 'Descrição não encontrada',
                    'linkCount' => $linkCount,
                    'contentLength' => $contentLength,
                ];
            } catch (\Exception $e) {
                \Yii::$app->session->setFlash('error', 'Erro ao processar a URL: ' . $e->getMessage());
            }
        }

        return $this->render('analyse-seo', [
            'seoData' => $seoData,
            'url' => $url,
        ]);
    }


    // search-list-sites

    public function actionSearchListSites()
    {
        return $this->render('search-list-sites');
    }

    // repo-hub

    public function actionRepoHub()
    {
        return $this->render('repo-hub');
    }

    public function actionMindMap()
    {
        return $this->render('mindmap');
    }
}
