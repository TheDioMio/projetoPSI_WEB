<?php

use yii\db\Migration;

class m251107_104830_update_animal_table extends Migration
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

        // === ANIMAL ===
        $schema = $this->db->getTableSchema('animal', true);

        // 1) Apagar colunas se já existirem
        if ($schema->getColumn('age_id') !== null) {
            $this->dropColumn('animal', 'age_id');
        }
        if ($schema->getColumn('size_id') !== null) {
            $this->dropColumn('animal', 'size_id');
        }

        // 2) Criar novamente as colunas
        $this->addColumn('animal', 'age_id',  $this->integer()->null()->after('id'));
        $this->addColumn('animal', 'size_id', $this->integer()->null()->after('age_id'));

        // 3) Criar índices
        $this->createIndex('idx_animal_age_id',  'animal', 'age_id');
        $this->createIndex('idx_animal_size_id', 'animal', 'size_id');

        // 4) Adicionar as Foreign Keys
        $this->addForeignKey('fk_animal_age',  'animal', 'age_id',  'animal_age',  'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_animal_size', 'animal', 'size_id', 'animal_size', 'id', 'SET NULL', 'CASCADE');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // Remover FKs
        $this->dropForeignKey('fk_animal_size', 'animal');
        $this->dropForeignKey('fk_animal_age',  'animal');

        // Remover índices
        $this->dropIndex('idx_animal_size_id', 'animal');
        $this->dropIndex('idx_animal_age_id',  'animal');

        // Remover colunas
        $this->dropColumn('animal', 'size_id');
        $this->dropColumn('animal', 'age_id');








        /*
        echo "m251107_104830_update_animal_table cannot be reverted.\n";

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
        echo "m251107_104830_update_animal_table cannot be reverted.\n";

        return false;
    }
    */
}
