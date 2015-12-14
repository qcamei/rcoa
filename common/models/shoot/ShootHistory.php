<?php

namespace common\models\shoot;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\User;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "{{%shoot_history}}".
 *
 * @property string $id
 * @property integer $b_id         任务id
 * @property integer $u_id         操作者 
 * @property integer $type         类型
 * @property string $history       历史记录
 * @property integer $created_at   创建时间
 * @property integer $updated_at   编辑时间
 *
 * @property User $u
 * @property ShootBookdetail $b
 */
class ShootHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shoot_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['b_id', 'u_id'], 'required'],
            [['b_id', 'u_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['history'], 'string', 'max' => 500]
        ];
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'b_id' => Yii::t('app', 'B ID'),
            'u_id' => Yii::t('app', 'U ID'),
            'type' => Yii::t('app', 'Type'),
            'history' => Yii::t('app', 'History'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['id' => 'u_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getB()
    {
        return $this->hasOne(ShootBookdetail::className(), ['id' => 'b_id']);
    }
}
