<?php

namespace common\models\teamwork;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_phase}}".
 *
 * @property integer $id            id  
 * @property integer $course_id     课程ID
 * @property string $name           课程阶段名称
 * @property string $weights        权重
 * @property string $create_by      创建者
 * @property integer $created_at    创建于
 * @property integer $updated_at    更新于
 * @property integer $index         索引
 * @property string $is_delete      是否删除
 * @property integer $progress      进度
 *
 * @property CourseLink[] $courseLinks  获取所有课程环节
 * @property CourseManage $course       获取课程
 * @property Phase $phase               获取阶段
 */
class CoursePhase extends ActiveRecord
{
    public $progress;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_course_phase}}';
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
            [['course_id'], 'required'],
            [['course_id', 'created_at', 'updated_at', 'index'], 'integer'],
            [['weights'], 'number', 'max' => 1],
            [['name'], 'string', 'max' => 255],
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
            'id' => Yii::t('rcoa/teamwork', 'Course Phase ID'),
            'course_id' => Yii::t('rcoa/teamwork', 'Course ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'weights' => Yii::t('rcoa/teamwork', 'Weights'),
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
    public function getCourseLinks()
    {
        return $this->hasMany(CourseLink::className(), ['course_phase_id' => 'id'])
                ->where(['is_delete' => 'N']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(CourseManage::className(), ['id' => 'course_id']);
    }
   
}
