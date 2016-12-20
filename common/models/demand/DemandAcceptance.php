<?php

namespace common\models\demand;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_acceptance}}".
 *
 * @property integer $id                        ID
 * @property integer $task_id                   任务ID
 * @property string $title                      标题
 * @property string $remark                     备注
 * @property string $create_by                  创建者
 * @property integer $created_at                创建于
 * @property integer $updated_at                更新于
 * @property string $complete_time              完成时间
 * @property integer $status                    状态
 *
 * @property DemandTask $task                   获取需求任务
 * @property User $createBy                     获取获取创建者
 */
class DemandAcceptance extends ActiveRecord
{
    /** 未完成 */
    const STATUS_NOTCOMPLETE = 0;
    /** 已完成 */
    const STATUS_COMPLETE = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_acceptance}}';
    }

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
            [['task_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['remark'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
            [['complete_time'], 'string', 'max' => 60],
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
            'title' => Yii::t('rcoa', 'Title'),
            'remark' => Yii::t('rcoa', 'Remark'),
            'create_by' => Yii::t('rcoa/demand', 'Create By'),
            'created_at' => Yii::t('rcoa/demand', 'Created At'),
            'updated_at' => Yii::t('rcoa/demand', 'Updated At'),
            'complete_time' => Yii::t('rcoa/demand', 'Complete Time'),
            'status' => Yii::t('rcoa/demand', 'Status'),
        ];
    }

    /**
     * 获取需求任务
     * @return ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(DemandTask::className(), ['id' => 'task_id']);
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
