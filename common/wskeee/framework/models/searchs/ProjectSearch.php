<?php

namespace wskeee\framework\models\searchs;

use wskeee\framework\models\Project;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * ProjectSearch represents the model behind the search form about `wskeee\framework\models\Project`.
 */
class ProjectSearch extends Project
{
    public $college = '';
    public $college_id = "";
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'level', 'created_at', 'updated_at', 'parent_id'], 'integer'],
            [['college_id','college','name', 'des'], 'safe'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge([
            'college'=>  Yii::t('rcoa/basedata', 'College')
        ],parent::attributeLabels());
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
        /* @var $query Query */
        $query = ProjectSearch::find();
        $query->select(['Item.id','Item.name','College.name AS college','College.id AS college_id']);
        $query->from(['Item'=>  self::tableName()]);
        $query->leftJoin(['College'=>  self::tableName()], 'Item.parent_id = College.id');
        $query->orderBy('Item.parent_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->setSort([
            'attributes' => [
                /* 指定其它字段 */
                /* 加入 */
                /* ============= */
                'college' => [
                    'asc' => ['Item.parent_id' => SORT_ASC], //table.字段，若行记录字段名唯一，可略table
                    'desc' => ['Item.parent_id' => SORT_DESC],
                ],
                /* ============= */
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider; 
        }

        $query->andFilterWhere([
            'Item.level' => $this->level,
        ]);

        $query->andFilterWhere(['like', 'Item.name', $this->name])
            ->andFilterWhere(['like', 'College.name', $this->college]);
    
        return $dataProvider;
    }
}
