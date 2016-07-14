<?php

namespace common\models\teamwork;

use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\teamwork\ItemManage;
use common\models\User;
use wskeee\framework\models\Item;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_manage}}".
 *
 * @property integer $id                ID
 * @property integer $project_id        项目Id
 * @property integer $course_id         课程Id
 * @property string $teacher            主讲教师
 * @property integer $lession_time      学时
 * @property integer $team_id           创建者所在团队
 * @property string $create_by          创建者
 * @property integer $created_at        创建于
 * @property string $plan_start_time    计划开始时间
 * @property string $plan_end_time      计划完成时间
 * @property string $real_carry_out     实际完成时间
 * @property integer $status            状态
 * @property string $des                描述
 * @property string $path               存储服务器路径
 * @property integer $progress          进度
 *
 * @property CourseLink[] $courseLinks              获取所有课程环节
 * @property Team $team                             获取团队
 * @property User $createBy                         获取创建者
 * @property Item $course                           获取课程
 * @property User $speakerTeacher                   获取主讲讲师
 * @property ItemManage $project                    获取项目
 * @property CoursePhase[] $coursePhases            获取所有课程阶段
 * @property CourseProducer[] $courseProducers      获取所有制作人
 * @property TeamMember[] $producers                获取所有团队成员
 * @property CourseSummary[] $courseSummaries       获取所有课程总结
 */
class CourseManage extends ActiveRecord
{
    
    /** 进度 */
    public $progress;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_course_manage}}';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className('created_at')
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'course_id', 'lession_time', 'created_at', 'updated_at', 'status'], 'integer'],
            [['project_id', 'course_id', 'teacher','path'], 'required'],
            [['teacher', 'create_by'], 'string', 'max' => 36],
            [['plan_start_time', 'plan_end_time', 'real_carry_out'], 'string', 'max' => 60],
            [['des','path'], 'string', 'max' => 255],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['teacher'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['teacher' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemManage::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
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
            'lession_time' => Yii::t('rcoa/teamwork', 'Lession Time'),
            'team_id' => Yii::t('rcoa/team', 'Team ID'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa/teamwork', 'Created At'),
            'plan_start_time' => Yii::t('rcoa/teamwork', 'Plan Start Time'),
            'plan_end_time' => Yii::t('rcoa/teamwork', 'Plan End Time'),
            'real_carry_out' => Yii::t('rcoa/teamwork', 'Real Carry Out'),
            'status' => Yii::t('rcoa', 'Status'),
            'des' => Yii::t('rcoa/teamwork', 'Des'),
            'path' => Yii::t('rcoa/teamwork', 'Path'),
        ];
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
     * 获取课程
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Item::className(), ['id' => 'course_id']);
    }
    
    /**
     * 获取主讲讲师
     * @return ActiveQuery
     */
    public function getSpeakerTeacher()
    {
        return $this->hasOne(User::className(), ['id' => 'teacher']);
    }
    
    /**
     * 获取项目管理
     * @return ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(ItemManage::className(), ['id' => 'project_id']);
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
    public function getTeamworkCourseProducers()
    {
        return $this->hasMany(TeamworkCourseProducer::className(), ['course_id' => 'id']);
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
     * 获取所有课程总结
     * @return ActiveQuery
     */
    public function getCourseSummaries()
    {
        return $this->hasMany(CourseSummary::className(), ['course_id' => 'id']);
    }
    
    /**
     * 获取状态是否为【正常】
     */
    public function getIsNormal()
    {
        return $this->status == ItemManage::STATUS_NORMAL;
    }
    
    /**
     * 获取状态是否为【完成】
     */
    public function getIsCarryOut()
    {
        return $this->status == ItemManage::STATUS_CARRY_OUT;
    }
    
}
