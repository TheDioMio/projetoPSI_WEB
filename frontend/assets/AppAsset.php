<?php

namespace frontend\assets;

use yii\web\AssetBundle;

use Yii;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap5\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();

        // Estamos a dizer ao Yii: "confio em ti para o JS, mas o CSS é meu"
        // Isto impede o Yii de carregar o 'bootstrap.css'
        foreach ($this->depends as $i => $dependency) {
            if ($dependency === 'yii\bootstrap5\BootstrapAsset') {
                // Remove a dependência de CSS do BootstrapAsset
                // O array 'css' pode não estar definido, por isso verificamos
                if (isset(Yii::$app->assetManager->bundles[$dependency])) {
                    Yii::$app->assetManager->bundles[$dependency]->css = [];
                }
                break;
            }
        }
    }
}
