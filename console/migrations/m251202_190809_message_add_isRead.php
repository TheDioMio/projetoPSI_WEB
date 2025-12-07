<?php

use yii\db\Migration;

class m251202_190809_message_add_isRead extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('message', 'isRead', $this->boolean()->defaultValue(0));
        $this->addColumn('message', 'subject', $this->string(255)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251202_190809_message_add_isRead cannot be reverted.\n";
        $this->dropColumn('message', 'isRead');
        $this->dropColumn('message', 'subject');
        return false;
    }


}
