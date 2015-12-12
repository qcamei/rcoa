<?php

namespace common\models\shoot;
use common\models\User;
use wskeee\rbac\RbacName;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{%shoot_history}}".
 *
 * @property string $id
 * @property integer $b_id     任务ID
 * @property integer $u_id     操作者
 * @property integer $type
 * @property string $history   原因
 * @property integer $updat_at 编辑时间
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
            [['b_id'], 'required'],
            [['b_id', 'u_id', 'type', 'updat_at'], 'integer'],
            [['history'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa', 'ID'),
            'b_id' => Yii::t('rcoa', 'B ID'),
            'u_id' => Yii::t('rcoa', 'U ID'),
            'type' => Yii::t('rcoa', 'Type'),
            'history' => Yii::t('rcoa', 'History'),
            'updat_time' => Yii::t('rcoa', 'Updat Time'),
        ];
    }
}
