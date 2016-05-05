<?php

namespace common\models\resource;

use Yii;

/**
 * This is the model class for table "{{%resource}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $des
 *
 * @property ResourceType $resourceType
 * @property ResourcePath[] $resourcePaths
 */
class Resource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%resource}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['name', 'des', 'image'], 'string', 'max' => 255]
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
            'type' => Yii::t('rcoa', 'Type'),
            'image' => Yii::t('rcoa/resource', 'Image'),
            'des' => Yii::t('rcoa', 'Des'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourceType()
    {
        return $this->hasOne(ResourceType::className(), ['id' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourcePaths()
    {
        return $this->hasMany(ResourcePath::className(), ['r_id' => 'id']);
    }
}
