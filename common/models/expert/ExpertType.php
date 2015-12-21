<?php

namespace common\models\expert;

use Yii;

/**
 * This is the model class for table "{{%expert_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $icon
 */
class ExpertType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%expert_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['icon'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'icon' => Yii::t('rcoa', 'Icon'),
        ];
    }
}
