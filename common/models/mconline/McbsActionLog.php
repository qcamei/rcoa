<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_action_log}}".
 *
 * @property integer $id
 * @property string $action
 * @property string $title
 * @property string $content
 * @property string $created_by
 * @property string $course_id
 * @property string $relative_id
 * @property string $created_at
 * @property string $updated_at
 */
class McbsActionLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_action_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['action', 'title'], 'string', 'max' => 50],
            [['created_by'], 'string', 'max' => 36],
            [['course_id', 'relative_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'action' => Yii::t('app', 'Action'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'created_by' => Yii::t('app', 'Created By'),
            'course_id' => Yii::t('app', 'Course ID'),
            'relative_id' => Yii::t('app', 'Relative ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
