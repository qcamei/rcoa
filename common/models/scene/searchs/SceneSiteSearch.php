<?php

namespace common\models\scene\searchs;

use common\models\scene\SceneSite;
use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * SceneSiteSearch represents the model behind the search form about `common\models\scene\SceneSite`.
 */
class SceneSiteSearch extends SceneSite
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'op_type', 'country', 'province', 'city', 'district', 'twon', 'is_publish', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['name', 'area', 'address', 'contact', 'manager_id', 'content_type', 'img_path', 'des', 'location', 'content'], 'safe'],
            [['price'], 'number'],
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
        $query = (new Query())
                ->select(['Site.id','Site.op_type','Site.area','Site.name','Site.manager_id','Site.content_type',
                    'Site.is_publish','Site.sort_order','User.nickname AS created_by'])
                ->from(['Site' => SceneSite::tableName()]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'id',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //关联查询管理员
        $query->leftJoin(['User' => User::tableName()], 'User.id = Site.manager_id');
                        
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'op_type' => $this->op_type,
            'country' => $this->country,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'twon' => $this->twon,
            'price' => $this->price,
            'is_publish' => $this->is_publish,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'manager_id', $this->manager_id])
            ->andFilterWhere(['like', 'content_type', $this->content_type])
            ->andFilterWhere(['like', 'img_path', $this->img_path])
            ->andFilterWhere(['like', 'des', $this->des])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
