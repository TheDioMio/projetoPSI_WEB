<?php

use yii\db\Migration;

class m251112_203004_rename_roles_table_to_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    private $userTable = '{{%user}}';
    private $oldRoleTable = '{{%roles}}';
    private $newRoleTable = '{{%role}}';
    private $fkName = 'fk-user-role_id';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        echo "A remover a FK '{$this->fkName}' da tabela 'user'...\n";
        $this->dropForeignKey($this->fkName, $this->userTable);

        echo "A renomear a tabela 'roles' para 'role'...\n";
        $this->renameTable($this->oldRoleTable, $this->newRoleTable);

        echo "A recriar a FK '{$this->fkName}' para apontar para 'role'...\n";
        $this->addForeignKey(
            $this->fkName,
            $this->userTable,    // Tabela filha (user)
            'role_id',           // Coluna filha (user.role_id)
            $this->newRoleTable, // Tabela mãe (a nova 'role')
            'id',                // Coluna mãe (role.id)
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "A remover a FK '{$this->fkName}' (que aponta para 'role')...\n";
        $this->dropForeignKey($this->fkName, $this->userTable);

        echo "A renomear a tabela 'role' de volta para 'roles'...\n";
        $this->renameTable($this->newRoleTable, $this->oldRoleTable);

        echo "A recriar a FK '{$this->fkName}' para apontar para 'roles'...\n";
        $this->addForeignKey(
            $this->fkName,
            $this->userTable,
            'role_id',
            $this->oldRoleTable, // Aponta para o nome antigo
            'id',
            'SET NULL',
            'CASCADE'
        );
    }
}