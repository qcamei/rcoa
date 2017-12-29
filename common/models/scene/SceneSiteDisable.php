<?php

namespace common\models\scene;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%scene_site_disable}}".
 *
 * @property string $id
 * @property string $site_id        场地ID
 * @property string $date           日期
 * @property integer $time_index    时间段
 * @property integer $is_disable    是否禁用：0否，1是
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SceneSite $site
 */
class SceneSiteDisable extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene_site_disable}}';
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
            [['site_id', 'time_index', 'is_disable', 'created_at', 'updated_at'], 'integer'],
            [['date'], 'required'],
            [['date'], 'safe'],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => SceneSite::className(), 'targetAttribute' => ['site_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'site_id' => Yii::t('app', 'Site ID'),
            'date' => Yii::t('app', 'Date'),
            'time_index' => Yii::t('app', 'Time Index'),
            'is_disable' => Yii::t('app', 'Is Disable'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(SceneSite::className(), ['id' => 'site_id']);
    }
}
