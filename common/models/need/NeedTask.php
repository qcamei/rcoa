<?php

namespace common\models\need;

use common\models\Company;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%need_task}}".
 *
 * @property string $id             需求ID
 * @property string $company_id     所属公司
 * @property string $business_id    行业ID
 * @property string $layer_id       层次/类型ID
 * @property string $profession_id  专业/工种ID
 * @property string $course_id      课程ID
 * @property string $task_name      需求名称
 * @property int $level             等级：0普通 1加急
 * @property double $performance_percent 绩效比值
 * @property integer $need_time      需求时间
 * @property integer $finish_time    完成时间
 * @property integer $status        状态：100创建中 200审核中 201审改中 300待承接 301待开始 302开发中 400验收中 401验改中 500已完成
 * @property integer $is_del        是否取消：0否 1是
 * @property string $save_path          成品路径
 * @property string $plan_content_cost  预计内容费用
 * @property string $plan_outsourcing_cost  预计外包费用
 * @property string $reality_content_cost   实际内容费用
 * @property string $reality_outsourcing_cost   实际外包费用
 * @property string $des            需求任务备注
 * @property string $receive_by     承接人
 * @property string $audit_by       审核人
 * @property string $created_by     创建人
 * @property string $created_at     创建时间
 * @property string $updated_at     更新时间
 * 
 * @property Company $company    获取公司
 * @property ItemType $business    获取行业
 * @property Item $layer    获取层次/类型
 * @property Item $profession    获取专业/工种
 * @property Item $course    获取课程
 * @property User $receiveBy    获取承接人
 * @property User $auditBy    获取审核人
 * @property User $createdBy    获取创建者
 * @property NeedContent[] $contents    获取开发内容
 * @property NeedTaskUser[] $taskUsers    获取开发人员
 * @property NeedAttachments[] $attachments    获取需求附件
 */
class NeedTask extends ActiveRecord
{
    /* 临时创建场景 */
    const SCENARIO_TEMP_CREATE = 'tempCreate';
    /** 普通等级 */
    const LEVEL_ORDINARY = 0;
    /** 加急等级 */
    const LEVEL_URGENT = 1;
    /** 默认状态 */
    const STATUS_DEFAULT = 0;
    /** 任务尚在创建中 */
    const STATUS_CREATEING = 100;
    /** 任务已发出，审核中 */
    const STATUS_AUDITING = 200;
    /** 任务未通过审核，正审改中 */
    const STATUS_CHANGEAUDIT = 201;
    /** 任务通过审核，等待承接 */
    const STATUS_WAITRECEIVE = 300;
    /** 任务已承接，等待开始 */
    const STATUS_WAITSTART = 301;
    /** 任务已开始，正开发中 */
    const STATUS_DEVELOPING = 302;
    /** 任务完成开发，正验收中 */
    const STATUS_CHECKING = 400;
    /** 任务未通过验收，正验改中 */
    const STATUS_CHANGECHECK = 401;
    /** 任务通过验收，任务结束 */
    const STATUS_FINISHED = 500;
    
    /**
     * 等级
     * @var array 
     */
    public static $levelMap = [
        self::LEVEL_ORDINARY => '普通',
        self::LEVEL_URGENT => '加急'
    ];
    
    /**
     * 状态
     * @var array 
     */
    public static $statusMap = [
        self::STATUS_CREATEING => '创建中',
        self::STATUS_AUDITING => '审核中',
        self::STATUS_CHANGEAUDIT => '审改中',
        self::STATUS_WAITRECEIVE => '待承接',
        self::STATUS_WAITSTART => '待开始',
        self::STATUS_DEVELOPING => '开发中',
        self::STATUS_CHECKING => '验收中',
        self::STATUS_CHANGECHECK => '验改中',
        self::STATUS_FINISHED => '已完成',
    ];
    
    /**
     * 默认状态
     * @var array 
     */
    public static $defaultMap = [
        self::STATUS_CREATEING,
        self::STATUS_AUDITING,
        self::STATUS_CHANGEAUDIT,
        self::STATUS_WAITRECEIVE,
        self::STATUS_WAITSTART,
        self::STATUS_DEVELOPING,
        self::STATUS_CHECKING,
        self::STATUS_CHANGECHECK,
    ];
    
