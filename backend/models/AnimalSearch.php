<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Animal;

/**
 * AnimalSearch represents the model behind the search form of `common\models\Animal`.
 */
class AnimalSearch extends Animal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'size', 'age', 'animal_type_id', 'vaccination_id','breed_id', 'neutered', 'user_id'], 'integer'],
            [['description', 'location', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Animal::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'size' => $this->size,
            'age' => $this->age,
            'animal_type_id' => $this->animal_type_id,
            'breed_id' => $this->breed_id,
            'vaccination_id' => $this->vaccination_id,
            'neutered' => $this->neutered,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'location', $this->location]);

        return $dataProvider;
    }
}
