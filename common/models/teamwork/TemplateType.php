<?php

namespace common\models\teamwork;

use Yii;

/**
 * This is the model class for table "{{%teamwork_template_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $create_by
 * @property integer $created_at
 * @property integer $updated_at
 */
class TemplateType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_template_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/teamwork', 'ID'),
            'name' => Yii::t('rcoa/teamwork', 'Name'),
            'create_by' => Yii::t('rcoa/teamwork', 'Create By'),
            'created_at' => Yii::t('rcoa/teamwork', 'Created At'),
            'updated_at' => Yii::t('rcoa/teamwork', 'Updated At'),
        ];
    }
}
