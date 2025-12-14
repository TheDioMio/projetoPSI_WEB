<?php
namespace backend\models;
use common\models\AnimalAge;
use common\models\AnimalType;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Animal;

class AnimalSearch extends Animal
{
    public $animal_type;
    public $animal_age;
    public function rules()
    {
        return [
            [['id', 'size_id', 'age_id', 'animal_type_id', 'vaccination_id','breed_id', 'neutered', 'user_id'], 'integer'],
            [['description', 'location', 'created_at', 'animal_type', 'name', 'animal_age'], 'safe'],
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
    public function search($params, $formName = null)
    {
        $query = Animal::find();
        $query->joinWith(['animalType', 'animalAge']);
        // ... (resto do código)

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
            'size_id' => $this->size_id,
            'age_id' => $this->age_id,
            'animal_type_id' => $this->animal_type_id,
            'breed_id' => $this->breed_id,
            'vaccination_id' => $this->vaccination_id,
            'neutered' => $this->neutered,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'name', $this->name]);

        //FILTROS PARA AS TABELAS RELACIONADAS!!!!
        $query->andFilterWhere(['like', AnimalType::tableName() . '.description', $this->animal_type]);
        $query->andFilterWhere(['like', AnimalAge::tableName() . '.description', $this->animal_age]);
//        $query->andFilterWhere(['like', User::tableName() . '.username', $this->listing_user]);


        $dataProvider->sort->attributes['animal_type'] = [
            'asc' => [AnimalType::tableName() . '.description' => SORT_ASC],
            'desc' => [AnimalType::tableName() . '.description' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['animal_age'] = [
            'asc' => [AnimalAge::tableName() . '.description' => SORT_ASC],
            'desc' => [AnimalAge::tableName() . '.description' => SORT_DESC],
        ];
        return $dataProvider;
    }
}
