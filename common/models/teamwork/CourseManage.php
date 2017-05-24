<?php

namespace common\models\teamwork;

use common\models\demand\DemandTask;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\teamwork\CourseAnnex;
use common\models\teamwork\ItemManage;
use common\models\User;
use wskeee\framework\models\Item;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course}}".
 *
 * @property integer $id                            ID
 * @property integer $demand_task_id                课程需求ID
 * @property integer $weekly_editors_people         周报编辑人
 * @property double $video_length                   视频时长
 * @property integer $question_mete                 题量
 * @property integer $case_number                   案例数
 * @property integer $activity_number               活动数
 * @property integer $team_id                       创建者所在团队
 * @property string $course_ops                     课程运维负责人
 * @property string $create_by                      创建者
 * @property integer $course_principal              课程负责人
 * @property integer $created_at                    创建于
 * @property integer $update_at                     更新于
 * @property string $plan_start_time                计划开始时间
 * @property string $plan_end_time                  计划完成时间
 * @property string $real_start_time                实际开始时间
 * @property string $real_carry_out                 实际完成时间
 * @property integer $status                        状态
 * @property string $des                            描述
 * @property string $path                           存储服务器路径
 * @property integer $progress                      进度
 *
 * @property CourseAnnex $courseAnnex               获取附件
 * @property CourseLink[] $courseLinks              获取所有课程环节
 * @property TeamMember $coursePrincipal            获取课程负责人
 * @property User $courseOps                        获取课程运维负责人
 * @property Team $team                             获取团队
 * @property User $createBy                         获取创建者
 * @property Item $course                           获取课程
 * @property User $speakerTeacher                   获取主讲讲师
 * @property ItemManage $project                    获取项目
 * @property TeamMember $weeklyEditorsPeople        获取周报编辑人
 * @property CoursePhase[] $coursePhases            获取所有课程阶段
 * @property CourseProducer[] $courseProducers      获取所有制作人
 * @property TeamMember[] $producers                获取所有团队成员
 * @property CourseSummary[] $courseWeeklys         获取所有周报开发人
 * @property CourseSummary[] $courseSummaries       获取所有课程总结
 * @property DemandTask $demandTask                 获取课程需求任务
 */
class CourseManage extends ActiveRecord
{
    /** 待开始场景 */
    const SCENARIO_WAITSTART = 'wait-start';
    /** 已完成场景 */
    const SCENARIO_CARRYOUT = 'carry-out';
    /** 更改团队和负责人场景 */
    const SCENARIO_CHANGE = 'change';
    
    /** 待开始 */
    const STATUS_WAIT_START = 100;
    /** 在建中 */
    const STATUS_NORMAL = 200;
    /** 暂停中 */
    const STATUS_PAUSE = 205;
    /** 已完成 */
    const STATUS_CARRY_OUT = 500;
    
    /** 新建模式 */
    const MODE_NEWBUILT = 0;
    /** 改造模式 */
    const MODE_REFORM = 1;

    /**
     * 进度
     * @var array
     */
    public static $progress = [];
    
    /**
     * 是否存在周报
     * @var array 
     */
    public $isExistWeekly = false ;
    
    /** 状态名 */
    public static $statusName = [
        self::STATUS_WAIT_START => '待开始',
        self::STATUS_NORMAL => '在建中',
        self::STATUS_PAUSE => '暂停中',
        self::STATUS_CARRY_OUT => '已完成',
    ];
    
