<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Listing;

/**
 * ListingSearch represents the model behind the search form of `common\models\Listing`.
 */
class ListingSearch extends Listing
{
    /**
     * {@inheritdoc}
     */

    public $q;

    public $animal_type_id;
    public $breed_id;
    public $animal_age_id;
    public $animal_size_id;

    public function rules()
    {
        return [
            [['id', 'animal_id', 'user_id', 'views', 'status',
                'animal_type_id', 'breed_id', 'animal_age_id', 'animal_size_id'], 'integer'],

            [['description', 'created_at', 'q'], 'safe'],
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

        $query = Listing::find()
            ->joinWith(['animal.animalType','animal.breed','animal.animalAge','animal.size']);

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

        $query->andFilterWhere([
            'or',
            ['like', 'listing.description', $this->q],
            ['like', 'animal.name', $this->q],
            ['like', 'animal_type.description', $this->q],
            ['like', 'animal.description', $this->q],
        ]);

        // 2. ⚠️ FILTROS DE DROPDOWN (NOVOS)
        // andFilterWhere ignora automaticamente valores vazios, 0 ou null.
        $query->andFilterWhere([
            'animal.animal_type_id' => $this->animal_type_id, // Filtrar pelo campo na tabela 'animal'
            'animal.breed_id' => $this->breed_id,
            'animal.age_id' => $this->animal_age_id,
            'animal.size_id' => $this->animal_size_id,

            // Se as colunas estiverem na tabela 'listing', use assim:
            // 'listing.animal_type_id' => $this->animal_type_id,

            // Assumo que os IDs estão no modelo Animal, o que é mais lógico.
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'or',
            ['like', 'listing.description', $this->q],
            ['like', 'animal.name', $this->q],
            ['like', 'animal_type.description', $this->q],
            ['like', 'animal.description', $this->q],
        ]);

        return $dataProvider;
    }
}
