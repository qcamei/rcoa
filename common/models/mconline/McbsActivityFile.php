<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_activity_file}}".
 *
 * @property string $id
 * @property string $activity_id        活动ID
 * @property string $file_id            文件ID
 * @property string $course_id          课程ID
 * @property string $created_by         创建人ID
 * @property string $expire_time        到期时间
 * @property string $created_at         创建时间
 * @property string $updated_at         更新时间
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
            [['activity_id', 'file_id', 'course_id', 'created_by'], 'required'],
            [['expire_time', 'created_at', 'updated_at'], 'integer'],
            [['activity_id', 'file_id', 'course_id', 'created_by'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'activity_id' => Yii::t('app', 'Activity ID'),
            'file_id' => Yii::t('app', 'File ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'expire_time' => Yii::t('app', 'Expire Time'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
