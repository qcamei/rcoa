<?php

namespace common\models\worksystem;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%worksystem_task_type}}".
 *
 * @property integer $id                                  id
 * @property string $name                                 任务类别名称
 * @property string $icon                                 任务类别图标
 * @property string $des                                  描述
 * @property integer $index                               索引
 * @property integer $is_delete                           是否删除
 * @property integer $created_at                          创建于
 * @property integer $updated_at                          更新于
 *
 * @property WorksystemAddAttributes[] $worksystemAddAttributes     获取所有工作系统任务附加属性
 * @property WorksystemAttributes[] $worksystemAttributes           获取所有工作系统基础附加属性
 */
class WorksystemTaskType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worksystem_task_type}}';
    }
    
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
            [['name'], 'required'],
            [['des'], 'string'],
            [['index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['name', 'icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/worksystem', 'ID'),
            'name' => Yii::t('rcoa/worksystem', 'Name'),
            'icon' => Yii::t('rcoa/worksystem', 'Icon'),
            'des' => Yii::t('rcoa', 'Des'),
            'index' => Yii::t('rcoa', 'Index'),
            'is_delete' => Yii::t('rcoa/worksystem', 'Is Delete'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
        ];
    }
    
    public function beforeSave($insert) {
        
        if(parent::beforeSave($insert))
        {
            $upload = UploadedFile::getInstance($this, 'icon');
            if($upload != null)
            {
                $string = $upload->name;
                $array = explode('.',$string);
                //获取后缀名，默认为 jpg 
                $ext = count($array) == 0 ? 'jpg' : $array[count($array)-1];
                $uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/worksystem/tasktype/');
                $name = md5(rand(1,10000) + time()) .".$ext";
                $upload->saveAs($uploadpath.$name);
                $this->icon = '/filedata/worksystem/tasktype/'.$name;
            }
            
            if(trim($this->icon) == '')
                $this->icon = $this->getOldAttribute ('icon');
            
            return true;
        }
    }

    /**
     * 获取所有工作系统任务附加属性
     * @return ActiveQuery
     */
    public function getWorksystemAddAttributes()
    {
        return $this->hasMany(WorksystemAddAttributes::className(), ['worksystem_task_type_id' => 'id']);
    }

    /**
     * 获取所有工作系统基础附加属性
     * @return ActiveQuery
     */
    public function getWorksystemAttributes()
    {
        return $this->hasMany(WorksystemAttributes::className(), ['worksystem_task_type_id' => 'id']);
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
