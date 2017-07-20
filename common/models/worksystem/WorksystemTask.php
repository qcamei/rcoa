<?php

namespace common\models\worksystem;

use common\models\demand\DemandTask;
use common\models\team\Team;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%worksystem_task}}".
 *
 * @property integer $id                                    id
 * @property integer $item_type_id                          引用基础行业id
 * @property integer $item_id                               引用基础层次/类型id
 * @property integer $item_child_id                         引用基础专业/工种id
 * @property integer $course_id                             引用基础课程id
 * @property integer $task_type_id                          引用工作系统任务类别id
 * @property string $name                                   任务名称
 * @property integer $level                                 任务等级：0为普通，1为加急
 * @property integer $is_brace                              是否支撑：0为否，1为是
 * @property integer $is_epiboly                            是否外包：0为否，1为是
 * @property string $budget_cost                            预计成本
 * @property string $reality_cost                           实际成本
 * @property string $budget_bonus                           预计奖金
 * @property string $reality_bonus                          实际奖金
 * @property string $plan_end_time                          要求完成时间
 * @property integer $external_team                         外部团队
 * @property integer $status                                状态
 * @property integer $progress                              进度
 * @property integer $create_team                           创建团队
 * @property string $create_by                              创建者
 * @property integer $index                                 索引
 * @property integer $is_delete                             是否删除：0为否，1为是
 * @property integer $created_at                            创建于
 * @property integer $updated_at                            更新于
 * @property integer $finished_at                           完成于
 * @property string $des                                    描述
 *
 * @property WorksystemAddAttributes[] $worksystemAddAttributes     获取所有附加属性
 * @property WorksystemContentinfo[] $worksystemContentinfos        获取所有内容信息
 * @property WorksystemOperation[] $worksystemOperations            获取所有操作记录
 * @property User $createBy                                         获取创建者
 * @property ItemType $itemType                                     获取基础行业
 * @property Item $item                                             获取基础层次/类型
 * @property Item $itemChild                                        获取基础专业/工种
 * @property Item $course                                           获取基础课程
 * @property WorksystemTaskType $worksystemTaskType                 获取工作系统任务类别
 * @property Team $createTeam                                       获取创建团队
 * @property Team $externalTeam                                     获取外部团队
 * @property WorksystemTaskProducer[] $worksystemTaskProducers      获取所有制作人员
 */
class WorksystemTask extends ActiveRecord
{
    /** 创建场景 */
    const SCENARIO_CREATE = 'create';
    /** 更新场景 */
    const SCENARIO_UPDATE = 'update';
    /** 普通等级 */
    const LEVEL_ORDINARY = 0;
    /** 加急等级 */
    const LEVEL_URGENT = 1;
    /** 取消支撑 */
    const  CANCEL_BRACE_MARK = 0;
    /** 寻求支撑 */
    const  SEEK_BRACE_MARK = 1;
    /** 取消外包 */
    const  CANCEL_EPIBOLY_MARK = 0;
    /** 寻求外包 */
    const  SEEK_EPIBOLY_MARK = 1;
    
    /** 默认状态 */
    const STATUS_DEFAULT = 100;
    /** 任务刚发出，等待审核 【待审核】 */
    const STATUS_WAITCHECK = 200;
    /** 任务审核不通过，等待调整 【调整中】 */
    const STATUS_ADJUSTMENTING = 205;
    /** 任务已经修改完成，等待继续审核 【审核中】 */
    const STATUS_CHECKING = 210;
    /** 任务审核完毕，等待指派 【待指派】 */
    const STATUS_WAITASSIGN = 300;
    /** 任务外包情况，等待承接 【待承接】 */
    const STATUS_WAITUNDERTAKE = 350;
    /** 任务已经分派制作人，等待开始制作，【待开始】 */
    const STATUS_TOSTART = 400;
    /** 任务已经开始在制作中， 【制作中】 */
    const STATUS_WORKING = 405;
    /** 任务已经制作完毕， 等待验收【待验收】 */
    const STATUS_WAITACCEPTANCE = 450;
    /** 任务验收不通过， 等待修改【修改中】 */
    const STATUS_UPDATEING = 455;
    /** 任务修改完毕， 等待继续验收【验收中】 */
    const STATUS_ACCEPTANCEING = 460;
    /** 任务已通过审核，任务结束， 【已完成】 */
    const STATUS_COMPLETED = 500;
    /** 因客观原因需要改期或者取消原定任务 【已取消】 */
    const STATUS_CANCEL = 900;
    
