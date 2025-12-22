<?php

namespace common\models;

use backend\mosquitto\MosquittoCatcher;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "animal".
 *
 * @property int $id
 * @property int|null $age_id
 * @property int|null $size_id
 * @property int|null $vaccination_id
 * @property string|null $description
 * @property int $animal_type_id
 * @property int|null $breed_id
 * @property int|null $neutered
 * @property string|null $location
 * @property int|null $user_id
 * @property string|null $created_at
 * @property string|null $name
 *
 * @property AnimalAge $age
 * @property AnimalType $animalType
 * @property Application[] $applications
 * @property Breed $breed
 * @property File[] $files
 * @property Listing[] $listings
 * @property AnimalSize $size
 * @property User $user
 * @property Vaccination $vaccination
 * @property Visit[] $visits
 */
class Animal extends ActiveRecord
{
    /**
     * {@inheritdoc}
     * @var UploadedFile[]
     */
    public static function tableName()
    {
        return 'animal';
    }

    const STATUS_ADOPTED  = 4;
    const STATUS_DEAD     = 3;
    const STATUS_DELETED  = 2;
    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 0;


    public $imageFiles;

    public function extraFields()
    {
        return [
            'files',
            'listing',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['age_id', 'size_id', 'vaccination_id', 'description', 'breed_id', 'location', 'user_id', 'created_at', 'name', 'observations'], 'default', 'value' => null],
            [['neutered'], 'default', 'value' => 0],
            [['age_id', 'size_id', 'vaccination_id', 'animal_type_id', 'breed_id', 'neutered', 'user_id'], 'integer'],
            [['description'], 'string'],
            [['animal_type_id', 'name', 'age_id', 'size_id', 'user_id', 'breed_id', 'vaccination_id', 'location'], 'required'],
            [['created_at', 'statusDate'], 'safe'],
            [['location'], 'string', 'max' => 150],
            [['observations'], 'string', 'max' => 120],
            [['name'], 'string', 'max' => 50],
            [['age_id'], 'exist', 'skipOnError' => true, 'targetClass' => AnimalAge::class, 'targetAttribute' => ['age_id' => 'id']],
            [['animal_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AnimalType::class, 'targetAttribute' => ['animal_type_id' => 'id']],
            [['breed_id'], 'exist', 'skipOnError' => true, 'targetClass' => Breed::class, 'targetAttribute' => ['breed_id' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => AnimalSize::class, 'targetAttribute' => ['size_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['vaccination_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vaccination::class, 'targetAttribute' => ['vaccination_id' => 'id']],

            [['imageFiles'], 'file',
                'skipOnEmpty' => (true),//obriga a carregar uma imagem
                'on' => ['create'],//mas só obriga no scenario create
                'extensions' => 'png, jpg, jpeg', // Tipos de ficheiro permitidos
                'maxFiles' => 5, // Permite o upload de 1 a 5 ficheiros
                'tooMany' => 'Só pode carregar um máximo de 5 fotos.',
                'maxSize' => 1024 * 1024 * 5, // 5MB por ficheiro (exemplo)
            ],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        // Cenário CREATE → obriga a ter fotos
        $scenarios['create'] = $scenarios[self::SCENARIO_DEFAULT];
        $scenarios['create'][] = 'imageFiles';

        // Cenário UPDATE → NÃO obriga a ter fotos
        $scenarios['update'] = $scenarios[self::SCENARIO_DEFAULT];

        return $scenarios;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'age_id' => 'Age ID',
            'size_id' => 'Size ID',
            'vaccination_id' => 'Vaccination ID',
            'description' => 'Description',
            'animal_type_id' => 'Tipo de animal',
            'breed_id' => 'Raça',
            'neutered' => 'Neutered',
            'location' => 'Location',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'observations' => 'Observations',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Age]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalAge()
    {
        return $this->hasOne(AnimalAge::class, ['id' => 'age_id']);
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
     * Gets query for [[Applications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::class, ['animal_id' => 'id']);
    }

    /**
     * Gets query for [[Breed]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBreed()
    {
        return $this->hasOne(Breed::class, ['id' => 'breed_id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['animal_id' => 'id']);
    }


    /**
     * Gets query for [[Listings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListing()
    {
        return $this->hasOne(Listing::class, ['animal_id' => 'id']);
    }

    /**
     * Gets query for [[Size]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(AnimalSize::class, ['id' => 'size_id']);
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

    /**
     * Gets query for [[Vaccination]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVaccination()
    {
        return $this->hasOne(Vaccination::class, ['id' => 'vaccination_id']);
    }

    /**
     * Gets query for [[Visits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(Visit::class, ['animal_id' => 'id']);
    }

    public function getAge()
    {
        return $this->hasOne(AnimalAge::class, ['id' => 'age_id']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'), // usa timestamp do MySQL
            ],
        ];
    }

    public function beforeSave($insert)
    {
        // preencher o campo statusDate no insert ou sempre que o status for alterado
        if (parent::beforeSave($insert)) {

            if ($insert || $this->isAttributeChanged('status')) {
                $this->statusDate = date('Y-m-d');
            }

            return true;
        }

        return false;
    }


    public function getPrimaryImage()
    {

        return $this->hasOne(File::class, ['animal_id' => 'id'])
            ->where (['type_id' => 1])
            ->orderBy(['animal_id' => SORT_ASC]); // Ou outro critério de ordenação/prioridade
    }




    /*******************temporario***************************************/



    /*
    public function afterSave($insert, $changedAttributes)
    {
        try {
            if (!$insert) {
                if (isset($changedAttributes['is_active'])) {
                    return;
                }
            }

            $myObj = new \stdClass();

            $myObj->id = $this->id;


            $myJSON = json_encode($myObj);

            if ($insert)
                MosquittoCatcher::makePublish('NEW_POSTED_ANIMAL', $myJSON);

        } catch (\Exception $e){
            return;
        }
    }

    */


    //Comentado porque não deixava criar anúncio
//    public function afterSave($insert, $changedAttributes)
//    {
//        if ($insert) {
//            MosquittoCatcher::makePublish(
//                'NEW_POSTED_ANIMAL',
//                json_encode(['id' => $this->id])
//            );
//        }
//    }






    /*******************temporario***************************************/

}
