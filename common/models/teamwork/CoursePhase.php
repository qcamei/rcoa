<?php

namespace common\models\teamwork;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_phase}}".
 *
 * @property integer $id            id  
 * @property integer $course_id     课程ID
 * @property integer $phase_id      阶段ID
 * @property string $weights        权重
 * @property string $is_delete      是否删除
 *
 * @property CourseLink[] $courseLinks  获取所有课程环节
 * @property CourseManage $course       获取课程
 * @property Phase $phase               获取阶段
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
            [['course_id', 'phase_id'], 'integer'],
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
            'is_delete' => Yii::t('rcoa/teamwork', 'Is Delete'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCourseLinks()
    {
        return $this->hasMany(CourseLink::className(), ['course_phase_id' => 'phase_id'])
                ->where(['course_id' => $this->course_id, 'is_delete' => 'N']);
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
