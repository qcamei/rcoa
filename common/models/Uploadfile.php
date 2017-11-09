<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%uploadfile}}".
 *
 * @property string $id
 * @property string $name
 * @property string $path
 * @property string $thumb_path
 * @property string $download_count
 * @property integer $del_mark
 * @property integer $is_del
 * @property integer $is_fixed
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 */
class Uploadfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%uploadfile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['download_count', 'del_mark', 'is_del', 'is_fixed', 'created_at', 'updated_at'], 'integer'],
            [['id'], 'string', 'max' => 32],
            [['name', 'path', 'thumb_path'], 'string', 'max' => 255],
            [['created_by'], 'string', 'max' => 36],
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
            'path' => Yii::t('app', 'Path'),
            'thumb_path' => Yii::t('app', 'Thumb Path'),
            'download_count' => Yii::t('app', 'Download Count'),
            'del_mark' => Yii::t('app', 'Del Mark'),
            'is_del' => Yii::t('app', 'Is Del'),
            'is_fixed' => Yii::t('app', 'Is Fixed'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
