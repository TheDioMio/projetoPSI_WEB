<?php
namespace frontend\controllers;

use common\models\Animal;
use common\models\Application;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ApplicationController extends Controller
{
    public function actionApply($animal_id)
    {
        // tem de estar autenticado para candidatar (opcional)
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Precisas de iniciar sessão para candidatar.');
            return $this->redirect(['site/login']);
        }

        $animal = Animal::findOne($animal_id);
        if ($animal === null) {
            throw new NotFoundHttpException('Animal não encontrado.');
        }

        $model = new Application([
            'animal_id'      => $animal->id,
            'user_id'        => Yii::$app->user->id,
            'target_user_id' => $animal->user_id, // dono do animal (se fizer sentido no teu caso)
            'status'         => 0,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        // Trata o POST
        if ($model->load(Yii::$app->request->post())) {
            // (opcional) garantir que data é array; o beforeSave fará json_encode
            if (!is_array($model->data)) {
                $model->data = (array)$model->data;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Candidatura enviada com sucesso!');
                return $this->redirect(['detail', 'id' => $animal->id]); // ou página que quiseres
            } else {
                // para veres rapidamente o que falhou
                Yii::error(['apply_save_errors' => $model->errors], __METHOD__);
                Yii::$app->session->setFlash('error', 'Corrige os erros do formulário.');
            }
        }

        // GET inicial ou POST inválido → volta a mostrar o form
        return $this->render('apply', [
            'animal' => $animal,
            'model'  => $model,
        ]);
    }
    public function actionMyApplications()
    {
        return $this->render('my-applications');
    }


    public function actionApplyUserPro()
    {
        return $this->render('apply-user-pro');
    }
}