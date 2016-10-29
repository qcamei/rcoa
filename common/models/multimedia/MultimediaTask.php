<?php

namespace common\models\multimedia;

use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%multimedia_manage}}".
 *
 * @property integer $id                                ID
 * @property integer $item_type_id                      行业ID
 * @property integer $item_id                           层次/类型ID
 * @property integer $item_child_id                     专业/工种ID
 * @property integer $course_id                         课程ID
 * @property string $name                               任务名称
 * @property integer $format_video_length             素材视频时长
 * @property integer $production_video_length           成品视频时长
 * @property integer $progress                          进度
 * @property integer $content_type                      任务类型
 * @property string $plan_end_time                      要求完成时间
 * @property integer $level                             等级
 * @property integer $make_team                         制作团队
 * @property integer $status                            状态
 * @property string $path                               框架表路径
 * @property integer $create_team                       创建团队
 * @property string $create_by                          创建者
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 * @property string $real_carry_out                     完成时间
 * @property string $des                                描述
 * @property integer $brace_mark                        支撑标识
 *
 * @property MultimediaContentType $contentType         获取任务类型
 * @property Team $createTeam                           获取创建团队
 * @property Item $itemChild                            获取专业/工种
 * @property Item $item                                 获取层次/类型
 * @property ItemType $itemType                         获取行业
 * @property Team $makeTeam                             获取制作团队
 * @property Item $course                               获取课程
 * @property User $createBy                             获取创建者
 * @property MultimediaCheck[] $multimediaChecks        获取所有的审核记录 
 * @property MultimediaProducer[] $producers            获取所有制作人
 * @property TeamMember[] $teamMember                   获取所有团队成员
 */
class MultimediaTask extends ActiveRecord
{
    
    /** 已完成场景 */
    const SCENARIO_COMPLETE = 'complete';
    /** 普通等级 */
    const LEVEL_ORDINARY = 0;
    /** 加急等级 */
    const LEVEL_URGENT = 1;
    /** 取消支撑 */
    const  CANCEL_BRACE_MARK = 0;
    /** 寻求支撑 */
    const  SEEK_BRACE_MARK = 1;
    /** 任务刚发出， 【待指派】 */
    const STATUS_ASSIGN = 5;
    /** 任务已经分派制作人，等待开始制作，【待开始】 */
    const STATUS_TOSTART = 10;
    /** 任务已经开始在制作中， 【制作中】 */
    const STATUS_WORKING = 11;
    /** 任务已经制作完成，等待审核 【待审核】 */
    const STATUS_WAITCHECK = 12;
    /**任务已经添加审核，等待修改 【修改中】 */
    const STATUS_UPDATEING = 13;
    /** 任务修改完成提交，等待继续审核 【审核中】 */
    const STATUS_CHECKING = 14;
    /** 任务已通过审核，任务结束， 【已完成】 */
    const STATUS_COMPLETED = 15;
    /** 因客观原因需要改期或者取消原定任务， 【已取消】 */
    const STATUS_CANCEL = 99;
    
