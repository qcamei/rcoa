<?php

namespace wskeee\framework\models;

use Yii;

/**
 * This is the model class for table "{{%framework_item_manage}}".
 *
 * @property integer $id
 * @property integer $item_type_id
 * @property integer $item_id
 * @property integer $item_child_id
 * @property string $create_by
 * @property integer $created_at
 * @property string $forecast_time
 * @property string $real_carry_out
 * @property integer $progress
 * @property integer $status
 * @property string $background
 * @property string $use
 *
 * @property FrameworkCourseManage[] $frameworkCourseManages
 * @property User $createBy
 * @property FrameworkItem $itemChild
 * @property FrameworkItem $item
 * @property FrameworkItemType $itemType
 */
class ItemManage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%framework_item_manage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_type_id', 'item_id', 'item_child_id', 'created_at', 'progress', 'status'], 'integer'],
            [['create_by'], 'string', 'max' => 36],
            [['forecast_time', 'real_carry_out'], 'string', 'max' => 60],
            [['background', 'use'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/framework', 'ID'),
            'item_type_id' => Yii::t('rcoa/framework', 'Item Type ID'),
            'item_id' => Yii::t('rcoa/framework', 'Item ID'),
            'item_child_id' => Yii::t('rcoa/framework', 'Item Child ID'),
            'create_by' => Yii::t('rcoa/framework', 'Create By'),
            'created_at' => Yii::t('rcoa/framework', 'Created At'),
            'forecast_time' => Yii::t('rcoa/framework', 'Forecast Time'),
            'real_carry_out' => Yii::t('rcoa/framework', 'Real Carry Out'),
            'progress' => Yii::t('rcoa/framework', 'Progress'),
            'status' => Yii::t('rcoa/framework', 'Status'),
            'background' => Yii::t('rcoa/framework', 'Background'),
            'use' => Yii::t('rcoa/framework', 'Use'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrameworkCourseManages()
    {
        return $this->hasMany(FrameworkCourseManage::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemChild()
    {
        return $this->hasOne(FrameworkItem::className(), ['id' => 'item_child_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(FrameworkItem::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemType()
    {
        return $this->hasOne(FrameworkItemType::className(), ['id' => 'item_type_id']);
    }
}
