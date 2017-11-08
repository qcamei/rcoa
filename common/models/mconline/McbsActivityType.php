<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_activity_type}}".
 *
 * @property string $id
 * @property string $name
 * @property string $des
 * @property string $icon_path
 * @property string $created_at
 * @property string $updated_at
 */
class McbsActivityType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_activity_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['des', 'icon_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'des' => Yii::t('app', 'Des'),
            'icon_path' => Yii::t('app', 'Icon Path'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
