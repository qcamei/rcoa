<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%company}}".
 *
 * @property string $id
 * @property string $name   公司名称
 * @property string $logo   公司Logo
 * @property string $des    公司简介
 */
class Company extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['logo', 'des'], 'string', 'max' => 255],
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
            'logo' => Yii::t('app', 'Logo'),
            'des' => Yii::t('app', 'Des'),
        ];
    }
    
     /**
     * 
     * @param type $insert 
     */
    public function beforeSave($insert) 
    {
        if (parent::beforeSave($insert))
        {
            $logo_name = md5(time());
            //图片上传
            $upload = UploadedFile::getInstance($this, 'logo');
            if($upload != null) {
                $string = $upload->name;
                $array = explode('.',$string);
                //获取后缀名，默认为 jpg 
                $ext = count($array) == 0 ? 'jpg' : $array[count($array)-1];
                $uploadpath = $this->fileExists(Yii::getAlias('@frontend/web/filedata/company/'));
                $upload->saveAs($uploadpath . $logo_name . '.' . $ext);
                $this->logo = '/filedata/company/' . $logo_name . '.' . $ext . '?rand=' . rand(0, 1000);
            }
            if (trim($this->logo) == '') {
                $this->logo = $this->getOldAttribute('logo');
            }
            return true;
        }
        return false;
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
