<?php

namespace common\models\demand;

use common\models\workitem\Workitem;
use common\models\workitem\WorkitemType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_workitem}}".
 *
 * @property integer $id                            ID
 * @property integer $demand_task_id                引用需求任务id
 * @property integer $workitem_type_id              引用工作项类型id
 * @property integer $workitem_id                   引用工作项id
 * @property integer $is_new                        是否为新建
 * @property integer $value_type                    数量的类型
 * @property integer $value                         工作项的需求数量
 * @property integer $cost                          成本
 * @property integer $created_at                    创建于
 * @property integer $updated_at                    更新于
 *
 * @property DemandDeliveryData[] $demandDeliveryDatas           获取所有支付数据
 * @property Workitem $workitem                                  获取工作项
 * @property DemandTask $demandTask                              获取需求任务
 * @property WorkitemType $workitemType                          获取工作项类型
 */
class DemandWorkitem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_workitem}}';
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
            [['demand_task_id', 'workitem_type_id', 'workitem_id', 'is_new', 'value', 'cost'], 'required'],
            [['demand_task_id', 'workitem_type_id', 'workitem_id', 'is_new',  'value_type', 'value', 'cost', 'created_at', 'updated_at'], 'integer'],
            [['workitem_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workitem::className(), 'targetAttribute' => ['workitem_id' => 'id']],
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
            'workitem_id' => Yii::t('rcoa/demand', 'Workitem ID'),
            'is_new' => Yii::t('rcoa/demand', 'Is New'),
            'value_type' => Yii::t('rcoa/demand', 'Value Type'),
            'value' => Yii::t('rcoa/demand', 'Value'),
            'cost' => Yii::t('rcoa/demand', 'Cost'),
            'created_at' => Yii::t('rcoa/demand', 'Created At'),
            'updated_at' => Yii::t('rcoa/demand', 'Updated At'),
        ];
    }

    /**
     * 获取所有支付数据
     * @return ActiveQuery
     */
    public function getDemandDeliveryDatas()
    {
        return $this->hasMany(DemandDeliveryData::className(), ['demand_workitem_id' => 'id']);
    }

    /**
     * 获取工作项
     * @return ActiveQuery
     */
    public function getWorkitem()
    {
        return $this->hasOne(Workitem::className(), ['id' => 'workitem_id']);
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
