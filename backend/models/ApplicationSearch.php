<?php

namespace app\models;

use common\models\Animal;
use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Application;

class ApplicationSearch extends Application
{
    public $animal_name;
    public $candidate_name;
    public $animal_owner_name;

    public function rules()
    {
        return [
            [['id', 'status', 'user_id', 'animal_id', 'type', 'target_user_id'], 'integer'],
            [['description', 'created_at', 'data', 'animal_name', 'candidate_name', 'animal_owner_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params, $formName = null)
    {
        $query = Application::find();

        /*Aqui, como temos POSSIVELMENTE dois campos que vão buscar informação ao user.name, é preciso fazer a
        diferenciação entre o nome do candidato, e o nome do dono do animal, para onde vai o pedido de candidatura.
        Isto porque o SearchModel assim só ia fazer filtragem a um dos 2 campos.
        */
        $query->joinWith(['candidate' => function($q){
            $q->from(['candidateUser' => User::tableName()]);
        }]);

        $query->joinWith(['animalOwner' => function($q) {
            $q->from(['ownerUser' => User::tableName()]);
        }]);

        $query->joinWith(['animal']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['animal_name'] = [
            'asc' => [Animal::tableName() . '.name' => SORT_ASC],
            'desc' => [Animal::tableName() . '.name' => SORT_DESC],
        ];

        // Ordenação pelo nome do CANDIDATO
        $dataProvider->sort->attributes['candidate_name'] = [
            'asc' => ['candidateUser.name' => SORT_ASC],
            'desc' => ['candidateUser.name' => SORT_DESC],
        ];

        // Ordenação pelo nome do DONO
        $dataProvider->sort->attributes['animal_owner_name'] = [
            'asc' => ['ownerUser.name' => SORT_ASC],
            'desc' => ['ownerUser.name' => SORT_DESC],
        ];

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'animal_id' => $this->animal_id,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'target_user_id' => $this->target_user_id,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'data', $this->data]);



        $query->andFilterWhere(['like', Animal::tableName() . '.name', $this->animal_name]);
        $query->andFilterWhere(['like', 'candidateUser.name', $this->candidate_name]);
        $query->andFilterWhere(['like', 'ownerUser.name', $this->animal_owner_name]);

        return $dataProvider;
    }
}
