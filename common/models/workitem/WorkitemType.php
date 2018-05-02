<?php

namespace common\models\workitem;

use common\models\demand\DemandWorkitemTemplate;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%workitem_type}}".
 *
 * @property integer $id                                ID
 * @property string $name                               名字
 * @property integer $index                             索引
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 *
 * @property DemandWorkitemTemplate[] $demandWorkitemTemplates      获取所有需求工作项模版
 */
class WorkitemType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%workitem_type}}';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'index'], 'required'],
            [['index', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/workitem', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'index' => Yii::t('rcoa', 'Index'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }

    /**
     * 获取所有需求工作项模版
     * @return ActiveQuery
     */
    public function getDemandWorkitemTemplates()
    {
        return $this->hasMany(DemandWorkitemTemplate::className(), ['workitem_type_id' => 'id']);
    }
}
