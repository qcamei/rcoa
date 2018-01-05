<?php

namespace common\models\scene;

use common\models\expert\Expert;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%scene_book}}".
 *
 * @property string $id             ID=MD5(site_id+Ymd+time_index)
 * @property string $site_id        场地ID
 * @property string $date           预定日期
 * @property integer $time_index    时间段0、1、2
 * @property string $status         状态
 * @property string $business_id    行业ID
 * @property string $level_id       层次/类型ID
 * @property string $profession_id  专业/工种
 * @property string $course_id      课程ID
 * @property integer $lession_time  课时
 * @property integer $content_type  内容类型：1板书、2蓝箱、3外拍、4白布、5书架
 * @property integer $shoot_mode    摄影模式：1标清 2高清
 * @property integer $is_photograph 是否拍照：0否，1是
 * @property integer $camera_count  机位数
 * @property string $start_time     开始时间
 * @property string $remark         备注
 * @property integer $is_transfer   是否为转让状态：0否，1是
 * @property string $teacher_id     老师ID
 * @property string $booker_id      预定人ID
 * @property string $created_by     创建人ID
 * @property string $created_at 
 * @property string $updated_at
 * @property string $ver            乐观锁,内容版本控制
 *
 * @property ItemType $business                 获取行业
 * @property Item $level                        获取层次/类型
 * @property Item $profession                   获取专业/工种
 * @property Item $course                       获取课程
 * @property Expert $teacher                    获取老师
 * @property User $booker                       获取预约人
 * @property User $createdBy                    获取创建者
 * @property SceneAppraise[] $sceneAppraises
 * @property SceneBookUser[] $sceneBookUsers
 * @property SceneMessage[] $sceneMessages
 */
class SceneBook extends ActiveRecord
{
    /** 预约超时限制  */
    const BOOKING_TIMEOUT = 2*60;
    /** 失约超时 */
    const STATUS_BREAK_PROMISE_TIMEOUT = 72*60*60;
    
    /** 默认状态 未预约 */
    const STATUS_DEFAULT = 100;
    /** 预约进行中 */
    const STATUS_BOOKING = 200;
    /** 委派状态,任务刚发出 */
    const STATUS_ASSIGN = 205;
    /** 拍摄中状态,已经分派摄影师，等待拍摄完成后评价 */
    const STATUS_SHOOTING = 300;
    /** 完成拍摄 评价状态 */
    const STATUS_APPRAISE = 305;
    /** 已完成,评价完成，任务结束 */
    const STATUS_COMPLETED = 500;
    /** 已失约,因其它问题导致在预定时间里没能完成拍摄任务，失约 */
    const STATUS_BREAK_PROMISE = 400;
    /** 已取消,因客观原因需要改期或者取消原定的拍摄任务，需要提前2天操作 */
    const STATUS_CANCEL = 900;

    /** 拍摄模式-标清 */
    const SHOOT_MODE_SD = 1;
    /** 拍摄模式-高清 */
    const SHOOT_MODE_HD = 2;
    
    /** 内容类型-板书 */
    const CONTENT_TYPE_BOARDBOOK = 1;
    /** 内容类型-蓝箱 */
    const CONTENT_TYPE_BLUEBOX = 2;
    /** 内容类型-外拍 */
    const CONTENT_TYPE_WAIPAI = 3;
    /** 内容类型-白布 */
    const CONTENT_TYPE_WHITECLOTH = 4;
    /** 内容类型-书架 */
    const CONTENT_TYPE_BOOKSHELVES = 5;


    /** 时段 上午 */
    const TIME_INDEX_MORNING = 0;
    /** 时段 下午 */
    const TIME_INDEX_AFTERNOON = 1;
    /** 时段 晚上 */
    const TIME_INDEX_NIGHT = 2;
    /**默认开始时间 上午*/
    const START_TIME_MORNING = '09:15';
    /**默认开始时间 下午午*/
    const START_TIME_AFTERNOON = '13:45';
    /**默认开始时间 晚上*/
    const START_TIME_NIGHT = '19:00';
    /* 临时创建场景 */
    const SCENARIO_TEMP_CREATE = 'tempCreate';

    /** 状态列表 */
    public $statusMap = [
        self::STATUS_DEFAULT => '未预约',
        self::STATUS_BOOKING => '预约中',
        self::STATUS_ASSIGN => '待指派',
        self::STATUS_SHOOTING => '待评价',
        self::STATUS_APPRAISE => '评价中',
        self::STATUS_COMPLETED => '已完成',
        self::STATUS_BREAK_PROMISE => '已失约',
        self::STATUS_CANCEL => '已取消',
    ];
    
    /** 拍摄模式列表 */
    public static $shootModeMap = [
        self::SHOOT_MODE_SD => '标清',
        self::SHOOT_MODE_HD => '高清',
    ];
    
    /** 内容类型列表 */
    public static $contentTypeMap = [
        self::CONTENT_TYPE_BOARDBOOK => '板书',
        self::CONTENT_TYPE_BLUEBOX => '蓝箱',
        self::CONTENT_TYPE_WAIPAI => '外拍',
        self::CONTENT_TYPE_WHITECLOTH => '白布',
        self::CONTENT_TYPE_BOOKSHELVES => '书架',
    ];

