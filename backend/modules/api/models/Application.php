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
class Application extends \common\models\Application
{
    public function fields()
    {
        // 1. CAMPOS COMUNS (Vão sempre, seja qual for o tipo)
        $fields = [
            'id',
            'status',
            'type',
            'created_at',
            'statusDate',
            'isRead',
            'description',

            // O user ID retorna o username de quem fez a candidatura
            'user_id' => function () {
                return $this->user ? $this->user->username : null;
            },

            // target user retorna o utilizador que é o destinatário
            'target_user_id' => function () {
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
