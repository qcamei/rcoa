<?php

namespace common\models\scene;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%scene_site}}".
 *
 * @property string $id
 * @property string $name           场地名称
 * @property integer $op_type       运营性质：1自营，2合作
 * @property string $area           场地所属区域
 * @property string $country        国家：1中国
 * @property string $province       省
 * @property string $city           市
 * @property string $district       区
 * @property string $twon           镇
 * @property string $address        详细地址
 * @property string $price          单价（元/小时）
 * @property string $contact        联系人
 * @property string $manager_id     管理ID
 * @property string $content_type   内容类型：1板书、2蓝箱、3外拍、4白布、5书架，多个用豆号分开
 * @property string $img_path       图片路径
 * @property integer $is_publish    是否发布：0未发布，1发布
 * @property integer $sort_order    排序
 * @property string $des            简介
 * @property string $location       位置
 * @property string $content        详细内容
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SceneSiteDisable[] $sceneSiteDisables
 */
class SceneSite extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene_site}}';
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
            [['op_type', 'country', 'province', 'city', 'district', 'twon', 'is_publish', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['location', 'content'], 'string'],
            [['name', 'img_path', 'des'], 'string', 'max' => 255],
            [['area', 'contact'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 120],
            [['manager_id'], 'string', 'max' => 36],
            [['content_type'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'op_type' => Yii::t('app', 'Op Type'),
            'area' => Yii::t('app', 'Area'),
            'country' => Yii::t('app', 'Country'),
            'province' => Yii::t('app', 'Province'),
            'city' => Yii::t('app', 'City'),
            'district' => Yii::t('app', 'District'),
            'twon' => Yii::t('app', 'Twon'),
            'address' => Yii::t('app', 'Address'),
            'price' => Yii::t('app', 'Price'),
            'contact' => Yii::t('app', 'Contact'),
            'manager_id' => Yii::t('app', 'Manager ID'),
            'content_type' => Yii::t('app', 'Content Type'),
            'img_path' => Yii::t('app', 'Img Path'),
            'is_publish' => Yii::t('app', 'Is Publish'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'des' => Yii::t('app', 'Des'),
            'location' => Yii::t('app', 'Location'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSceneSiteDisables()
    {
        return $this->hasMany(SceneSiteDisable::className(), ['site_id' => 'id']);
    }
}
