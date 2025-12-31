<?php

namespace backend\modules\api\models;

use Yii;

class Comment extends \common\models\Comment
{
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getListing()
    {
        return $this->hasOne(Listing::class, ['id' => 'listing_id']);
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'animal_id' => function () {
                return $this->listing->animal_id ?? null;
            },
            'user_id',
            'comment_text' => 'text',
            'comment_date' => 'created_at',
            'name_user' => function () {
                return $this->user->name ?? null;
            },
            'avatar_user' => function () {
                return $this->user->profileImage?->path;
            },
        ];
    }


    public function extraFields()
    {
        return [];
    }



}
