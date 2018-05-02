<?php

namespace common\models\scene;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%scene_appraise_result}}".
 *
 * @property string $id
 * @property string $appraise_id        题目ID
 * @property string $user_id            用户ID
 * @property string $value              评价分数
 * @property string $data               数据详细
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SceneAppraise $appraise
 */
class SceneAppraiseResult extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene_appraise_result}}';
    }

    /**
     * @inheritdoc
     */
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
            [['appraise_id'], 'required'],
            [['appraise_id', 'value', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'string', 'max' => 36],
            [['data'], 'string', 'max' => 255],
            [['appraise_id'], 'exist', 'skipOnError' => true, 'targetClass' => SceneAppraise::className(), 'targetAttribute' => ['appraise_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'appraise_id' => Yii::t('app', 'Appraise ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'value' => Yii::t('app', 'Value'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAppraise()
    {
        return $this->hasOne(SceneAppraise::className(), ['id' => 'appraise_id']);
    }
}
