<?php

namespace backend\modules\api\models;

use Yii;


class Listing extends \common\models\Listing
{
    public function fields()
    {
        return [
            'id',
            'description',
            'views',
            'status',
            'created_at',
            'comments',
        ];
    }

    public function extraFields()
    {
        return [];
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['listing_id' => 'id'])
            ->orderBy(['created_at' => SORT_DESC]);
    }



}
