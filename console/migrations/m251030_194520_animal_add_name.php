<?php

use yii\db\Migration;

class m251030_194520_animal_add_name extends Migration
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
        echo "m251030_194520_animal_add_name cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('animal', 'name', $this->string(50));
    }

    public function down()
    {
        $this->dropColumn('animal', 'name');
    }

}
