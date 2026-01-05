<?php


namespace common\tests\unit;

use common\models\Animal;
use common\tests\UnitTester;

class animalTest extends \Codeception\Test\Unit
{

    private const STRING = 'ABCDE';
    private const STRING_51 = 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';

    protected UnitTester $tester;

    protected function _before()
    {
      //  $this->createValidAnimal(true);
    }




    // tests


    public function testValidations()
    {
        $animal = new Animal();
        $animal->scenario = Animal::SCENARIO_API_CREATE;

        $this->assertFalse($animal->validate());
        $animal->animal_type_id = null;
        $animal->name           = null;
        $animal->age_id         = null;
        $animal->size_id        = null;
        $animal->breed_id       = null;
        $animal->vaccination_id = null;
        $animal->location       = null;

        $this->assertFalse($animal->validate(['animal_type_id']));
        $this->assertFalse($animal->validate(['name']));
        $this->assertFalse($animal->validate(['age_id']));
        $this->assertFalse($animal->validate(['size_id']));
        $this->assertFalse($animal->validate(['breed_id']));
        $this->assertFalse($animal->validate(['vaccination_id']));
        $this->assertFalse($animal->validate(['location']));

        $animal->animal_type_id = self::STRING;
        $animal->age_id = self::STRING;
        $animal->size_id = self::STRING;
        $animal->user_id = self::STRING;
        $animal->breed_id  = self::STRING;
        $animal->vaccination_id = self::STRING;

        $this->assertFalse($animal->validate(['animal_type_id']));
        $this->assertFalse($animal->validate(['age_id']));
        $this->assertFalse($animal->validate(['size_id']));
        $this->assertFalse($animal->validate(['user_id']));
        $this->assertFalse($animal->validate(['breed_id']));
        $this->assertFalse($animal->validate(['vaccination_id']));

        $animal = new Animal();
        $animal->animal_type_id = 1;
        $animal->name           = 'Rex';
        $animal->age_id         = 1;
        $animal->size_id        = 1;
        $animal->user_id        = 1;
        $animal->breed_id       = 1;
        $animal->vaccination_id = 1;
        $animal->location       = 'Leiria';

        $this->assertTrue($animal->validate(['animal_type_id']));
        $this->assertTrue($animal->validate(['name']));
        $this->assertTrue($animal->validate(['age_id']));
        $this->assertTrue($animal->validate(['size_id']));
        $this->assertTrue($animal->validate(['user_id']));
        $this->assertTrue($animal->validate(['breed_id']));
        $this->assertTrue($animal->validate(['vaccination_id']));
        $this->assertTrue($animal->validate(['location']));
    }


    public function testSaveAndRead()
    {
        $animal = $this->createValidAnimal(false);
        $animal->name = 'Zacarias';
        $result = $animal->save();
        $this->assertTrue($result);

        $animalReadFromDatabase = Animal::find()->where(['id' => $animal->id])->one();
        $this->assertNotNull($animalReadFromDatabase);
        $this->assertEquals('Zacarias', $animalReadFromDatabase->name);
    }



    public function testSaveInvalidName()
    {
        $animal = $this->createValidAnimal(false);
        $animal->name = self::STRING_51;

        $result = $animal->save();
        $this->assertFalse($result);

        $animalReadFromDatabase = Animal::find()->where([
            'location' => 'Leiria',
            'name' => self::STRING_51,
        ])->one();
        $this->assertNull($animalReadFromDatabase);
    }



    public function testUpdateAndRead(){

        $animal = $this->createValidAnimal(true);

        // ler da BD
        $animalFromDb = Animal::find()->where(['id' => $animal->id])->one();
        $this->assertNotNull($animalFromDb, 'Nao foi encontrado animal na BD');

        // nome inicial tem de ser igual ao que criaste em createValidAnimal
        $this->assertEquals('Rex', $animalFromDb->name, 'The name of the animal found in Database is different');

        // atualizar
        $animalFromDb->name = 'Joana';
        $animalFromDb->save();

        // voltar a ler e confirmar update
        $animalFromDb2 = Animal::find()->where(['id' => $animal->id])->one();
        $this->assertEquals('Joana', $animalFromDb2->name, 'The name of the animal found in Database after update is different');
    }




    public function testDelete()
    {
        $animal = $this->createValidAnimal(true);

        $animalFromDb = Animal::find()->where(['id' => $animal->id])->one();
        $this->assertNotNull($animalFromDb);

        $animalFromDb->delete();

        $animalFromDb2 = Animal::find()->where(['id' => $animal->id])->one();
        $this->assertNull($animalFromDb2);
    }

    private function createValidAnimal(Bool $save=false){
        $animal = new Animal();
        $animal->animal_type_id = 1;
        $animal->name           = 'Rex';
        $animal->age_id         = 1;
        $animal->size_id        = 1;
        $animal->user_id        = 1;
        $animal->breed_id       = 1;
        $animal->vaccination_id = 1;
        $animal->location       = 'Leiria';

        if($save){
            $animal->save(false);
        }
        return $animal;
    }

    /*
    public function testSomeFeature()
    {

    }
    */
}
