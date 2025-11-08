<?php

use yii\db\Migration;

class m251107_191525_update_animal_table extends Migration
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

        // === ANIMAL (alterações) ===
        $schema = $this->db->getTableSchema('animal', true);

        // 1) Remover coluna antiga "vaccines" (lista/texto)
        if ($schema->getColumn('vaccines') !== null) {
            $this->dropColumn('animal', 'vaccines');
        }

        // 2) Adicionar "vaccination_id"
        if ($schema->getColumn('vaccination_id') === null) {
            $this->addColumn('animal', 'vaccination_id', $this->integer()->null()->after('size_id'));
        }

        // 3) Index
        $this->createIndex('idx_animal_vaccination_id', 'animal', 'vaccination_id');

        // 4) Limpeza básica para evitar erro de FK (0 -> NULL; ids inexistentes -> NULL)
        $this->execute("UPDATE animal SET vaccination_id = NULL WHERE vaccination_id = 0");
        $this->execute("
            UPDATE animal a
            LEFT JOIN vaccination v ON v.id = a.vaccination_id
            SET a.vaccination_id = NULL
            WHERE a.vaccination_id IS NOT NULL AND v.id IS NULL
        ");

        // 5) Foreign Key
        $this->addForeignKey(
            'fk_animal_vaccination',
            'animal', 'vaccination_id',
            'vaccination', 'id',
            'SET NULL',   // on delete
            'CASCADE'     // on update
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // Remover FK e índice
        $this->dropForeignKey('fk_animal_vaccination', 'animal');
        $this->dropIndex('idx_animal_vaccination_id', 'animal');

        // Remover coluna "vaccination_id"
        $schema = $this->db->getTableSchema('animal', true);
        if ($schema->getColumn('vaccination_id') !== null) {
            $this->dropColumn('animal', 'vaccination_id');
        }

        // Recriar coluna antiga "vaccines" (ajusta o tipo conforme o teu original)
        $this->addColumn('animal', 'vaccines', $this->string(255)->null()->after('size_id'));

        /*
        echo "m251107_191525_update_animal_table cannot be reverted.\n";

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
        echo "m251107_191525_update_animal_table cannot be reverted.\n";

        return false;
    }
    */
}
