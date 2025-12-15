<?php

use yii\db\Migration;

class m251214_205028_add_application_isRead_statusDate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('application', 'statusDate', $this->date()->notNull()->defaultValue(date('Y-m-d')));
        $this->addColumn('application', 'isRead', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('application', 'statusDate');
        $this->dropColumn('application', 'isRead');
    }

}
