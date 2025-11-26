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

        // add "createPost" permission
        $loginFrontend = $auth->createPermission('loginFrontend');
        $loginFrontend->description = 'loginFrontend';
        $auth->add($loginFrontend);

        $loginBackend = $auth->createPermission('loginBackend');
        $loginBackend->description = 'loginBackend';
        $auth->add($loginBackend);

        // add "updatePost" permission
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        //Criar o ROLE User
        // add "author" role and give this role the "createPost" permission
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $loginFrontend);
        //$auth->addChild($user, $login); // so para exemplo de atribuir uma permissão

        //Criar o ROLE UserPro
        // add "author" role and give this role the "createPost" permission
        $userPro = $auth->createRole('userPro');
        $auth->add($userPro);
        $auth->addChild($userPro, $loginFrontend);
        //$auth->addChild($userPro, $login);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $loginBackend);
        $auth->addChild($admin, $loginFrontend); // adicionado temporariamente para dar acesso frontend ao admin
        //$auth->addChild($admin, $user);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($admin, 1);
    }
}