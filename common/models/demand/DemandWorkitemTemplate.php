<?php

namespace common\models\demand;

use common\models\workitem\Workitem;
use common\models\workitem\WorkitemType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_workitem_template}}".
 *
 * @property integer $id                                    ID
 * @property integer $demand_workitem_template_type_id      引用需求工作项模版类型id
 * @property integer $workitem_type_id                      引用工作项类型id
 * @property integer $workitem_id                           引用工作项id
 * @property integer $is_new                                是否为新建
 * @property integer $value_type                            数量的类型
 * @property integer $created_at                            创建于
 * @property integer $updated_at                            更新于
 *
 * @property WorkitemType $workitemType                     获取工作项类型
 * @property DemandWorkitemTemplateType $demandWorkitemTemplateType     获取需求工作项模版类型
 * @property Workitem $workitem                             获取工作项
 */
class DemandWorkitemTemplate extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_workitem_template}}';
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
            [['demand_workitem_template_type_id', 'workitem_type_id', 'workitem_id', 'is_new', 'value_type'], 'required'],
            [['demand_workitem_template_type_id', 'workitem_type_id', 'workitem_id',  'is_new', 'value_type', 'created_at', 'updated_at'], 'integer'],
            [['workitem_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkitemType::className(), 'targetAttribute' => ['workitem_type_id' => 'id']],
            [['demand_workitem_template_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandWorkitemTemplateType::className(), 'targetAttribute' => ['demand_workitem_template_type_id' => 'id']],
            [['workitem_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workitem::className(), 'targetAttribute' => ['workitem_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'demand_workitem_template_type_id' => Yii::t('rcoa/demand', 'Demand Workitem Template Type ID'),
            'workitem_type_id' => Yii::t('rcoa/demand', 'Workitem Type ID'),
            'workitem_id' => Yii::t('rcoa/demand', 'Workitem ID'),
            'is_new' => Yii::t('rcoa/demand', 'Is New'),
            'value_type' => Yii::t('rcoa/demand', 'Value Type'),
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
     * 获取需求工作项模版类型
     * @return ActiveQuery
     */
    public function getDemandWorkitemTemplateType()
    {
        return $this->hasOne(DemandWorkitemTemplateType::className(), ['id' => 'demand_workitem_template_type_id']);
    }

    /**
     * 获取工作项
     * @return ActiveQuery
     */
    public function getWorkitem()
    {
        return $this->hasOne(Workitem::className(), ['id' => 'workitem_id']);
    }
}
