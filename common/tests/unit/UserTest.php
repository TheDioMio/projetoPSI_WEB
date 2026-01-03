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

    public function testSaveAndRead()
    {
        $user = $this->createValidUser(false);
        $user->name = 'Zacarias';

        $result = $user->save();

        if (!$result) {
            var_dump($user->errors);
        }

        $this->assertTrue($result);
    }

    public function testUpdateAndRead(){

        $user = $this->createValidUser(true);

        $userReadFromDatabase = User::find()->where(['id' => $user->id])->one();
        $this->assertNotNull($userReadFromDatabase, 'Nao foi encontrada pessoa com nome Maria');
        $this->assertEquals('userTeste', $userReadFromDatabase->name, 'The name of the person found in Database is different' );

        $user->name = 'Joana';
        $user->save();

        $userReadFromDatabase2 = User::find()->where(['id' => $user->id])->one();
        $this->assertEquals('Joana', $userReadFromDatabase2->name, 'The name of the person found in Database is different' );
    }

    public function testDelete(){

        $user = $this->createValidUser(true);

        $userReadFromDatabase = User::find()->where(['id' => $user->id])->one();
        $this->assertNotNull($userReadFromDatabase, 'Nao foi encontrada pessoa com nome Maria');

        $userReadFromDatabase->delete();
        $userReadFromDatabase2 = User::find()->where(['id' => $user->id])->one();
        $this->assertNull($userReadFromDatabase2, 'A pessoa não foi apagada');
    }

    private function createValidUser(bool $save = false): User
    {
        $user = new User();

        $user->scenario = User::SCENARIO_DEFAULT;

        $user->name = 'userTeste';
        $user->username = 'user_' . uniqid();
        $user->email = uniqid() . '@teste.pt';

        $user->role_id = User::ROLE_USER;

        $user->status = User::STATUS_ACTIVE;

        // Campos técnicos
        $user->setPassword('123456789');
        $user->generateAuthKey();

        if ($save) {
            $user->save(false);
        }

        return $user;
    }
}
