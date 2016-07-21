<?php

namespace common\models\teamwork;

use common\models\teamwork\Link;
use common\models\teamwork\Phase;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teamwork_template_type}}".
 *
 * @property integer $id                    id
 * @property string $name                   模版名称
 * @property string $create_by              创建者
 * @property integer $created_at            创建于
 * @property integer $updated_at            更新于
 * @property string $des                    描述
 * 
 * @property Phase[] $phaseTemplateTypes    获取模版类型下所有阶段
 * @property Link[] $linkTemplateType       获取模版类型下所有环节
 */
class TemplateType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teamwork_template_type}}';
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
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'des'], 'string', 'max' => 255],
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
            'name' => Yii::t('rcoa', 'Name'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
            'des' => Yii::t('rcoa', 'Des'),
        ];
    }
    
    /**
     * 获取模版类型下所有阶段
     * @return type
     */
    public function  getPhaseTemplateTypes()
    {
        return $this->hasMany(Phase::className(), ['template_type_id' => 'id']);
    }
    
    /**
     * 获取模版类型下所有环节
     * @return type
     */
    public function  getLinkTemplateTypes()
    {
        return $this->hasMany(Link::className(), ['template_type_id' => 'id']);
    }
}
