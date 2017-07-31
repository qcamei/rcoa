<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\modules\expert_admin\models;

use common\models\expert\Expert;
use common\models\User;
use wskeee\rbac\RbacName;
use Yii;
use yii\base\Model;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Description of ExpertCreateForm
 * 
 * @property int $u_id 用户id
 * @property string $username 用户名
 * @property string $nickname 昵称
 * @property integer $sex       性别
 * @property string $phone      电话
 * @property string $email      邮箱
 * @property string $personal_image      个人形象
 * @property int $type      专家类型
 * @property string $birth      生日
 * @property string $job_title      头衔
 * @property string $job_name      职称
 * @property string $level      等级
 * @property string $employer      单位信息
 * @property string $attainment      成就
 * @author Administrator
 */
class ExpertCreateForm extends Model {
    
    /** 创建后的id */
    public $u_id;
    /** 登录名 */
    public $username;
    /** 名称 */
    public $nickname;
    /** 性别 */
    public $sex;
    /** 手机 */
    public $phone;
    /** 邮箱 */
    public $email;
    /** 头像 */
    public $personal_image;
    
    /** 类型 */
    public $type;
    /** 出生年月 */
    public $birth;
    /** 头衔 */
    public $job_title;
    /** 职称 */
    public $job_name;
    /** 等级 */
    public $level;
    /** 单位信息 */
    public $employer;
    /** 成就 */
    public $attainment;
    
    public $isNew = true;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','nickname','email','type'],'required'],
            [['username'],'username_unique'],
            [['username'],'string', 'max'=>32],
            [['username','nickname','email','personal_image','phone'], 'string', 'max' => 255],
            [['job_title','job_name','level','employer'], 'string', 'max' => 64],
            [['attainment','birth'], 'string'],
            [['type','sex'], 'integer'],
            [['email'], 'email'],
            [['personal_image'], 'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png']
        ];
    }
    
    /**
     * 重写唯一过虑器
     */
    public function username_unique()
    {
        if($this->getIsNewRecord())
        {
            $value = $this->username;
            $count = User::find()
                    ->where(['username'=>$value])
                    ->count();

            if($count>0)
            {
                $message = Yii::t('yii', '{attribute}"{value}" has already been taken.');
                $params = [
                    'attribute'=>$this->getAttributeLabel('username'),
                    'value'=>$value
                ];
                $this->addError('username', Yii::$app->getI18n()->format($message, $params, Yii::$app->language));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('rcoa', 'Username'),
            'nickname' => Yii::t('rcoa', 'Nickname'),
            'phone' => Yii::t('rcoa', 'Phone'),
            'email' => Yii::t('rcoa', 'Email'),
            'personal_image' => Yii::t('rcoa', 'Personal Image'),
            'sex' => Yii::t('rcoa', 'Sex'),
            'u_id' => Yii::t('rcoa', 'U ID'),
            'type' => Yii::t('rcoa', 'Type'),
            'birth' => Yii::t('rcoa', 'Birth'),
            'job_title' => Yii::t('rcoa', 'Job Title'),
            'job_name' => Yii::t('rcoa', 'Job Name'),
            'level' => Yii::t('rcoa', 'Level'),
            'employer' => Yii::t('rcoa', 'Employer'),
            'attainment' => Yii::t('rcoa', 'Attainment'),
        ];
    }
    /**
     * 生成 一个合成的专家模型
     * @param int $id
     */
    public static function find($id)
    {
        try
        {
            $user = User::findOne($id);
            $expert = Expert::findOne($id);

            $model = new ExpertCreateForm();

            $model->u_id = $user->id;
            $model->username = $user->username;
            $model->nickname = $user->nickname;
            $model->sex = $user->sex;
            $model->email = $user->email;
            $model->phone = $user->phone;

            $model->personal_image = $expert->personal_image;
            $model->type = $expert->type;
            $model->birth = $expert->birth;
            $model->job_title = $expert->job_title;
            $model->job_name = $expert->job_name;
            $model->level = $expert->level;
            $model->employer = $expert->employer;
            $model->attainment = $expert->attainment;

            $model->isNew = false;

            return $model;
        } catch (\Exception $ex) {
            throw new \yii\web\NotFoundHttpException("没有找到对应的专家数据！".$ex->getMessage());
        }
    }
    /**
     * @return bool
     */
    public function getIsNewRecord()
    {
        return $this->isNew;
    }
    
    public function save()
    {
        if($this->validate())
        {
            try
            {
                /** 获取提交上来的图片 */
                $upload = UploadedFile::getInstance($this, 'personal_image');
                if($upload != null)
                {
                    $uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/expert/personalImage/');
                    $upload->saveAs($uploadpath.$this->username.'.jpg');
                    $this->personal_image ='/filedata/expert/personalImage/'.$this->username.'.jpg';
                }

                $trans = Yii::$app->db->beginTransaction();
                /**  创建系统用户 */
                /* @var $user User */    
                $user = User::findOne(['username'=>$this->username]);
                if($user == null)
                    $user = $this->isNew ? new User() : User::findOne($this->u_id);
                $user->scenario = $user->getIsNewRecord() ? User::SCENARIO_CREATE : User::SCENARIO_UPDATE;
                $user->username = $this->username;
                $user->nickname = $this->nickname;
                $user->sex = $this->sex;
                $user->email = $this->email;
                $user->phone = $this->phone;
                $user->password = '123456';
                $user->password2 = '123456';
                $user->save();
                
                /** 创建专家数据 */    
                $expert = $this->isNew ? new Expert() : Expert::findOne($this->u_id);
                $expert->u_id = ($this->isNew ? $user->primaryKey : $user->id);
                if($upload!=null)
                    $expert->personal_image = $this->personal_image;
                $expert->type = $this->type;
                $expert->birth = $this->birth;
                $expert->job_title = $this->job_title;
                $expert->job_name = $this->job_name;
                $expert->level = $this->level;
                $expert->employer = $this->employer;
                $expert->attainment = $this->attainment;
                $expert->save();
                
                
                $this->u_id = $expert->u_id;
                
                if($this->isNew)
                {
                    /** 添加专家到【老师】角色 */    
                    \Yii::$app->authManager->assign(\Yii::$app->authManager->getRole(RbacName::ROLE_TEACHERS), $user->id);
                }
                
                $trans -> commit();
                
                $this->isNew = false;
                return true;
            } catch (Exception $ex) {
                $trans ->rollBack();
                throw new HttpException('404', json_encode($user->getErrors()).  json_encode($expert->getErrors()));
            }
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
