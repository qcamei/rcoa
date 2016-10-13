<?php

namespace common\models\teamwork;

use common\models\teamwork\CourseManage;
use common\models\team\TeamMember;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_producer}}".
 *
 * @property integer $course_id     课程ID
 * @property integer $producer       制作人
 *
 * @property TeamMember $producerOne    获取团队成员
 * @property CourseManage $course       获取课程
 */
class CourseProducer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_course_producer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'producer'], 'required'],
            [['course_id', 'producer'], 'integer'],
            [['producer'], 'exist', 'skipOnError' => true, 'targetClass' => TeamMember::className(), 'targetAttribute' => ['producer' => 'id']],
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
            'producer' => Yii::t('rcoa/teamwork', 'Producer'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProducerOne()
    {
        return $this->hasOne(TeamMember::className(), ['id' => 'producer']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(CourseManage::className(), ['id' => 'course_id']);
    }
}
