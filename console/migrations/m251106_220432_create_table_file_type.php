<?php

use yii\db\Migration;

class m251106_220432_create_table_file_type extends Migration
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
        echo "m251106_220432_create_table_file_type cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

        // FILE_TYPE
        $this->createTable('file_type', [
            'id' => $this->primaryKey(),
            'description' => $this->string(100)->notNull(),
        ], $tableOptions);

    }

    public function down()
    {


        $this->dropTable('file_type');

        /*
        echo "m251106_220432_create_table_file_type cannot be reverted.\n";

        return false;
        */
    }

}
