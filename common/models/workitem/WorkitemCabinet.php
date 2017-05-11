<?php

namespace common\models\workitem;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%workitem_cabinet}}".
 *
 * @property integer $id
 * @property integer $workitem_id
 * @property integer $index
 * @property string $name
 * @property string $title
 * @property string $type
 * @property string $poster             视频预览图
 * @property string $path               图片路径或者视频路径
 * @property string $content
 * @property string $is_delete
 * 
 * @property Workitem $workitem 引用工作项
 */
class WorkitemCabinet extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%workitem_cabinet}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workitem_id'], 'required'],
            [['workitem_id', 'index'], 'integer'],
            [['name', 'title', 'type','poster', 'path', 'content'], 'string', 'max' => 255],
            [['is_delete'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/workitem', 'ID'),
            'workitem_id' => Yii::t('rcoa/workitem', 'Workitem ID'),
            'index' => Yii::t('rcoa', 'Index'),
            'name' => Yii::t('rcoa', 'Name'),
            'title' => Yii::t('rcoa', 'Title'),
            'type' => Yii::t('rcoa', 'Type'),
            'poster' => Yii::t('rcoa/workitem', 'Poster'),
            'path' => Yii::t('rcoa/workitem', 'Path'),
            'content' => Yii::t('rcoa/workitem', 'Content'),
            'is_delete' => Yii::t('rcoa', 'Is Delete'),
        ];
    }
    
    public function beforeSave($insert) {
        if(parent::beforeSave($insert))
        {
            $this->content = htmlentities($this->content);
            
            $upload = UploadedFile::getInstance($this, 'poster');
            if($upload != null)
            {
                $string = $upload->name;
                $array = explode('.',$string);
                //获取后缀名，默认为 jpg 
                $ext = count($array) == 0 ? 'jpg' : $array[count($array)-1];
                $uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/workitem/cabinet/');
                $name = md5(rand(1,10000) + time()) .".$ext";
                $upload->saveAs($uploadpath.$name);
                /* 资源类型为图片时，保存为主资源路径，不是即为预览图 */
                if($this->type == 'image'){
                    $this->path = '/filedata/workitem/cabinet/'.$name;
                }else{
                    $this->poster = '/filedata/workitem/cabinet/'.$name;
                }
            }
            if(trim($this->path) == '' && $this->type == 'image')
                $this->path = '/filedata/workitem/cabinet/defalut.jpg';
            
            return true;
        }
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
    
    public function getWorkitem()
    {
        return $this->hasOne(Workitem::className(), ['id' => 'workitem_id']);
    }
}
