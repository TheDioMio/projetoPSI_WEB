<?php

use yii\db\Migration;

class m251218_131652_turn_animal_id_on_application_nullable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Alterar a coluna animal_id para permitir NULL
        $this->alterColumn('{{%application}}', 'animal_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Se tentarmos voltar atrás, vai dar erro se existirem candidaturas sem animal.
        // Por segurança, apagamos as que têm NULL antes de voltar a meter NOT NULL.
        $this->delete('{{%application}}', ['animal_id' => null]);

        // Reverte para NOT NULL como estava no SQL original
        $this->alterColumn('{{%application}}', 'animal_id', $this->integer()->notNull());

        return true;
    }
}
