<?php

namespace common\models\teamwork;

use common\models\teamwork\CourseManage;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_course_summary}}".
 *
 * @property integer $course_id     课程ID
 * @property string $create_time    创建时间
 * @property string $content        内容
 * @property string $create_by      周报开发人
 * @property integer $created_at    创建于
 * @property integer $updated_at    更新于
 *
 * @property User $createBy                   获取周报开发人
 * @property CourseManage $course             获取课程
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
    
    
    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'create_time'], 'required'],
            [['course_id', 'created_at', 'updated_at'], 'integer'],
            [['create_by'], 'string', 'max' => 36],
            [['create_time'], 'string', 'max' => 60],
            [['content'], 'string'],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_by' => 'id']],
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
            'content' => Yii::t('rcoa', 'Content'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa/teamwork', 'Created At'),
            'updated_at' => Yii::t('rcoa/teamwork', 'Updated At'),
        ];
    }
    
    public function beforeSave($insert) {
        if(parent::beforeSave($insert))
        {
            $this->content = htmlentities($this->content);
            return true;
        }
    }
    public function afterFind() {
        
        $this->content = html_entity_decode($this->content);
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
        return $this->hasOne(CourseManage::className(), ['id' => 'course_id']);
    }
}
