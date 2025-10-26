<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "animal_type".
 *
 * @property int $id
 * @property string $description
 *
 * @property Animal[] $animals
 * @property Breed[] $breeds
 */
class AnimalType extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'animal_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string', 'max' => 100],
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
        ];
    }

    /**
     * Gets query for [[Animals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimals()
    {
        return $this->hasMany(Animal::class, ['animal_type_id' => 'id']);
    }

    /**
     * Gets query for [[Breeds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBreeds()
    {
        return $this->hasMany(Breed::class, ['animal_type_id' => 'id']);
    }

}
