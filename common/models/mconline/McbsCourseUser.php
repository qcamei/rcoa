<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_course_user}}".
 *
 * @property string $id
 * @property string $course_id
 * @property string $user_id
 * @property integer $privilege
 * @property string $created_at
 * @property string $updated_at
 */
class McbsCourseUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['privilege', 'created_at', 'updated_at'], 'integer'],
            [['course_id'], 'string', 'max' => 32],
            [['user_id'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'privilege' => Yii::t('app', 'Privilege'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
