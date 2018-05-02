<?php

namespace common\models\need\searchs;

use common\models\need\NeedTaskLog;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * NeedTaskLogSearch represents the model behind the search form of `common\models\need\NeedTaskLog`.
 */
class NeedTaskLogSearch extends NeedTaskLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['need_task_id', 'action', 'title', 'content', 'created_by'], 'safe'],
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
        $this->need_task_id = ArrayHelper::getValue($params, 'need_task_id');
        $pageSize = ArrayHelper::getValue($params, 'page');
        
        $query = NeedTaskLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize == null ? 10 : $pageSize,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'action' => $this->action,
            'title' => $this->title,
            'need_task_id' => $this->need_task_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 'content', $this->content]);
        
        $query->orderBy(['id' => SORT_DESC]);
        $query->with('createdBy');
     
        return [
            'filter' => $params,
            'dataProvider' => $dataProvider
        ];
    }
}
