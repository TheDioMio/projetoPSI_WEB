<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "breed".
 *
 * @property int $id
 * @property string $description
 * @property int $animal_type_id
 *
 * @property AnimalType $animalType
 * @property Animal[] $animals
 */
class Breed extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'breed';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'animal_type_id'], 'required'],
            [['animal_type_id'], 'integer'],
            [['description'], 'string', 'max' => 120],
            [['animal_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AnimalType::class, 'targetAttribute' => ['animal_type_id' => 'id']],
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
            'animal_type_id' => 'Animal Type ID',
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
     * Gets query for [[Animals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimals()
    {
        return $this->hasMany(Animal::class, ['breed_id' => 'id']);
    }

}
