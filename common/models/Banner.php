<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $id
 * @property string $name   宣传栏名称
 * @property string $path   宣传栏图片/视频路径
 * @property string $link   图片/视频链接
 * @property string $des    描述
 * @property integer $index 顺序
 * @property string $isdisplay  是否显示
 */
class Banner extends \yii\db\ActiveRecord
{
    /** 显示 */
    const DISPLAY = 1;
    /** 不显示 */
    const NOT_SHOW = 0;
    
    /**
     * 显示 or 不显示
     * @var array 
     */
    public static $show = [
        self::DISPLAY => '是',
        self::NOT_SHOW => '否',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['index'], 'required'],
            [['index'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['path', 'link', 'des'], 'string', 'max' => 255],
            [['isdisplay'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/banner', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'path' =>  Yii::t('rcoa/banner', 'Path'),
            'link' =>  Yii::t('rcoa', 'Link'),
            'des' =>  Yii::t('rcoa', 'Des'),
            'index' =>  Yii::t('rcoa', 'Index'),
            'isdisplay' =>  Yii::t('rcoa/banner', 'Isdisplay'),
        ];
    }
}
