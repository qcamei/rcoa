<?php

namespace common\models\workitem;

use common\models\demand\DemandWorkitem;
use common\models\demand\DemandWorkitemTemplate;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%workitem}}".
 *
 * @property integer $id                                ID
 * @property string $name                               名称
 * @property integer $index                             索引
 * @property string $unit                               单位
 * @property string $des                                描述
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 *
 * @property DemandWorkitem[] $demandWorkitems          获取所有的需求工作项
 * @property DemandWorkitemTemplate[] $demandWorkitemTemplates      获取所有的需求工作项模版数据
 * @property WorkitemCost[] $workitemCosts              获取工作项价值
 */
class Workitem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%workitem}}';
    }
    
    public function behaviors() {
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
            [['name', 'index', 'unit'], 'required'],
            [['index', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['unit'], 'string', 'max' => 8],
            [['des'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/workitem', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'index' => Yii::t('rcoa', 'Index'),
            'unit' => Yii::t('rcoa/workitem', 'Unit'),
            'des' => Yii::t('rcoa', 'Des'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }

    /**
     * 获取所有的需求工作项
     * @return ActiveQuery
     */
    public function getDemandWorkitems()
    {
        return $this->hasMany(DemandWorkitem::className(), ['workitem_id' => 'id']);
    }

    /**
     * 获取所有的需求工作项模版数据
     * @return ActiveQuery
     */
    public function getDemandWorkitemTemplates()
    {
        return $this->hasMany(DemandWorkitemTemplate::className(), ['workitem_id' => 'id']);
    }

    /**
     * 获取工作项价值
     * @return ActiveQuery
     */
    public function getWorkitemCosts()
    {
        return $this->hasMany(WorkitemCost::className(), ['workitem_id' => 'id']);
    }
}
