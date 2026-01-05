<?php

namespace frontend\models;

use common\models\Animal;
use common\models\Application;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Listing;
use yii\db\Expression;

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
    public function search($params, $formName = null, $userId = null)
    {
        $query = Listing::find()
            ->joinWith(['animal.animalType','animal.breed','animal.animalAge','animal.size']);

        // add conditions that should always apply here
        //Vai só buscar todas as listagens que NÃO sejam STATUS_DELETED
        $query->andWhere(['!=', Listing::tableName() . '.status', Listing::STATUS_DELETED]);

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


        $query->andFilterWhere([
            'animal.animal_type_id' => $this->animal_type_id, // Filtrar pelo campo na tabela 'animal'
            'animal.breed_id' => $this->breed_id,
            'animal.age_id' => $this->animal_age_id,
            'animal.size_id' => $this->animal_size_id,

            // 'listing.animal_type_id' => $this->animal_type_id,


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

    public function getUserStatistics($userId) {
        // 1. KPIs
        $totalAnimals  = (int)Animal::find()->where(['user_id' => $userId])->count();
        $totalListings = (int)Listing::find()->where(['user_id' => $userId, 'status' => 1])->count();
        $totalViews    = (int)Listing::find()->where(['user_id' => $userId])->sum('views');
        $totalApps     = (int)Application::find()->joinWith('animal')->where(['animal.user_id' => $userId])->count();

        // 2. Trend (Evolução)
        $sixMonthsAgo = date('Y-m-01', strtotime('-5 months'));

        $animaisPorMes = Animal::find()
            ->select([new Expression("DATE_FORMAT(created_at, '%Y-%m') as month"), new Expression("COUNT(*) as total")])
            ->where(['user_id' => $userId])
            ->andWhere(['>=', 'created_at', $sixMonthsAgo])
            ->groupBy('month')->orderBy('month ASC')->asArray()->all();

        $trendLabels = [];
        $trendData = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i months"));
            $trendLabels[] = date('M Y', strtotime("-$i months"));

            $found = array_filter($animaisPorMes, fn($r) => $r['month'] == $m);
            $trendData[] = !empty($found) ? (int)array_values($found)[0]['total'] : 0;
        }

        // 3. Status Apps
        $appStats = Application::find()
            ->select(['application.status', 'COUNT(*) as total'])
            ->joinWith('animal')
            ->where(['animal.user_id' => $userId])
            ->groupBy('application.status')->asArray()->all();

        $statusMap = [
            Application::STATUS_SENT => 'Pendente',
            Application::STATUS_IN_REVIEW => 'Em Análise',
            Application::STATUS_APPROVED => 'Aprovado',
            Application::STATUS_REJECTED => 'Rejeitado'
        ];

        $appLabels = [];
        $appData = [];
        foreach ($appStats as $s) {
            $appLabels[] = $statusMap[(int)$s['status']] ?? 'Outro';
            $appData[] = (int)$s['total'];
        }

        // 4. Tipos
        $typeStats = Animal::find()->alias('a')
            ->select(['t.description', 'COUNT(a.id) as total'])
            ->joinWith('animalType t')
            ->where(['a.user_id' => $userId])
            ->groupBy('a.animal_type_id')->asArray()->all();

        //5. Top Vistos
        $topVistos = Listing::find()
            ->with(['animal', 'animal.animalType'])
            ->where(['user_id' => $userId])
            ->orderBy(['views' => SORT_DESC])
            ->limit(5)
            ->all();

        $typeLabels = array_column($typeStats, 'description');
        $typeData = array_column($typeStats, 'total');

        // RETORNO CORRIGIDO (Sem 'charts', chaves diretas na raiz)
        return [
            'kpi' => [
                'animals' => $totalAnimals,
                'listings' => $totalListings,
                'views' => $totalViews,
                'applications' => $totalApps
            ],
            'trend' => [
                'labels' => $trendLabels,
                'data' => $trendData
            ],
            'appStatus' => [
                'labels' => $appLabels,
                'data' => $appData
            ],
            'types' => [
                'labels' => $typeLabels,
                'data' => $typeData
            ],
            'topVistos' => $topVistos,
        ];
    }
}
