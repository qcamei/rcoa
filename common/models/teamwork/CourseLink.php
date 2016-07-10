<?php

namespace common\models\teamwork;

use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use common\models\teamwork\Link;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_link}}".
 *
 * @property integer $id                   id
 * @property integer $course_id            课程ID
 * @property integer $course_phase_id      课程阶段ID
 * @property integer $link_id              环节ID
 * @property integer $total                总数
 * @property integer $completed            已完成数
 * @property string $is_delete             是否删除
 *
 * @property CourseManage $course          获取课程
 * @property CoursePhase $coursePhase      获取课程阶段
 * @property Link $link                    获取环节
 */
class CourseLink extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_course_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
         return [
            [['course_id', 'course_phase_id', 'link_id'], 'required'],
            [['course_id', 'course_phase_id', 'link_id', 'total', 'completed'], 'integer'],
            [['is_delete'], 'string', 'max' => 4],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourseManage::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['course_phase_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoursePhase::className(), 'targetAttribute' => ['course_phase_id' => 'phase_id']],
            [['link_id'], 'exist', 'skipOnError' => true, 'targetClass' => Link::className(), 'targetAttribute' => ['link_id' => 'id']],
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
            'course_phase_id' => Yii::t('rcoa/teamwork', 'Course Phase ID'),
            'link_id' => Yii::t('rcoa/teamwork', 'Link ID'),
            'total' => Yii::t('rcoa/teamwork', 'Total'),
            'completed' => Yii::t('rcoa/teamwork', 'Completed'),
            'is_delete' => Yii::t('rcoa/teamwork', 'Is Delete'),
        ];
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
    public function getCoursePhase()
    {
        return $this->hasOne(CoursePhase::className(), ['phase_id' => 'course_phase_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Link::className(), ['id' => 'link_id']);
    }
}
