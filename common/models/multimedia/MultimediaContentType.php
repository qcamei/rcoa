<?php

namespace common\models\multimedia;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%multimedia_content_type}}".
 *
 * @property integer $id                                ID
 * @property string $name                               内容类型名称
 * @property string $des                                描述
 * @property integer $index                             索引
 *
 * @property MultimediaTask[] $multimediaTasks          获取所有任务
 * @property MultimediaTypeProportion[] $proportions    获取所有类型比例
 */
class MultimediaContentType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%multimedia_content_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'des'], 'string', 'max' => 255],
            [['index'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/multimedia', 'ID'),
            'name' => Yii::t('rcoa/multimedia', 'Name'),
            'des' => Yii::t('rcoa', 'Des'),
            'index' => Yii::t('rcoa', 'Index'),
        ];
    }

    /**
     * 获取所有任务
     * @return ActiveQuery
     */
    public function getMultimediaTasks()
    {
        return $this->hasMany(MultimediaTask::className(), ['content_type' => 'id']);
    }

    /**
     * 获取所有类型比例
     * @return ActiveQuery
     */
    public function getProportions()
    {
        return $this->hasMany(MultimediaTypeProportion::className(), ['content_type' => 'id']);
    }
}
