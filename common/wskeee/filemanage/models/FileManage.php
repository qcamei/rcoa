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
 * @property string $icon   图标
 *
 * @property FileManage $fileManagePid  获取父级ID
 * @property FileManage[] $fileManages  获取所有子级
 * @property FilemanageDetail $filemanageDetail 获取文档内容
 * @property FilemanageOwner $filemanageOwner   获取所有者
 */
class FileManage extends ActiveRecord
{
    /** 文档类型 目录 */
    const FM_LIST = 1;
    /** 文档类型 文件 */
    const FM_FILE = 2;
    /** 文件夹图标 */
    const ICON_FOLDER = 'glyphicon glyphicon-folder-open';
    /** 文档图标 */
    const ICON_FILE = 'glyphicon glyphicon-file';
    /** 文件夹图像 */
    const IMAGE_FOLDER = '/filedata/image/folder.png';
    /** 文档图像 */
    const IMAGE_FILE = '/filedata/image/file.png';
    
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
            [['name', 'image'], 'string', 'max' => 255],
            [['keyword', 'icon'], 'string', 'max' => 50],
            [['type', 'name', 'keyword', 'icon', 'image'], 'required'],
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
            'icon' => Yii::t('rcoa', 'Icon'),
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
}
