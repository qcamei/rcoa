<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mconline_course}}".
 *
 * @property string $id
 * @property string $item_type_id
 * @property string $item_id
 * @property string $item_child_id
 * @property string $course_id
 * @property string $create_by
 * @property integer $status
 * @property integer $is_publish
 * @property string $publish_time
 * @property string $close_time
 * @property string $des
 * @property string $created_at
 * @property string $updated_at
 */
class McbsCourse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_type_id', 'item_id', 'item_child_id', 'course_id'], 'required'],
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'status', 'is_publish', 'publish_time', 'close_time', 'created_at', 'updated_at'], 'integer'],
            [['des'], 'string'],
            [['id'], 'string', 'max' => 32],
            [['create_by'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa', 'ID'),
            'item_type_id' => Yii::t('rcoa', 'Item Type ID'),
            'item_id' => Yii::t('rcoa', 'Item ID'),
            'item_child_id' => Yii::t('rcoa', 'Item Child ID'),
            'course_id' => Yii::t('rcoa', 'Course ID'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'status' => Yii::t('rcoa', 'Status'),
            'is_publish' => Yii::t('rcoa', 'Is Publish'),
            'publish_time' => Yii::t('rcoa', 'Publish Time'),
            'close_time' => Yii::t('rcoa', 'Close Time'),
            'des' => Yii::t('rcoa', 'Des'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }
}
