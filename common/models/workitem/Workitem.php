<?php

namespace common\models\workitem;

use common\models\demand\DemandWorkitem;
use common\models\demand\DemandWorkitemTemplate;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%workitem}}".
 *
 * @property integer $id                                ID
 * @property string $name                               名称
 * @property integer $index                             索引
 * @property string $cover                              封面
 * @property string $unit                               单位
 * @property string $des                                描述
 * @property string $content                            内容详情
 * @property integer $created_at                        创建于
 * @property integer $updated_at                        更新于
 *
 * @property WorkitemCost[] $costs                      获取工作项价值
 * @property WorkitemCabinet[] $cabinets                所有展示资源
 */
class Workitem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%workitem}}';
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
            [['name', 'index', 'unit'], 'required'],
            [['index', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['unit'], 'string', 'max' => 8],
            [['des','cover'], 'string', 'max' => 255],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/workitem', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'index' => Yii::t('rcoa', 'Index'),
            'cover' => Yii::t('rcoa/workitem', 'Cover'),
            'unit' => Yii::t('rcoa/workitem', 'Unit'),
            'des' => Yii::t('rcoa', 'Des'),
            'content' => Yii::t('rcoa/workitem', 'Content'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }
    
    public function beforeSave($insert) {
        if(parent::beforeSave($insert))
        {
            $this->content = htmlentities($this->content);
            
            $upload = UploadedFile::getInstance($this, 'cover');
            if($upload != null)
            {
                $string = $upload->name;
                $array = explode('.',$string);
                //获取后缀名，默认为 jpg 
                $ext = count($array) == 0 ? 'jpg' : $array[count($array)-1];
                $uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/workitem/cover/');
                $name = md5(rand(1,10000) + time()) .".$ext";
                $upload->saveAs($uploadpath.$name);
                $this->cover = '/filedata/workitem/cover/'.$name;
            } else {
                if(trim($this->cover) == '')
                    $this->cover = '/filedata/workitem/cover/defalut.jpg';
            }
            return true;
        }
    }
    public function afterFind() {
        
        $this->content = html_entity_decode($this->content);
    }

    /**
     * 获取工作项价值
     * @return ActiveQuery
     */
    public function getCosts()
    {
        return $this->hasMany(WorkitemCost::className(), ['workitem_id' => 'id']);
    }
    
    /**
     * 获取工作项的展示资源
     * @return ActiveQuery
     */
    public function getCabinets(){
        return $this->hasMany(WorkitemCabinet::className(), ['workitem_id' => 'id'])->where(['is_delete' => 'N']);
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
