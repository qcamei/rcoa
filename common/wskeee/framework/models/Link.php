<?php

namespace wskeee\framework\models;

use Yii;

/**
 * This is the model class for table "{{%framework_link}}".
 *
 * @property integer $id
 * @property integer $phase_id
 * @property string $name
 * @property integer $type
 * @property string $unit
 * @property integer $progress
 * @property string $create_by
 *
 * @property User $createBy
 * @property FrameworkPhase $phase
 */
class Link extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%framework_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'phase_id', 'type', 'progress'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 16],
            [['create_by'], 'string', 'max' => 36]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/framework', 'ID'),
            'phase_id' => Yii::t('rcoa/framework', 'Phase ID'),
            'name' => Yii::t('rcoa/framework', 'Name'),
            'type' => Yii::t('rcoa/framework', 'Type'),
            'unit' => Yii::t('rcoa/framework', 'Unit'),
            'progress' => Yii::t('rcoa/framework', 'Progress'),
            'create_by' => Yii::t('rcoa/framework', 'Create By'),
        ];
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
    public function getPhase()
    {
        return $this->hasOne(FrameworkPhase::className(), ['id' => 'phase_id']);
    }
}
