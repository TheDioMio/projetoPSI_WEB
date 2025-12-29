<?php

namespace backend\modules\api\models;

use common\models\Animal;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int $status
 * @property string|null $description
 * @property int $user_id
 * @property int|null $animal_id
 * @property int|null $type
 * @property string|null $created_at
 * @property int|null $target_user_id
 * @property string|null $data
 * @property string $statusDate
 * @property int|null $isRead
 *
 * @property Animal $animal
 * @property User $targetUser
 * @property User $user
 */
class Application extends \common\models\Application {
    //VARIÁVEIS TEMPORÁRIAS PARA RECEBER O JSON DO ANDROID
    //(O Android manda isto solto no POST, não manda dentro de um array 'data')
    public $age;
    public $contact;
    public $motive;
    public $home;
    public $bills;
    public $timeAlone;
    public $children;
    public $followUp;
    public function fields()
    {
        // 1. CAMPOS COMUNS (Vão sempre, seja qual for o tipo)
        $fields = [
            'id',
            'status' => fn() => $this->getStatusLabel(),
            'type',
            'created_at',
            'statusDate',
            'isRead',
            'description',

            // O user ID retorna o username de quem fez a candidatura
            'candidate_name' => function () {
                return $this->user ? $this->user->username : null;
            },

            // target user retorna o utilizador que é o destinatário
            'target_user_name' => function () {
                return $this->targetUser ? $this->targetUser->username : null;
            },
        ];

        // 2. Se o TYPE da candidatura for adoption, manda estes campos:
        if ($this->type == self::TYPE_ADOPTION) {
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

                // Campos específicos do JSON data para adoção
                'age' => fn() => $this->data['age'] ?? null,
                'contact' => fn() => $this->data['contact'] ?? null,
                'motive' => fn() => $this->data['motive'] ?? null,
                'home' => fn() => $this->getHomeLabel(),
                'bills' => fn() => $this->getYesNoLabel($this->data['bills'] ?? null),
                'timeAlone' => fn() => $this->getTimeAloneLabel(),
                'children' => fn() => $this->getYesNoLabel($this->data['children'] ?? null),
                'followUp' => fn() => $this->getYesNoLabel($this->data['followUp'] ?? null),
            ]);
        }

        // 3. Se o TYPE da candidatura for user pro, manda estes campos:
        elseif ($this->type == self::TYPE_USER_PRO) {
            $fields = array_merge($fields, [
                // Campos específicos do JSON data para user pro
                'professional_name' => fn() => $this->data['professional_name'] ?? null,
                'nif' => fn() => $this->data['nif'] ?? null,
                'area' => fn() => $this->getAreaLabel(),
                'experience_level' => fn() => $this->getExperienceLevelLabel(),
                'website' => fn() => $this->data['website'] ?? null,
                'availability' => fn() => $this->getAvailabilityLabel(),
                'bio' => fn() => $this->data['bio'] ?? null,
            ]);
        }
        return $fields;
    }

    /**
     * 2. REGRAS DE VALIDAÇÃO
     * Sem isto, o $model->load() ignora os dados vindos do Android.
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            // Definir estes campos como 'safe' ou com validações específicas
            [['age', 'contact', 'motive', 'home', 'bills', 'timeAlone', 'children', 'followUp'], 'string'],
            // Podes forçar idade a ser int se quiseres, mesmo vindo como string "99" o PHP trata bem
            ['age', 'integer'],
        ]);
    }

    /*
     * 3. ANTES DE GUARDAR OS DADOS RECEBIDOS DO ANDROID NA BD
     * Aqui é convertida a String EX."Sim" -> 1 e guardamos no JSON 'data'
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Se for uma criação ou edição via API e tivermos recebido estes dados
        if ($this->type == self::TYPE_ADOPTION) {

            // Criamos o array para guardar na coluna JSON 'data'
            $dataToSave = [
                'age' => fn() => $this->data['age'] ?? null,
                'contact' => fn() => $this->data['contact'] ?? null,
                'motive' => fn() => $this->data['motive'] ?? null,

                // Conversões Inversas (Texto -> ID/Int)
                'bills' => fn() => $this->getYesNoLabel($this->data['bills'] ?? null),
                'children' => fn() => $this->getYesNoLabel($this->data['children'] ?? null),
                'followUp' => fn() => $this->getYesNoLabel($this->data['followUp'] ?? null),

                // Conversões de Dropdowns específicos
                'home' => fn() => $this->getHomeLabel(),
                'timeAlone' => fn() => $this->getTimeAloneLabel(),
            ];

            // Guardamos na propriedade real da BD
            $this->data = $dataToSave;
        }

        return true;
    }

    // --- FUNÇÕES AUXILIARES DE CONVERSÃO ---

    private function convertSimNaoToInt($value) {
        if ($value === 'Sim' || $value === 'Yes') return 1;
        return 0; // Default para 'Não' ou null
    }

    private function convertHomeTextToInt($text) {
        switch ($text) {
            case 'Apartamento': return 1;
            case 'Casa': return 2;
            case 'Quinta': return 3;
            default: return 1; // Valor default
        }
    }

    private function convertTimeAloneTextToInt($text) {
        switch ($text) {
            case '0 - 4 Horas': return 1;
            case '4 - 8 Horas': return 2;
            case '+ 8 Horas': return 3;
            default: return 1;
        }
    }

    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }

    public function getTargetUser()
    {
        return $this->hasOne(User::class, ['id' => 'target_user_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
