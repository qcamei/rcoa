<?php

namespace common\models\team;

use common\models\multimedia\MultimediaAssignTeam;
use common\models\multimedia\MultimediaTask;
use common\models\Position;
use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\web\User;



/**
 * This is the model class for table "{{%team}}".
 *
 * @property integer $id                               id
 * @property string $name                              名称
 * @property integer $type                             类型
 * @property string $team_icon                         团队图标
 * @property string $image                             团队背景图
 * @property string $des                               描述
 * @property integer $index                            索引
 * @property string $is_delete                         是否删除
 *
 * @property MultimediaAssignTeam[] $assignTeams       获取所有多媒体团队指派人
 * @property MultimediaTask[] $createTeams             获取所有多媒体任务创建团队
 * @property MultimediaTask[] $makeTeams               获取所有多媒体任务制作团队
 * @property TeamType $teamType                        获取团队类型    
 * @property TeamMember[] $teamMembers                 获取所有团队成员
 * @property User[] $us                                获取所有团队成员用户
 * @property CourseManage[] $courseManages             获取所有课程
 * @property ItemManage[] $itemManages                 获取所有项目
 */
class Team extends ActiveRecord
{
    /** 确定删除 */
    const SURE_DELETE = 'Y';
    /** 取消删除 */
    const CANCEL_DELETE = 'N';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%team}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'index'], 'integer'],
            [['name', 'team_icon',  'image', 'des'], 'string', 'max' => 255],
            [['is_delete'], 'string', 'max' => 4],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => TeamType::className(), 'targetAttribute' => ['type' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa/team', 'ID'),
            'name' => Yii::t('rcoa', 'Name'),
            'type' => Yii::t('rcoa', 'Type'),
            'team_icon' => Yii::t('rcoa/team', 'Team Icon'),
            'image' => Yii::t('rcoa', 'Image'),
            'des' => Yii::t('rcoa', 'Des'),
            'index' => Yii::t('rcoa', 'Index'),
            'is_delete' => Yii::t('rcoa/team', 'Is Delete'),
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
            $uploadIcon = UploadedFile::getInstance($this, 'team_icon');
            $uploadImage = UploadedFile::getInstance($this, 'image');
            if($uploadIcon != null || $uploadImage != null)
            {
                $uploadIconPath = $this->fileExists(Yii::getAlias('@filedata').'/team/icon/');
                $uploadImagePath = $this->fileExists(Yii::getAlias('@filedata').'/team/image/');
                $uploadIcon->saveAs($uploadIconPath.$uploadIcon->name.'.'.$uploadIcon->extension);
                $uploadImage->saveAs($uploadImagePath.$uploadImage->name.'.'.$uploadImage->extension);
                $this->team_icon = '/filedata/team/icon/'.$uploadIcon->name.'.'.$uploadIcon->extension;
                $this->image = '/filedata/team/image/'.$uploadImage->name.'.'.$uploadImage->extension;
                
                if(trim($this->team_icon) == '' || trim($this->image) == ''){
                    $this->team_icon = $this->getOldAttribute ('team_icon');
                    $this->image = $this->getOldAttribute ('image');
                }
            }
            return true;
        }else
            return false;
    }
    
    /**
     * 获取所有多媒体团队指派人
     * @return ActiveQuery
     */
    public function getAssignTeams()
    {
        return $this->hasMany(MultimediaAssignTeam::className(), ['team_id' => 'id']);
    }

    /**
     * 获取所有多媒体任务创建团队
     * @return ActiveQuery
     */
    public function getCreateTeams()
    {
        return $this->hasMany(MultimediaTask::className(), ['create_team' => 'id']);
    }

    /**
     * 获取所有多媒体任务制作团队
     * @return ActiveQuery
     */
    public function getMakeTeam()
    {
        return $this->hasMany(MultimediaTask::className(), ['make_team' => 'id']);
    }

    
    /**
     * 获取团队类型
     * @return ActiveQuery
     */
    public function getTeamType()
    {
        return $this->hasOne(TeamType::className(), ['id' => 'type']);
    }
    
    

    /**
     * 获取所有团队成员
     * @return ActiveQuery
     */
    public function getTeamMembers()
    {
        return $this->hasMany(TeamMember::className(), ['team_id' => 'id'])
                ->leftJoin(['Position' => Position::tableName()], 'Position.id = position_id')
                ->where(['!=', 'is_delete', TeamMember::SURE_DELETE])
                ->with('user')
                ->with('position')
                ->orderBy('Position.level asc');
    }

    /**
     * 获取所有团队成员用户
     * @return ActiveQuery
     */
    public function getUs()
    {
        return $this->hasMany(User::className(), ['id' => 'u_id'])->viaTable('{{%team_member}}', ['team_id' => 'id']);
    }
    
    /**
     * 获取所有课程
     * @return ActiveQuery
     */
    public function getCourseManages()
    {
        return $this->hasMany(CourseManage::className(), ['team_id' => 'id']);
    }

    /**
     * 获取所有项目
     * @return ActiveQuery
     */
    public function getItemManages()
    {
        return $this->hasMany(ItemManage::className(), ['team_id' => 'id']);
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
