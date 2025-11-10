<?php

use yii\db\Migration;

class m251110_104921_update_application_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('application', 'data', $this->json()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('application', 'data');
        /*
        echo "m251110_104921_update_application_table cannot be reverted.\n";

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
        echo "m251110_104921_update_application_table cannot be reverted.\n";

        return false;
    }
    */
}
