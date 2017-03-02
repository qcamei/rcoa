<?php

namespace common\models\product;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%product_details}}".
 *
 * @property integer $id                    ID
 * @property integer $product_id            产品ID
 * @property integer $created_at            创建于
 * @property integer $updated_at            更新于
 * @property string $details                详情
 *
 * @property Product $product               获取产品
 */
class ProductDetails extends ActiveRecord
{
    /**
     * 上传文件路径
     * @var string 
     */
    public $uploadpath = '';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_details}}';
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
            [['product_id', 'created_at', 'updated_at'], 'integer'],
            [['details'], 'file','maxFiles' => 10,'extensions'=>'jpg,png,gif'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/product', 'ID'),
            'product_id' => Yii::t('rcoa/product', 'Product ID'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
            'details' => Yii::t('rcoa/product', 'Details'),
        ];
    }
    
    /*public function beforeSave($insert) {
        if(parent::beforeSave($insert))
        {
            $this->details = htmlentities($this->details);
            return true;
        }
    }
    public function afterFind() {
        $this->details = html_entity_decode($this->details);
    }*/
    
    /**
     * 
     * @param type $insert 
    
    public function beforeSave($insert) 
    {
        if(parent::beforeSave($insert))
        {
            $upload = UploadedFile::getInstances($this, 'details');  
            
            if($upload != null){
                $values = [];
                $this->uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/product/'.date('Y-m-d', time()).'/');
                foreach ($upload as $index => $fl){
                    $fl->saveAs($this->uploadpath .$fl->baseName. '.' . $fl->extension);
                    $values[] = [
                        'product_id' => $this->product_id,
                        'created_at' => $this->created_at,
                        'updated_at' => $this->updated_at,
                        'details' => '/filedata/product/'.date('Y-m-d', time()).'/'.$fl->baseName.'.'.$fl->extension,
                        'index' => $index,
                    ];
                   
                }
               Yii::$app->db->createCommand()->batchInsert(self::tableName(), 
                ['product_id', 'created_at', 'updated_at', 'details', 'index'], $values)->execute();
               
            }               
            return true;
        }else
            return false;
    } */

    /**
     * 获取产品
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    
    /**
     * 检查目标路径是否存在，不存即创建目标
     * @param string $uploadpath    目录路径
     * @return string
     
    private function fileExists($uploadpath) {

        if (!file_exists($uploadpath)) {
            mkdir($uploadpath);
        }
        return $uploadpath;
    }*/
}
