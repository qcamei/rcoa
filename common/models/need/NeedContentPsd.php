<?php

namespace common\models\need;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%need_content_psd}}".
 *
 * @property string $id
 * @property string $workitem_type_id   工作项类型ID
 * @property string $workitem_id        工作项ID
 * @property string $price_new          新建单价
 * @property string $price_remould      改造单价
 * @property int $sort_order            排序
 * @property string $created_at         创建时间
 * @property string $updated_at         更新时间
 */
class NeedContentPsd extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%need_content_psd}}';
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
            [['workitem_type_id', 'workitem_id', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['price_new', 'price_remould'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'workitem_type_id' => Yii::t('app', 'Workitem Type ID'),
            'workitem_id' => Yii::t('app', 'Workitem ID'),
            'price_new' => Yii::t('app', 'Price New'),
            'price_remould' => Yii::t('app', 'Price Remould'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
