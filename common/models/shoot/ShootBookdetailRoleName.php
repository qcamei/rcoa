<?php

namespace common\models\shoot;

use wskeee\rbac\models\AuthItem;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\User;

/**
 * This is the model class for table "{{%shoot_bookdetail_role_name}}".
 *
 * @property integer $b_id  拍摄任务id
 * @property string $u_id  用户角色id 
 * @property string $role_name  角色
 * @property integer $primary_foreign 主从关系
 *
 * @property AuthItem $roleName
 * @property ShootBookdetail $b
 * @property User $u
 */
class ShootBookdetailRoleName extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shoot_bookdetail_role_name}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['b_id', 'u_id', 'role_name'], 'required'],
            [['b_id',  'primary_foreign'], 'integer'],
            [['u_id',], 'string', 'max' => 36],
            [['role_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'b_id' => Yii::t('app', 'B ID'),
            'u_id' => Yii::t('app', 'U ID'),
            'role_name' => Yii::t('app', 'Role Name'),
            'primary_foreign' => Yii::t('app', 'Primary Foreign'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getRoleName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'role_name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getB()
    {
        return $this->hasOne(ShootBookdetail::className(), ['id' => 'b_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['id' => 'u_id']);
    }
}
