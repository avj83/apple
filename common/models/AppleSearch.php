<?php

namespace common\models;

use DateTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AppleSearch represents the model behind the search form about `common\models\Apple`.
 */
class AppleSearch extends Apple
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['created_at', 'falled_at'], 'match', 'pattern' => "/\d{4}\-\d{2}\-\d{2}\s\d{2}\:\d{2}\:\d{2}/"],
            [['color', 'status'], 'safe'],
            [['size'], 'number'],
        ];
    }

    /**
     * @inheritdoc
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Apple::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'size' => $this->size,
        ]);



        if ($this->created_at){
            $times = $this->getTimes($this->created_at);
            $query->andWhere(['between','created_at', $times->begin, $times->end]);
        }

        if ($this->falled_at){
            $times = $this->getTimes($this->falled_at);
            $query->andWhere(['between','falled_at', $times->begin, $times->end]);
        }

        $query->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['status' => $this->status]);

        return $dataProvider;
    }

    /**
     * getTimes
     *
     * @param $date
     *
     * @return \stdClass
     */
    protected function getTimes($date)
    {
        $res = new \stdClass();
        $timestamp = strtotime($date);
        $res->begin = strtotime(date("Y-m-d H:00:00", $timestamp));
        $res->end   = strtotime("+1 hour", $res->begin);
        return $res;
    }

}