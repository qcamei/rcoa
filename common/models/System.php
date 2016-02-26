<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%system}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $module_image
 * @property string $modules_link
 * @property string $des
 *
 * @property Job[] $jobs
 */
class System extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 64],
            [['module_image', 'module_link', 'des'], 'string', 'max' => 255]
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
            'module_image' => Yii::t('rcoa', 'Module Image'),
            'module_link' => Yii::t('rcoa', 'Module Link'),
            'des' => Yii::t('rcoa', 'Des'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobs()
    {
        return $this->hasMany(Job::className(), ['system_id' => 'id']);
    }
}
