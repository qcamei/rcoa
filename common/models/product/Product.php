<?php

namespace common\models\product;

use common\models\demand\DemandTaskProduct;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id                    
 * @property integer $type                              产品类别
 * @property string $name                               产品名称
 * @property string $unit_price                         单价
 * @property string $currency                           币种
 * @property integer $level                             等级
 * @property string $image                              主图
 * @property string $des                                描述
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 * @property integer $parent_id                         父级ID
 *
 * @property DemandTaskProduct[] $demandTaskProducts    获取所有需求任务产品
 * @property ProductType $productType                   获取产品类别
 * @property Product $parent                            获取父级ID
 * @property Product[] $products                        获取所有产品
 * @property ProductDetails $productDetail              获取单个产品详情
 * @property ProductDetails[] $productDetails           获取所有产品详情
 */
class Product extends ActiveRecord
{
    /** 分类 */
    const CLASSIFICATION = 1;
    /** 产品 */
    const GOODS = 2;

    /**
     * 产品等级名称
     * @var array 
     */
    public static $levelName = [
        self::CLASSIFICATION => '分类',
        self::GOODS => '产品',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }
    
    public function behaviors() {
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
            [['type', 'level', 'name'], 'required'],
            [['type', 'level', 'created_at', 'updated_at'], 'integer'],
            [['unit_price'], 'number'],
            [['des'], 'string'],
            [['currency'], 'string', 'max' => 4],
            [['name', 'image'], 'string', 'max' => 255],
            //[['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/product', 'ID'),
            'type' => Yii::t('rcoa/product', 'Type'),
            'name' => Yii::t('rcoa', 'Name'),
            'unit_price' => Yii::t('rcoa/product', 'Unit Price'),
            'currency' => Yii::t('rcoa/product', 'Currency'),
            'level' => Yii::t('rcoa', 'Level'),
            'image' => Yii::t('rcoa/product', 'Image'),
            'des' => Yii::t('rcoa', 'Des'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
            'parent_id' => Yii::t('rcoa', 'Parent ID'),
        ];
    }
    
    /**
     * 
     * @param type $insert 
     */
    public function beforeSave($insert) 
    {
        if(parent::beforeSave($insert))
        {
            $upload = UploadedFile::getInstance($this, 'image');
            if($upload != null)
            {
                $string = $upload->name;
                $array = explode('.',$string);
                //获取后缀名，默认为 jpg 
                $ext = count($array) == 0 ? 'jpg' : $array[count($array)-1];
                $uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/product/'.date('Y-m-d', time()).'/');
                $uploadname = time().rand(1, 4);
                $upload->saveAs($uploadpath.$uploadname.'.'.$ext);
                $this->image = '/filedata/product/'.date('Y-m-d', time()).'/'.$uploadname.'.'.$ext;
                
                if(trim($this->image) == '')
                    $this->image = $this->getOldAttribute ('image');
            }
            return true;
        }else
            return false;
    }
    
    /**
     * 获取所有需求任务产品
     * @return ActiveQuery
     */
    public function getDemandTaskProducts()
    {
        return $this->hasMany(DemandTaskProduct::className(), ['product_id' => 'id']);
    }
    
    /**
     * 获取产品类别
     * @return ActiveQuery
     */
    public function getProductType()
    {
        return $this->hasOne(ProductType::className(), ['id' => 'type']);
    }
    
    /**
     * 获取父级ID
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Product::className(), ['id' => 'parent_id']);
    }

    /**
     * 获取单个产品详情
     * @return ActiveQuery
     */
    public function getProductDetail()
    {
        return $this->hasOne(ProductDetails::className(), ['product_id' => 'id']);
    }
    
    /**
     * 获取所有产品
     * @return ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['parent_id' => 'id']);
    }

    /**
     * 获取所有产品详情
     * @return ActiveQuery
     */
    public function getProductDetails()
    {
        return $this->hasMany(ProductDetails::className(), ['product_id' => 'id'])->orderBy('index');
    }
    
    /**
     * 检查目标路径是否存在，不存即创建目标
     * @param string $uploadpath    目录路径
     * @return string
     */
    private function fileExists($uploadpath) {

        if (!file_exists($uploadpath)) {
            mkdir($uploadpath);
        }
        return $uploadpath;
    }
}
