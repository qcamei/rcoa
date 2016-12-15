<?php

namespace common\models\demand;

use Yii;

/**
 * This is the model class for table "{{%demand_operation}}".
 *
 * @property integer $id
 * @property integer $task_id
 * @property integer $task_status
 * @property string $action_id
 * @property string $create_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $is_delete
 *
 * @property DemandTask $task
 * @property DemandOperationUser[] $demandOperationUsers
 */
class DemandOperation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_operation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'task_status', 'created_at', 'updated_at'], 'integer'],
            [['action_id'], 'string', 'max' => 20],
            [['create_by'], 'string', 'max' => 36],
            [['is_delete'], 'string', 'max' => 4],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandTask::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'task_id' => Yii::t('rcoa/demand', 'Task ID'),
            'task_status' => Yii::t('rcoa/demand', 'Task Status'),
            'action_id' => Yii::t('rcoa/demand', 'Action ID'),
            'create_by' => Yii::t('rcoa/demand', 'Create By'),
            'created_at' => Yii::t('rcoa/demand', 'Created At'),
            'updated_at' => Yii::t('rcoa/demand', 'Updated At'),
            'is_delete' => Yii::t('rcoa/demand', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(DemandTask::className(), ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDemandOperationUsers()
    {
        return $this->hasMany(DemandOperationUser::className(), ['operation_id' => 'id']);
    }
}
