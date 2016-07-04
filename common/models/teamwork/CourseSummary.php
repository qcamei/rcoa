<?php

namespace common\models\teamwork;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_summary}}".
 *
 * @property integer $course_id     课程ID
 * @property string $create_time    创建时间
 * @property string $content        总结
 * 
 * @property CourseManage $course   获取课程
 */
class CourseSummary extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_course_summary}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id'], 'required'],
            [['course_id'], 'integer'],
            [['create_time'], 'string', 'max' => 60],
            [['content'], 'string', 'max' => 255],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourseManage::className(), 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'course_id' => Yii::t('rcoa/teamwork', 'Course ID'),
            'create_time' => Yii::t('rcoa/teamwork', 'Create Time'),
            'content' => Yii::t('rcoa/teamwork', 'Content'),
        ];
    }
    
    public function getCourse()
    {
        return $this->hasOne(CourseManage::className(), ['id' => 'course_id']);
    }
}
