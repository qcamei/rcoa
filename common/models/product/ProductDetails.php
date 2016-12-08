<?php

namespace common\models\product;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%product_details}}".
 *
 * @property integer $id                    ID
 * @property integer $product_id            产品ID
 * @property integer $created_at            创建于
 * @property integer $updated_at            更新于
 * @property string $details                详情
 *
 * @property Product $product               获取产品
 */
class ProductDetails extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_details}}';
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
            [['product_id', 'created_at', 'updated_at'], 'integer'],
            [['details'], 'string'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/product', 'ID'),
            'product_id' => Yii::t('rcoa/product', 'Product ID'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
            'details' => Yii::t('rcoa/product', 'Details'),
        ];
    }
    
    public function beforeSave($insert) {
        if(parent::beforeSave($insert))
        {
            $this->details = htmlentities($this->details);
            return true;
        }
    }
    public function afterFind() {
        $this->details = html_entity_decode($this->details);
    }

    /**
     * 获取产品
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
