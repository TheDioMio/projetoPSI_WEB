<?php

use yii\db\Migration;

class m251022_183526_create_petpanion_tables extends Migration
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
        echo "m251022_183526_create_petpanion_tables cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

        // USER
        /*$this->createTable('user', [
            *'id' => $this->primaryKey(),
            'name' => $this->string(120)->notNull(),
            *'email' => $this->string(150)->notNull()->unique(),
            *'username' => $this->string(60)->notNull()->unique(),
            *'password' => $this->string(255)->notNull(),
            'address' => $this->string(255)->notNull(),
        ], $tableOptions);
*/
        $this->addColumn('user', 'name', $this->string(120)->notNull()->after('id'));
        $this->addColumn('user', 'address', $this->string(255)->notNull()->after('password_hash'));

        // ANIMAL_TYPE
        $this->createTable('animal_type', [
            'id' => $this->primaryKey(),
            'description' => $this->string(100)->notNull(),
        ], $tableOptions);

        // BREED
        $this->createTable('breed', [
            'id' => $this->primaryKey(),
            'description' => $this->string(120)->notNull(),
            'animal_type_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // ANIMAL
        $this->createTable('animal', [
            'id' => $this->primaryKey(),
            'description' => $this->text(),
            'size' => $this->integer(),
            'age' => $this->integer(),
            'animal_type_id' => $this->integer()->notNull(),
            'breed_id' => $this->integer(),
            'vaccines' => $this->integer(),
            'neutered' => $this->boolean()->defaultValue(false),
            'location' => $this->string(150),
            'user_id' => $this->integer(),
            'created_at' => $this->dateTime(),
        ], $tableOptions);

        // LISTING
        $this->createTable('listing', [
            'id' => $this->primaryKey(),
            'description' => $this->text(),
            'animal_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'views' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime(),
        ], $tableOptions);

        // COMMENT
        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'listing_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'text' => $this->text(),
            'created_at' => $this->dateTime(),
        ], $tableOptions);

        // FILE
        $this->createTable('file', [
            'id' => $this->primaryKey(),
            'animal_id' => $this->integer(),
            'user_id' => $this->integer(),
            'path' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime(),
        ], $tableOptions);

        // APPLICATION
        $this->createTable('application', [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'description' => $this->string(255),
            'user_id' => $this->integer()->notNull(),
            'animal_id' => $this->integer()->notNull(),
            'type' => $this->integer(),
            'created_at' => $this->dateTime(),
            'target_user_id' => $this->integer(),
        ], $tableOptions);

        // MESSAGE
        $this->createTable('message', [
            'id' => $this->primaryKey(),
            'text' => $this->string(255)->notNull(),
            'sender_user_id' => $this->integer()->notNull(),
            'receiver_user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
        ], $tableOptions);

        // VISIT
        $this->createTable('visit', [
            'id' => $this->primaryKey(),
            'visit_date' => $this->date()->notNull(),
            'start_time' => $this->time()->notNull(),
            'end_time' => $this->time(),
            'user_id' => $this->integer()->notNull(),
            'animal_id' => $this->integer()->notNull(),
            'listing_id' => $this->integer()->notNull(),
            'shelter_id' => $this->integer(),
            'visit_name' => $this->string(150)->notNull(),
            'status' => $this->integer()->notNull(),
        ], $tableOptions);

        // Foreign Keys
        $this->addForeignKey('fk_breed_animal_type', 'breed', 'animal_type_id', 'animal_type', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_animal_animal_type', 'animal', 'animal_type_id', 'animal_type', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_animal_breed', 'animal', 'breed_id', 'breed', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_animal_user', 'animal', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE');

        $this->addForeignKey('fk_listing_animal', 'listing', 'animal_id', 'animal', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_listing_user', 'listing', 'user_id', 'user', 'id', 'RESTRICT', 'CASCADE');

        $this->addForeignKey('fk_comment_listing', 'comment', 'listing_id', 'listing', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_comment_user', 'comment', 'user_id', 'user', 'id', 'RESTRICT', 'CASCADE');

        $this->addForeignKey('fk_file_animal', 'file', 'animal_id', 'animal', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_file_user', 'file', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE');

        $this->addForeignKey('fk_application_animal', 'application', 'animal_id', 'animal', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_application_user', 'application', 'user_id', 'user', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_application_target_user', 'application', 'target_user_id', 'user', 'id', 'SET NULL', 'CASCADE');

        $this->addForeignKey('fk_message_sender', 'message', 'sender_user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_message_receiver', 'message', 'receiver_user_id', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk_visit_animal', 'visit', 'animal_id', 'animal', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_visit_listing', 'visit', 'listing_id', 'listing', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_visit_user', 'visit', 'user_id', 'user', 'id', 'RESTRICT', 'CASCADE');

    }

    public function down()
    {
        /*echo "m251022_183526_create_petpanion_tables cannot be reverted.\n";
        return false;*/
        $this->dropTable('visit');
        $this->dropTable('message');
        $this->dropTable('application');
        $this->dropTable('file');
        $this->dropTable('comment');
        $this->dropTable('listing');
        $this->dropTable('animal');
        $this->dropTable('breed');
        $this->dropTable('animal_type');
        $this->dropColumn('user', 'name');
        $this->dropColumn('user', 'address');
    }

}
