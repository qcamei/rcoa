<?php

namespace common\models\demand;

use Yii;

/**
 * This is the model class for table "{{%demand_delivery_data}}".
 *
 * @property integer $id
 * @property integer $demand_delivery_id
 * @property integer $demand_workitem_id
 * @property integer $value
 *
 * @property DemandWorkitem $demandWorkitem
 * @property DemandDelivery $demandDelivery
 */
class DemandDeliveryData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_delivery_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['demand_delivery_id', 'demand_workitem_id', 'value'], 'required'],
            [['demand_delivery_id', 'demand_workitem_id', 'value'], 'integer'],
            [['demand_workitem_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandWorkitem::className(), 'targetAttribute' => ['demand_workitem_id' => 'id']],
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
            'demand_delivery_id' => Yii::t('rcoa/demand', 'Demand Delivery ID'),
            'demand_workitem_id' => Yii::t('rcoa/demand', 'Demand Workitem ID'),
            'value' => Yii::t('rcoa/demand', 'Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDemandWorkitem()
    {
        return $this->hasOne(DemandWorkitem::className(), ['id' => 'demand_workitem_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDemandDelivery()
    {
        return $this->hasOne(DemandDelivery::className(), ['id' => 'demand_delivery_id']);
    }
}
