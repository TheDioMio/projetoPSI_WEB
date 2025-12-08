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

        $createComment = $auth->createPermission('createComment');
        $createComment->description = 'createComment';
        $auth->add($createComment);

        $loginFrontend = $auth->createPermission('loginFrontend');
        $loginFrontend->description = 'loginFrontend';
        $auth->add($loginFrontend);

        $loginBackend = $auth->createPermission('loginBackend');
        $loginBackend->description = 'loginBackend';
        $auth->add($loginBackend);

        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $loginFrontend);
        $auth->addChild($user, $createComment);


        $userPro = $auth->createRole('userPro');
        $auth->add($userPro);
        $auth->addChild($userPro, $loginFrontend);
        $auth->addChild($userPro, $createComment);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $loginBackend);


        $auth->assign($admin, 1);
    }
}