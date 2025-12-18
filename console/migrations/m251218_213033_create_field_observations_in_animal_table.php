<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%field_observations_in_animal}}`.
 */
class m251218_213033_create_field_observations_in_animal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%animal}}', 'observations', $this->string(120)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%animal}}', 'observations');
    }
}
