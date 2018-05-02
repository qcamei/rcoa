<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "{{%product_type}}".
 *
 * @property integer $id            
 * @property string $name                   类别名称
 * @property string $des                    描述
 * @property integer $index                 索引
 * @property string $is_delete              是否删除
 */
class ProductType extends \yii\db\ActiveRecord
{
    
    /** 确定删除 */
    const SURE_DELETE = 'Y';
    /** 取消删除 */
    const CANCEL_DELETE = 'N';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['index'], 'integer'],
            [['name', 'des'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/product', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'des' => Yii::t('rcoa', 'Des'),
            'index' => Yii::t('rcoa', 'Index'),
        ];
    }
}
