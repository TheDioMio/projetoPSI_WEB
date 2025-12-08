<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $listing_id
 * @property int $user_id
 * @property string|null $text
 * @property string|null $created_at
 * @property string|null $created_time
 *
 * @property Listing $listing
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'created_at'], 'default', 'value' => null],
            [['listing_id', 'user_id'], 'required'],
            [['listing_id', 'user_id'], 'integer'],
            [['text'], 'string'],
            [['created_at'], 'safe'],
            [['listing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Listing::class, 'targetAttribute' => ['listing_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['text'], 'required', 'message' => 'O comentário não pode ser vazio.'],

            ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'listing_id' => 'Listing ID',
            'user_id' => 'User ID',
            'text' => 'Text',
            'created_at' => 'Created At',
            'created_time' => 'Created Time',
            'subject' => 'Subject',
        ];
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

    public function getUserImage()
    {
        return $this->hasOne(File::class, ['user_id' => 'user_id'])
            ->andOnCondition(['type_id' => 2]); // type 2 = foto de user
    }
}
