<?php

namespace common\models\teamwork;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_phase}}".
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $phase_id
 * @property string $weights
 * @property integer $progress
 * @property string $is_delete
 *
 * @property CourseLink[] $courseLinks
 * @property CourseManage $course
 * @property Phase $phase
 */
class CoursePhase extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_course_phase}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'phase_id'], 'required'],
            [['course_id', 'phase_id', 'progress'], 'integer'],
            [['weights'], 'number'],
            [['is_delete'], 'string', 'max' => 4],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourseManage::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['phase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Phase::className(), 'targetAttribute' => ['phase_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/teamwork', 'ID'),
            'course_id' => Yii::t('rcoa/teamwork', 'Course ID'),
            'phase_id' => Yii::t('rcoa/teamwork', 'Phase ID'),
            'weights' => Yii::t('rcoa/teamwork', 'Weights'),
            'progress' => Yii::t('rcoa/teamwork', 'Progress'),
            'is_delete' => Yii::t('rcoa/teamwork', 'Is Delete'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCourseLinks()
    {
        return $this->hasMany(CourseLink::className(), ['course_phase_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(CourseManage::className(), ['id' => 'course_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPhase()
    {
        return $this->hasOne(Phase::className(), ['id' => 'phase_id']);
    }
}
