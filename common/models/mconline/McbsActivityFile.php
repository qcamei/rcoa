<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_activity_file}}".
 *
 * @property string $activity_id
 * @property string $file_id
 */
class McbsActivityFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_activity_file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'file_id'], 'required'],
            [['activity_id', 'file_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => Yii::t('app', 'Activity ID'),
            'file_id' => Yii::t('app', 'File ID'),
        ];
    }
}