    /**
     * 任务id
     * @var array 
     */
    public static $taskIds;
    /**
     * 多媒体操作
     * @var array 
     */
    public static $operation = [];
    /**
     * 总的标准工作量
     * @var number 
     
    public $total_workloa;*/
    /**
     * 剩余的标准工作量
     * @var number 
     
    public $surplus_workloa;*/
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
        self::STATUS_ASSIGN,
        self::STATUS_TOSTART,
        self::STATUS_WORKING,
        self::STATUS_WAITCHECK,
        self::STATUS_UPDATEING,
        self::STATUS_CHECKING,
    ];
    /**
     * 状态名称
     * @var array 
     */
    public static $statusNmae = [
        self::STATUS_ASSIGN => '待指派',
        self::STATUS_TOSTART => '待开始',
        self::STATUS_WORKING => '制作中',
        self::STATUS_WAITCHECK => '待审核',
        self::STATUS_UPDATEING => '修改中',
        self::STATUS_CHECKING => '审核中',
        self::STATUS_COMPLETED => '已完成',
        self::STATUS_CANCEL => '已取消',
    ];
    /**
     * 状态下对应的进度
     * @var array 
     */
    public static $statusProgress = [
        self::STATUS_ASSIGN => 0,
        self::STATUS_TOSTART => 5,
        self::STATUS_WORKING => 10,
        self::STATUS_WAITCHECK => 80,
        self::STATUS_UPDATEING => 80,
        self::STATUS_CHECKING => 80,
        self::STATUS_COMPLETED => 100,
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%multimedia_task}}';
    }

    public function scenarios() 
    {
        return [
            
            self::SCENARIO_COMPLETE => [
                'production_video_length'
            ],
            self::SCENARIO_DEFAULT => [
                'id', 'item_type_id', 'item_id', 'item_child_id', 'course_id', 'name', 'material_video_length', 
                'content_type','level', 'path', 'make_team', 'status', 'create_team', 'created_at', 
                'updated_at', 'brace_mark', 'name', 'des', 'plan_end_time', 'real_carry_out', 'create_by'
            ],
        ];
    }
    
    public function behaviors() {
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
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'name', 'material_video_length', 'content_type', 'level', 'path'], 'required'],
            [['production_video_length'], 'required', 'on' => [self::SCENARIO_COMPLETE]],
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'progress', 'content_type', 'level', 'make_team', 'status', 'create_team', 'created_at', 'updated_at', 'brace_mark'], 'integer'],
            [['material_video_length', 'production_video_length'], 'checkVideoLen'],
            [['name', 'path', 'des'], 'string', 'max' => 255],
            [['plan_end_time', 'real_carry_out'], 'string', 'max' => 60],
            [['create_by'], 'string', 'max' => 36],
            [['content_type'], 'exist', 'skipOnError' => true, 'targetClass' => MultimediaContentType::className(), 'targetAttribute' => ['content_type' => 'id']],
            [['create_team'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['create_team' => 'id']],
            [['item_child_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_child_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['item_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemType::className(), 'targetAttribute' => ['item_type_id' => 'id']],
            [['make_team'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['make_team' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
        ];
    }

    /**
     * 检验视频时长格式是否正确
     * @param string $attribute     video_length
     * @param string $params
     */
    public function checkVideoLen($attribute, $params)
    {
        $format = $this->getAttribute($attribute);  
        if(!is_numeric($format))  
        {  
            if(strpos($format ,":"))  
            {  
                $times =  explode(":", $format);  
            }else if(strpos($format ,'：')){  
                $times =  explode(":", $format);  
            }else  
            {  
                $this->addError($attribute, "格式不正确，请按 00:00:00 格式录入！");  
                return false;  
            }  
            $h = (int)$times[0] ;  
            $m = (int)$times[1];  
            $s = count($times) == 3 ? (int)$times[2] : 0;  
            $videolength = $h*3600+$m*60+$s;  
   
            if($videolength > 0){  
                $this->setAttribute($attribute, $videolength);  
            }  
            else  
            {  
                $this->addError($attribute, "视频时长不可以为0。");  
                return false;  
            }  
        }  
        return true; 
        
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/multimedia', 'ID'),
            'item_type_id' => Yii::t('rcoa/multimedia', 'Item Type'),
            'item_id' => Yii::t('rcoa/multimedia', 'Item'),
            'item_child_id' => Yii::t('rcoa/multimedia', 'Item Child'),
            'course_id' => Yii::t('rcoa/multimedia', 'Course'),
            'name' => Yii::t('rcoa/multimedia', 'Name'),
            'material_video_length' => Yii::t('rcoa/multimedia', 'Material Video Length'),
            'production_video_length' => Yii::t('rcoa/multimedia', 'Production Video Length'),
            'progress' => Yii::t('rcoa/multimedia', 'Progress'),
            'content_type' => Yii::t('rcoa/multimedia', 'Content Type'),
            'plan_end_time' => Yii::t('rcoa/multimedia', 'Plan End Time'),
            'level' => Yii::t('rcoa/multimedia', 'Level'),
            'make_team' => Yii::t('rcoa/multimedia', 'Make Team'),
            'status' => Yii::t('rcoa/multimedia', 'Status'),
            'path' => Yii::t('rcoa/multimedia', 'Path'),
            'create_team' => Yii::t('rcoa/multimedia', 'Create Team'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa/multimedia', 'Created At'),
            'updated_at' => Yii::t('rcoa/multimedia', 'Updated At'),
            'real_carry_out' => Yii::t('rcoa/multimedia', 'Real Carry Out'),
            'des' => Yii::t('rcoa/multimedia', 'Des'),
            'brace_mark' => Yii::t('rcoa/multimedia', 'Brace Mark'),
        ];
    }

    /**
     * 获取任务类型
     * @return ActiveQuery
     */
    public function getContentType()
    {
        return $this->hasOne(MultimediaContentType::className(), ['id' => 'content_type']);
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
     * 获取制作团队
     * @return ActiveQuery
     */
    public function getMakeTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'make_team']);
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
     * 获取所有的审核记录 
     * @return ActiveQuery
     */
    public function getMultimediaChecks()
    {
        return $this->hasMany(MultimediaCheck::className(), ['task_id' => 'id']);
    }

    /**
     * 获取所有制作人
     * @return ActiveQuery
     */
    public function getProducers()
    {
        return $this->hasMany(MultimediaProducer::className(), ['task_id' => 'id']);
    }

    /**
     * 获取所有团队成员
     * @return ActiveQuery
     */
    public function getTeamMember()
    {
        return $this->hasMany(TeamMember::className(), ['id' => 'producer'])
               ->viaTable('{{%multimedia_producer}}', ['task_id' => 'id'])
               ->with('user');
    }
    
    /**
     * 获取是否在【待指派】状态
     * @return type
     */
    public function getIsStatusAssign()
    {
        return $this->status == self::STATUS_ASSIGN;
    }
    
    /**
     * 获取是否在【待开始】状态
     * @return type
     */
    public function getIsStatusTostart()
    {
        return $this->status == self::STATUS_TOSTART;
    }
    
    /**
     * 获取是否在【制作中】状态
     * @return type
     */
    public function getIsStatusWorking()
    {
        return $this->status == self::STATUS_WORKING;
    }
    
    /**
     * 获取是否在【待审核】状态
     * @return type
     */
    public function getIsStatusWaitCheck()
    {
        return $this->status == self::STATUS_WAITCHECK;
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
     * 获取是否在【审核中】状态
     * @return type
     */
    public function getIsStatusChecking()
    {
        return $this->status == self::STATUS_CHECKING;
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
     * 获取是否在【待开始】后状态
     * @return type
     */
    public function getIsStatusStartAfter()
    {
        return $this->status > self::STATUS_TOSTART;
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
     * 获取状态下对应的进度
     * @return type
     */
    public function getStatusProgress()
    {
        return self::$statusProgress[$this->status];
    }
}
