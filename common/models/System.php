<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%system}}".
 *
 * @property integer $id    模块ID
 * @property string $name   模块名称
 * @property string $module_image   模块图片
 * @property string $modules_link   模块链接
 * @property string $des    模块描述
 * @property string $isjump 是否跳转页面
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
            [['module_image', 'module_link', 'des','isjump'], 'string', 'max' => 255]
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
            'isjump' => Yii::t('rcoa', 'Jump'),
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
