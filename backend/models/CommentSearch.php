<?php
namespace app\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Comment;
use common\models\Animal;
use common\models\User;

class CommentSearch extends Comment
{
    public $animal_name;
    public $user_username;
    public function rules()
    {
        return [
            [['id', 'listing_id', 'user_id'], 'integer'],
            [['text', 'created_at'], 'safe'],
            [['animal_name', 'user_username'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = Comment::find();

        //Isto aqui faz um join com as tabelas relacionadas ao Comment, é preciso para funções de procura.
        $query->joinWith(['listing.animal', 'listing.user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['animal_name'] = [
            'asc' => [Animal::tableName() . '.name' => SORT_ASC],
            'desc' => [Animal::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['user_username'] = [
            'asc' => [User::tableName() . '.username' => SORT_ASC],
            'desc' => [User::tableName() . '.username' => SORT_DESC],
        ];


        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            Comment::tableName().'.id' => $this->id,
            Comment::tableName().'.listing_id' => $this->listing_id,
            Comment::tableName().'.user_id' => $this->user_id,
        ]);
        $query->andFilterWhere(['like', Comment::tableName().'.text', $this->text]);
        $query->andFilterWhere(['like', Comment::tableName().'.created_at', $this->created_at]);

        //FILTROS PARA AS TABELAS RELACIONADAS!!!!
        $query->andFilterWhere(['like', Animal::tableName() . '.name', $this->animal_name]);
        $query->andFilterWhere(['like', User::tableName() . '.username', $this->user_username]);

        return $dataProvider;
    }
}