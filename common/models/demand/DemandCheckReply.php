<?php

namespace common\models\demand;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_check_reply}}".
 *
 * @property integer $id                        ID
 * @property integer $demand_check_id           引用审核记录ID
 * @property string $title                      标题
 * @property integer $pass                      是否通过
 * @property string $des                        备注
 * @property string $create_by                  创建者
 * @property integer $created_at                创建于
 * @property integer $updated_at                更新于
 *
 * @property User $createBy                     获取创建者
 * @property DemandCheck $demandCheck           获取审核记录
 */
class DemandCheckReply extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_check_reply}}';
    }
    
    public function behaviors() 
    {
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
            [['demand_check_id', 'pass', 'created_at', 'updated_at'], 'integer'],
            [['des'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['create_by'], 'string', 'max' => 36],
            [['demand_check_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandCheck::className(), 'targetAttribute' => ['demand_check_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'demand_check_id' => Yii::t('rcoa/demand', 'Demand Check ID'),
            'title' => Yii::t('rcoa/demand', 'Title'),
            'pass' => Yii::t('rcoa/demand', 'Pass'),
            'des' => Yii::t('rcoa/demand', 'Des'),
            'create_by' => Yii::t('rcoa/demand', 'Create By'),
            'created_at' => Yii::t('rcoa/demand', 'Created At'),
            'updated_at' => Yii::t('rcoa/demand', 'Updated At'),
        ];
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
     * 获取审核记录
     * @return ActiveQuery
     */
    public function getDemandCheck()
    {
        return $this->hasOne(DemandCheck::className(), ['id' => 'demand_check_id']);
    }
}
