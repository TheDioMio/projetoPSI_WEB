<?php

use yii\db\Migration;

class m251108_000352_update_animal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $schema = $this->db->getTableSchema('animal', true);
        if ($schema->getColumn('size') !== null) {
            $this->dropColumn('animal', 'size');
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->addColumn('animal', 'size', $this->integer()->null()->after('description'));


        /*
        echo "m251108_000352_update_animal_table cannot be reverted.\n";

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
        echo "m251108_000352_update_animal_table cannot be reverted.\n";

        return false;
    }
    */
}
