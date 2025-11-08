<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Handles changing columns `created_at` and `updated_at` from INT to DATETIME in table `{{%user}}`.
 */
class m251108_154523_update_user_table_created_at_updated_at extends Migration
{
    private $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     *
     * Documentação:
     * A função safeUp() converte INT -> DATETIME.
     * Lógica corrigida: Criamos a nova coluna, convertemos os dados, e SÓ DEPOIS
     * apagamos a antiga.
     */
    public function safeUp()
    {
        /* Um dos objetivos desta migração é atualizar as tabelas de tempo dentro da tabela user de int, para datetime,
        isto enquanto não se perde nenhum dado. Para isto, passamos por um processo de conversão.
        */
        // === Processar a coluna CREATED_AT ===
        $this->addColumn($this->tableName, 'created_at_new', $this->dateTime()->notNull()->after('status'));
        $this->update($this->tableName, [
            'created_at_new' => new Expression('FROM_UNIXTIME(created_at)')
        ]);
        $this->dropColumn($this->tableName, 'created_at');
        $this->renameColumn($this->tableName, 'created_at_new', 'created_at');


        // === Processar a coluna UPDATED_AT ===
        $this->addColumn($this->tableName, 'updated_at_new', $this->dateTime()->notNull()->after('created_at'));
        $this->update($this->tableName, [
            'updated_at_new' => new Expression('FROM_UNIXTIME(updated_at)')
        ]);
        $this->dropColumn($this->tableName, 'updated_at');
        $this->renameColumn($this->tableName, 'updated_at_new', 'updated_at');
    }

    /**
     * {@inheritdoc}
     *
     * Documentação:
     * A função safeDown() reverte a mudança: DATETIME -> INT.
     */
    public function safeDown()
    {
        // === Processar a coluna CREATED_AT (Reverter) ===
        $this->addColumn($this->tableName, 'created_at_old_int', $this->integer()->notNull()->after('status'));
        $this->update($this->tableName, [
            'created_at_old_int' => new Expression('UNIX_TIMESTAMP(created_at)')
        ]);
        $this->dropColumn($this->tableName, 'created_at');
        $this->renameColumn($this->tableName, 'created_at_old_int', 'created_at');


        // === Processar a coluna UPDATED_AT (Reverter) ===
        $this->addColumn($this->tableName, 'updated_at_old_int', $this->integer()->notNull()->after('created_at'));
        $this->update($this->tableName, [
            'updated_at_old_int' => new Expression('UNIX_TIMESTAMP(updated_at)')
        ]);
        $this->dropColumn($this->tableName, 'updated_at');
        $this->renameColumn($this->tableName, 'updated_at_old_int', 'updated_at');
    }
}