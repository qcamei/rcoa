<?php

namespace common\models\need\searchs;

use common\models\need\NeedTask;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * NeedTaskSearch represents the model behind the search form of `common\models\need\NeedTask`.
 */
class NeedTaskSearch extends NeedTask
{
    /**
     * 关键字
     * @var string 
     */
    public $keyword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'task_name', 'level', 'status', 'is_del', 'save_path', 'des', 'receive_by', 'audit_by', 'created_by'], 'safe'],
            [['company_id', 'business_id', 'layer_id', 'profession_id', 'course_id', 'need_time', 'finish_time', 'created_at', 'updated_at'], 'integer'],
            [['performance_percent', 'plan_content_cost', 'plan_outsourcing_cost', 'reality_content_cost', 'reality_outsourcing_cost'], 'number'],
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
        $is_search = ArrayHelper::getValue($params, 'is_search', 0);    //是否为搜索
        $is_receive = ArrayHelper::getValue($params, 'is_receive', 0);    //是否为承接
        $this->keyword = ArrayHelper::getValue($params, 'NeedTaskSearch.keyword');   //关键字
        $this->audit_by = ArrayHelper::getValue($params, 'NeedTaskSearch.audit_by', \Yii::$app->user->id);   //审核人
        $this->receive_by = ArrayHelper::getValue($params, 'NeedTaskSearch.receive_by', \Yii::$app->user->id);   //承接人
        $this->created_by = ArrayHelper::getValue($params, 'NeedTaskSearch.created_by', \Yii::$app->user->id);   //发布者
        $this->status = ArrayHelper::getValue($params, 'NeedTaskSearch.status', self::$defaultMap); //状态
        
        $query = NeedTask::find();
        //复制对象
        $queryCopy = clone $query;
        //获取发布者 和 承接者
        $created_bys = array_unique(array_filter(ArrayHelper::getColumn($queryCopy->all(), 'created_by')));
        $receive_bys = array_unique(array_filter(ArrayHelper::getColumn($queryCopy->all(), 'receive_by')));
        
        //添加条件，在这里应该总是适用
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        //过滤条件
        $query->andFilterWhere([
            'id' => $this->id,
            'company_id' => $this->company_id,
            'business_id' => $this->business_id,
            'layer_id' => $this->layer_id,
            'profession_id' => $this->profession_id,
            'course_id' => $this->course_id,
            'level' => $this->level,
            'performance_percent' => $this->performance_percent,
            'need_time' => $this->need_time,
            'finish_time' => $this->finish_time,
            'is_del' => 0,
            'plan_content_cost' => $this->plan_content_cost,
            'plan_outsourcing_cost' => $this->plan_outsourcing_cost,
            'reality_content_cost' => $this->reality_content_cost,
            'reality_outsourcing_cost' => $this->reality_outsourcing_cost,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        //判断传值上来的状态是否为 默认 0，如果是则条件为非【已完成】状态下的状态
        //var_dump($this->status === self::STATUS_DEFAULT);exit;
        if($this->status === self::STATUS_CREATEING){
            $query->andFilterWhere(['status' => self::$defaultMap]);
        }else if(empty($this->status)){
            $query->andFilterWhere(['NOT IN', 'status', self::STATUS_DEFAULT]);
        }else{
            $query->andFilterWhere(['status' => $this->status]);
        }
        //如果是承接列表就不加载该条件
        if(!$is_receive){
            //判断是否为搜索下的条件过滤
            if($is_search){
                $query->andFilterWhere(['receive_by' => $this->receive_by, 'created_by' => $this->created_by]);
            }else{
                $query->andFilterWhere(['or', ['receive_by' => $this->receive_by],
                    ['audit_by' => $this->audit_by], ['created_by' => $this->created_by],
                ]);
            }
        }
        //模糊查询
        $query->andFilterWhere(['like', 'task_name', $this->keyword])
            ->andFilterWhere(['like', 'save_path', $this->save_path])
            ->andFilterWhere(['like', 'des', $this->des]);

        $query->orderBy(['level' => SORT_DESC]);
        
        $query->with('business', 'layer', 'profession', 'course', 'receiveBy', 'createdBy');
        
        return [
            'dataProvider' => $dataProvider,
            'created_by' => $created_bys,
            'receive_by' => $receive_bys,
        ];
    }
}
