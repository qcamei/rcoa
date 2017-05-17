<?php

namespace common\models\demand;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%demand_appeal_reply}}".
 *
 * @property integer $id                                id
 * @property integer $demand_appeal_id                  引用申诉id
 * @property string $title                              回复标题
 * @property integer $pass                              是否同意
 * @property string $des                                备注
 * @property string $create_by                          创建者
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 *
 * @property DemandAppeal $demandAppeal                 获取申诉信息
 * @property User $createBy                             获取创建者
 */
class DemandAppealReply extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demand_appeal_reply}}';
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
            [['demand_appeal_id', 'pass', 'created_at', 'updated_at'], 'integer'],
            [['des'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['create_by'], 'string', 'max' => 36],
            [['demand_appeal_id'], 'exist', 'skipOnError' => true, 'targetClass' => DemandAppeal::className(), 'targetAttribute' => ['demand_appeal_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/demand', 'ID'),
            'demand_appeal_id' => Yii::t('rcoa/demand', 'Demand Appeal ID'),
            'title' => Yii::t('rcoa/demand', 'Title'),
            'pass' => Yii::t('rcoa/demand', 'Pass'),
            'des' => Yii::t('rcoa/demand', 'Des'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }

    /**
     * 获取申诉信息
     * @return ActiveQuery
     */
    public function getDemandAppeal()
    {
        return $this->hasOne(DemandAppeal::className(), ['id' => 'demand_appeal_id']);
    }
    
    /**
     * 获取创建者
     * @return ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }
}
