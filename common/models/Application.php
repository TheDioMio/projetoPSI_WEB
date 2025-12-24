<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\JsonExpression;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int $status
 * @property string|null $description
 * @property int $user_id
 * @property int $animal_id
 * @property int|null $type
 * @property string|null $created_at
 * @property int|null $target_user_id
 * @property string|null $data
 *
 * @property Animal $animal
 * @property User $targetUser
 * @property User $user
 */
class Application extends ActiveRecord
{

    public static function homeOptions(): array
    {
        return [
            1 => 'Própria',
            2 => 'Arrendada (Senhorio autoriza animais)',
            3 => 'Arrendada (Senhorio não autoriza animais)',
        ];
    }

    public function getHomeLabel(): string {
        return self::homeOptions()[$this->data['home'] ?? null] ?? '-';
    }

    public function getAreaLabel(): string {
        return self::getAreasAtuacao()[$this->data['area'] ?? null] ?? '-';
    }

    public function getExperienceLevelLabel(): string{
        return self::getAnosExperiencia()[$this->data['experience_level'] ?? null] ?? '—';
    }

    public function getAvailabilityLabel(): string{
        return self::getDisponibilidade()[$this->data['availability'] ?? null] ?? '-';
    }

    public static function timeAloneOptions(): array
    {
        return [
            0 => 'Menos de 4 Horas',
            1 => 'Entre 4 a 8 Horas',
            2 => 'Mais de 8 Horas',
        ];
    }

    public function getTimeAloneLabel(): string
    {
        return self::timeAloneOptions()[$this->data['timeAlone'] ?? null] ?? '—';
    }

    public static function yesNoOptions(): array
    {
        return [
            1 => 'Sim',
            0 => 'Não',
        ];
    }

    public function getYesNoLabel($value): string
    {
        return self::yesNoOptions()[$value] ?? '—';
    }



    //Isto aqui é para os diferentes cenários de cada candidatura
    const SCENARIO_ADOPTION = 'adoption';
    const SCENARIO_USER_PRO = 'user_pro';

    //Status da candidatura= 0 => SENT, 1 => in_review, 2 => approved, 3 => rejected, 4 => cancelled.


    // Estados principais
    const STATUS_SENT        = 0; // enviada (nunca aberta)
    const STATUS_IN_REVIEW   = 1; // aberta por quem recebe
    const STATUS_APPROVED    = 2;
    const STATUS_REJECTED    = 3;
    const STATUS_CANCELLED   = 4;


    const READ_NO  = 0;
    const READ_YES = 1;



    //Tipos de candidatura (isto vai para a coluna 'type' dentro da nossa tabela "Application")
    const TYPE_ADOPTION = 1;
    const TYPE_USER_PRO = 2;

