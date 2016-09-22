<?php

namespace common\models\multimedia;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%multimedia_type_proportion}}".
 *
 * @property integer $id                            ID
 * @property integer $content_type                  任务内容类型
 * @property string $proportion                     比例
 * @property integer $created_at                    创建于
 * @property integer $updated_at                    更新于
 *
 * @property MultimediaContentType $contentType     获取任务内容类型
 */
class MultimediaTypeProportion extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%multimedia_type_proportion}}';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content_type', 'created_at', 'updated_at'], 'integer'],
            [['proportion'], 'number'],
            [['content_type'], 'exist', 'skipOnError' => true, 'targetClass' => MultimediaContentType::className(), 'targetAttribute' => ['content_type' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/multimedia', 'ID'),
            'content_type' => Yii::t('rcoa/multimedia', 'Content Type'),
            'proportion' => Yii::t('rcoa/multimedia', 'Proportion'),
            'created_at' => Yii::t('rcoa/multimedia', 'Created At'),
            'updated_at' => Yii::t('rcoa/multimedia', 'Updated At'),
        ];
    }

    /**
     * 获取任务内容类型
     * @return ActiveQuery
     */
    public function getContentType()
    {
        return $this->hasOne(MultimediaContentType::className(), ['id' => 'content_type']);
    }
}
