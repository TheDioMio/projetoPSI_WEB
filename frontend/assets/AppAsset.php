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

    // 1. OS SEUS FICHEIROS CSS
    // O Yii vai carregar estes ficheiros.
    // A ORDEM É IMPORTANTE!
    public $css = [
        // Google Fonts e Ícones (do seu <head>)
        'https://fonts.googleapis.com/css2?family=Poppins&family=Roboto:wght@700&display=swap',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
        'lib/flaticon/font/flaticon.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',

        // Bibliotecas do Template
        'lib/owlcarousel/assets/owl.carousel.min.css',

        // O CSS DO SEU TEMPLATE (htmlcodex)
        // Este é o ficheiro que o seu template NÃO pode viver sem.
        'css/bootstrap.min.css',

        // Os seus estilos personalizados (devem vir por último)
        'css/style.css',
        'css/site.css', // O seu ficheiro original do Yii
    ];

    // 2. OS SEUS FICHEIROS JS (do template)
    // O Yii vai carregar estes ficheiros.
    // O jQuery e o Bootstrap.bundle.js são carregados pelas $depends
    public $js = [
        // Plugins do Template
        'lib/easing/easing.min.js',
        'lib/waypoints/waypoints.min.js',
        'lib/owlcarousel/owl.carousel.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js',

        // O script principal do template (deve vir por último)
        'js/main.js',
    ];

    // 3. DEPENDÊNCIAS (O PONTO CENTRAL)
    // Isto diz ao Yii: "Antes de carregares o $js, tens de carregar ISTO:"
    public $depends = [
        'yii\web\YiiAsset', // Carrega o yii.js
        'yii\bootstrap5\BootstrapAsset', // Carrega o jQuery e o bootstrap.bundle.js
    ];

    /**
     * ESTA FUNÇÃO É O SEGREDO!
     * Ela é executada e diz ao Yii:
     * "Ok, eu pedi o 'BootstrapAsset', mas faz-me um favor:
     * NÃO INCLUAS o ficheiro 'bootstrap.css' que vem contigo.
     * Eu trato do CSS (na minha lista $css lá em cima)."
     */
    public function init()
    {
        parent::init();

        foreach ($this->depends as $i => $dependency) {
            if ($dependency === 'yii\bootstrap5\BootstrapAsset') {
                if (isset(Yii::$app->assetManager->bundles[$dependency])) {
                    // Impede o Yii de carregar o 'bootstrap.css' default
                    Yii::$app->assetManager->bundles[$dependency]->css = [];
                }
                break;
            }
        }
    }
}

