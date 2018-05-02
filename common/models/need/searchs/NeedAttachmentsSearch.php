<?php

namespace common\models\need\searchs;

use common\models\need\NeedAttachments;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * NeedAttachmentsSearch represents the model behind the search form of `common\models\need\NeedAttachments`.
 */
class NeedAttachmentsSearch extends NeedAttachments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['need_task_id', 'upload_file_id', 'is_del'], 'safe'],
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
        
        $query = NeedAttachments::find();

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
        $query->andFilterWhere([
            'id' => $this->id,
            'need_task_id' => $this->need_task_id,
            'upload_file_id' => $this->upload_file_id,
            'is_del' => 0,
        ]);

        return $dataProvider;
    }
}
