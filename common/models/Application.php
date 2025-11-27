<?php

namespace common\models;

use Yii;
use yii\db\JsonExpression;

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
 * @property string|null $data
 *
 * @property Animal $animal
 * @property User $targetUser
 * @property User $user
 */
class Application extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'application';
    }

    public function rules()
    {
        return [
            [['description', 'type', 'created_at', 'target_user_id', 'data'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 0],
            [['status', 'user_id', 'animal_id', 'type', 'target_user_id'], 'integer'],
            [['user_id', 'animal_id'], 'required'],
            [['created_at', 'data'], 'safe'],
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
        ];
    }

    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }

    public function getTargetUser()
    {
        return $this->hasOne(User::class, ['id' => 'target_user_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    //Para ser mais fácil perceber quem é candidato, quem é o dono do animal na BD, e fazer a distinção no SearchController:
    public function getCandidate()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getAnimalOwner()
    {
        return $this->hasOne(User::class, ['id' => 'target_user_id']);
    }

    public function beforeSave($insert)
    {
        $data = $this->data;

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            $data = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        } elseif (!is_array($data)) {
            $data = [];
        }

        foreach (['home','timeAlone','children','bills','followUp','age'] as $k) {
            if (array_key_exists($k, $data) && $data[$k] !== '') {
                if (is_numeric($data[$k])) $data[$k] = (int)$data[$k];
            }
        }

        // Deixa o driver tratar do JSON
        $this->data = new JsonExpression($data);

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        // Em geral já vem array; se vier string por algum motivo, decodifica:
        if (is_string($this->data)) {
            $decoded = json_decode($this->data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->data = $decoded;
            }
        }
        parent::afterFind();
    }

}
