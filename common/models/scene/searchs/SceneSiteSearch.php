<?php

namespace common\models\scene\searchs;

use common\models\scene\SceneSite;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * SceneSiteSearch represents the model behind the search form about `common\models\scene\SceneSite`.
 */
class SceneSiteSearch extends SceneSite
{
    /** @var integer 性质 */
    private $op_type;
    /** @var string  区域 */
    private $area;
    /** @var string  内容类型 */
    private $content_type;
    /** @var integer 分页 */
    private $page;
    /** @var integer 显示数量 */
    private $limit;
    /** @var integer 显示数量 */
    private $limits;
    
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
    
    /**
     * 补充搜索查询
     * @param array $params
     * @return Query
     */
    public function dataSearceh($params)
    {
        //复制对象，为对应属性查询条件
        $query = $this->sceneSearch($params);
        //按课程id分组
        $query->groupBy("SceneSite.id");                   
        //查总数量
        $totalCount = count($query->all());
        //额外字段属性
        $query->addSelect(['SceneSite.id', 'SceneSite.name', 'SceneSite.op_type', 'SceneSite.area', 'SceneSite.address',
            'SceneSite.content_type', 'SceneSite.price', 'SceneSite.img_path', 'AsText(location)']);
        //课程排序，条件判断
        if ($this->sort_order == 'sort_order') {
            $query->orderBy(['SceneSite.id' => SORT_ASC, "SceneSite.$this->sort_order" => SORT_ASC]);
        } else {
            $query->orderBy(["SceneSite.price" => SORT_ASC]);
        }
        //复制搜索结果
        $data = clone $query;
        //场地预约（主页）--显示数量 
        $query->offset(($this->page-1)*$this->limit)->limit($this->limit);
        //场地预约（主页）--分页
        $pages = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $this->limit]);
        
        //场地列表--显示数量 
        $query->offset(($this->page-1)*$this->limits)->limit($this->limits);
        //场地列表--分页
        $listpages = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $this->limits]);
        
        return [
            'query' => $query->all(),
            'data' => $data->all(),
            'totalCount' => $totalCount,
            'pages' => $pages,
            'listpages' => $listpages,
        ];
    }
    
    /**
     * 场地条件搜索
     * @param array $params
     * @return Query
     */
    public function sceneSearch($params)
    {
        $this->op_type = ArrayHelper::getValue($params, 'op_type');             //性质
        $this->area = ArrayHelper::getValue($params, 'area');                   //区域
        $this->content_type = ArrayHelper::getValue($params, 'content_type');   //内容类型
        $this->sort_order = ArrayHelper::getValue($params, 'sort_order', 'sort_order');     //排序
        $this->page = ArrayHelper::getValue($params, 'page', 1);                            //分页
        $this->limit = ArrayHelper::getValue($params, 'limit', 4);                          //限制显示数量    
        $this->limits = ArrayHelper::getValue($params, 'limit', 8);                          //限制显示数量    
        
        $query = (new Query())->select('SceneSite.id')->from(['SceneSite' => SceneSite::tableName()]);
        //查询的必要条件
        $query->where(['is_publish' => 1]);
        
        //需求条件查询
        $query->andFilterWhere(['SceneSite.op_type' => $this->op_type]);
        $query->andFilterWhere(['SceneSite.area' => $this->area]);
        $query->andFilterWhere(['like','SceneSite.content_type', $this->content_type]);
        
        return $query;
    }
        
    /**
     * 获取过滤筛选的结果
     * @return array
     */
    public function filterSearch($params)
    {
        $op_type = ArrayHelper::getValue($params, 'op_type');
        $area = ArrayHelper::getValue($params, 'area');
        $content_type = ArrayHelper::getValue($params, 'content_type');
        $filters = [];
        //性质
        if($op_type != null){
            $nature = ['filter_value' => SceneSite::$TYPES[$op_type]];
            unset($params['op_type']);
            $filters += [Yii::t('app', 'Nature') => array_merge($nature, ['url' => Url::to(array_merge(['index'], $params))])];
        }
        //区域
        if($area != null){
            $address = (new Query())->select(['SceneSite.area AS filter_value'])
                    ->from(['SceneSite' => SceneSite::tableName()])->where(['area' => $area])->one();
            unset($params['area']);
            $filters += [Yii::t('app', 'Area') => array_merge($address, ['url' => Url::to(array_merge(['index'], $params))])];
        }
        //内容类型
        if($content_type != null){
            $type_name = ['filter_value' => SceneSite::$CONTENT_TYPES[$content_type]];
            unset($params['content_type']);
            $filters += [Yii::t('app', 'Type') => array_merge($type_name, ['url' => Url::to(array_merge(['index'], $params))])];
        }
        
        return $filters;
        
    }
}
