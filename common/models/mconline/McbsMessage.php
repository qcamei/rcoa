<?php

namespace common\models\mconline;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%mcbs_message}}".
 *
 * @property string $id
 * @property string $title                              标题
 * @property string $content                            内容
 * @property string $create_by                          创建者
 * @property string $course_id                          课程id
 * @property string $activity_id                        活动id
 * @property string $reply_id                           回复的留言ID
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property McbsCourseActivity $activity               获取课程框架活动
 * @property McbsCourse $course                         获取课程框架课程
 */
class McbsMessage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_message}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() 
    {
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
            [['content'], 'string'],
            [['reply_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['create_by'], 'string', 'max' => 36],
            [['course_id', 'activity_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'created_by' => Yii::t('app', 'Create By'),
            'course_id' => Yii::t('app', 'Course ID'),
            'activity_id' => Yii::t('app', 'Activity ID'),
            'reply_id' => Yii::t('app', 'Reply ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 
     * @param type $insert 
     */
    public function beforeSave($insert) 
    {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord){
                $this->create_by = Yii::$app->user->id;
            }
            
            return true;
        }else
            return false;
    }
    
    /**
     * 获取课程框架活动
     * @return ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(McbsCourseActivity::className(), ['id' => 'activity_id']);
    }
    
    /**
     * 获取课程框架课程
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(McbsCourse::className(), ['id' => 'course_id']);
    }
}
