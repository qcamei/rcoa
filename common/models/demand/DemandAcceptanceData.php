<?php

namespace common\models\demand;

use Yii;

/**
 * This is the model class for table "{{%demand_acceptance_data}}".
 *
 * @property integer $id
 * @property integer $demand_acceptance_id
 * @property integer $workitem_type_id
 * @property integer $value
 *
 * @property DemandAcceptance $demandAcceptance
 */
class DemandAcceptanceData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_acceptance_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['demand_acceptance_id', 'workitem_type_id', 'value'], 'required'],
            [['demand_acceptance_id', 'workitem_type_id', 'value'], 'integer'],
            [['demand_acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandAcceptance::className(), 'targetAttribute' => ['demand_acceptance_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'demand_acceptance_id' => Yii::t('rcoa/demand', 'Demand Acceptance ID'),
            'workitem_type_id' => Yii::t('rcoa/demand', 'Workitem Type ID'),
            'value' => Yii::t('rcoa/demand', 'Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDemandAcceptance()
    {
        return $this->hasOne(DemandAcceptance::className(), ['id' => 'demand_acceptance_id']);
    }
}
