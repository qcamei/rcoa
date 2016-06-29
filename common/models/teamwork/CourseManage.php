<?php

namespace common\models\teamwork;

use common\models\team\Team;
use common\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_manage}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $course_id
 * @property string $teacher
 * @property integer $lession_time
 * @property string $create_by
 * @property integer $created_at
 * @property string $plan_start_time
 * @property string $plan_end_time
 * @property string $real_carry_out
 * @property integer $progress
 * @property integer $status
 * @property string $des
 * @property integer $resource_people
 *
 * @property Team $resourcePeople
 * @property User $createBy
 * @property FrameworkItem $course
 * @property User $teacher0
 * @property FrameworkItemManage $project
 */
class CourseManage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_course_manage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'course_id', 'lession_time', 'created_at', 'progress', 'status', 'resource_people'], 'integer'],
            [['teacher', 'create_by'], 'string', 'max' => 36],
            [['plan_start_time', 'plan_end_time', 'real_carry_out'], 'string', 'max' => 60],
            [['des'], 'string', 'max' => 255]
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
            'create_by' => Yii::t('rcoa/teamwork', 'Create By'),
            'created_at' => Yii::t('rcoa/teamwork', 'Created At'),
            'plan_start_time' => Yii::t('rcoa/teamwork', 'Plan Start Time'),
            'plan_end_time' => Yii::t('rcoa/teamwork', 'Plan End Time'),
            'real_carry_out' => Yii::t('rcoa/teamwork', 'Real Carry Out'),
            'progress' => Yii::t('rcoa/teamwork', 'Progress'),
            'status' => Yii::t('rcoa/teamwork', 'Status'),
            'des' => Yii::t('rcoa/teamwork', 'Des'),
            'resource_people' => Yii::t('rcoa/teamwork', 'Resource People'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getResourcePeople()
    {
        return $this->hasOne(Team::className(), ['id' => 'resource_people']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(FrameworkItem::className(), ['id' => 'course_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeacher0()
    {
        return $this->hasOne(User::className(), ['id' => 'teacher']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(FrameworkItemManage::className(), ['id' => 'project_id']);
    }
}
