<?php

namespace common\models\resource;

use Yii;

/**
 * This is the model class for table "{{%resource_path}}".
 *
 * @property integer $id
 * @property integer $r_id
 * @property string $path
 * @property integer $type
 * @property string $des
 *
 * @property Resource $r
 */
class ResourcePath extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%resource_path}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['r_id', 'type'], 'integer'],
            [['path', 'des'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa', 'ID'),
            'r_id' => Yii::t('rcoa/resource', 'R ID'),
            'path' => Yii::t('rcoa/resource', 'Path'),
            'type' => Yii::t('rcoa', 'Type'),
            'des' => Yii::t('rcoa', 'Des'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getR()
    {
        return $this->hasOne(Resource::className(), ['id' => 'r_id']);
    }
}
