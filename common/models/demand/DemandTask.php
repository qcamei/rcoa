<?php

namespace common\models\demand;

use common\config\AppGlobalVariables;
use common\models\product\Product;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\teamwork\CourseManage;
use common\models\User;
use common\wskeee\job\JobManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;



/**
 * This is the model class for table "{{%demand_task}}".
 *
 * @property integer $id                                ID
 * @property integer $item_type_id                      行业ID
 * @property integer $item_id                           层次/类型ID
 * @property integer $item_child_id                     专业/工种ID
 * @property integer $course_id                         课程ID
 * @property string $teacher                            教师ID
 * @property integer $lesson_time                       学时
 * @property integer $credit                            学分
 * @property string $course_description                 课程简介
 * @property integer $budget_cost                       预算开发成本
 * @property integer $cost                              实际开发成本
 * @property integer $external_budget_cost              外部预算成本
 * @property integer $external_reality_cost             外部实际成本
 * @property integer $bonus_proportion                  绩效比值
 * @property integer $score                             绩效得分
 * @property integer $mode                              模式
 * @property integer $team_id                           开发团队ID
 * @property string $undertake_person                   承接人
 * @property integer $develop_principals                开发负责人
 * @property string $plan_check_harvest_time            计划验收时间
 * @property string $reality_check_harvest_time         实际验收时间
 * @property integer $status                            状态
 * @property integer $progress                          进度
 * @property string $create_by                          创建者
 * @property integer $create_team                       创建团队
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 * @property integer $finished_at                       结束于
 * @property string $des                                备注
 *
 * @property DemandAcceptance[] $demandAcceptances      获取所有的验收记录
 * @property DemandCheck $demandCheck                   获取最新的审核记录
 * @property DemandCheck[] $demandChecks                获取所有的审核记录
 * @property Item $course                               获取课程
 * @property Item $itemChild                            获取专业/工种
 * @property Item $item                                 获取层次/类型
 * @property ItemType $itemType                         获取行业
 * @property DemandTaskAnnex[] $demandTaskAnnexes       获取所有的附件
 * @property DemandTaskProduct[] $demandTaskProducts    获取所有的课程产品
 * @property DemandToTeamwork[] $demandToTeamworks
 * @property DemandWorkitem[] $demandWorkitems          获取所有任务相关联的工作项
 * @property Team $team                                 获取开发团队
 * @property Team $createTeam                           获取创建团队
 * @property User $createBy                             获取创建者
 * @property User $undertakePerson                      获取承接人
 * @property TeamMember $developPrincipals              获取开发负责人
 * @property User $speakerTeacher                       获取教师
 * @property CourseManage $teamworkCourse               获取团队工作课程开发数据
 */
class DemandTask extends ActiveRecord
{
    /** 新建模式 */
    const MODE_NEWBUILT = 0;
    /** 改造模式 */
    const MODE_REFORM = 1;
    /** 默认状态 */
    const STATUS_DEFAULT = 100;
    /** 任务刚发出，等待审核 【待审核】 */
    const STATUS_CHECK = 101;
    /** 任务未通过审核，正在调整 【调整中】 */
    const STATUS_ADJUSTMENTING = 102;
    /** 任务调整完毕，正在审核 【审核中】 */
    const STATUS_CHECKING = 103;
    /** 任务已通过审核，等待承接 【待承接】 */
    const STATUS_UNDERTAKE = 200;
    /** 任务已承接，正在开发 【开发中】 */
    const STATUS_DEVELOPING = 201;
    /** 课程开发已完成，等待验收 【待验收】 */
    const STATUS_ACCEPTANCE = 202;
    /** 课程未通过验收，正在修改 【修改中】 */
    const STATUS_UPDATEING = 203;
    /** 课程修改完毕，正在验收 【验收中】 */
    const STATUS_ACCEPTANCEING = 204;
    /** 课程验收完毕，正在待确认 【待确认】 */
    const STATUS_WAITCONFIRM = 205;
    /** 协商完毕，等待修改绩效得分 【申诉中】 */
    const STATUS_APPEALING = 206;
    /** 任务已通过验收，任务结束 【已完成】 */
    const STATUS_COMPLETED = 500;
    /** 因客观原因需要改期或者取消原定任务 【已取消】 */
    const STATUS_CANCEL = 900;

