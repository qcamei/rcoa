<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_course_activity}}".
 *
 * @property string $id
 * @property string $section_id
 * @property integer $type_id
 * @property string $name
 * @property string $des
 * @property integer $sort_order
 * @property string $created_at
 * @property string $updated_at
 */
class McbsCourseActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course_activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'section_id'], 'required'],
            [['type_id', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['id', 'section_id'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 100],
            [['des'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'section_id' => Yii::t('app', 'Section ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'name' => Yii::t('app', 'Name'),
            'des' => Yii::t('app', 'Des'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
