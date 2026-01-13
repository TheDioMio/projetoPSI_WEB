<?php

namespace backend\modules\api\models;

use common\models\AnimalAge;
use common\models\AnimalSize;
use common\models\AnimalType;
use common\models\Breed;
use common\models\Vaccination;
use Yii;


class Animal extends \common\models\Animal
{

    public function fields()
    {
        return [
            'id',
            'name',
            'description',
            'created_at',
            'location',
            'status',
            'user_id',
            'user_role' => fn() => $this->user?->role_id,
            'age' => function () {
                return $this->animalAge ? $this->animalAge->description : null;
            },

            'size' => function () {
                return $this->size ? $this->size->description : null;
            },

            'type' => function () {
                return $this->animalType ? $this->animalType->description : null;
            },

            'breed' => function () {
                return $this->breed ? $this->breed->description : null;
            },

            'neutered',
            'vaccination' => function () {
                return $this->vaccination ? $this->vaccination->description : null;
            },

            // dados do user dono do animal
            'owner_name' => fn() => $this->user?->name,
            'owner_email' => fn() => $this->user?->email,
            'owner_avatar' => fn() => $this->user?->profileImage?->path,


            // LISTING
//            'listing_description' => fn() => $this->listing?->description,
//            'listing_views' => fn() => $this->listing?->views,
            'files',
        ];
    }

    public function extraFields()
    {
        return [
            'files',
            'listing',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getListing()
    {
        return $this->hasOne(Listing::class, ['animal_id' => 'id']);
    }

    public function getFiles()
    {
        return $this->hasMany(File::class, ['animal_id' => 'id']);
    }
}
