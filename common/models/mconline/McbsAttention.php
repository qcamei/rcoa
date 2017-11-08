<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_attention}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $course_id
 * @property string $created_at
 * @property string $updated_at
 */
class McbsAttention extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_attention}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'course_id'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['user_id'], 'string', 'max' => 36],
            [['course_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
