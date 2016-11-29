<?php

namespace common\models\teamwork;

use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use common\models\teamwork\Link;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_link}}".
 *
 * @property integer $id                   id
 * @property integer $course_id            课程ID
 * @property integer $course_phase_id      课程阶段ID
 * @property integer $link_id              环节ID
 * @property string $name                  课程环节名称
 * @property integer $type                 类型
 * @property integer $total                总数
 * @property integer $completed            已完成数
 * @property string $unit                  单位
 * @property string $create_by             创建者
 * @property integer $created_at           创建于
 * @property integer $updated_at           更新于
 * @property integer $index                索引
 * @property string $is_delete             是否删除
 *
 * @property CourseManage $course          获取课程
 * @property CoursePhase $coursePhase      获取课程阶段
 * @property Link $link                    获取环节
 */
class CourseLink extends ActiveRecord
{
    /**
     * 课程环节进度
     * @var integer 
     
    public $progress;*/

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_course_link}}';
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
            [['course_id', 'course_phase_id', ], 'required'],
            [['course_id', 'course_phase_id', 'type', 'total', 'completed', 'created_at', 'updated_at', 'index'], 'integer'],
            [['total'], 'integer', 'min' => 1],
            [['is_delete'], 'string', 'max' => 4],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourseManage::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['course_phase_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoursePhase::className(), 'targetAttribute' => ['course_phase_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/teamwork', 'Link ID'),
            'course_id' => Yii::t('rcoa/teamwork', 'Course ID'),
            'course_phase_id' => Yii::t('rcoa/teamwork', 'Course Phase ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'type' => Yii::t('rcoa', 'Type'),
            'total' => Yii::t('rcoa/teamwork', 'Total'),
            'completed' => Yii::t('rcoa/teamwork', 'Completed'),
            'unit' => Yii::t('rcoa/teamwork', 'Unit'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
            'index' => Yii::t('rcoa', 'Index'),
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
        return $this->hasOne(CoursePhase::className(), ['id' => 'course_phase_id']);
    }

}
