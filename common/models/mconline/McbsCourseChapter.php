<?php

namespace common\models\mconline;

use Yii;

/**
 * This is the model class for table "{{%mcbs_course_chapter}}".
 *
 * @property string $id
 * @property string $block_id
 * @property string $name
 * @property string $des
 * @property integer $sort_order
 * @property string $created_at
 * @property string $updated_at
 */
class McbsCourseChapter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mcbs_course_chapter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'block_id'], 'required'],
            [['sort_order', 'created_at', 'updated_at'], 'integer'],
            [['id', 'block_id'], 'string', 'max' => 32],
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
            'block_id' => Yii::t('app', 'Block ID'),
            'name' => Yii::t('app', 'Name'),
            'des' => Yii::t('app', 'Des'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
