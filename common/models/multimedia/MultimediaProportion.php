<?php

namespace common\models\multimedia;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%multimedia_proportion}}".
 *
 * @property integer $id                                  ID
 * @property string $name_type                            类型名称
 * @property string $proportion                           比例
 * @property string $des                                  描述
 *
 * @property MultimediaManage[] $multimediaManages        获取所有任务
 */
class MultimediaProportion extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%multimedia_proportion}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['proportion'], 'number'],
            [['name_type', 'des'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/multimedia', 'ID'),
            'name_type' => Yii::t('rcoa/multimedia', 'Name Type'),
            'proportion' => Yii::t('rcoa/multimedia', 'Proportion'),
            'des' => Yii::t('rcoa', 'Des'),
        ];
    }

    /**
     * 获取所有任务
     * @return ActiveQuery
     */
    public function getMultimediaManages()
    {
        return $this->hasMany(MultimediaManage::className(), ['content_type' => 'id']);
    }
}