    /**
     * 课程需求操作
     * @var array 
     */
    public static $operation = [];
    /**
     * 课程产品总额
     * @var array 
     */
    public static $productTotal = [];
    /**
     * 模式名称
     * @var array 
     */
    public static $modeName = [
        self::MODE_NEWBUILT => '新建',
        self::MODE_REFORM => '改造'
    ];
    /**
     * 默认状态
     * @var array 
     */
    public static $defaultStatus = [
        self::STATUS_DEFAULT,
        self::STATUS_CHECK,
        self::STATUS_ADJUSTMENTING,
        self::STATUS_CHECKING,
        self::STATUS_UNDERTAKE,
        self::STATUS_DEVELOPING,
        self::STATUS_ACCEPTANCE,
        self::STATUS_UPDATEING,
        self::STATUS_ACCEPTANCEING,
        self::STATUS_WAITCONFIRM,
        self::STATUS_APPEALING,
    ];
    
    /**
     * 承接人任务排序
     * @var array
     */
    public static $orderBy = [
        self::STATUS_UNDERTAKE,
        self::STATUS_DEFAULT,
        self::STATUS_CHECK,
        self::STATUS_ADJUSTMENTING,
        self::STATUS_CHECKING,
        self::STATUS_DEVELOPING,
        self::STATUS_ACCEPTANCE,
        self::STATUS_UPDATEING,
        self::STATUS_ACCEPTANCEING,
        self::STATUS_WAITCONFIRM,
        self::STATUS_APPEALING,
    ];

