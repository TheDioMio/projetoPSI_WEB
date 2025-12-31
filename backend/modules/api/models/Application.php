<?php
namespace backend\modules\api\models;
use common\models\Animal;
use common\models\User;
use Yii;

class Application extends \common\models\Application {

    // VARIÁVEIS TEMPORÁRIAS (Input do Android)
    public $name;
    public $age;
    public $contact;
    public $motive;
    public $home;
    public $bills;
    public $timeAlone;
    public $children;
    public $followUp;

    // Variáveis do UserPro
    public $professional_name;
    public $nif;
    public $website;
    public $bio;

    // 1. REGRAS: ACEITAM INTEIROS
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'contact', 'motive', 'professional_name', 'nif', 'website', 'bio', 'created_at'], 'string'],
            [['age', 'home', 'bills', 'timeAlone', 'children', 'followUp'], 'integer'],
            [['status', 'type', 'animal_id', 'user_id', 'target_user_id'], 'integer'],
        ]);
    }

    // 2. OUTPUT: CHAMAMOS AS NOVAS FUNÇÕES COM NOMES ÚNICOS
    public function fields()
    {
        $dataJson = [];
        if (is_string($this->data)) {
            $dataJson = json_decode($this->data, true);
        } elseif (is_array($this->data)) {
            $dataJson = $this->data;
        }
        if (!is_array($dataJson)) $dataJson = [];

        $fields = [
            'id',
            'status' => fn() => $this->getStatusLabel(),
            'type',
            'created_at',
            'statusDate',
            'isRead',
            'description',

            'candidate_name' => function () {
                return $this->user ? $this->user->username : 'Desconhecido';
            },
//            'target_user_name' => function () {
//                return $this->targetUser ? $this->targetUser->username : 'Desconhecido';
//            },
            'target_user_name' => function(){
                $animal = Animal::findOne($this->animal_id);
                return $animal->user->username;
            }

        ];

        // Se for ADOÇÃO
        if ($this->type == 1) {
            $fields = array_merge($fields, [
                'animal_id',
                'animal_name' => function () {
                    return $this->animal ? $this->animal->name : null;
                },
                'animal_image' => function () {
                    if ($this->animal && !empty($this->animal->files)) {
                        return $this->animal->files[0]->path;
                    }
                    return null;
                },

                // DADOS DIRETOS
                'age' => fn() => $dataJson['age'] ?? null,
                'name' => fn() => $dataJson['name'] ?? null,
                'contact' => fn() => $dataJson['contact'] ?? null,
                'motive' => fn() => $dataJson['motive'] ?? null,

                // DADOS CONVERTIDOS (INT -> STRING) USANDO NOMES NOVOS
                'home' => function() use ($dataJson) {
                    $val = $dataJson['home'] ?? null;
                    return parent::homeOptions()[$val] ?? null;
                },
                'bills' => function() use ($dataJson) {
                    $val = $dataJson['bills'] ?? null;
                    return parent::yesNoOptions()[$val] ?? null;
                },
                'timeAlone' => function() use ($dataJson) {
                    $val = $dataJson['timeAlone'] ?? null;
                    return parent::timeAloneOptions()[$val] ?? null;
                },
                'children' => function() use ($dataJson) {
                    $val = $dataJson['children'] ?? null;
                    return parent::yesNoOptions()[$val] ?? null;
                },

                'followUp' => function() use ($dataJson) {
                    $val = $dataJson['followUp'] ?? null;
                    return parent::yesNoOptions()[$val] ?? null;
                },
            ]);
        }

        return $fields;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->type == 1) {
            $dataToSave = [
                'age' => $this->age,
                'name' => $this->name,
                'contact' => $this->contact,
                'motive' => $this->motive,
                'home' => $this->home,
                'bills' => $this->bills,
                'timeAlone' => $this->timeAlone,
                'children' => $this->children,
                'followUp' => $this->followUp,
            ];
            $this->data = json_encode($dataToSave);
        }
        return true;
    }

    public function afterFind()
    {
        parent::afterFind(); // Mantém o comportamento base

        // 1. Obter os dados (seja array ou string)
        $dataJson = $this->data;

        // Se vier como string (JSON), descodificar
        if (is_string($dataJson)) {
            $dataJson = json_decode($dataJson, true);
        }

        // 2. Preencher as variáveis públicas com os valores antigos
        if (is_array($dataJson)) {
            $this->age = $dataJson['age'] ?? null;
            $this->name = $dataJson['name'] ?? null;
            $this->contact = $dataJson['contact'] ?? null;
            $this->motive = $dataJson['motive'] ?? null;

            // Tratamento especial para inteiros (Selects/Spinners)
            $this->home = isset($dataJson['home']) ? (int)$dataJson['home'] : null;
            $this->bills = isset($dataJson['bills']) ? (int)$dataJson['bills'] : null;
            $this->timeAlone = isset($dataJson['timeAlone']) ? (int)$dataJson['timeAlone'] : null;
            $this->children = isset($dataJson['children']) ? (int)$dataJson['children'] : null;
            $this->followUp = isset($dataJson['followUp']) ? (int)$dataJson['followUp'] : null;
        }
    }

    // RELAÇÕES
    public function getAnimal() {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }

    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getTargetUser() {
        return $this->hasOne(User::class, ['id' => 'target_user_id']);
    }
}