    /**
     * 模式名称
     * @var array 
     
    public static $modeName = [
        self::MODE_NEWBUILT => '新建',
        self::MODE_REFORM => '改造'
    ];*/

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_course}}';
    }
    
    public function scenarios() 
    {
        return [
            self::SCENARIO_WAITSTART => [
                'real_start_time',
            ],
            self::SCENARIO_CARRYOUT => [
                'course_ops', 'status','video_length', 'question_mete', 'case_number', 'activity_number', 'real_carry_out', 'path'
            ],
            self::SCENARIO_CHANGE => [
               'team_id', 'course_principal'
            ],
            self::SCENARIO_DEFAULT => [
                'id', 'demand_task_id', 'weekly_editors_people', 
                'video_length','question_mete', 'case_number', 'activity_number', 'team_id', 'course_ops', 'create_by', 
                'plan_start_time', 'plan_end_time', 'real_start_time', 'real_carry_out', 'status','des', 'path'
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
            [['demand_task_id',  'question_mete', 'case_number', 'activity_number', 'team_id', 'created_at', 'updated_at', 'status', 'weekly_editors_people'], 'integer'],
            [['demand_task_id',  'weekly_editors_people'], 'required'],
            [['video_length', 'course_ops', 'question_mete', 'case_number', 'activity_number', 'path'], 'required', 'on' => [self::SCENARIO_CARRYOUT]],
            [['course_ops', 'create_by'], 'string', 'max' => 36],
            [['course_principal'], 'integer','on' => [self::SCENARIO_CHANGE]],
            [['team_id', 'course_principal'], 'required', 'on' => [self::SCENARIO_CHANGE]],
            [['plan_start_time', 'plan_end_time', 'real_carry_out'], 'string', 'max' => 60],
            [['real_start_time'], 'string', 'max' => 60, 'on' => [self::SCENARIO_WAITSTART]],
            [['path'], 'string', 'max' => 255],
            [['des'], 'string'],
            [['course_ops'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['course_ops' => 'id']],
            [['weekly_editors_people'], 'exist', 'skipOnError' => true, 'targetClass' => TeamMember::className(), 'targetAttribute' => ['weekly_editors_people' => 'id']],
            [['course_principal'], 'exist', 'skipOnError' => true, 'targetClass' => TeamMember::className(), 'targetAttribute' => ['course_principal' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['teacher'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['teacher' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemManage::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['video_length'],'checkVideoLen'],
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
   
            if($videolength == 0){
                $this->setAttribute($attribute, null);
            }else if($videolength > 0){  
                $this->setAttribute($attribute, $videolength);  
            }else{  
                $this->addError($attribute, Yii::t('rcoa/teamwork', 'Video Length')."不可以小于0。");  
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
            'id' => Yii::t('rcoa/teamwork', 'ID'),
            'project_id' => Yii::t('rcoa/teamwork', 'Project ID'),
            'course_id' => Yii::t('rcoa/teamwork', 'Course ID'),
            'teacher' => Yii::t('rcoa/teamwork', 'Teacher'),
            'mode' => Yii::t('rcoa/teamwork', 'Mode'),
            'weekly_editors_people' => Yii::t('rcoa/teamwork', 'Weekly Editors People'),
            'credit' => Yii::t('rcoa/teamwork', 'Credit'),
            'lession_time' => Yii::t('rcoa/teamwork', 'Lession Time'),
            'video_length' => Yii::t('rcoa/teamwork', 'Video Length'),
            'question_mete' => Yii::t('rcoa/teamwork', 'Question Mete'),
            'case_number' => Yii::t('rcoa/teamwork', 'Case Number'),
            'activity_number' => Yii::t('rcoa/teamwork', 'Activity Number'),
            'team_id' => Yii::t('rcoa/team', 'Team ID'),
            'course_ops' => Yii::t('rcoa/teamwork', 'Course Ops'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'course_principal' => Yii::t('rcoa/teamwork', 'Course Principal'),
            'created_at' => Yii::t('rcoa/teamwork', 'Created At'),
            'updated_at' => Yii::t('rcoa/teamwork', 'Updated At'),
            'plan_start_time' => Yii::t('rcoa/teamwork', 'Plan Start Time'),
            'plan_end_time' => Yii::t('rcoa/teamwork', 'Plan End Time'),
            'real_start_time' => Yii::t('rcoa/teamwork', 'Real Start Time'),
            'real_carry_out' => Yii::t('rcoa/teamwork', 'Real Carry Out'),
            'status' => Yii::t('rcoa', 'Status'),
            'des' => Yii::t('rcoa/teamwork', 'Des'),
            'path' => Yii::t('rcoa/teamwork', 'Path'),
        ];
    }
    
    public function beforeSave($insert) {
        if(parent::beforeSave($insert))
        {
            $this->des = htmlentities($this->des);
            return true;
        }
    }
    public function afterFind() {
        
        $this->des = html_entity_decode($this->des);
    }
    
    /**
     * 获取附件
     * @return ActiveQuery
     */
    public function getCourseAnnex()
    {
        return $this->hasOne(CourseAnnex::className(), ['course_id' => 'id']);
    }
    
    /**
     * 获取所有课程环节
     * @return ActiveQuery
     */
    public function getCourseLinks()
    {
        return $this->hasMany(CourseLink::className(), ['course_id' => 'id']);
    }
    
    /**
     * 获取课程运维负责人
     * @return ActiveQuery
     */
    public function getCourseOps()
    {
        return $this->hasOne(User::className(), ['id' => 'course_ops']);
    }

    /**
     * 获取周报编辑人
     * @return ActiveQuery
     */
    public function getWeeklyEditorsPeople()
    {
        return $this->hasOne(TeamMember::className(), ['id' => 'weekly_editors_people']);
    }
    
    /**
     * 获取团队
     * @return ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
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
     * 获取课程负责人
     * @return ActiveQuery
     */
    public function getCoursePrincipal()
    {
        return $this->hasOne(TeamMember::className(), ['id' => 'course_principal']);
    }
    
    /**
     * 获取课程需求任务
     * @return ActiveQuery
     */
    public function getDemandTask()
    {
        return $this->hasOne(DemandTask::className(), ['id' => 'demand_task_id']);
    }

    /**
     * 获取所有阶段
     * @return ActiveQuery
     */
    public function getCoursePhases()
    {
        return $this->hasMany(CoursePhase::className(), ['course_id' => 'id']);
    }

    /**
     * 获取所有制作人
     * @return ActiveQuery
     */
    public function getCourseProducers()
    {
        return $this->hasMany(CourseProducer::className(), ['course_id' => 'id']);
    }
    
    /**
     * 获取所有团队成员
     * @return ActiveQuery
     */
    public function getProducers()
    {
        return $this->hasMany(TeamMember::className(), ['u_id' => 'producer'])->viaTable('{{%teamwork_course_producer}}', ['course_id' => 'id']);
    }
    
    /**
     * 获取所有周报开发人
     * @return ActiveQuery
     */
    public function getCourseWeeklys()
    {
        return $this->hasMany(CourseSummary::className(), ['create_by' => 'weekly_editors_people']);
    }

    /**
     * 获取所有课程周报
     * @return ActiveQuery
     */
    public function getCourseSummaries()
    {
        return $this->hasMany(CourseSummary::className(), ['course_id' => 'id']);
    }
    
    /**
     * 获取状态是否为【待开始】
     */
    public function getIsWaitStart()
    {
        return $this->status == self::STATUS_WAIT_START;
    }        
    
    /**
     * 获取状态是否为【在建中】
     */
    public function getIsNormal()
    {
        return $this->status == self::STATUS_NORMAL;
    }
    
    /**
     * 获取状态是否为【暂停中】
     */
    public function getIsPause()
    {
        return $this->status == self::STATUS_PAUSE;
    }
    
    /**
     * 获取状态是否为【已完成】
     */
    public function getIsCarryOut()
    {
        return $this->status == self::STATUS_CARRY_OUT;
    }
    
    /**
     * 获取状态名称
     */
    public function getStatusName()
    {
        return self::$statusName[$this->status];
    }
    
}
