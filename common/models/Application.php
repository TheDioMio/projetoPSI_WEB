<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int $status
 * @property string|null $description
 * @property int $user_id
 * @property int $animal_id
 * @property int|null $type
 * @property string|null $created_at
 * @property int|null $target_user_id
 *
 * @property Animal $animal
 * @property User $targetUser
 * @property User $user
 */
class Application extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'type', 'created_at', 'target_user_id'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 0],
            [['status', 'user_id', 'animal_id', 'type', 'target_user_id'], 'integer'],
            [['user_id', 'animal_id'], 'required'],
            [['created_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [['animal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::class, 'targetAttribute' => ['animal_id' => 'id']],
            [['target_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['target_user_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'description' => 'Description',
            'user_id' => 'User ID',
            'animal_id' => 'Animal ID',
            'type' => 'Type',
            'created_at' => 'Created At',
            'target_user_id' => 'Target User ID',
        ];
    }

    /**
     * Gets query for [[Animal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }

    /**
     * Gets query for [[TargetUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTargetUser()
    {
        return $this->hasOne(User::class, ['id' => 'target_user_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
