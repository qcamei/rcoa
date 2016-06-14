<?php

namespace common\wskeee\filemanage\models\searchs;

use wskeee\filemanage\models\FileManage;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FileManageDetailSearch represents the model behind the search form about `wskeee\filemanage\models\FileManage`.
 */
class DetailSearch extends FileManage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'pid'], 'integer'],
            [['name', 'keyword', 'icon'], 'safe'],
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
        $query = FileManage::find()->where(['type' => FileManage::FM_FILE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'pid' => $this->pid,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'icon', $this->icon]);

        return $dataProvider;
    }
}
