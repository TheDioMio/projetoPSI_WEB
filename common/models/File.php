<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property int|null $animal_id
 * @property int|null $user_id
 * @property string $path
 * @property string|null $created_at
 * @property string|null $type
 *
 * @property Animal $animal
 * @property User $user
 * @property int $animal_type_id
 * @property UploadedFile $imageFile
 */


class File extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     * @var $imageFile
     */
    public $imageFile;
    public function upload()
    {
        if ($this->validate()) {
            $result = false;
            foreach ($this->imageFile as $file) {
                $result |= $file->saveAs('uploads/animal/' . $file->baseName . '.' . $file->extension);

            }
            return $result;
        } else {
            dd($this->errors);
            return false;
        }
    }


    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['animal_id', 'user_id', 'created_at', 'type'], 'default', 'value' => null],
            [['animal_id', 'user_id'], 'integer'],
            [['path'], 'required'],
            [['created_at'], 'safe'],
            [['path'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 50],
            [['animal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::class, 'targetAttribute' => ['animal_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
           // [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 5],
           // [['profileImage'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
           // [['documentFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, docx', 'maxFiles' => 3],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'animal_id' => 'Animal ID',
            'user_id' => 'User ID',
            'path' => 'Path',
            'created_at' => 'Created At',
            'type' => 'Type',
        ];
    }

    /**
     * Gets query for [[Animal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
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

    public function getUrl(): string
    {
        return $this->path;
    }

}
