<?php

use yii\db\Migration;

class m251106_222849_populate_table_FileType extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('file_type', ['id', 'description'], [
            [1, 'animal_photo'],
            [2, 'avatar'],
            [3, 'document'],
        ]);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->delete('file_type', ['id' => [1, 2, 3]]);
        /*
        echo "m251106_222849_populate_table_FileType cannot be reverted.\n";

        return false;
        */
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251106_222849_populate_table_FileType cannot be reverted.\n";

        return false;
    }
    */
}
