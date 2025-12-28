<?php

namespace common\models;

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
 * @property User $receiverUser
 * @property User $senderUser
 */
class Message extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'default', 'value' => null],
            [['isRead'], 'default', 'value' => 0],
            [['text', 'sender_user_id', 'receiver_user_id', 'subject'], 'required'],
            [['sender_user_id', 'receiver_user_id', 'isRead'], 'integer'],
            [['created_at'], 'safe'],
            [['text', 'subject'], 'string', 'max' => 255],
            [['receiver_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['receiver_user_id' => 'id']],
            [['sender_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['sender_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'sender_user_id' => 'Sender User ID',
            'receiver_user_id' => 'Receiver User ID',
            'created_at' => 'Created At',
            'isRead' => 'Is Read',
            'subject' => 'Subject',
        ];
    }

    /**
     * Gets query for [[ReceiverUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverUser()
    {
        return $this->hasOne(User::class, ['id' => 'receiver_user_id']);
    }

    /**
     * Gets query for [[SenderUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSenderUser()
    {
        return $this->hasOne(User::class, ['id' => 'sender_user_id']);
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
