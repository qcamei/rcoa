<?php

namespace common\models\expert;

use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%expert}}".
 *
 * @property integer $u_id
 * @property integer $type
 * @property string $birth
 * @property string $personal_image     个人形象
 * @property string $job_title          头衔
 * @property string $job_name           职称
 * @property string $level              等级
 * @property string $employer           单位信息
 * @property string $attainment         成就
 *
 * @property User $user
 * @property ExpertType $expertType
 * @property ExpertProject[] $expertProjects
 */
class Expert extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%expert}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['u_id',], 'string', 'max' => 36],
            [['attainment','birth'], 'string'],
            [['job_title', 'job_name', 'level', 'employer'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('rcoa', 'Username'),
            'nickname' => Yii::t('rcoa', 'Nickname'),
            'personal_image' => Yii::t('rcoa', 'Personal Image'),
            'sex' => Yii::t('rcoa', 'Sex'),
            'email' => Yii::t('rcoa', 'Email'),
            'phone' => Yii::t('rcoa', 'Phone'),
            'avatar' => Yii::t('rcoa', 'Avatar'),
            'u_id' => Yii::t('rcoa', 'U ID'),
            'type' => Yii::t('rcoa', 'Type'),
            'birth' => Yii::t('rcoa', 'Birth'),
            'job_title' => Yii::t('rcoa', 'Job Title'),
            'job_name' => Yii::t('rcoa', 'Job Name'),
            'level' => Yii::t('rcoa', 'Level'),
            'employer' => Yii::t('rcoa', 'Employer'),
            'attainment' => Yii::t('rcoa', 'Attainment'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'u_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getExpertType()
    {
        return $this->hasOne(ExpertType::className(), ['id' => 'type']);
    }
    /**
     * @return ActiveQuery
     */
    public function getExpertProjects()
    {
        return $this->hasMany(ExpertProject::className(), ['expert_id' => 'u_id']);
    }
}
