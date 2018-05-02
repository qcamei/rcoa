<?php

namespace common\models\scene\searchs;

use common\models\mconline\McbsActionLog;
use common\models\scene\SceneActionLog;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * SceneActionLogSearch represents the model behind the search form about `common\models\mconline\McbsActionLog`.
 */
class SceneActionLogSearch extends SceneActionLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['book_id', 'action', 'title', 'content', 'created_by'], 'safe'],
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
        $this->book_id = ArrayHelper::getValue($params, 'book_id');
        $pageSize = ArrayHelper::getValue($params, 'page');
        
        $query = SceneActionLog::find();

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
            'book_id' => $this->book_id,
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