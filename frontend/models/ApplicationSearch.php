<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Application;

/**
 * ApplicationSearch represents the model behind the search form of `common\models\Application`.
 */
class ApplicationSearch extends Application
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'user_id', 'animal_id', 'type', 'target_user_id'], 'integer'],
            [['description', 'created_at', 'data'], 'safe'],
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
        $query = Application::find();

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
            'status' => $this->status,
            'user_id' => $this->user_id,
            'animal_id' => $this->animal_id,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'target_user_id' => $this->target_user_id,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }

    // aqui separamos a logica das candidaturas recebidas
    public function searchInbox($params)
    {
        $query = Application::find()
            ->where(['target_user_id' => Yii::$app->user->id])
            ->andWhere(['type' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status,
            'type'   => $this->type,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    //e aqui das candidaturas enviadas
    public function searchOutbox($params)
    {
        $query = Application::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['type' => Application::TYPE_ADOPTION]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status,
            'type'   => $this->type,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }


}
