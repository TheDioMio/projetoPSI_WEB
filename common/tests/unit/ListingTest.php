<?php


namespace common\tests\unit;

use common\models\Listing;
use common\tests\UnitTester;

class ListingTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
//        $this->createValidListing(true);
    }



    public function testValidations() {
        $listing = new Listing();

        $listing->description =null;
        $listing->animal_id =null;
        $listing->user_id =null;
        $listing->views =null;
        $listing->status =null;

        $this->assertTrue($listing->validate(['description']));
        $this->assertFalse($listing->validate(['animal_id']));
        $this->assertFalse($listing->validate(['user_id']));
        $this->assertTrue($listing->validate(['views']));
        $this->assertTrue($listing->validate(['status']));


        $listing->animal_id ='string';
        $listing->user_id ='string';
        $listing->views ='string';
        $listing->status ='string';

        $this->assertFalse($listing->validate(['animal_id']));
        $this->assertFalse($listing->validate(['user_id']));
        $this->assertFalse($listing->validate(['views']));
        $this->assertFalse($listing->validate(['status']));



       $listing->description = 'teste';
       $listing->animal_id = 1;
       $listing->user_id = 1;
       $listing->views = 123456789;
       $listing->status = 1;


       $this->assertTrue($listing->validate(['description']));
       $this->assertTrue($listing->validate(['animal_id']));
       $this->assertTrue($listing->validate(['user_id']));
       $this->assertTrue($listing->validate(['views']));
       $this->assertTrue($listing->validate(['status']));

    }


    public function testSaveAndRead()
    {
        $listing = $this->createValidListing(false);
        $listing->description = 'Zacarias';
        $result = $listing->save(false);
        $this->assertTrue($result);


        $listingReadFromDatabase = Listing::find()->where(['id' => $listing->id])->one();
        $this->assertNotNull($listingReadFromDatabase);
        $this->assertEquals('Zacarias',$listingReadFromDatabase->description,'The description of the listing found in Database is different');
    }

    public function testUpdateAndRead(){

        $listing = $this->createValidListing(true);

        $listingReadFromDatabase = Listing::find()->where(['id' => $listing->id])->one();
        $this->assertNotNull($listingReadFromDatabase, 'Nao foi encontrada descricao com 123456789');
        $this->assertEquals('123456789', $listingReadFromDatabase->description, 'The name of the person found in Database is different' );

        $listing->description = 'qwerty';
        $listing->save(false);

        $listingReadFromDatabase2 = Listing::find()->where(['id' => $listing->id])->one();
        $this->assertEquals('qwerty', $listingReadFromDatabase2->description, 'The description found in Database is different' );
    }

    public function testDelete()
    {

        $listing = $this->createValidListing(true);

        $listingReadFromDatabase = Listing::find()->where(['id' => $listing->id])->one();
        $this->assertNotNull($listingReadFromDatabase, 'Nao foi encontrada pessoa com nome Maria');

        $listingReadFromDatabase->delete();
        $listingReadFromDatabase2 = Listing::find()->where(['id' => $listing->id])->one();
        $this->assertNull($listingReadFromDatabase2, 'A pessoa não foi apagada');

    }


    private function createValidListing(Bool $save=false): Listing{
        $listing = new Listing();
        $listing->description = '123456789';
        $listing->animal_id = 1;
        $listing->user_id = 1;
        $listing->status = 1;

        if ($save) {
            $this->assertTrue($listing->save(false), 'Failed to save listing in createValidListing');
        }

        return $listing;
    }
}
