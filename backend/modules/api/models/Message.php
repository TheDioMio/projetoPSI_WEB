<?php

namespace backend\modules\api\models;

use backend\mosquitto\MosquittoCatcher;
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
            'sender_username' => function ($model) {
                return $model->senderUser ? $model->senderUser->username : null;
            },
            'receiver_username' => function ($model) {
                return $model->receiverUser ? $model->receiverUser->username : null;
            },
        ];

        return $fields;
    }

    public function getSenderUser()
    {
        return $this->hasOne(\backend\modules\api\models\User::class, ['id' => 'sender_user_id']);
    }

    public function getReceiverUser()
    {
        return $this->hasOne(\backend\modules\api\models\User::class, ['id' => 'receiver_user_id']);
    }

    public function extraFields()
    {
        return [
            'senderUser',
            'receiverUser',
        ];
    }



    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $topic = 'users/' . $this->receiver_user_id . '/NEW_MESSAGE';

            MosquittoCatcher::makePublish(
                $topic,
                json_encode(['id' => $this->id])
            );
        }
    }


}
