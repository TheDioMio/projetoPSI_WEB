<?php

namespace backend\modules\api\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $text
 * @property int $sender_user_id
 * @property int $receiver_user_id
 * @property string|null $created_at
 * @property int|null $isRead
 * @property string $subject
 *
 * @property User $receiverUser0
 * @property User $senderUser0
 */
class Message extends \common\models\Message
{

    public $modelClass = 'backend\modules\api\models\Message';

    public function fields()
    {
        $fields = [
            'id',
            'subject',
            'text',
            'sender_user_id',
            'receiver_user_id',
            'created_at',
            'isRead',
        ];

        return $fields;
    }

    public function extraFields()
    {
        return [
            'senderUser',
            'receiverUser',
        ];
    }


}