        /** 时间段名称 */
    public static $timeIndexMap = [
        self::TIME_INDEX_MORNING => '上',
        self::TIME_INDEX_AFTERNOON => '下',
        self::TIME_INDEX_NIGHT => '晚',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene_book}}';
    }

    /**
     * @inheritdoc
     */
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
            [['id', 'booker_id', 'created_by'], 'required'],
            [['site_id', 'time_index', 'status', 'business_id', 'level_id', 'profession_id', 'course_id', 'lession_time', 'content_type', 'shoot_mode', 'is_photograph', 'camera_count', 'is_transfer', 'created_at', 'updated_at', 'ver'], 'integer'],
            [['date'], 'safe'],
            [['id'], 'string', 'max' => 32],
            [['start_time'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 255],
            [['teacher_id', 'booker_id', 'created_by'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'site_id' => Yii::t('app', 'Site ID'),
            'date' => Yii::t('app', 'Date'),
            'time_index' => Yii::t('app', 'Time Index'),
            'status' => Yii::t('app', 'Status'),
            'business_id' => Yii::t('app', 'Business ID'),
            'level_id' => Yii::t('app', 'Level ID'),
            'profession_id' => Yii::t('app', 'Profession ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'lession_time' => Yii::t('app', 'Lession Time'),
            'content_type' => Yii::t('app', 'Content Type'),
            'shoot_mode' => Yii::t('app', 'Shoot Mode'),
            'is_photograph' => Yii::t('app', 'Is Photograph'),
            'camera_count' => Yii::t('app', 'Camera Count'),
            'start_time' => Yii::t('app', 'Start Time'),
            'remark' => Yii::t('app', 'Remark'),
            'is_transfer' => Yii::t('app', 'Is Transfer'),
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'booker_id' => Yii::t('app', 'Booker ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'ver' => Yii::t('app', 'Ver'),
        ];
    }

    /**
     * 获取基础行业
     * @return ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(ItemType::className(), ['id' => 'business_id']);
    }

    /**
     * 获取基础层次/类型
     * @return ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Item::className(), ['id' => 'level_id']);
    }

    /**
     * 获取基础专业/工种
     * @return ActiveQuery
     */
    public function getProfession()
    {
        return $this->hasOne(Item::className(), ['id' => 'profession_id']);
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
     * 获取预约人
     * @return ActiveQuery
     */
    public function getBooker()
    {
        return $this->hasOne(User::className(), ['id' => 'booker_id']);
    }
    
    /**
     * 获取创建者
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
    /**
     * 获取老师
     * @return ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Expert::className(), ['u_id' => 'teacher_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getSceneActionLogs()
    {
        return $this->hasMany(SceneActionLog::className(), ['book_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSceneAppraises()
    {
        return $this->hasMany(SceneAppraise::className(), ['book_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSceneBookUsers()
    {
        return $this->hasMany(SceneBookUser::className(), ['book_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSceneMessages()
    {
        return $this->hasMany(SceneMessage::className(), ['book_id' => 'id']);
    }
    
    /**
     * 获取状态显示
     * @return string
     */
    public function getStatusName()
    {
        return $this->statusMap[$this->status];
    }
    
    /**
     * 获取是否为【未预约】
     * @return bool
     */
    public function getIsNew()
    {
        return $this->status == self::STATUS_DEFAULT;
    }
    
    /**
     * 获取是滞为有效果数据
     * 新建或者临时创建为无效数据
     */
    public function getIsValid()
    {
        return $this->status != self::STATUS_DEFAULT && $this->status != self::STATUS_BOOKING;
    }
    
    /**
     * 获取是否在【预约中】状态
     * @return bool 
     */
    public function getIsBooking()
    {
        return $this->status == self::STATUS_BOOKING;
    }
    
    /**
     * 获取是否在【待指派】状态
     */
    public function getIsAssign()
    {
        return $this->status == self::STATUS_ASSIGN;
    }
    
    /**
     * 获取是否在【评价中】状态
     */
    public function getIsAppraise()
    {
        return $this->status == self::STATUS_APPRAISE;
    }
    
    /**
     * 获取是否在【待评价】状态
     */
    public function getIsStausShootIng()
    {
        return $this->status == self::STATUS_SHOOTING;
    }
    /**
     * 获取是否在【已失约】状态
     */
    public function getIsStatusBreakPromise()
    {
        return $this->status == self::STATUS_BREAK_PROMISE;
    }
    
    /**
     * 获取是否在【已完成】状态
     */
    public function getIsStatusCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }
    
    /**
     * 获取是否在【已取消】状态
     */
    public function getIsStatusCancel()
    {
        return $this->status == self::STATUS_CANCEL;
    }

    /**
     * 获取预约锁定剩余时间
     * @return int 秒
     */
    public function getBookTimeRemaining()
    {
        if($this->getIsBooking())
            return self::BOOKING_TIMEOUT - (time() - $this->updated_at);
        else
            return 0;
    }
    
    /**
     * 获取是否可以执行/查看【评价】操作
     */
    public function canAppraise()
    {
        return $this->status >= self::STATUS_SHOOTING;
    }
    
    /**
     * 获取内容类型
     * @return string
     */
    public function getContentTypeName()
    {
        return self::$contentTypeMap[$this->content_type];
    }
    
    /**
     * 获取拍摄模式
     * @return string
     */
    public function getShootModeName()
    {
        return self::$shootModeMap[$this->shoot_mode];
    }
    
    /**
     * 获取时间段名称
     * @return string
     */
    public function getTimeIndexName()
    {
        return self::$timeIndexMap[$this->time_index];
    }
}
