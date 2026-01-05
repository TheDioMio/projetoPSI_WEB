<?php


namespace common\tests\unit;

use common\models\Comment;
use common\tests\UnitTester;

class CommentTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testValidations()
    {
        $comment = new Comment();
        $comment->listing_id = null;
        $comment->user_id = null;
        $comment->text = null;

        $this->assertFalse($comment->validate(['listing_id']));
        $this->assertFalse($comment->validate(['user_id']));
        $this->assertFalse($comment->validate(['text']));


        $comment->listing_id = 'string';
        $comment->user_id = 'string';

        $this->assertFalse($comment->validate(['listing_id']));
        $this->assertFalse($comment->validate(['user_id']));

        $comment->listing_id = -1;
        $comment->user_id = -1;

        $this->assertFalse($comment->validate(['listing_id']));
        $this->assertFalse($comment->validate(['user_id']));

        $comment->listing_id = 18;
        $comment->user_id = 1;
        $comment->text = 'Rua Principal';


        $this->assertTrue($comment->validate(['listing_id']));
        $this->assertTrue($comment->validate(['user_id']));
        $this->assertTrue($comment->validate(['text']));

    }

    public function testSaveAndRead(){
        $comment = $this->createValidComment(false);
        $comment->text = 'Zacarias';
        $result = $comment->save();
        $this->assertTrue($result);

        $commentReadFromDatabase = Comment::find()->where(['id' => $comment->id])->one();
        $this->assertNotNull($commentReadFromDatabase);
        $this->assertEquals('Zacarias', $commentReadFromDatabase->text, 'The name of the text found in Database is different' );
    }

    public function testUpdateAndRead(){

        $comment = $this->createValidComment(true);

        $commentReadFromDatabase = Comment::find()->where(['id' => $comment->id])->one();
        $this->assertNotNull($commentReadFromDatabase, 'Nao foi encontrado comment com texto comment');
        $this->assertEquals('comment', $commentReadFromDatabase->text, 'The text found in Database is different' );

        $comment->text = 'comment alterado';
        $comment->save();

        $commentReadFromDatabase2 = Comment::find()->where(['id' => $comment->id])->one();
        $this->assertEquals('comment alterado', $commentReadFromDatabase2->text, 'The name of the person found in Database is different' );
    }

    public function testDelete(){

        $comment = $this->createValidComment(true);

        $commentReadFromDatabase = Comment::find()->where(['id' => $comment->id])->one();
        $this->assertNotNull($commentReadFromDatabase, 'Nao foi encontrado comentario');

        $commentReadFromDatabase->delete();
        $commentReadFromDatabase2 = Comment::find()->where(['id' => $comment->id])->one();
        $this->assertNull($commentReadFromDatabase2, 'o comentario nao foi encontrado');

    }

    public function createValidComment(Bool $save=false){

        $comment = new Comment();
        $comment->listing_id = 18;
        $comment->user_id = 1;
        $comment->text = 'comment';

        if($save){
            $comment->save();
        }
        return $comment;
    }
}
