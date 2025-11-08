<?php

use yii\db\Migration;

class m251106_225639_populate_Aux_Tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251106_225639_populate_Aux_Tables cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        // ANIMAL_SIZE
        $this->batchInsert('animal_size', ['id', 'description'], [
            [1, 'Pequeno (< 10 Kg)'],
            [2, 'Médio (11 – 26 Kg)'],
            [3, 'Grande (27-45 Kg)'],
            [4, 'Gigante (> 45 Kgs)'],
        ]);

        //**********************************************************************

        // ANIMAL_AGE
        $this->batchInsert('animal_age', ['id', 'description'], [
            [1, 'Jovem (0 – 2 anos)'],
            [2, 'Adulto (3 – 8 anos)'],
            [3, 'Sénior (> 9 anos)'],
        ]);

        //**********************************************************************

        // VACCINATION
        $this->batchInsert('vaccination', ['id', 'description'], [
            [1, 'Completa'],
            [2, 'Parcial'],
            [3, 'Não Vacinado'],
        ]);

        //**********************************************************************

        // ROLES
        $this->batchInsert('roles', ['id', 'description'], [
            [1, 'Administrador'],
            [2, 'User Pro'],
            [3, 'User'],
        ]);

        //**********************************************************************
    }

    public function down()
    {

        $this->delete('animal_size', ['id' => [1, 2, 3, 4]]);

        $this->delete('animal_age', ['id' => [1, 2, 3]]);

        $this->delete('vaccination', ['id' => [1, 2, 3]]);

        $this->delete('roles', ['id' => [1, 2, 3]]);




        /*
        echo "m251106_225639_populate_Aux_Tables cannot be reverted.\n";

        return false;
        */
    }

}
