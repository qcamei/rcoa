<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\expert\searchs;

use common\models\expert\Expert;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Description of BasedataExperSearch
 *
 * @author Administrator
 */
class BasedataExperSearch extends Expert{
    
    public $nickname;
    public $username;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nickname','username'],'string'],
            [['u_id', 'type', 'birth'], 'integer'],
            [['nickname','username','job_title', 'job_name', 'level', 'employer', 'attainment'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'nickname'=>  Yii::t('rcoa/basedata', 'Nickname')
            ]);
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
        $query = BasedataExperSearch::find();
        $query->select([
            'Expert.u_id',
            'User.username AS username',
            'User.nickname AS nickname',
            'Expert.birth',
            'Expert.job_title',
            'Expert.employer',
            'Expert.personal_image',
        ]);
        $query->from(['Expert'=>  Expert::tableName()]);
        $query->leftJoin(['User'=>  User::tableName()],'Expert.u_id = User.id');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->setSort([
            'attributes' => [
                /*
                'nickname' => [
                    'asc' => ['user.nickname' => SORT_ASC],
                    'desc' => ['user.nickname' => SORT_DESC],
                ]*/
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'Expert.job_title', $this->job_title])
            ->andFilterWhere(['like', 'Expert.employer', $this->employer])
            ->andFilterWhere(['like', 'User.username', $this->username])
            ->andFilterWhere(['like', 'User.nickname', $this->nickname]);

        return $dataProvider;
    }
}
