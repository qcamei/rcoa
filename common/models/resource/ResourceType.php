<?php

namespace common\models\resource;

use Yii;

/**
 * This is the model class for table "{{%resource_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 *
 * @property Resource[] $resources
 */
class ResourceType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%resource_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'image'], 'string', 'max' => 255]
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
            'image' => Yii::t('rcoa/resource', 'Image'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResources()
    {
        return $this->hasMany(Resource::className(), ['type' => 'id']);
    }
}
