<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

//para correr o ficheiro executar o comando
//php yii rbac/init
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        //-------------------- PERMISSION -----------------------------
        $uploadAvatar = $auth->createPermission('uploadAvatar'); //NÃO ESTÁ ATRIBUIDA
        $uploadAvatar->description = 'uploadAvatar';
        $auth->add($uploadAvatar);


        //permissão de gestão de candidaturas no Frontend
        $applicationManeger = $auth->createPermission('applicationManeger');
        $applicationManeger->description = 'applicationManeger';
        $auth->add($applicationManeger);

        //permissão de ver estatísticas no Frontend
        $statisticsPage = $auth->createPermission('statisticsPage');
        $statisticsPage->description = 'Aceder à página de estatísticas sobre a conta';
        $auth->add($statisticsPage);

        //permissão de gestão de mensagens no Frontend
        $messageManeger = $auth->createPermission('messageManeger');
        $messageManeger->description = 'messageManeger';
        $auth->add($messageManeger);

        //permissão de apagar um ficheiro (foto do animal)no Frontend
        $fileDelete = $auth->createPermission('fileDelete');
        $fileDelete->description = 'fileDelete';
        $auth->add($fileDelete);

        //permissão para manipular Listings no Frontend
        $listingsManeger = $auth->createPermission('listingsManeger');
        $listingsManeger->description = 'listingsManeger';
        $auth->add($listingsManeger);

        //permissão para adicionar observações nos animais
        $animalObservations = $auth->createPermission('animalObservations');
        $animalObservations->description = 'Ver e criar observações nos seus animais';
        $auth->add($animalObservations);

        //permissão para candidatar-se a userPro
        $applyUserPro = $auth->createPermission('applyUserPro');
        $applyUserPro->description = 'Candidatar-se a User Pro';
        $auth->add($applyUserPro);

        $createComment = $auth->createPermission('createComment');
        $createComment->description = 'Criar comentários';
        $auth->add($createComment);

        $updateAnimalStatus = $auth->createPermission('updateAnimalStatus');
        $updateAnimalStatus->description = 'updateAnimalStatus';
        $auth->add($updateAnimalStatus);

        $loginFrontend = $auth->createPermission('loginFrontend');
        $loginFrontend->description = 'Acesso ao Frontend (Login)';
        $auth->add($loginFrontend);

        $loginBackend = $auth->createPermission('loginBackend');
        $loginBackend->description = 'Acesso ao Backend (Login)';
        $auth->add($loginBackend);

        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);


        //--------------ROLES-----------------------------------

        // Definição de User e as suas permissões
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $loginFrontend);
        $auth->addChild($user, $createComment);
        $auth->addChild($user, $updateAnimalStatus);
        $auth->addChild($user, $listingsManeger);
        $auth->addChild($user, $messageManeger);
        $auth->addChild($user, $fileDelete);
        $auth->addChild($user, $applicationManeger);
        $auth->addChild($user, $updatePost);

        // Definição de UserPro e as suas permissões
        $userPro = $auth->createRole('userPro');
        $auth->add($userPro);
        $auth->addChild($userPro, $loginFrontend);
        $auth->addChild($userPro, $createComment);
        $auth->addChild($userPro, $updateAnimalStatus);
        $auth->addChild($userPro, $listingsManeger);
        $auth->addChild($userPro, $messageManeger);
        $auth->addChild($userPro, $fileDelete);
        $auth->addChild($userPro, $applicationManeger);
        $auth->addChild($userPro, $updatePost);
        $auth->addChild($userPro, $statisticsPage);
        $auth->addChild($userPro, $animalObservations);

        //Definição de Admin e as suas permissões
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $loginBackend);

        $auth->assign($admin, 1);
    }
}