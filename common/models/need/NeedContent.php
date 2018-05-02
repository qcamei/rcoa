<?php

namespace common\models\need;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%need_content}}".
 *
 * @property string $id
 * @property string $need_task_id       需求任务ID
 * @property string $workitem_type_id   工作项类型ID
 * @property string $workitem_id        工作项ID
 * @property int $is_new                是否新建：0否 1是
 * @property string $price              单价
 * @property string $plan_num           预计数量
 * @property string $reality_num        实际数量
 * @property int $sort_order            排序
 * @property int $is_del                是否删除：0否 1是
 * @property string $created_by         创建人
 * @property string $created_at         创建时间
 * @property string $updated_at         更新时间
 */
class NeedContent extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%need_content}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() 
    {
        return [
            TimestampBehavior::class
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workitem_type_id', 'workitem_id', 'is_new', 'plan_num', 'reality_num', 'sort_order', 'is_del', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['need_task_id'], 'string', 'max' => 32],
            [['created_by'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'need_task_id' => Yii::t('app', 'Need Task ID'),
            'workitem_type_id' => Yii::t('app', 'Workitem Type ID'),
            'workitem_id' => Yii::t('app', 'Workitem ID'),
            'is_new' => Yii::t('app', 'Is New'),
            'price' => Yii::t('app', 'Price'),
            'plan_num' => Yii::t('app', 'Plan Num'),
            'reality_num' => Yii::t('app', 'Reality Num'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'is_del' => Yii::t('app', 'Is Del'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