    public static function tableName()
    {
        return 'application';
    }

    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_SENT      => 'Pendente',
            self::STATUS_IN_REVIEW => 'Em análise',
            self::STATUS_APPROVED  => 'Aprovada',
            self::STATUS_REJECTED  => 'Rejeitada',
            self::STATUS_CANCELLED => 'Cancelada',
        ];
    }

    public function getStatusLabel(): string
    {
        return self::getStatusLabels()[$this->status] ?? '—';
    }

    // estados que aplicamos a consoante onde estamos
    public static function getAllowedTransitions(): array
    {
        return [
            self::STATUS_SENT => [
                self::STATUS_IN_REVIEW,   // quando quem recebe abre
                self::STATUS_CANCELLED,   // quando o candidato cancela
            ],
            self::STATUS_IN_REVIEW => [
                self::STATUS_APPROVED,
                self::STATUS_REJECTED,
            ],
            // NÃO têm transições
            self::STATUS_APPROVED  => [],
            self::STATUS_REJECTED  => [],
            self::STATUS_CANCELLED => [],
        ];
    }


    //aqui alteramos o estado
    public function canChangeStatusTo(int $newStatus): bool
    {
        $allowed = self::getAllowedTransitions()[$this->status] ?? [];
        return in_array($newStatus, $allowed, true);
    }


    // quando criamos um novo registo iniciamos os estados no default
    public function init()
    {
        parent::init();


        //Igor, tive que comentar isto porque se não o ApplicationSearch explodia
//        if ($this->isNewRecord && $this->created_at === null) {
//            $this->created_at = date('Y-m-d');
//            $this->isRead = self::READ_NO;
//        }
    }


    // utilizamos para marcar uma candidatura com lida e mudar o estado para "em análise"
    public function markAsRead(): bool
    {
        if ($this->isRead == self::READ_YES) {
            return true;
        }

        $this->isRead = self::READ_YES;

        if ($this->status === self::STATUS_SENT) {
            $this->status = self::STATUS_IN_REVIEW;
        }

        return $this->save(false);
    }

    // funcao chamada para aprovar uma candidatura
    public function approve(): bool
    {
        if (!$this->canChangeStatusTo(self::STATUS_APPROVED)) {
            return false;
        }

        $this->status = self::STATUS_APPROVED;
        $this->statusDate = date('Y-m-d');

        if (!$this->save(false)) {
            return false;
        }

        $this->sendStatusMessage(
            'Candidatura aprovada',
            "A sua candidatura para o animal {$this->animal->name} foi aprovada.\nEntraremos em contacto consigo em breve."
        );

        return true;
    }

    // funcao chamada para rejeitar uma cndidatura
    public function reject(): bool
    {
        if (!$this->canChangeStatusTo(self::STATUS_REJECTED)) {
            return false;
        }

        $this->status = self::STATUS_REJECTED;
        $this->statusDate = date('Y-m-d');

        if (!$this->save(false)) {
            return false;
        }

        $this->sendStatusMessage(
            'Candidatura rejeitada',
            "Lamentamos informar que a sua candidatura para o animal {$this->animal->name} não foi aprovada."
        );

        return true;

    }

    //funcao chamada para cancelar uma candidatura
    public function cancel(): bool
    {
        if (!$this->canChangeStatusTo(self::STATUS_CANCELLED)) {
            return false;
        }

        $this->status = self::STATUS_CANCELLED;
        return $this->save(false);
    }

    protected function sendStatusMessage($subject, $text)
    {
        $message = new Message();
        $message->sender_user_id = Yii::$app->user->id; // quem aprova/rejeita
        $message->receiver_user_id = $this->user_id;    // candidato
        $message->subject = $subject;
        $message->text = $text;
        $message->created_at = date('Y-m-d H:i:s');
        $message->isRead = 0;

        $message->save(false);
    }


    //ISTO É IMPORTANTE PARA OS CENÁRIOS FUNCIONAREM!
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // No cenário USER_PRO, permitimos guardar estes campos:
        $scenarios[self::SCENARIO_USER_PRO] = ['user_id', 'type', 'status', 'created_at', 'description', 'data'];

        return $scenarios;
    }

    public function rules()
    {
        return [
            [['description', 'type', 'created_at', 'target_user_id', 'data'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 0],
            [['status', 'user_id', 'animal_id', 'type', 'target_user_id'], 'integer'],

            //user ID é SEMPRE obrigatório, independentemente do cenário
            [['user_id'], 'required'],
            //animal_id só é obrigatório no cenário de ADOÇÃO (ou default)
            [['animal_id'], 'required', 'on' => [self::SCENARIO_DEFAULT, self::SCENARIO_ADOPTION]],

            [['created_at', 'data', 'statusDate'], 'safe'],
            [['description'], 'string', 'min' => 3, 'max' => 120, 'tooShort' => 'O nome é demasiado curto!'],
            [['animal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::class, 'targetAttribute' => ['animal_id' => 'id']],
            [['target_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['target_user_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['isRead'], 'default', 'value' => 0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'description' => 'Description',
            'user_id' => 'User ID',
            'animal_id' => 'Animal ID',
            'type' => 'Type',
            'created_at' => 'Created At',
            'target_user_id' => 'Target User ID',
            'data' => 'Data',
            'statusDate' => 'Data do estado',
            'isRead' => 'isRead',
        ];
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

    //Para ser mais fácil perceber quem é candidato, quem é o dono do animal na BD, e fazer a distinção no SearchController:
    public function getCandidate()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getAnimalOwner()
    {
        return $this->hasOne(User::class, ['id' => 'target_user_id']);
    }

    public function beforeSave($insert)
    {
        $data = $this->data;

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            $data = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        } elseif (!is_array($data)) {
            $data = [];
        }

        foreach (['home','timeAlone','children','bills','followUp','age'] as $k) {
            if (array_key_exists($k, $data) && $data[$k] !== '') {
                if (is_numeric($data[$k])) $data[$k] = (int)$data[$k];
            }
        }

        // Deixa o driver tratar do JSON
        $this->data = new JsonExpression($data);

        if ($this->isAttributeChanged('status')) {
            $this->statusDate = date('Y-m-d');
        }


        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        // Em geral já vem array; se vier string por algum motivo, decodifica:
        if (is_string($this->data)) {
            $decoded = json_decode($this->data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->data = $decoded;
            }
        }
        parent::afterFind();
    }

    public static function getAreasAtuacao()
    {
        return [
            1 => 'Clínica Veterinária',
            2 => 'Canil / Abrigo',
            3 => 'Outro',
        ];
    }

    public static function getAnosExperiencia()
    {
        return [
            1 => 'Menos de 1 ano',
            2 => '1 a 3 anos',
            3 => '3 a 5 anos',
            4 => 'Mais de 5 anos'
        ];
    }

    public static function getDisponibilidade() {
        return [
            1 => 'Tempo Inteiro (Comercial)',
            2 => 'Part-time',
            3 => 'Apenas Fins de Semana',
            4 => 'Apenas por Marcação',
        ];
    }
}
