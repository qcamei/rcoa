<?php

namespace common\models\demand;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_delivery}}".
 *
 * @property integer $id                                    ID
 * @property integer $demand_task_id                        引用需求任务ID
 * @property string $des                                    描述
 * @property integer $create_by                             创建者
 * @property integer $created_at                            创建于
 * @property integer $updated_at                            更新于
 *  
 * @property DemandAcceptance[] $demandAcceptances          获取所有需求验收记录数据
 * @property DemandTask $demandTask                         获取需求任务
 * @property DemandDeliveryData[] $demandDeliveryDatas      获取所有需求交付数据
 */
class DemandDelivery extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_delivery}}';
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
            //[['demand_task_id', 'des', 'create_by'], 'required'],
            [['demand_task_id', 'created_at', 'updated_at'], 'integer'],
            [['create_by', 'des'], 'string'],
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
            'des' => Yii::t('rcoa/demand', 'Des'),
            'create_by' => Yii::t('rcoa/demand', 'Create By'),
            'created_at' => Yii::t('rcoa/demand', 'Created At'),
            'updated_at' => Yii::t('rcoa/demand', 'Updated At'),
        ];
    }

    /**
     * 获取所有需求验收记录数据
     * @return ActiveQuery
     */
    public function getDemandAcceptances()
    {
        return $this->hasMany(DemandAcceptance::className(), ['demand_delivery_id' => 'id']);
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
     * 获取所有需求交付数据
     * @return ActiveQuery
     */
    public function getDemandDeliveryDatas()
    {
        return $this->hasMany(DemandDeliveryData::className(), ['demand_delivery_id' => 'id']);
    }
}
