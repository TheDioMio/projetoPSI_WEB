<?php

use yii\db\Migration;

class m251213_230227_add_animal_statusDate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%animal}}', 'statusDate', $this->date()->notNull()->defaultValue(date('Y-m-d')));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%animal}}', 'statusDate');
    }

}
