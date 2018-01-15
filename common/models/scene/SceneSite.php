<?php

namespace common\models\scene;

use common\models\Region;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

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
    /** 自营 */
    const TYPE_NO_CHOICE = 1;
    /** 合作 */
    const TYPE_YES_CHOICE = 2;
    /** 类型 */
    public static $TYPES = [
        self::TYPE_NO_CHOICE => '自营',
        self::TYPE_YES_CHOICE => '合作',
    ];
    
    /** 板书 */
    const TYPE_ONE = '板书';
    /** 蓝箱 */
    const TYPE_TWO = '蓝箱';
    /** 外拍 */
    const TYPE_THREE = '外拍';
    /** 白布 */
    const TYPE_FOUR = '白布';
    /** 书架 */
    const TYPE_FIVE = '书架';
    /** 类型 */
    public static $CONTENT_TYPES = [
        self::TYPE_ONE => '板书',
        self::TYPE_TWO => '蓝箱',
        self::TYPE_THREE => '外拍',
        self::TYPE_FOUR => '白布',
        self::TYPE_FIVE => '书架',
    ];
    
    
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
            [['op_type','area', 'province', 'city', 'district', 'name', 'contact', 'manager_id', 'content_type', 'price'], 'required'],
            [['op_type', 'country', 'province', 'city', 'district', 'twon', 'is_publish', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['location', 'content'], 'string'],
            [['name', 'img_path', 'des'], 'string', 'max' => 255],
            [['area', 'contact'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 120],
            [['manager_id'], 'string', 'max' => 36],
//            [['content_type'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', '{Scene}{Name}',['Scene' => Yii::t('app', 'Scene'),'Name' => Yii::t('app', 'Name'),]),
            'op_type' => Yii::t('app', 'Nature'),
            'area' => Yii::t('app', 'Area'),
            'country' => Yii::t('app', 'Country'),
            'province' => Yii::t('app', 'Province'),
            'city' => Yii::t('app', 'City'),
            'district' => Yii::t('app', 'District'),
            'twon' => Yii::t('app', 'Twon'),
            'address' => Yii::t('app', 'Address'),
            'price' => Yii::t('app', 'Price'),
            'contact' => Yii::t('app', 'Contact'),
            'manager_id' => Yii::t('app', 'Manager'),
            'content_type' => Yii::t('app', 'Content Type'),
            'img_path' => Yii::t('app', 'Picture'),
            'is_publish' => Yii::t('app', '{Is}{Publish}',['Is' => Yii::t('app', 'Is'),'Publish' => Yii::t('app', 'Publish'),]),
            'sort_order' => Yii::t('app', 'Sort'),
            'des' => Yii::t('app', 'Introduction'),
            'location' => Yii::t('app', 'Location'),
            'content' => Yii::t('app', 'Detail'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 管理员
     * @return ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(User::className(), ['id'=>'manager_id']);
    }
    
    /**
     * 省/市/区/镇
     * @return ActiveQuery
     */
    public function getAdds1(){
        return $this->hasOne(Region::className(), ['id'=>'province']);
    }
    public function getAdds2(){
        return $this->hasOne(Region::className(), ['id'=>'city']);
    }
    public function getAdds3(){
        return $this->hasOne(Region::className(), ['id'=>'district']);
    }
    public function getAdds4(){
        return $this->hasOne(Region::className(), ['id'=>'twon']);
    }

    /**
     * 
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)){
            //把内容性质转为字符串保存
            $content_type = ArrayHelper::getValue(Yii::$app->request->post(), 'SceneSite.content_type', []);
            $this->content_type = implode(",", $content_type);
            //拿到经纬度并处理
            $location = ArrayHelper::getValue(Yii::$app->request->post(), 'SceneSite.location');
            $this->location = new Expression("GeomFromText('POINT($location)')");
            //图片上传
            $img_name = md5(time());
            $upload = UploadedFile::getInstance($this, 'img_path');
            if($upload !== null){
                $string = $upload->name;
                $array = explode('.', $string);
                //获取后缀名，默认名为.jpg
                $ext = count($array) == 0 ? 'jpg' : $array[count($array)-1];
                $uploadpath = $this->fileExists(Yii::getAlias('@filedata') . '/scene/');
                $upload->saveAs($uploadpath . $img_name . '.' . $ext) ;
                $this->img_path = '/filedata/scene/' . $img_name . '.' . $ext . '?r=' . rand(1, 10000);
            }
            if(trim($this->img_path) == ''){
                $this->img_path = $this->getOldAttribute('img_path');
            }
            return true;
        }
        return false;
    }
    
    /**
     * 检查目标路径是否存在，不存即创建目标
     * @param type $uploadpath  目标路径
     * @return type
     */
    private function fileExists($uploadpath){
        if(!file_exists($uploadpath)){
            mkdir($uploadpath);
        }
        return $uploadpath;
    }
    
    /**
     * @return ActiveQuery
     */
    public function getSceneSiteDisables()
    {
        return $this->hasMany(SceneSiteDisable::className(), ['site_id' => 'id']);
    }
    
    /**
     * 获取省/市/区/镇
     * @param integer $parent_id
     * @return array
     */
    public function getCityList($parent_id)
    {  
        $model = Region::findAll(['parent_id' => $parent_id]);  
        return ArrayHelper::map($model, 'id', 'name');  
    }
    
    /**
     * 获取所有人
     * @return array
     */
    public function getUsers()
    {
        $users = (new Query())
                ->select(['id','nickname'])
                ->from(['Users' => User::tableName()])
                ->all();
        return ArrayHelper::map($users, 'id', 'nickname');
    }

}
