<?php

namespace wskeee\filemanage\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%filemanage}}".
 *
 * @property integer $id    ID
 * @property integer $type  类型
 * @property string $name   名称
 * @property integer $pid   上一级
 * @property string $keyword    关键字
 * @property string $image  图像
 * @property string $file_link   附件链接
 *
 * @property FileManage $fileManagePid  获取父级ID
 * @property FileManage[] $fileManages  获取所有子级
 * @property FilemanageDetail $filemanageDetail 获取文档内容
 * @property FilemanageOwner $filemanageOwner   获取所有者
 */
class FileManage extends ActiveRecord
{
    /** 文档类型 目录 */
    const FM_FOLDER = 1;
    /** 文档类型 文件 */
    const FM_FILE = 2;
    /** 文档类型 附件上传 */
    const FM_UPLOAD = 3;
    /** 类型名称 */
    public $typeName = [
       self::FM_FOLDER => '目录', 
       self::FM_FILE => '文件', 
       self::FM_UPLOAD => '附件', 
    ];
    /** 文档管理图像 */
    public $fileImageMap = [
        self::FM_FOLDER => '/filedata/image/folder.png',
        self::FM_FILE => '/filedata/image/text.png',
        self::FM_UPLOAD => [
            'text' => '/filedata/image/text.png',
            'txt' => '/filedata/image/text.png',
            'docx' => '/filedata/image/docx.png',
            'doc' => '/filedata/image/docx.png',
            'pptx' => '/filedata/image/pptx.png',
            'ppt' => '/filedata/image/pptx.png',
            'xlsx' => '/filedata/image/xlsx.png',
            'xls' => '/filedata/image/pdf.png',
            'pdf' => '/filedata/image/pdf.png',
            'rar' => '/filedata/image/rar.png',
            'zip' => '/filedata/image/rar.png',
        ]
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filemanage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'pid'], 'integer'],
            [['name', 'image','file_link'], 'string', 'max' => 255],
            [['keyword'], 'string', 'max' => 50],
            [['type', 'name', 'keyword', 'image'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/fileManage', 'ID'),
            'type' => Yii::t('rcoa', 'Type'),
            'name' => Yii::t('rcoa', 'Name'),
            'pid' => Yii::t('rcoa/fileManage', 'Pid'),
            'keyword' => Yii::t('rcoa/fileManage', 'Keyword'),
            'image' => Yii::t('rcoa', 'Image'),
            'file_link' => Yii::t('rcoa/fileManage', 'File Link'),
        ];
    }

    /**
     * 获取父级ID
     * @return ActiveQuery
     */
    public function getFileManagePid()
    {
        return $this->hasOne(FileManage::className(), ['id' => 'pid']);
    }

    /**
     * 获取子级ID
     * @return ActiveQuery
     */
    public function getFileManages()
    {
        return $this->hasMany(FileManage::className(), ['pid' => 'id']);
    }

    /**
     * 获取文档内容
     * @return ActiveQuery
     */
    public function getFilemanageDetail()
    {
        return $this->hasOne(FilemanageDetail::className(), ['fm_id' => 'id']);
    }

    /**
     * 获取所有者
     * @return ActiveQuery
     */
    public function getFilemanageOwner()
    {
        return $this->hasOne(FilemanageOwner::className(), ['fm_id' => 'id']);
    }
    
    /**
     * 获取类型名称
     * @return type
     */
    public function getTypeName()
    {
        return $this->typeName[$this->type];
    }
    
    /**
     * 获取是否为【目录】类型
     */
    public function getFmFolder()
    {
        return $this->type == self::FM_FOLDER;
    }
    
    /**
     * 获取是否为【文件】类型
     */
    public function getFmFile()
    {
        return $this->type == self::FM_FILE;
    }
    
    /**
     * 获取是否为【附件上传】类型
     */
    public function getFmUpload()
    {
        return $this->type == self::FM_UPLOAD;
    }
    
}