    /**
     * 进度
     * @var array 
     */
    public static $progressMap = [
        self::STATUS_CREATEING => 5,
        self::STATUS_AUDITING => 10,
        self::STATUS_CHANGEAUDIT => 10,
        self::STATUS_WAITRECEIVE => 20,
        self::STATUS_WAITSTART => 50,
        self::STATUS_DEVELOPING => 80,
        self::STATUS_CHECKING => 90,
        self::STATUS_CHANGECHECK => 90,
        self::STATUS_FINISHED => 100,
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%need_task}}';
    }

    public function scenarios() {
        return [
            self::SCENARIO_DEFAULT => [
                'business_id', 'layer_id', 'profession_id', 'course_id', 'task_name', 'level', 'performance_percent', 'need_time', 'content_type', 'booker_id',
                'finish_time', 'status', 'is_del', 'save_path', 'plan_content_cost', 'plan_outsourcing_cost', 'reality_content_cost', 'reality_outsourcing_cost',
                'des', 'receive_by', 'audit_by', 'created_by'
            ],
            self::SCENARIO_TEMP_CREATE => [
                'id', 'company_id', 'created_by',
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors() 
    {
        return [
            TimestampBehavior::class
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id'], 'required'],
            [['business_id', 'layer_id', 'profession_id', 'course_id', 'task_name', 'need_time'], 'required'],
            [['company_id', 'business_id', 'layer_id', 'profession_id', 'course_id', 'level', 'finish_time', 'status', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['performance_percent', 'plan_content_cost', 'plan_outsourcing_cost', 'reality_content_cost', 'reality_outsourcing_cost'], 'number'],
            [['id'], 'string', 'max' => 32],
            [['task_name'], 'string', 'max' => 50],
            [['save_path', 'des'], 'string', 'max' => 255],
            [['receive_by', 'audit_by', 'created_by'], 'string', 'max' => 36],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'company_id' => Yii::t('app', 'Company ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'layer_id' => Yii::t('app', 'Layer ID'),
            'profession_id' => Yii::t('app', 'Profession ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'task_name' => Yii::t('app', 'Task Name'),
            'level' => Yii::t('app', 'Level'),
            'performance_percent' => Yii::t('app', 'Performance Percent'),
            'need_time' => Yii::t('app', 'Need Time'),
            'finish_time' => Yii::t('app', 'Finish Time'),
            'status' => Yii::t('app', 'Status'),
            'is_del' => Yii::t('app', 'Is Del'),
            'save_path' => Yii::t('app', 'Save Path'),
            'plan_content_cost' => Yii::t('app', 'Plan Content Cost'),
            'plan_outsourcing_cost' => Yii::t('app', 'Plan Outsourcing Cost'),
            'reality_content_cost' => Yii::t('app', 'Reality Content Cost'),
            'reality_outsourcing_cost' => Yii::t('app', 'Reality Outsourcing Cost'),
            'des' => Yii::t('app', 'Des'),
            'receive_by' => Yii::t('app', 'Receive By'),
            'audit_by' => Yii::t('app', 'Audit By'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    public function beforeSave($insert) 
    {
        if (parent::beforeSave($insert)) {
            if($this->scenario != self::SCENARIO_TEMP_CREATE){
                if(!is_numeric($this->need_time)){
                    $this->need_time = strtotime($this->need_time);
                }
            }
            return true;
        }
        
        return false;
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(ItemType::class, ['id' => 'business_id']);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getLayer()
    {
        return $this->hasOne(Item::className(), ['id' => 'layer_id']);
    }

    /**
     * 
     * @return ActiveQuery
     */
    public function getProfession()
    {
        return $this->hasOne(Item::class, ['id' => 'profession_id']);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Item::class, ['id' => 'course_id']);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getReceiveBy()
    {
        return $this->hasOne(User::class, ['id' => 'receive_by']);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getAuditBy()
    {
        return $this->hasOne(User::class, ['id' => 'audit_by']);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(NeedContent::class, ['need_task_id' => 'id'])
            ->where(['is_del' => 0])->orderBy(['sort_order' => SORT_ASC, 'is_new' => SORT_ASC]);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getTaskUsers()
    {
        return $this->hasMany(NeedTaskUser::class, ['need_task_id' => 'id'])
            ->where(['is_del' => 0])->orderBy(['privilege' => SORT_DESC]);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(NeedAttachments::class, ['need_task_id' => 'id'])
            ->where(['is_del' => 0]);
    }
    
    /**
     * 获取是否在【默认】状态
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->status == self::STATUS_DEFAULT;
    }
    
    /**
     * 获取是否在【创建中】状态
     * @return boolean
     */
    public function getIsCreateing()
    {
        return $this->status == self::STATUS_CREATEING;
    }
    
    /**
     * 获取是否在【审核中】状态
     * @return boolean
     */
    public function getIsAuditing()
    {
        return $this->status == self::STATUS_AUDITING;
    }
    
    /**
     * 获取是否在【审改中】状态
     * @return boolean
     */
    public function getIsChangeAudit()
    {
        return $this->status == self::STATUS_CHANGEAUDIT;
    }
    
    /**
     * 获取是否在【待承接】状态
     * @return boolean
     */
    public function getIsWaitReceive()
    {
        return $this->status == self::STATUS_WAITRECEIVE;
    }
    
    /**
     * 获取是否在【待开始】状态
     * @return boolean
     */
    public function getIsWaitStart()
    {
        return $this->status == self::STATUS_WAITSTART;
    }
    
    /**
     * 获取是否在【开发中】状态
     * @return boolean
     */
    public function getIsDeveloping()
    {
        return $this->status == self::STATUS_DEVELOPING;
    }
    
    /**
     * 获取是否在【验收中】状态
     * @return boolean
     */
    public function getIsChecking()
    {
        return $this->status == self::STATUS_CHECKING;
    }
    
    /**
     * 获取是否在【验改中】状态
     * @return boolean
     */
    public function getIsChangeCheck()
    {
        return $this->status == self::STATUS_CHANGECHECK;
    }
    
    /**
     * 获取是否在【已完成】状态
     * @return boolean
     */
    public function getIsFinished()
    {
        return $this->status == self::STATUS_FINISHED;
    }
  
    /**
     * 获取状态名称
     * @return string
     */
    public function getStatusName()
    {
        return self::$statusMap[$this->status];
    }
}
