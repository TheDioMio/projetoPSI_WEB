<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "visit".
 *
 * @property int $id
 * @property string $visit_date
 * @property string $start_time
 * @property string|null $end_time
 * @property int $user_id
 * @property int $animal_id
 * @property int $listing_id
 * @property int|null $shelter_id
 * @property string $visit_name
 * @property int $status
 *
 * @property Animal $animal
 * @property Listing $listing
 * @property User $user
 */
class Visit extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['end_time', 'shelter_id'], 'default', 'value' => null],
            [['visit_date', 'start_time', 'user_id', 'animal_id', 'listing_id', 'visit_name', 'status'], 'required'],
            [['visit_date', 'start_time', 'end_time'], 'safe'],
            [['user_id', 'animal_id', 'listing_id', 'shelter_id', 'status'], 'integer'],
            [['visit_name'], 'string', 'max' => 150],
            [['animal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::class, 'targetAttribute' => ['animal_id' => 'id']],
            [['listing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Listing::class, 'targetAttribute' => ['listing_id' => 'id']],
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
            'visit_date' => 'Visit Date',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'user_id' => 'User ID',
            'animal_id' => 'Animal ID',
            'listing_id' => 'Listing ID',
            'shelter_id' => 'Shelter ID',
            'visit_name' => 'Visit Name',
            'status' => 'Status',
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
     * Gets query for [[Listing]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListing()
    {
        return $this->hasOne(Listing::class, ['id' => 'listing_id']);
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
