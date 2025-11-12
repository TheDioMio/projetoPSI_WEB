<?php

use yii\db\Migration;

/**
 * Handles adding 'role_id' column to table `{{%user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%roles}}`
 */
class m251112_193803_add_role_id_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%user}}',
            'role_id',
            $this->integer()->defaultValue(null)
        );

        $this->createIndex(
            'idx-user-role_id',
            '{{%user}}',
            'role_id'
        );

        $this->addForeignKey(
            'fk-user-role_id',    // Nome da chave
            '{{%user}}',          // Tabela de origem (filha)
            'role_id',            // Coluna de origem
            '{{%roles}}',         // Tabela de destino (mãe)
            'id',                 // Coluna de destino
            'SET NULL',           // ON DELETE: Se uma role for apagada, o 'user.role_id' fica NULL.
            'CASCADE'             // ON UPDATE: Se o 'roles.id' mudar, atualiza aqui.
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user-role_id',
            '{{%user}}'
        );

        $this->dropIndex(
            'idx-user-role_id',
            '{{%user}}'
        );

        $this->dropColumn('{{%user}}', 'role_id');
    }
}