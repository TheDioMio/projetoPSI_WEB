<?php
namespace frontend\controllers;

use common\models\Animal;
use common\models\Application;
use Yii;
use yii\base\DynamicModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ApplicationController extends Controller
{
    public function actionApply($animal_id)
    {
        //Tem de estar autenticado para candidatar
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Faça login para se candidatar!');
            return $this->redirect(['site/login']);
        }

        $animal = Animal::findOne($animal_id);
        if ($animal === null) {
            throw new NotFoundHttpException('Animal não encontrado.');
        }

        $model = new Application([
            'animal_id'      => $animal->id,
            'user_id'        => Yii::$app->user->id,
            'target_user_id' => $animal->user_id, //Dono do animal
            'status'         => 0,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        // Trata o POST
        if ($model->load(Yii::$app->request->post())) {
            //Garantir que data é array; o beforeSave fará json_encode
            if (!is_array($model->data)) {
                $model->data = (array)$model->data;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Candidatura enviada com sucesso!');
                return $this->redirect(['detail', 'id' => $animal->id]);
            } else {
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
        //1.º Autenticação:
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Faça login para se candidatar!');
            return $this->redirect(['site/login']);
        }

        //2.º Criar o modelo dinâmico para o formulário, isto deixa-nos validar e ter muito mais controlo sobre o que vai para a 'data'.
        $formModel = new DynamicModel([
            'professional_name', 'nif', 'area_id', 'experience_level',
            'website', 'availability', 'bio', 'certificates'
        ]);

        //3.º Definir regras de validação para o formulário
        $formModel->addRule(['professional_name', 'bio', 'availability'], 'string')
            ->addRule(['professional_name', 'nif', 'area_id', 'experience_level', 'bio'], 'required')
            ->addRule(['nif', 'area_id', 'experience_level'], 'integer')
            ->addRule(['website'], 'url', ['defaultScheme' => 'http']);


        //4.º Processar o POST
        if ($formModel->load(Yii::$app->request->post())) {
            //Validação se o formulário é válido
            if ($formModel->validate()) {
                //A. Preparar os dados para guardar na nossa BD.
                $application = new Application();

                $application->scenario = Application::SCENARIO_USER_PRO; //NUNCA ESQUECER DE DECLARAR QUAL É O CENÁRIO!!!!!
                $application->user_id = Yii::$app->user->id;
                $application->type = Application::TYPE_USER_PRO; //Declara logo que a candidatura é de tipo 2 (userPro), ISTO ESTÁ TUDO DEFINIDO EM CIMA, CONSTANTES!
                $application->status = 0; //Está pendente, ainda não foi vista sequer.
                $application->created_at = date('Y-m-d H:i:s');

                $application->animal_id = 16; //FORÇA A ENVIAR UM ANIMAL_ID, já que na Application é obrigatório um animal_id. Só para testes.

                //Professional name é o mesmo que o nosso 'name', esqueci-me que tínhamos essa coluna na BD, mas está a funcionar por isso, por agora, não se mexe
                $application->description = 'Candidatura UserPro: ' . $formModel->professional_name;

                //Empacotar tudo no JSON para a 'data'
                $dataToSave = $formModel->getAttributes();
                $application->data = $dataToSave;

                //B. Guardar
                if ($application->save()) {
                    //Isto é tipo o Toast de Android
                    Yii::$app->session->setFlash('success', 'Candidatura submetida com sucesso! Vamos analisar os teus dados.');
                    return $this->redirect(['site/index']);
                } else {
                    //Isto é tipo o Toast de Android
                    Yii::$app->session->setFlash('error', 'Erro ao guardar a candidatura na base de dados.');
                }
            } else {
                //Isto é tipo o Toast de Android
                Yii::$app->session->setFlash('error', 'Por favor corrige os erros no formulário.');
            }
        }

        //5.º Renderizar a View
        return $this->render('apply-user-pro', [
            'model' => $formModel,  // Enviamos o DynamicModel para a view desenhar os campos
        ]);
    }
}