<?php

namespace common\models\mconline\searchs;

use common\models\mconline\McbsAttention;
use common\models\mconline\McbsCourse;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * McbsCourseSearch represents the model behind the search form about `common\models\mconline\McbsCourse`.
 */
class McbsCourseSearch extends McbsCourse {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'created_by', 'des'], 'safe'],
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'status', 'is_publish', 'publish_time', 'close_time', 'created_at', 'updated_at'], 'integer'],
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
        $query = McbsCourse::find();

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
            'item_type_id' => $this->item_type_id,
            'item_id' => $this->item_id,
            'item_child_id' => $this->item_child_id,
            'course_id' => $this->course_id,
            'status' => $this->status,
            'is_publish' => $this->is_publish,
            'publish_time' => $this->publish_time,
            'close_time' => $this->close_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
    
    /**
     * 查询相同的内容
     * @param array $params
     * @return array
     */
    public function searchIdentity($params){
        //查询McbsCourse
        $query = (new Query())
                ->select(['McbsCourse.id'])
                ->from(['McbsCourse' => McbsCourse::tableName()]);
        //关联查询创建者
        $query->leftJoin(['CreateBy' => User::tableName()], 'CreateBy.id = McbsCourse.created_by');
        //关联查询基础课程
        $query->leftJoin(['ItemType' => ItemType::tableName()], 'ItemType.id = McbsCourse.item_type_id')
                ->leftJoin(['Item' => Item::tableName()], 'Item.id = McbsCourse.item_id')
                ->leftJoin(['ItemChild' => Item::tableName()], 'ItemChild.id = McbsCourse.item_child_id')
                ->leftJoin(['ItemCourse' => Item::tableName()], 'ItemCourse.id = McbsCourse.course_id');
        
        return $query;
    }

    /**
     * 获取我创建的课程信息
     * @param array $params
     * @return array
     */
    public function searchMyCourse($params) {
        $page = ArrayHelper::getValue($params, 'page', 1);                      //分页
        $keywords = ArrayHelper::getValue($params, 'keyword');                  //关键字
        $query = $this->searchIdentity($params);                                //获取查询相同的内容                                   
        //查询条件
        $query->where(['created_by' => Yii::$app->user->id]);
        //按关键字模糊搜索
        $query->andFilterWhere(['or',
            ['like', 'ItemChild.name', $keywords],
            ['like', 'ItemCourse.name', $keywords],
        ]);
        //复制对象计算总数
        $pageCopy = clone $query;
        $totalCount = count(array_flip(ArrayHelper::getColumn($pageCopy->all(), 'id')));
        //字段
        $query->addSelect([
            'Item.name AS item_name', 'ItemChild.name AS item_child_name', 'ItemCourse.name AS item_course_name',
            'ItemType.name AS itemtype_name', 'CreateBy.nickname AS created_by', 'McbsCourse.updated_at AS updated_at'
        ]);
        //分组、排序、截取
        $query->groupBy(['McbsCourse.id'])
                ->orderBy(['McbsCourse.id' => SORT_DESC]);
        //查询结果
        $results = $query->limit(20)->offset(($page - 1) * 20)->all();
        
        return [
            'param' => $params,
            'totalCount' => $totalCount,
            'result' => $results,
        ];
    }
    
    /**
     * 获取我关注的课程信息
     * @param array $params
     * @return array
     */
    public function searchMyAttention($params){
        $page = ArrayHelper::getValue($params, 'page', 1);                      //分页
        $keywords = ArrayHelper::getValue($params, 'keyword');                  //关键字
        $query = $this->searchIdentity($params);                                //获取查询相同的内容                                  
        //关联查询关注的课程
        $query->leftJoin(['McbsAttention' => McbsAttention::tableName()], 'McbsAttention.course_id = McbsCourse.id');
        //查询条件
        $query->where(['McbsAttention.user_id' => Yii::$app->user->id]);
        //按关键字模糊搜索
        $query->andFilterWhere(['or',
            ['like', 'ItemChild.name', $keywords],
            ['like', 'ItemCourse.name', $keywords],
            ['like', 'CreateBy.nickname', $keywords],
        ]);
        //复制对象计算总数
        $pageCopy = clone $query;
        $totalCount = count(array_flip(ArrayHelper::getColumn($pageCopy->all(), 'id')));
        //字段
        $query->addSelect([
            'Item.name AS item_name', 'ItemChild.name AS item_child_name', 'ItemCourse.name AS item_course_name',
            'ItemType.name AS itemtype_name', 'CreateBy.nickname AS created_by', 'McbsAttention.updated_at AS updated_at'
        ]);
        //分组、排序、截取
        $query->groupBy(['McbsAttention.id'])
                ->orderBy(['McbsAttention.id' => SORT_DESC]);
        //查询结果
        $results = $query->limit(20)->offset(($page - 1) * 20)->all();
        
        return [
            'param' => $params,
            'totalCount' => $totalCount,
            'result' => $results,
        ];
        
    }
    
    /**
     * 获取所有课程信息
     * @param array $params
     * @return array
     */
    public function searchCourseInfo($params) {
        $page = ArrayHelper::getValue($params, 'page', 1);                      //分页
        $itemId = ArrayHelper::getValue($params, 'item_id');                    //层次/类型
        $itemChildId = ArrayHelper::getValue($params, 'item_child_id');         //专业/工种
        $createBy = ArrayHelper::getValue($params, 'created_by');                //创建者
        $keywords = ArrayHelper::getValue($params, 'keyword');                  //关键字
        $query = $this->searchIdentity($params);                                //获取查询相同的内容
        //按字段id搜索
        $query->andFilterWhere([
            'McbsCourse.item_id' => $itemId,
            'McbsCourse.item_child_id' => $itemChildId,
            'McbsCourse.created_by' => $createBy,
        ]);
        //按关键字模糊搜索
        $query->andFilterWhere(['or',
            ['like', 'Item.name', $keywords],
            ['like', 'ItemChild.name', $keywords],
            ['like', 'ItemCourse.name', $keywords],
            ['like', 'CreateBy.nickname', $keywords],
        ]);
        //复制对象计算总数
        $pageCopy = clone $query;
        $totalCount = count(array_flip(ArrayHelper::getColumn($pageCopy->all(), 'id')));
        //字段
        $query->addSelect([
            'Item.name AS item_name', 'ItemChild.name AS item_child_name', 'ItemCourse.name AS item_course_name',
            'ItemType.name AS itemtype_name', 'CreateBy.nickname AS created_by', 'McbsCourse.updated_at AS updated_at'
        ]);
        //分组、排序、截取
        $query->groupBy(['McbsCourse.id'])
                ->orderBy(['McbsCourse.id' => SORT_DESC]);
        //查询结果
        $results = $query->limit(20)->offset(($page - 1) * 20)->all();
        
        return [
            'param' => $params,
            'totalCount' => $totalCount,
            'result' => $results,
        ];
    }    
    

}
