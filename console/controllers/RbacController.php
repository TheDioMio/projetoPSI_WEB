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
        $loginFrontEnd = $auth->createPermission('loginFrontEnd');
        $loginFrontEnd->description = 'loginFrontEnd';
        $auth->add($loginFrontEnd);

        // add "updatePost" permission
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        //Criar o ROLE User
        // add "author" role and give this role the "createPost" permission
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $loginFrontEnd);
        //$auth->addChild($user, $login); // so para exemplo de atribuir uma permissão

        //Criar o ROLE UserPro
        // add "author" role and give this role the "createPost" permission
        $userPro = $auth->createRole('userPro');
        $auth->add($userPro);
        $auth->addChild($userPro, $loginFrontEnd);
        //$auth->addChild($userPro, $login);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $loginFrontEnd); // adicionado temporariamente para dar acesso frontend ao admin
        //$auth->addChild($admin, $user);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($admin, 1);
        $auth->assign($admin, 9);
            $auth->assign($user, 2);
        $auth->assign($userPro, 3);
    }
}