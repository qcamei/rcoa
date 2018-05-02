<?php

namespace wskeee\framework\models;

use common\models\teamwork\ItemManage;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%framework_item_type}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property ItemManage[] $itemManages
 */
class ItemType extends ActiveRecord
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
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetAttribute' => ['name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/framework', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getItemManages()
    {
        return $this->hasMany(ItemManage::className(), ['item_type_id' => 'id']);
    }
}
