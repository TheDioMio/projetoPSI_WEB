<?php

use yii\db\Migration;
use common\models\User;
class m251207_020355_create_user_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Verificar se já existe
        $existing = User::findOne(['username' => 'admin']);
        if ($existing) {
            echo "Admin already exists. Skipping creation.\n";
            return;
        }

        $user = new User();
        $user->id = 1; // força ID = 1
        $user->username = 'admin';
        $user->name = 'admin';
        $user->email = 'admin@example.com';
        $user->setPassword('123456789');
        $user->generateAuthKey();

        // IMPORTANTE: permitir definir ID manualmente
        $user->isNewRecord = true;

        if (!$user->save(false)) { // false para ignorar validações se necessário
            throw new \Exception("Failed to create admin user");
        }

        echo "Admin user created with ID: {$user->id}\n";
    }

    public function safeDown()
    {
        $user = User::findOne(1);

        if ($user) {
            $user->delete();
            echo "Admin user deleted.\n";
        }
    }


}
