<?php

namespace common\models\searchs;

use common\models\Uploadfile;
use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * UploadfileSearch represents the model behind the search form about `common\models\Uploadfile`.
 */
class UploadfileSearch extends Uploadfile {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'name', 'path', 'thumb_path', 'created_by'], 'safe'],
            [['download_count', 'del_mark', 'is_del', 'is_fixed', 'created_at', 'updated_at', 'size'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = $query = (new Query())
                ->select(['Uploadfile.id', 'Uploadfile.name AS filename', 'Uploadfile.del_mark AS delmark', 'Uploadfile.is_del AS isdel',
                    'CreateBy.nickname AS created_by'])
                ->from(['Uploadfile' => Uploadfile::tableName()]);

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
        //关联查询创建者
        $query->leftJoin(['CreateBy' => User::tableName()], 'CreateBy.id = Uploadfile.created_by');
        
        // grid filtering conditions
        $query->andFilterWhere([
            'download_count' => $this->download_count,
            'del_mark' => $this->del_mark,
            'is_del' => $this->is_del,
            'is_fixed' => $this->is_fixed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'size' => $this->size,
            'name' => $this->name,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'path', $this->path])
                ->andFilterWhere(['like', 'thumb_path', $this->thumb_path])
                ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }

}
