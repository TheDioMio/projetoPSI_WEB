<?php

namespace backend\models;

use common\models\AnimalType;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Breed;

/**
 * BreedSearch represents the model behind the search form of `common\models\Breed`.
 */

class BreedSearch extends Breed {
    public $animal_type_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'animal_type_id'], 'integer'],
            [['description', 'animal_type_name'], 'safe'],
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
        $query = Breed::find();

        $query->joinWith(['animalType']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['animal_type_name'] = [
            'asc' => [AnimalType::tableName() . '.description' => SORT_ASC],
            'desc' => [AnimalType::tableName() . '.description' => SORT_DESC],
        ];
        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'animal_type_id' => $this->animal_type_id,
        ]);


        //Isto aqui é para não dar erro de ambiguity!
        $query->andFilterWhere(['like', Breed::tableName().'.id', $this->id]);
        $query->andFilterWhere(['like', Breed::tableName().'.description', $this->description]);

        $query->andFilterWhere(['like', AnimalType::tableName() . '.description', $this->animal_type_name]);
        return $dataProvider;
    }
}
