<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GiftTimeline;

class GiftTimelineSearch extends GiftTimeline
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['id'], 'integer'],
            [['name'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = GiftTimeline::find()
        
        ->where(['<>','status',GiftTimeline::STATUS_DELETED]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
    //    $query->andFilterWhere([
    //         'category_id' => $this->category_id
    //     ]);
       
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

  
}
