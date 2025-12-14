<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "listing".
 *
 * @property int $id
 * @property string|null $description
 * @property int $animal_id
 * @property int $user_id
 * @property int|null $views
 * @property int $status
 * @property string|null $created_at
 *
 * @property Animal $animal
 * @property Comment[] $comments
 * @property User $user
 * @property Visit[] $visits
 */
class Listing extends \yii\db\ActiveRecord
{


    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_DELETED  = 2;
    const STATUS_DEAD     = 3;
    const STATUS_ADOPTED  = 4;

    public static function getStatusLabels()
    {
        return [
            self::STATUS_INACTIVE => 'Inativo, não publicar anúncio.',
            self::STATUS_ACTIVE   => 'Ativo, publicar anúncio.',
            self::STATUS_DELETED  => 'Apagado',
            self::STATUS_DEAD     => 'Falecido',
            self::STATUS_ADOPTED  => 'Adotado',
        ];
    }

    public function getStatusLabel()
    {
        return self::getStatusLabels()[$this->status] ?? 'Desconhecido';
    }


    public static function getAllowedStatusesForUser()
    {
        return [
            self::STATUS_INACTIVE => 'Inativo, não publicar anúncio.',
            self::STATUS_ACTIVE   => 'Ativo, publicar anúncio.',
            self::STATUS_ADOPTED  => 'Adotado',
        ];
    }

    public static function getAllowedStatusesForUserPro()
    {
        return [
            self::STATUS_INACTIVE => 'Inativo, não publicar anúncio.',
            self::STATUS_ACTIVE   => 'Ativo, publicar anúncio.',
            self::STATUS_DEAD     => 'Falecido',
            self::STATUS_ADOPTED  => 'Adotado',

        ];
    }




    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'listing';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'created_at'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 0],
            [['description'], 'string'],
            [['animal_id', 'user_id'], 'required'],
            [['animal_id', 'user_id', 'views', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['animal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::class, 'targetAttribute' => ['animal_id' => 'id']],
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
            'description' => 'Description',
            'animal_id' => 'Animal ID',
            'user_id' => 'User ID',
            'views' => 'Views',
            'status' => 'Status',
            'created_at' => 'Created At',
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
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['listing_id' => 'id']);
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

    /**
     * Gets query for [[Visits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(Visit::class, ['listing_id' => 'id']);
    }
}
