<?php


namespace common\tests\unit;

use common\models\User;
use common\tests\UnitTester;

class UserTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testValidations()
    {
        $user = new User();
        $user->name = null;
        $user->username = null;
        $user->email = null;

        $this->assertFalse($user->validate(['name']));
        $this->assertFalse($user->validate(['username']));
        $this->assertFalse($user->validate(['email']));
        

        $user = new User();
        $user->name = 'teste';
        $user->username = 'teste';
        $user->email = 'ThisIs@Valid.com';



        $this->assertTrue($user->validate(['name']));
        $this->assertTrue($user->validate(['username']));
        $this->assertTrue($user->validate(['email']));


    }


        // tests
    public function testSomeFeature()
    {

    }
}
