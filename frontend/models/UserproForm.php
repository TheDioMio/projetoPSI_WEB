<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Application;

/**
 * ProfessionalForm é o modelo por trás do formulário de candidatura profissional.
 */
class UserproForm extends Model
{
    public $professional_name;
    public $nif;
    public $area_id;
    public $experience_level;
    public $website;
    public $availability;
    public $bio;
    public $terms;

    /**
     * Regras de Validação
     */
    public function rules()
    {
        return [
            // Campos Obrigatórios
            [['professional_name', 'nif', 'area_id', 'experience_level', 'bio'], 'required', 'message' => 'Este campo é obrigatório.'],

            // Validação de Texto
            [['professional_name'], 'string', 'min' => 3, 'max' => 120],
            [['bio'], 'string', 'min' => 10],

            // Validação do NIF (Exemplo: deve ter 9 dígitos numéricos)
            ['nif', 'match', 'pattern' => '/^[0-9]{9}$/', 'message' => 'O NIF deve conter exatamente 9 dígitos numéricos.'],

            // Garantir que são números inteiros
            [['area_id', 'experience_level'], 'integer'],

            // Validação de URL (adiciona http:// se a pessoa esquecer)
            ['website', 'url', 'defaultScheme' => 'https', 'message' => 'Insira um URL válido (ex: www.site.com)'],

            // Validação da disponibilidade (garante que não foi injetado código malicioso)
            ['availability', 'in', 'range' => array_keys(Application::getDisponibilidade())],

            // Checkbox dos termos
            ['terms', 'required', 'requiredValue' => 1, 'message' => 'Deve aceitar os termos e condições.'],
        ];
    }

    /**
     * Nomes bonitos para aparecerem nas etiquetas do formulário (labels)
     */
    public function attributeLabels()
    {
        return [
            'professional_name' => 'Nome do Profissional ou Empresa',
            'nif' => 'NIF/NIPC',
            'area_id' => 'Área Principal',
            'experience_level' => 'Experiência na Área',
            'website' => 'Website ou Redes Sociais',
            'availability' => 'Disponibilidade Habitual',
            'bio' => 'Apresentação / Biografia',
            'terms' => 'Termos e Condições',
        ];
    }

    /**
     * Lógica para guardar a candidatura na base de dados.
     * Retorna true se correu bem, false se falhou.
     */
    public function submitApplication()
    {
        // 1. Validar os dados deste formulário antes de prosseguir
        if (!$this->validate()) {
            return false;
        }

        // 2. Criar a nova Application (Base de dados)
        $application = new Application();
        $application->scenario = Application::SCENARIO_USER_PRO;

        $application->user_id = Yii::$app->user->id;
        $application->type = Application::TYPE_USER_PRO;
        $application->status = Application::STATUS_SENT;
        $application->created_at = date('Y-m-d H:i:s');

        // Usamos o nome do profissional como descrição breve
        $application->description = $this->professional_name;

        // 3. Preparar o JSON data com os dados do formulário
        $application->data = [
            'professional_name' => $this->professional_name,
            'nif'               => $this->nif,
            'area_id'           => $this->area_id,
            'experience_level'  => $this->experience_level,
            'website'           => $this->website,
            'availability'      => $this->availability,
            'bio'               => $this->bio,
        ];

        // 4. Guardar
        return $application->save();
    }
}