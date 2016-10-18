<?php

namespace common\models\multimedia;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%multimedia_operation}}".
 *
 * @property integer $id                                ID
 * @property integer $task_id                           任务ID
 * @property integer $task_statu                        任务状态
 * @property string $action_id                          当前操作方法
 * @property string $create_by                          创建者
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 * @property string $is_delete                          是否删除
 *
 * @property MultimediaTask $task                       获取多媒体任务
 * @property MultimediaOperationUser $operationUsers[]  获取所有操作用户           
 */
class MultimediaOperation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%multimedia_operation}}';
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
            [['task_id', 'task_statu', 'created_at', 'updated_at'], 'integer'],
            [['create_by'], 'string', 'max' => 36],
            [['action_id'], 'string', 'max' => 20],
            [['is_delete'], 'string', 'max' => 4],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => MultimediaTask::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/multimedia', 'ID'),
            'task_id' => Yii::t('rcoa/multimedia', 'Task ID'),
            'task_statu' => Yii::t('rcoa/multimedia', 'Task Statu'),
            'action_id' => Yii::t('rcoa/multimedia', 'Action ID'),
            'create_by' => Yii::t('rcoa/multimedia', 'Create By'),
            'created_at' => Yii::t('rcoa/multimedia', 'Created At'),
            'updated_at' => Yii::t('rcoa/multimedia', 'Updated At'),
            'is_delete' => Yii::t('rcoa/multimedia', 'Is Delete'),
        ];
    }

    /**
     * 获取多媒体任务
     * @return ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(MultimediaTask::className(), ['id' => 'task_id']);
    }
    
    /**
     * 获取所有操作用户
     * @return ActiveQuery
     */
    public function getOperationUsers()
    {
        return $this->hasMany(MultimediaOperationUser::className(), ['operation_id' => 'id']);
    }
}