    /**
     * 状态名称
     * @var array 
     */
    public static $statusNmae = [
        self::STATUS_DEFAULT => '创建中',
        self::STATUS_CHECK => '待审核',
        self::STATUS_ADJUSTMENTING => '调整中',
        self::STATUS_CHECKING => '审核中',
        self::STATUS_UNDERTAKE => '待承接',
        self::STATUS_DEVELOPING => '开发中',
        self::STATUS_ACCEPTANCE => '待验收',
        self::STATUS_UPDATEING => '修改中',
        self::STATUS_ACCEPTANCEING => '验收中',
        self::STATUS_WAITCONFIRM => '待确认',
        self::STATUS_APPEALING => '申诉中',
        self::STATUS_COMPLETED => '已完成',
        self::STATUS_CANCEL => '已取消',
    ];
    /**
     * 状态进度
     * @var array 
     */
    public static $statusProgress = [
        self::STATUS_CHECK => 5,
        self::STATUS_ADJUSTMENTING => 5,
        self::STATUS_CHECKING => 5,
        self::STATUS_UNDERTAKE => 20,
        self::STATUS_DEVELOPING => 50,
        self::STATUS_ACCEPTANCE => 80,
        self::STATUS_UPDATEING => 80,
        self::STATUS_ACCEPTANCEING => 80,
        self::STATUS_WAITCONFIRM => 95,
        self::STATUS_APPEALING => 95,
        self::STATUS_COMPLETED => 100,
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_task}}';
    }

    public function behaviors() 
    {
        return [
            TimestampBehavior::className()
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'teacher', 'course_description', 'lesson_time', 'credit'],'required'],
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'lesson_time', 'credit', 'mode', 'team_id', 'create_team', 'develop_principals', 'status', 'progress', 'created_at', 'updated_at', 'finished_at'], 'integer'],
            [['course_description', 'des'], 'string'],
            [['budget_cost', 'cost', 'external_budget_cost', 'external_reality_cost', 'bonus_proportion', 'score'], 'number'],
            [['teacher', 'undertake_person', 'create_by'], 'string', 'max' => 36],
            [['plan_check_harvest_time', 'reality_check_harvest_time'], 'string', 'max' => 60],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['item_child_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_child_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['item_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemType::className(), 'targetAttribute' => ['item_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'item_type_id' => Yii::t('rcoa/demand', 'Item Type'),
            'item_id' => Yii::t('rcoa/demand', 'Item'),
            'item_child_id' => Yii::t('rcoa/demand', 'Item Child'),
            'course_id' => Yii::t('rcoa/demand', 'Course'),
            'teacher' => Yii::t('rcoa/demand', 'Teacher'),
            'lesson_time' => Yii::t('rcoa/demand', 'Lesson Time'),
            'credit' => Yii::t('rcoa/demand', 'Credit'),
            'course_description' => Yii::t('rcoa/demand', 'Course Description'),
            'budget_cost' => Yii::t('rcoa/demand', 'Budget Cost'),
            'cost' => Yii::t('rcoa/demand', 'Cost'),
            'external_budget_cost' => Yii::t('rcoa/demand', 'External Budget Cost'),
            'external_reality_cost' => Yii::t('rcoa/demand', 'External Reality Cost'),
            'bonus_proportion' => Yii::t('rcoa/demand', 'Bonus Proportion'),
            'score' => Yii::t('rcoa/demand', 'Score'),
            'mode' => Yii::t('rcoa/demand', 'Mode'),
            'team_id' => Yii::t('rcoa/demand', 'Team'),
            'undertake_person' => Yii::t('rcoa/demand', 'Undertake Person'),
            'develop_principals' => Yii::t('rcoa/demand', 'Develop Principals'),
            'plan_check_harvest_time' => Yii::t('rcoa/demand', 'Plan Check Harvest Time'),
            'reality_check_harvest_time' => Yii::t('rcoa/demand', 'Reality Check Harvest Time'),
            'status' => Yii::t('rcoa', 'Status'),
            'progress' => Yii::t('rcoa', 'Progress'),
            'create_by' => Yii::t('rcoa/demand', 'Create By'),
            'create_team' => Yii::t('rcoa/demand', 'Create Team'),
            'created_at' => Yii::t('rcoa/demand', 'Created At'),
            'updated_at' => Yii::t('rcoa/demand', 'Updated At'),
            'finished_at' => Yii::t('rcoa/demand', 'Finished At'),
            'des' => Yii::t('rcoa/demand', 'Des'),
        ];
    }

    public function afterFind() 
    {
        $this->setUpOvertimeOperation();
    }
    
    /**
     * 获取所有的验收记录
     * @return ActiveQuery
     */
    public function getDemandAcceptances()
    {
        return $this->hasMany(DemandAcceptance::className(), ['demand_task_id' => 'id']);
    }

    /**
     * 获取最新的审核记录
     * @return ActiveQuery
     */
    public function getDemandCheck()
    {
        return $this->hasOne(DemandCheck::className(), ['demand_task_id' => 'id'])
               ->orderBy('id desc');
    }
    
    /**
     * 获取所有的审核记录
     * @return ActiveQuery
     */
    public function getDemandChecks()
    {
        return $this->hasMany(DemandCheck::className(), ['demand_task_id' => 'id']);
    }
    
    /**
     * 获取开发团队
     * @return ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }
    
    /**
     * 获取创建团队团队
     * @return ActiveQuery
     */
    public function getCreateTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'create_team']);
    }

    /**
     * 获取课程
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Item::className(), ['id' => 'course_id']);
    }
    
    /**
     * 获取创建者
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }
    
    /**
     * 获取承接人
     * @return ActiveQuery
     */
    public function getUndertakePerson()
    {
        return $this->hasOne(User::className(), ['id' => 'undertake_person']);
    }
    
    /**
     * 获取开发负责人
     * @return ActiveQuery
     */
    public function getDevelopPrincipals()
    {
        return $this->hasOne(TeamMember::className(), ['id' => 'develop_principals']);
    }

    /**
     * 获取专业/工种
     * @return ActiveQuery
     */
    public function getItemChild()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_child_id']);
    }

    /**
     * 获取层次/类型
     * @return ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * 获取行业
     * @return ActiveQuery
     */
    public function getItemType()
    {
        return $this->hasOne(ItemType::className(), ['id' => 'item_type_id']);
    }

    /**
     * 获取教师
     * @return ActiveQuery
     */
    public function getSpeakerTeacher()
    {
        return $this->hasOne(User::className(), ['id' => 'teacher']);
    }
    
    /**
     * 获取团队工作课程开发数据
     * @return ActiveQuery
     */
    public function getTeamworkCourse()
    {
        return $this->hasOne(CourseManage::className(), ['demand_task_id' => 'id']);
    }
    
    /**
     * 获取所有的附件
     * @return ActiveQuery
     */
    public function getDemandTaskAnnexes()
    {
        return $this->hasMany(DemandTaskAnnex::className(), ['task_id' => 'id']);
    }

    /**
     * 获取所有的课程产品
     * @return ActiveQuery
     */
    public function getDemandTaskProducts()
    {
        return $this->hasMany(DemandTaskProduct::className(), ['task_id' => 'id'])
               ->leftJoin(['Product' => Product::tableName()], ['product_id' => 'Product.id']);
    }
    
    /**
     * 获取所有任务相关联的工作项
     * @return ActiveQuery
     */
    public function getDemandWorkitems()
    {
        return $this->hasMany(DemandWorkitem::className(), ['demand_task_id' => 'id'])
               ->orderBy('index')
               ->with('workitem', 'workitemType', 'demandTask');
    }


    /**
     * @return ActiveQuery
     */
    public function getDemandToTeamworks()
    {
        return $this->hasMany(DemandToTeamwork::className(), ['task_id' => 'id']);
    }
    
    /**
     * 获取是否在【默认】状态
     * @return type
     */
    public function getIsStatusDefault()
    {
        return $this->status == self::STATUS_DEFAULT;
    }
    
    /**
     * 获取是否在【待审核】状态
     * @return type
     */
    public function getIsStatusCheck()
    {
        return $this->status == self::STATUS_CHECK;
    }
    
    /**
     * 获取是否在【调整中】状态
     * @return type
     */
    public function getIsStatusAdjusimenting()
    {
        return $this->status == self::STATUS_ADJUSTMENTING;
    }
    
    /**
     * 获取是否在【审核中】状态
     * @return type
     */
    public function getIsStatusChecking()
    {
        return $this->status == self::STATUS_CHECKING;
    }
    
    /**
     * 获取是否在【待承接】状态
     * @return type
     */
    public function getIsStatusUndertake()
    {
        return $this->status == self::STATUS_UNDERTAKE;
    }
    
    /**
     * 获取是否在【开发中】状态
     * @return type
     */
    public function getIsStatusDeveloping()
    {
        return $this->status == self::STATUS_DEVELOPING;
    }
    
    /**
     * 获取是否在【待验收】状态
     * @return type
     */
    public function getIsStatusAcceptance()
    {
        return $this->status == self::STATUS_ACCEPTANCE;
    }
    
    /**
     * 获取是否在【修改中】状态
     * @return type
     */
    public function getIsStatusUpdateing()
    {
        return $this->status == self::STATUS_UPDATEING;
    }
    
    /**
     * 获取是否在【验收中】状态
     * @return type
     */
    public function getIsStatusAcceptanceing()
    {
        return $this->status == self::STATUS_ACCEPTANCEING;
    }
    
    /**
     * 获取是否在【待确认】状态
     * @return type
     */
    public function getIsStatusWaitConfirm()
    {
        return $this->status == self::STATUS_WAITCONFIRM;
    }
    
    /**
     * 获取是否在【申诉中】状态
     * @return type
     */
    public function getIsStatusAppealing()
    {
        return $this->status == self::STATUS_APPEALING;
    }
    
    /**
     * 获取是否在【已完成】状态
     * @return type
     */
    public function getIsStatusCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }
    
    /**
     * 获取是否在【已取消】状态
     * @return type
     */
    public function getIsStatusCancel()
    {
        return $this->status == self::STATUS_CANCEL;
    }
    
    /**
     * 获取状态名称
     * @return type
     */
    public function getStatusName()
    {
        return self::$statusNmae[$this->status];
    }
    
    /**
     * 获取状态进度
     * @return type
     */
    public function getStatusProgress()
    {
        return self::$statusProgress[$this->status];
    }
    
    /**
     * 超时操作
     */
    public function setUpOvertimeOperation()
    {
        $overtime = strtotime($this->reality_check_harvest_time.'+ 15 day');
        if($this->getIsStatusWaitConfirm() && time() > $overtime){
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            
            $this->status = self::STATUS_COMPLETED;
            $this->progress = self::$statusProgress[self::STATUS_COMPLETED];
            $this->finished_at = $overtime;
            
            //开启事务
            $trans = Yii::$app->db->beginTransaction();
            try
            {
                if($this->save(false,['status', 'progress', 'finished_at'])){
                    $jobManager->updateJob(AppGlobalVariables::getSystemId(), $this->id, [
                        'progress'=> self::$statusProgress[self::STATUS_COMPLETED], 'status' => self::$statusNmae[self::STATUS_COMPLETED]]); 
                }
                $trans->commit();   //提交
            } catch (Exception $ex) {
                $trans->rollBack();     //回滚
            }
        }
    }
}
