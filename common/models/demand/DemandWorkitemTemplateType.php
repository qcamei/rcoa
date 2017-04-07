<?php

namespace common\models\demand;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_workitem_template_type}}".
 *
 * @property integer $id                        ID
 * @property string $name                       类型名称
 * @property string $des                        描述
 * @property integer $created_at                创建于
 * @property integer $updated_at                更新于
 *
 * @property DemandWorkitemTemplate[] $demandWorkitemTemplates      获取所有需求工作项模块
 */
class DemandWorkitemTemplateType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_workitem_template_type}}';
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
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['des'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'des' => Yii::t('rcoa', 'Des'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }

    /**
     * 获取所有需求工作项模块
     * @return ActiveQuery
     */
    public function getDemandWorkitemTemplates()
    {
        return $this->hasMany(DemandWorkitemTemplate::className(), ['demand_workitem_template_type_id' => 'id']);
    }
}
