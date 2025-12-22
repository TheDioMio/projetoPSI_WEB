<?php

namespace backend\modules\api\models;

use common\models\Animal;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int $status
 * @property string|null $description
 * @property int $user_id
 * @property int|null $animal_id
 * @property int|null $type
 * @property string|null $created_at
 * @property int|null $target_user_id
 * @property string|null $data
 * @property string $statusDate
 * @property int|null $isRead
 *
 * @property Animal $animal
 * @property User $targetUser
 * @property User $user
 */
class Application extends ActiveRecord
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
            [['description', 'animal_id', 'type', 'created_at', 'target_user_id', 'data'], 'default', 'value' => null],
            [['isRead'], 'default', 'value' => 0],
            [['statusDate'], 'default', 'value' => '2025-12-15'],
            [['status', 'user_id', 'animal_id', 'type', 'target_user_id', 'isRead'], 'integer'],
            [['user_id'], 'required'],
            [['created_at', 'data', 'statusDate'], 'safe'],
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
            'data' => 'Data',
            'statusDate' => 'Status Date',
            'isRead' => 'Is Read',
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
