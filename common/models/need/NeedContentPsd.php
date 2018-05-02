<?php

namespace common\models\need;

use common\models\workitem\Workitem;
use common\models\workitem\WorkitemType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
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
 * @property int $is_del                是否显示:0不启用，1启用
 * @property string $created_at         创建时间
 * @property string $updated_at         更新时间
 * 
 * @property WorkitemType $workType     获取工作项类型
 * @property Workitem $workitem         获取工作项
 */
class NeedContentPsd extends ActiveRecord
{
    /** 否 */
    const SHOW_NO_DEL = 0;
    /** 是 */
    const SHOW_YES_DEL = 1;
    
    /** 是否启用 */
    public static $SHOW_TYPES = [
        self::SHOW_NO_DEL => '否',
        self::SHOW_YES_DEL => '是',
    ];
    
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
            [['workitem_type_id', 'workitem_id', 'sort_order', 'is_del', 'created_at', 'updated_at'], 'integer'],
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
            'workitem_type_id' => Yii::t('app', '{Belong}{Type}', [
                'Belong' => \Yii::t('app', 'Belong'), 'Type' => \Yii::t('app', 'Type')]),
            'workitem_id' => Yii::t('app', '{Belong}{Workitem}', [
                'Belong' => \Yii::t('app', 'Belong'), 'Workitem' => \Yii::t('app', 'Workitem')]),
            'price_new' => Yii::t('app', '{New Built}{Unit Price}', [
                'New Built' => \Yii::t('app', 'New Built'), 'Unit Price' => \Yii::t('app', 'Unit Price')]),
            'price_remould' => Yii::t('app', '{Reform}{Unit Price}', [
                'Reform' => \Yii::t('app', 'Reform'), 'Unit Price' => \Yii::t('app', 'Unit Price')]),
            'sort_order' => Yii::t('app', 'Sort'),
            'is_del' => Yii::t('app', 'Enable'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 获取内容类型
     * @return array
     */
    public function getWorkitemType()
    {
        return $this->hasOne(WorkitemType::class, ['id' => 'workitem_type_id']);
    }
    
    /**
     * 获取工作项
     * @return array
     * 
     * @return ActiveQuery
     */
    public function getWorkType()
    {
        return $this->hasOne(WorkitemType::class, ['id' => 'workitem_type_id']);
    }
    
    /**
     * 
     * @return ActiveQuery
     */
    public function getWorkitem()
    {
        return $this->hasOne(Workitem::class, ['id' => 'workitem_id']);
    }
}
