<?php


namespace common\tests\unit;

use common\models\Animal;
use common\models\Application;
use common\models\User;
use common\tests\UnitTester;

class ApplicationTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testValidations()
    {
        $application = new Application();

        $this->assertFalse($application->validate());

        $this->assertArrayHasKey('animal_id', $application->errors);
        $this->assertArrayHasKey('user_id', $application->errors);


        $application->user_id   = 1;
        $application->animal_id = 1;
        $application->description = 'aa'; // 2 chars, min = 3
        $this->assertFalse($application->validate(['description']));
        $this->assertArrayHasKey('description', $application->errors);

        $application->user_id   = 1;
        $application->animal_id = 1;
        $application->description = 'aa'; // 2 chars, min = 3
        $this->assertFalse($application->validate(['description']));
        $this->assertArrayHasKey('description', $application->errors);

        $application->description = 'Pedido de adoção';
        $this->assertTrue($application->validate(['description']));

    }

    public function testSaveAndRead()
    {

        $animal = new Animal([
            'animal_type_id'   => 1,
            'name'             => 'Application Test Animal',
            'age_id'           => 1,
            'size_id'          => 1,
            'user_id'          => null,
            'breed_id'         => 1,
            'vaccination_id'   => 1,
            'location'         => 'Leiria',
        ]);
        $animal->save(false);

        // criar User de suporte
        $user = new User();
        $user->username = 'app_test_user';
        $user->email = 'app_test@example.com';
        $user->setPassword('123456789');
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $user->save(false);

        $application = new Application();
        $application->user_id     = $user->id;
        $application->animal_id   = $animal->id;
        $application->description = 'Gostaria de adotar este animal.';
        $application->status      = 0; // default (podes usar constante se tiveres)

        $this->assertTrue($application->save(), 'Application should be saved');

        $applicationFromDb = Application::findOne($application->id);
        $this->assertNotNull($applicationFromDb);

        $this->assertEquals($user->id, $applicationFromDb->user_id);
        $this->assertEquals($animal->id, $applicationFromDb->animal_id);
        $this->assertEquals('Gostaria de adotar este animal.', $applicationFromDb->description);
        $this->assertEquals(0, $applicationFromDb->status);

    }
}
