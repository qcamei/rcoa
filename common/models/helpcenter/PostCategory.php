<?php

namespace common\models\helpcenter;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{%post_category}}".
 *
 * @property string $id
 * @property string $parent_id          父级ID
 * @property string $app_id             应用ID
 * @property string $name               名称
 * @property string $des                描述
 * @property integer $is_show           是否显示：0不显示，1显示
 * @property integer $level             等级
 * @property string $icon               图标
 * @property string $href               跳转路径
 * @property string $created_at
 * @property string $updated_at
 */
class PostCategory extends ActiveRecord
{
    /** app-frontend */
    const APP_FRONTEND = 'app-frontend';
    /** app-mconline */
    const APP_MCONLINE = 'app-mconline';
    /** app-backend */
    const APP_BACKEND = 'app-backend';
    
    /** APP_ID */
    public static $APPID = [
        self::APP_FRONTEND => 'app-frontend',
        self::APP_MCONLINE => 'app-mconline',
        self::APP_BACKEND => 'app-backend',
    ];
       
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_category}}';
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
            [['app_id', 'name'], 'required'],
            [['parent_id', 'is_show', 'level', 'created_at', 'updated_at'], 'integer'],
            [['app_id', 'des', 'icon', 'href'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'app_id' => Yii::t('app', 'App ID'),
            'name' => Yii::t('app', 'Name'),
            'des' => Yii::t('app', 'Des'),
            'is_show' => Yii::t(null, '{Is}{Show}', [
                    'Is' => Yii::t('app', 'Is'),
                    'Show' => Yii::t('app', 'Show'),
                ]),
            'level' => Yii::t('app', 'Level'),
            'icon' => Yii::t('app', 'Icon'),
            'href' => Yii::t('app', 'Href'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 父级
     * @return ActiveQuery
     */
    public function getParent(){
        return $this->hasOne(PostCategory::className(), ['id'=>'parent_id']);
    }
    
}
