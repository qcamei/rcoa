<?php

namespace common\models\mconline;

use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%mcbs_action_log}}".
 *
 * @property integer $id
 * @property string $action                             动作
 * @property string $title                              标题
 * @property string $content                            内容
 * @property string $create_by                          创建者
 * @property string $course_id                          课程id  
 * @property string $relative_id                        相关id
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property McbsCourse $mcbsCourse                     获取板书课程
 * @property User $createBy                             获取创建者
 */
class McbsActionLog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_action_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['action', 'title'], 'string', 'max' => 50],
            [['create_by'], 'string', 'max' => 36],
            [['course_id', 'relative_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'action' => Yii::t('app', 'Action'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'create_by' => Yii::t('app', 'Create By'),
            'course_id' => Yii::t('app', 'Course ID'),
            'relative_id' => Yii::t('app', 'Relative ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 获取板书课程
     * @return ActiveQuery
     */
    public function getMcbsCourse()
    {
        return $this->hasOne(McbsCourse::className(), ['id' => 'course_id']);
    }
    
    /**
     * 获取创建者
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }
}
