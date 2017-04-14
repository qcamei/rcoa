<?php

namespace common\models\demand;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_acceptance}}".
 *
 * @property integer $id                                    ID
 * @property integer $demand_task_id                        需求任务ID
 * @property integer $demand_delivery_id                    任务交付记录ID
 * @property integer $pass                                  是否通过
 * @property string  $des                                    描述
 * @property integer $create_by                             创建者
 * @property integer $created_at                            创建于
 * @property integer $updated_at                            更新于
 *
 * @property DemandTask $demandTask                         获取需求任务
 * @property DemandDelivery $demandDelivery                 获取交付记录
 * @property DemandAcceptanceData[] $demandAcceptanceDatas  获取所有验收数据
 */
class DemandAcceptance extends ActiveRecord
{
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
            //[['demand_task_id', 'demand_delivery_id',  'create_by'], 'required'],
            [['demand_task_id', 'demand_delivery_id', 'pass', 'created_at', 'updated_at'], 'integer'],
            [['des', 'create_by'], 'string'],
            [['demand_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandTask::className(), 'targetAttribute' => ['demand_task_id' => 'id']],
            [['demand_delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandDelivery::className(), 'targetAttribute' => ['demand_delivery_id' => 'id']],
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
            'demand_delivery_id' => Yii::t('rcoa/demand', 'Demand Delivery ID'),
            'pass' => Yii::t('rcoa/demand', 'Pass'),
            'des' => Yii::t('rcoa/demand', 'Des'),
            'create_by' => Yii::t('rcoa/demand', 'Create By'),
            'created_at' => Yii::t('rcoa/demand', 'Created At'),
            'updated_at' => Yii::t('rcoa/demand', 'Updated At'),
        ];
    }

    /**
     * 获取需求任务
     * @return ActiveQuery
     */
    public function getDemandTask()
    {
        return $this->hasOne(DemandTask::className(), ['id' => 'demand_task_id']);
    }

    /**
     * 获取交付记录
     * @return ActiveQuery
     */
    public function getDemandDelivery()
    {
        return $this->hasOne(DemandDelivery::className(), ['id' => 'demand_delivery_id']);
    }

    /**
     * 获取所有验收数据
     * @return ActiveQuery
     */
    public function getDemandAcceptanceDatas()
    {
        return $this->hasMany(DemandAcceptanceData::className(), ['demand_acceptance_id' => 'id']);
    }
}
