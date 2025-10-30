<?php

use yii\db\Migration;

class m251030_193654_file_add_type extends Migration
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
        echo "m251030_193654_file_add_type cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('file', 'type', $this->string(50));
    }

    public function down()
    {

        $this->dropColumn('file', 'type');

    }

}
