<?php

use yii\db\Migration;

class m251208_225626_add_animal_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('{{%animal}}', 'status', $this->integer()->notNull()->defaultValue(1));

        // criar indices para poder filtrar
        $this->createIndex(
            'idx-animal-status',
            '{{%animal}}',
            'status'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Remover o índice
        $this->dropIndex(
            'idx-animal-status',
            '{{%animal}}'
        );

        // Remover a coluna
        $this->dropColumn('{{%animal}}', 'status');
    }

}
