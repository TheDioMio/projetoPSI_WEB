<?php

namespace backend\models;

use common\models\Animal;
use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Listing;

/**
 * ListingSearch represents the model behind the search form of `common\models\Listing`.
 */
class ListingSearch extends Listing
{
    public $animal_name;
    public $listing_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'animal_id', 'user_id', 'views', 'status'], 'integer'],
            [['description', 'created_at', 'listing_user', 'animal_name'], 'safe'],
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
        $query = Listing::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        //Isto aqui faz um join com as tabelas relacionadas à Listing, é preciso para funções de procura.
        $query->joinWith(['animal', 'user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['animal_name'] = [
            'asc' => [Animal::tableName() . '.name' => SORT_ASC],
            'desc' => [Animal::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['listing_user'] = [
            'asc' => [User::tableName() . '.username' => SORT_ASC],
            'desc' => [User::tableName() . '.username' => SORT_DESC],
        ];


        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'animal_id' => $this->animal_id,
            'user_id' => $this->user_id,
            'views' => $this->views,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);


        //Isto teve que ser mudado porque, caso contrário, dá erro de ambiguity (tabela animal também tem um campo de description).
        $query->andFilterWhere(['like', Listing::tableName().'.description', $this->description]);

        //FILTROS PARA AS TABELAS RELACIONADAS!!!!
        $query->andFilterWhere(['like', Animal::tableName() . '.name', $this->animal_name]);
        $query->andFilterWhere(['like', User::tableName() . '.username', $this->listing_user]);


        return $dataProvider;
    }
}