    /**
     * 等级名称
     * @var  array
     */
    public static $levelName = [
        self::LEVEL_ORDINARY => '普通',
        self::LEVEL_URGENT => '加急',
    ];
    /**
     * 默认状态
     * @var array 
     */
    public static $defaultStatus = [
        self::STATUS_DEFAULT,
        self::STATUS_WAITCHECK,
        self::STATUS_ADJUSTMENTING,
        self::STATUS_CHECKING,
        self::STATUS_WAITASSIGN,
        self::STATUS_WAITUNDERTAKE,
        self::STATUS_TOSTART,
        self::STATUS_WORKING,
        self::STATUS_WAITACCEPTANCE,
        self::STATUS_UPDATEING,
        self::STATUS_ACCEPTANCEING,
       
    ];
    /**
     * 状态名称
     * @var array 
     */
    public static $statusNmae = [
        self::STATUS_DEFAULT => '创建中',
        self::STATUS_WAITCHECK => '待审核',
        self::STATUS_ADJUSTMENTING => '调整中',
        self::STATUS_CHECKING => '审核中',
        self::STATUS_WAITASSIGN => '待指派',
        self::STATUS_WAITUNDERTAKE => '待承接',
        self::STATUS_TOSTART => '待开始',
        self::STATUS_WORKING => '制作中',
        self::STATUS_WAITACCEPTANCE => '待验收',
        self::STATUS_UPDATEING => '修改中',
        self::STATUS_ACCEPTANCEING => '验收中',
        self::STATUS_COMPLETED => '已完成',
        self::STATUS_CANCEL => '已取消',
    ];
    /**
     * 状态下对应的进度
     * @var array 
     */
    public static $statusProgress = [
        self::STATUS_WAITCHECK => 5,
        self::STATUS_ADJUSTMENTING => 5,
        self::STATUS_CHECKING => 5,
        self::STATUS_WAITASSIGN => 10,
        self::STATUS_WAITUNDERTAKE => 10,
        self::STATUS_TOSTART => 50,
        self::STATUS_WORKING => 50,
        self::STATUS_WAITACCEPTANCE => 80,
        self::STATUS_UPDATEING => 80,
        self::STATUS_ACCEPTANCEING => 80,
        self::STATUS_COMPLETED => 100,
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_task}}';
    }
    
    public function scenarios() 
    {
        return [
            self::SCENARIO_CREATE => [
                'item_type_id', 'item_id', 'item_child_id', 'course_id', 'task_type_id', 
                'level', 'external_team', 'create_team', 'created_at', 'updated_at',
                'budget_cost', 'budget_bonus',
                'des', 'name', 'plan_end_time', 'create_by'
            ],
            self::SCENARIO_UPDATE => [
                'item_type_id', 'item_id', 'item_child_id', 'course_id', 'task_type_id', 
                'level', 'external_team', 'create_team', 'created_at', 'updated_at',
                'budget_cost', 'budget_bonus',
                'des', 'name', 'plan_end_time', 'create_by'
            ],
            self::SCENARIO_DEFAULT => [
                'item_type_id', 'item_id', 'item_child_id', 'course_id', 'task_type_id', 
                'level', 'is_brace', 'is_epiboly', 'external_team', 'status', 'progress', 'create_team', 
                'index', 'is_delete', 'created_at', 'updated_at', 'finished_at',
                'budget_cost', 'reality_cost', 'budget_bonus', 'reality_bonus',
                'des', 'name', 'plan_end_time', 'create_by'
            ]
        ];
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
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'task_type_id', 'name', 'plan_end_time', 'create_team'], 'required', 'on'=>[self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'task_type_id', 'level', 'is_brace', 'is_epiboly', 'external_team', 'status', 'progress', 'create_team', 'index', 'is_delete', 'created_at', 'updated_at', 'finished_at'], 'integer'],
            [['budget_cost', 'reality_cost', 'budget_bonus', 'reality_bonus'], 'number'],
            [['des'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['plan_end_time'], 'string', 'max' => 60],
            [['create_by'], 'string', 'max' => 36],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['item_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemType::className(), 'targetAttribute' => ['item_type_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['item_child_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_child_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['task_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorksystemTaskType::className(), 'targetAttribute' => ['task_type_id' => 'id']],
            [['create_team'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['create_team' => 'id']],
            [['external_team'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['external_team' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
            'item_type_id' => Yii::t('rcoa/worksystem', 'Item Type ID'),
            'item_id' => Yii::t('rcoa/worksystem', 'Item ID'),
            'item_child_id' => Yii::t('rcoa/worksystem', 'Item Child ID'),
            'course_id' => Yii::t('rcoa/worksystem', 'Course ID'),
            'task_type_id' => Yii::t('rcoa/worksystem', 'Task Type ID'),
            'name' => Yii::t('rcoa/worksystem', 'Name'),
            'level' => Yii::t('rcoa/worksystem', 'Level'),
            'is_brace' => Yii::t('rcoa/worksystem', 'Is Brace'),
            'is_epiboly' => Yii::t('rcoa/worksystem', 'Is Epiboly'),
            'budget_cost' => Yii::t('rcoa/worksystem', 'Budget Cost'),
            'reality_cost' => Yii::t('rcoa/worksystem', 'Reality Cost'),
            'budget_bonus' => Yii::t('rcoa/worksystem', 'Budget Bonus'),
            'reality_bonus' => Yii::t('rcoa/worksystem', 'Reality Bonus'),
            'plan_end_time' => Yii::t('rcoa/worksystem', 'Plan End Time'),
            'external_team' => Yii::t('rcoa/worksystem', 'External Team'),
            'status' => Yii::t('rcoa', 'Status'),
            'progress' => Yii::t('rcoa', 'Progress'),
            'create_team' => Yii::t('rcoa/worksystem', 'Create Team'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'index' => Yii::t('rcoa', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa/worksystem', 'Created At'),
            'updated_at' => Yii::t('rcoa/worksystem', 'Updated At'),
            'finished_at' => Yii::t('rcoa/worksystem', 'Finished At'),
            'des' => Yii::t('rcoa/worksystem', 'Des'),
        ];
    }

    /**
     * 获取所有附加属性
     * @return ActiveQuery
     */
    public function getWorksystemAddAttributes()
    {
        return $this->hasMany(WorksystemAddAttributes::className(), ['worksystem_task_id' => 'id']);
    }

    /**
     * 获取所有内容信息
     * @return ActiveQuery
     */
    public function getWorksystemContentinfos()
    {
        return $this->hasMany(WorksystemContentinfo::className(), ['worksystem_task_id' => 'id']);
    }

    /**
     * 获取所有操作记录
     * @return ActiveQuery
     */
    public function getWorksystemOperations()
    {
        return $this->hasMany(WorksystemOperation::className(), ['worksystem_task_id' => 'id']);
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
     * 获取基础行业
     * @return ActiveQuery
     */
    public function getItemType()
    {
        return $this->hasOne(ItemType::className(), ['id' => 'item_type_id']);
    }

    /**
     * 获取基础层次/类型
     * @return ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * 获取基础专业/工种
     * @return ActiveQuery
     */
    public function getItemChild()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_child_id']);
    }

    /**
     * 获取基础课程
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Item::className(), ['id' => 'course_id']);
    }
    
    /**
     * 获取工作系统任务类别
     * @return ActiveQuery
     */
    public function getWorksystemTaskType()
    {
        return $this->hasOne(WorksystemTaskType::className(), ['id' => 'task_type_id']);
    }

    /**
     * 获取创建团队
     * @return ActiveQuery
     */
    public function getCreateTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'create_team']);
    }

    /**
     * 获取外部团队
     * @return ActiveQuery
     */
    public function getExternalTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'external_team']);
    }

    /**
     * 获取所有制作人员
     * @return ActiveQuery
     */
    public function getWorksystemTaskProducers()
    {
        return $this->hasMany(WorksystemTaskProducer::className(), ['worksystem_task_id' => 'id']);
    }
    
    /**
     * 获取是否在【默认】状态
     * @return boolean
     */
    public function getIsStatusDefault()
    {
        return $this->status == self::STATUS_DEFAULT;
    }
    
    /**
     * 获取是否在【待审核】状态
     * @return boolean
     */
    public function getIsStatusWaitCheck()
    {
        return $this->status == self::STATUS_WAITCHECK;
    }
    
    /**
     * 获取是否在【调整中】状态
     * @return boolean
     */
    public function getIsStatusAdjustmenting()
    {
        return $this->status == self::STATUS_ADJUSTMENTING;
    }
    
    /**
     * 获取是否在【审核中】状态
     * @return boolean
     */
    public function getIsStatusChecking()
    {
        return $this->status == self::STATUS_CHECKING;
    }
    
    /**
     * 获取是否在【待指派】状态
     * @return boolean
     */
    public function getIsStatusWaitAssign()
    {
        return $this->status == self::STATUS_WAITASSIGN;
    }
    
    /**
     * 获取是否在【待承接】状态
     * @return boolean
     */
    public function getIsStatusWaitUndertake()
    {
        return $this->status == self::STATUS_WAITUNDERTAKE;
    }
    
    /**
     * 获取是否在【待开始】状态
     * @return boolean
     */
    public function getIsStatusToStart()
    {
        return $this->status == self::STATUS_TOSTART;
    }
    
    /**
     * 获取是否在【制作中】状态
     * @return boolean
     */
    public function getIsStatusWorking()
    {
        return $this->status == self::STATUS_WORKING;
    }
    
    /**
     * 获取是否在【待验收】状态
     * @return boolean
     */
    public function getIsStatusWaitAcceptance()
    {
        return $this->status == self::STATUS_WAITACCEPTANCE;
    }
    
    /**
     * 获取是否在【修改中】状态
     * @return boolean
     */
    public function getIsStatusUpdateing()
    {
        return $this->status == self::STATUS_UPDATEING;
    }
    
    /**
     * 获取是否在【验收中】状态
     * @return boolean
     */
    public function getIsStatusAcceptanceing()
    {
        return $this->status == self::STATUS_ACCEPTANCEING;
    }
    
    /**
     * 获取是否在【已完成】状态
     * @return boolean
     */
    public function getIsStatusCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }
    
    /**
     * 获取是否在【已取消】状态
     * @return boolean
     */
    public function getIsStatusCancel()
    {
        return $this->status == self::STATUS_CANCEL;
    }
    
    /**
     * 获取是否【寻求支撑】
     * @return boolean
     */
    public function getIsSeekBrace()
    {
        return $this->is_brace == self::SEEK_BRACE_MARK;
    }
    
    /**
     * 获取是否【取消支撑】
     * @return boolean
     */
    public function getIsCancelBrace()
    {
        return $this->is_brace == self::CANCEL_BRACE_MARK;
    }
    
    /**
     * 获取是否【寻求外包】
     * @return boolean
     */
    public function getIsSeekEpiboly()
    {
        return $this->is_epiboly == self::SEEK_EPIBOLY_MARK;
    }
    
    /**
     * 获取是否【取消外包】
     * @return boolean
     */
    public function getIsCancelEpiboly()
    {
        return $this->is_epiboly == self::CANCEL_EPIBOLY_MARK;
    }
    
    /**
     * 获取状态名称
     * @return string
     */
    public function getStatusName()
    {
        return self::$statusNmae[$this->status];
    }
    
    /**
     * 获取状态进度
     * @return string
     */
    public function getStatusProgress()
    {
        return self::$statusProgress[$this->status];
    }
}
