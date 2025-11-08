<?php

use yii\db\Migration;

class m251106_224025_create_Aux_Tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        echo "m251106_224025_create_Aux_Tables cannot be reverted.\n";

        return false;

    }


    // Use up()/down() to run migration code without a transaction.


    public function up()
    {
        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';



        // ANIMAL_SIZE
        $this->createTable('animal_size', [
            'id' => $this->primaryKey(),
            'description' => $this->string(100)->notNull(),
        ], $tableOptions);

        //***************************************************************

        // ANIMAL_AGE
        $this->createTable('animal_age', [
            'id' => $this->primaryKey(),
            'description' => $this->string(100)->notNull(),
        ], $tableOptions);

        //***************************************************************

        // VACCINATION
        $this->createTable('vaccination', [
            'id' => $this->primaryKey(),
            'description' => $this->string(100)->notNull(),
        ], $tableOptions);

        //***************************************************************

        // ROLES
        $this->createTable('roles', [
            'id' => $this->primaryKey(),
            'description' => $this->string(100)->notNull(),
        ], $tableOptions);

    }

    public function down()
    {

        $this->dropTable('animal_size');
        $this->dropTable('animal_age');
        $this->dropTable('vaccination');
        $this->dropTable('roles');

        /*
        echo "m251106_224025_create_Aux_Tables cannot be reverted.\n";

        return false;

        */
    }

}
