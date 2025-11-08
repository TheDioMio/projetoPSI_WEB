<?php

use yii\db\Migration;

class m251107_190628_update_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // === FILE (alterações) ===
        $schema = $this->db->getTableSchema('file', true);

        // remover coluna antiga "type" (texto/código)
        if ($schema->getColumn('type') !== null) {
            $this->dropColumn('file', 'type');
        }

        // adicionar "type_id"
        if ($schema->getColumn('type_id') === null) {
            $this->addColumn('file', 'type_id', $this->integer()->null()->after('id'));
        }

        // índice
        $this->createIndex('idx_file_type_id', 'file', 'type_id');

        // FK -> file_type(id)
        $this->addForeignKey(
            'fk_file_type',
            'file', 'type_id',
            'file_type', 'id',
            'SET NULL',   // on delete
            'CASCADE'     // on update
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        // remover FK/índice
        $this->dropForeignKey('fk_file_type', 'file');
        $this->dropIndex('idx_file_type_id', 'file');

        // remover "type_id"
        $schema = $this->db->getTableSchema('file', true);
        if ($schema->getColumn('type_id') !== null) {
            $this->dropColumn('file', 'type_id');
        }

        // recriar "type" (ajuste o tipo se antes era outro)
        $this->addColumn('file', 'type', $this->string(100)->null()->after('id'));


        /*
        echo "m251107_190628_update_file_table cannot be reverted.\n";

        return false;
        */
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251107_190628_update_file_table cannot be reverted.\n";

        return false;
    }
    */
}
