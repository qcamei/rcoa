<?php

namespace common\models\demand;

use common\models\product\Product;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_task_product}}".
 *
 * @property integer $id                ID
 * @property integer $task_id           任务ID
 * @property integer $product_id        产品ID
 * @property integer $number            数量
 *
 * @property Product $product           获取产品
 * @property DemandTask $task           获取需求任务
 */
class DemandTaskProduct extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_task_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'product_id', 'number'], 'required'],
            [['task_id', 'product_id', 'number'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandTask::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'task_id' => Yii::t('rcoa/demand', 'Task ID'),
            'product_id' => Yii::t('rcoa/demand', 'Product ID'),
            'number' => Yii::t('rcoa/demand', 'Number'),
        ];
    }

    /**
     * 获取产品
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * 获取需求任务
     * @return ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(DemandTask::className(), ['id' => 'task_id']);
    }
}
