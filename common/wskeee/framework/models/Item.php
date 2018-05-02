<?php

namespace wskeee\framework\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%framework_item}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $des
 * @property integer $level
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $parent_id
 *
 * @property Project $parent
 * @property Project[] $frameworks
 * @property ProjectItemChild[] $frameworkItemChildren
 * @property ProjectItemChild[] $frameworkItemChildren0
 */
class Item extends ActiveRecord
{
    /** 项目 */
    const LEVEL_COLLEGE = 1;
    /** 子项目 */
    const LEVEL_PROJECT = 2;
    /** 子课程 */
    const LEVEL_COURSE = 3;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%framework_item}}';
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
            [['name'],'required'],
            [['level', 'created_at', 'updated_at', 'parent_id'], 'integer'],
            [['name', 'des'], 'string', 'max' => 255],
            [['name', 'parent_id'], 'unique', 'targetAttribute' => ['name', 'parent_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'des' => '描述',
            'level' => '等级',
            'created_at' => '创建于',
            'updated_at' => '更新于',
            'parent_id' => '父级id'
        ];
    }
    
    public function beforeSave($insert) {
        $this->name = trim($this->name);
        return parent::beforeSave($insert);
    }
    
    /**
     * 获取父级ID信息
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Project::className(), ['id' => 'parent_id']);
    }

    /**
     * 获取子级ID信息
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['parent_id' => 'id']);
    }
   
}
