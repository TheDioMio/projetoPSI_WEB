<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ];
    }
    
    /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');

        $I->see('Sign in to start your session');


        // teste campos vazios

        $I->fillField('input[name="LoginForm[username]"]', '');
        $I->fillField('input[name="LoginForm[password]"]', '');
        $I->click('Sign In');
        $I->see('Sign in to start your session');

        // teste login errado

        $I->fillField('input[name="LoginForm[username]"]', 'wrong');
        $I->fillField('input[name="LoginForm[password]"]', 'wrong');
        $I->click('Sign In');
        $I->see('Incorrect username or password.');
        $I->dontSee('Admin Panel');

        // teste login correto

        $I->fillField('input[name="LoginForm[username]"]', 'erau');
        $I->fillField('input[name="LoginForm[password]"]', 'password_0');
        $I->click('Sign In');

        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
        $I->see('Sair', 'a, button', 'form button[type=submit]');
        $I->see('Admin Panel');
        $I->see('Dashboard');
    }
}
