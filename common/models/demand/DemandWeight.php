<?php

namespace common\models\demand;

use common\models\workitem\WorkitemType;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_weight}}".
 *
 * @property integer $id                            ID
 * @property integer $demand_task_id                引用需求任务ID
 * @property integer $workitem_type_id              引用工作项类型ID
 * @property string $weight                         比重
 * @property string $sl_weight                      数量比重
 * @property string $zl_weight                      质量比重
 * @property integer $created_at                    创建于
 * @property integer $updated_at                    更新于
 * 
 * @property WorkitemType $workitemType             获取工作项类型
 * @property DemandTask $demandTask                 获取需求任务
 */
class DemandWeight extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_weight}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['demand_task_id', 'workitem_type_id'], 'required'],
            [['demand_task_id', 'workitem_type_id', 'created_at', 'updated_at'], 'integer'],
            [['weight', 'sl_weight', 'zl_weight'], 'number'],
            [['workitem_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkitemType::className(), 'targetAttribute' => ['workitem_type_id' => 'id']],
            [['demand_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandTask::className(), 'targetAttribute' => ['demand_task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'demand_task_id' => Yii::t('rcoa/demand', 'Demand Task ID'),
            'workitem_type_id' => Yii::t('rcoa/demand', 'Workitem Type ID'),
            'weight' => Yii::t('rcoa/demand', 'Weight'),
            'sl_weight' => Yii::t('rcoa/demand', 'Sl Weight'),
            'zl_weight' => Yii::t('rcoa/demand', 'Zl Weight'),
            'created_at' => Yii::t('rcoa/demand', 'Created At'),
            'updated_at' => Yii::t('rcoa/demand', 'Updated At'),
        ];
    }
    
    /**
     * 获取工作项类型
     * @return ActiveQuery
     */
    public function getWorkitemType()
    {
        return $this->hasOne(WorkitemType::className(), ['id' => 'workitem_type_id']);
    }

    /**
     * 获取需求任务
     * @return ActiveQuery
     */
    public function getDemandTask()
    {
        return $this->hasOne(DemandTask::className(), ['id' => 'demand_task_id']);
    }
}
