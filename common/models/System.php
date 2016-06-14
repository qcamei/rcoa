<?php

namespace common\models;

use common\wskeee\job\models\Job;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%system}}".
 *
 * @property integer $id    模块ID
 * @property string $name   模块名称
 * @property string $aliases   模块别名
 * @property string $module_image   模块图片
 * @property string $modules_link   模块链接
 * @property string $des    模块描述
 * @property string $isjump 是否跳转页面
 *
 * @property Job[] $jobs
 */
class System extends ActiveRecord
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
            [['isjump'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['module_image', 'module_link', 'des', 'aliases'], 'string', 'max' => 255]
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
            'isjump' => Yii::t('rcoa', 'Isjump'),
            'aliases' => Yii::t('rcoa', 'Aliases'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getJobs()
    {
        return $this->hasMany(Job::className(), ['system_id' => 'id']);
    }
}
