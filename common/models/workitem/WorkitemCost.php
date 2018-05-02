<?php

namespace common\models\workitem;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%workitem_cost}}".
 *
 * @property integer $id                            ID
 * @property integer $workitem_id                   引用工作项id
 * @property integer $cost_new                      新建价值
 * @property integer $cost_remould                  改造价值
 * @property string $target_month                   目标月份
 * @property integer $created_at                    创建于
 * @property integer $updated_at                    更新于
 *
 * @property Workitem $workitem                     获取工作项
 */
class WorkitemCost extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%workitem_cost}}';
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
            [['workitem_id', 'cost_new', 'cost_remould', 'target_month'], 'required'],
            [['workitem_id', 'created_at', 'updated_at'], 'integer'],
            [['cost_new', 'cost_remould'], 'number'],
            [['target_month'], 'string', 'max' => 10],
            [['workitem_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workitem::className(), 'targetAttribute' => ['workitem_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/workitem', 'ID'),
            'workitem_id' => Yii::t('rcoa/workitem', 'Workitem ID'),
            'cost_new' => Yii::t('rcoa/workitem', 'Cost New'),
            'cost_remould' => Yii::t('rcoa/workitem', 'Cost Remould'),
            'target_month' => Yii::t('rcoa/workitem', 'Target Month'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
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
