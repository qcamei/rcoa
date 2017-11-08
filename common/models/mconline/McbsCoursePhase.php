<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_course_phase}}".
 *
 * @property string $id
 * @property string $course_id
 * @property string $name
 * @property double $value_percent
 * @property string $des
 * @property integer $sort_order
 * @property string $created_at
 * @property string $updated_at
 */
class McbsCoursePhase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course_phase}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'course_id'], 'required'],
            [['value_percent'], 'number'],
            [['des'], 'string'],
            [['sort_order', 'created_at', 'updated_at'], 'integer'],
            [['id', 'course_id'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 100],
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
            'name' => Yii::t('app', 'Name'),
            'value_percent' => Yii::t('app', 'Value Percent'),
            'des' => Yii::t('app', 'Des'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
