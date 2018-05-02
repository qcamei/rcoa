<?php

namespace common\models\team;

use common\models\Position;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CourseProducer;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%team_member}}".
 *
 * @property integer $id                            ID
 * @property integer $team_id                       团队ID
 * @property string $u_id                           用户ID
 * @property string $is_leader                      是否为队长
 * @property integer $index                         索引
 * @property integer $position_id                   职位ID
 * @property string $is_delete                      是否删除
 *
 * @property Position $position                     获取职位
 * @property Team $team                             获取团队
 * @property User $user                             获取用户
 * @property CourseManage[] $weeklyEditorsPeople    获取所有周报编辑人
 * @property CourseProducer[] $courseProducers      获取所有资源制作人
 * @property CourseManage[] $courses                获取所有课程指定制作人
 */
class TeamMember extends ActiveRecord
{
    /** 队长 */
    const TEAMLEADER = 'Y';
    /** 队员 */
    const TEAMMEMBER = 'N';
    
    /** 确定删除 */
    const SURE_DELETE = 'Y';
    /** 取消删除 */
    const CANCEL_DELETE = 'N';

    /** 队长 or 队员 */
    public static $is_leaders = [
        self::TEAMLEADER => '队长',
        self::TEAMMEMBER => '队员'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%team_member}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_id', 'u_id', 'position_id'], 'required'],
            [['index', 'position_id'], 'integer'],
            [['u_id'], 'string', 'max' => 36],
            [['is_leader', 'is_delete'], 'string', 'max' => 4],
            [['team_id', 'u_id'], 'unique', 'targetAttribute' => ['team_id', 'u_id'], 'comboNotUnique' => \Yii::t('rcoa/team', 'Under the same team please do not repeat to add the same members')],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
            [['u_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['u_id' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/team', 'ID'),
            'team_id' => Yii::t('rcoa/team', 'Team ID'),
            'u_id' => Yii::t('rcoa/team', 'U ID'),
            'is_leader' => Yii::t('rcoa/team', 'Is Leader'),
            'index' => Yii::t('rcoa', 'Index'),
            'position_id' => Yii::t('rcoa/team', 'Position'),
            'is_delete' => Yii::t('rcoa/team', 'Is Delete'),
        ];
    }
    
    /**
     * 获取职位
     * @return ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['id' => 'position_id']);
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
     * 获取用户
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'u_id']);
    }

    /**
     * 获取所有课程周报编辑人
     * @return ActiveQuery
     */
    public function getWeeklyEditorsPeople()
    {
        return $this->hasMany(CourseManage::className(), ['weekly_editors_people' => 'id']);
    }

    /**
     * 获取所有课程制作人
     * @return ActiveQuery
     */
    public function getCourseProducers()
    {
        return $this->hasMany(CourseProducer::className(), ['producer' => 'id']);
    }

    /**
     * 获取所有课程指定制作人
     * @return ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(CourseManage::className(), ['id' => 'course_id'])->viaTable('{{%teamwork_course_producer}}', ['producer' => 'id']);
    }
}
