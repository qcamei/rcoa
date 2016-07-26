<?php

namespace common\models\teamwork;

use common\models\teamwork\CourseManage;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_annex}}".
 *
 * @property integer $id                    id
 * @property integer $course_id             课程ID
 * @property string $name                   附件名称
 * @property string $path                   附件路径
 * @property string $is_delete              是否删除
 *  
 * @property CourseManage $course           获取课程
 */
class CourseAnnex extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_course_annex}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id'], 'required'],
            [['course_id'], 'integer'],
            [['name', 'path'], 'string', 'max' => 255],
            [['is_delete'], 'string', 'max' => 4],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourseManage::className(), 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/team', 'ID'),
            'course_id' => Yii::t('rcoa/teamwork', 'Course ID'),
            'name' => Yii::t('rcoa/teamwork', 'Annex Name'),
            'path' => Yii::t('rcoa/teamwork', 'Annex Path'),
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
}
