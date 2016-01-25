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
            [['module_image', 'modules_link', 'des'], 'string', 'max' => 255]
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
            'module_image' => Yii::t('app', 'Module Image'),
            'modules_link' => Yii::t('app', 'Modules Link'),
            'des' => Yii::t('app', 'Des'),
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
