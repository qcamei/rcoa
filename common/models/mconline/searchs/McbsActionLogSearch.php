<?php

namespace common\models\mconline\searchs;

use common\models\mconline\McbsActionLog;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * McbsActionLogSearch represents the model behind the search form about `common\models\mconline\McbsActionLog`.
 */
class McbsActionLogSearch extends McbsActionLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['action', 'title', 'content', 'created_by', 'course_id', 'relative_id'], 'safe'],
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
        $this->course_id = ArrayHelper::getValue($params, 'course_id');
        $this->relative_id = ArrayHelper::getValue($params, 'relative_id');
        $pageSize = ArrayHelper::getValue($params, 'page');
        
        $query = McbsActionLog::find();

        // add conditions that should always apply here

        $this->load($params);

        /*if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }*/

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'course_id' => $this->course_id,
            'relative_id' => $this->relative_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content]);
        
        $query->orderBy('id DESC');
        $query->with('createBy');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize == null ? 10 : $pageSize,
            ],
        ]);
        
        return [
            'filter' => $params,
            'dataProvider' => $dataProvider
        ];
    }
}