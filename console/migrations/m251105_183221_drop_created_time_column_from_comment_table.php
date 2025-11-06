<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%comment}}`.
 */
class m251105_183221_drop_created_time_column_from_comment_table extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%comment}}', 'created_time');
    }

    public function safeDown()
    {
        $this->addColumn('{{%comment}}', 'created_time', $this->time()->defaultValue(null));
    }
}
