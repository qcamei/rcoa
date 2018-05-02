<?php

namespace common\models\teamwork;

use common\models\teamwork\Link;
use common\models\teamwork\TemplateType;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_phase_template}}".
 *
 * @property integer $id                        ID
 * @property integer $template_type_id          模版类型
 * @property string $name                       名称
 * @property string $weights                    权重
 * @property string $create_by                  创建者
 * @property integer $created_at                创建于
 * @property integer $updated_at                更新于
 * @property integer $index                     索引
 *
 * @property Link[] $links                      获取所有环节
 * @property User $createBy                     获取创建者
 * @property TemplateType $templateType         获取模版类型
 */
class Phase extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_phase_template}}';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className('created_at')
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_type_id', 'created_at', 'updated_at', 'index'], 'integer'],
            [['weights'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
            [['is_delete'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/teamwork', 'ID'),
            'template_type_id' => Yii::t('rcoa/teamwork', 'Template Type'),
            'name' => Yii::t('rcoa', 'Name'),
            'weights' => Yii::t('rcoa/teamwork', 'Weights'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'index' => Yii::t('rcoa', 'Index'),
            'is_delete' => Yii::t('rcoa', 'Is Delete'),
        ];
    }

    /**
     * 获取所有环节
     * @return ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(Link::className(), ['phase_id' => 'id']);
    }

    /**
     * 获取创建者
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }
    
    /**
     * 获取模版类型
     * @return type
     */
    public function getTemplateType()
    {
        return $this->hasOne(TemplateType::className(), ['id' => 'template_type_id']);
    }
}
