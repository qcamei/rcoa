<?php

namespace wskeee\framework\models;

use Yii;

/**
 * This is the model class for table "{{%framework_item_type}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property FrameworkItemManage[] $frameworkItemManages
 */
class ItemType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%framework_item_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/framework', 'ID'),
            'name' => Yii::t('rcoa/framework', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrameworkItemManages()
    {
        return $this->hasMany(FrameworkItemManage::className(), ['item_type_id' => 'id']);
    }
}
