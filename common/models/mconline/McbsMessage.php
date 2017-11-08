<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_message}}".
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $created_by
 * @property string $course_id
 * @property string $activity_id
 * @property string $reply_id
 * @property string $created_at
 * @property string $updated_at
 */
class McbsMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['reply_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['created_by'], 'string', 'max' => 36],
            [['course_id', 'activity_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'created_by' => Yii::t('app', 'Created By'),
            'course_id' => Yii::t('app', 'Course ID'),
            'activity_id' => Yii::t('app', 'Activity ID'),
            'reply_id' => Yii::t('app', 'Reply ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
