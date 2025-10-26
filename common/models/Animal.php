<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "animal".
 *
 * @property int $id
 * @property string|null $description
 * @property int|null $size
 * @property int|null $age
 * @property int $animal_type_id
 * @property int|null $breed_id
 * @property int|null $vaccines
 * @property int|null $neutered
 * @property string|null $location
 * @property int|null $user_id
 * @property string|null $created_at
 *
 * @property AnimalType $animalType
 * @property Application[] $applications
 * @property Breed $breed
 * @property File[] $files
 * @property Listing[] $listings
 * @property User $user
 * @property Visit[] $visits
 */
class Animal extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'animal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'size', 'age', 'breed_id', 'vaccines', 'location', 'user_id', 'created_at'], 'default', 'value' => null],
            [['neutered'], 'default', 'value' => 0],
            [['description'], 'string'],
            [['size', 'age', 'animal_type_id', 'breed_id', 'vaccines', 'neutered', 'user_id'], 'integer'],
            [['animal_type_id'], 'required'],
            [['created_at'], 'safe'],
            [['location'], 'string', 'max' => 150],
            [['animal_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AnimalType::class, 'targetAttribute' => ['animal_type_id' => 'id']],
            [['breed_id'], 'exist', 'skipOnError' => true, 'targetClass' => Breed::class, 'targetAttribute' => ['breed_id' => 'id']],
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
            'size' => 'Size',
            'age' => 'Age',
            'animal_type_id' => 'Animal Type ID',
            'breed_id' => 'Breed ID',
            'vaccines' => 'Vaccines',
            'neutered' => 'Neutered',
            'location' => 'Location',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[AnimalType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalType()
    {
        return $this->hasOne(AnimalType::class, ['id' => 'animal_type_id']);
    }

    /**
     * Gets query for [[Applications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::class, ['animal_id' => 'id']);
    }

    /**
     * Gets query for [[Breed]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBreed()
    {
        return $this->hasOne(Breed::class, ['id' => 'breed_id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['animal_id' => 'id']);
    }

    /**
     * Gets query for [[Listings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListings()
    {
        return $this->hasMany(Listing::class, ['animal_id' => 'id']);
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
        return $this->hasMany(Visit::class, ['animal_id' => 'id']);
    }

}
