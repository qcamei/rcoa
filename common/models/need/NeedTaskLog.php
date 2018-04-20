<?php

namespace common\models\need;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%need_task_log}}".
 *
 * @property string $id
 * @property string $need_task_id   需求任务ID
 * @property string $action         操作名称
 * @property string $title          标题
 * @property string $content        内容
 * @property string $created_by     创建者
 * @property string $created_at     创建时间
 * @property string $updated_at     更新时间
 */
class NeedTaskLog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%need_task_log}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() 
    {
        return [
            TimestampBehavior::class
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['need_task_id'], 'string', 'max' => 32],
            [['action', 'title'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 500],
            [['created_by'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'need_task_id' => Yii::t('app', 'Need Task ID'),
            'action' => Yii::t('app', 'Action'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
