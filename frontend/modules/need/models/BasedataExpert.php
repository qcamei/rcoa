<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\need\models;

use common\models\expert\Expert;
use common\models\User;
use wskeee\rbac\RbacName;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Description of BasedataExpert
 * @property string $username           用户名
 * @property string $nickname           专家名称
 * @property integer $sex               性别
 * @property string $phone              电话
 * @property string $email              邮箱
 * @property string $personal_image     个人形象
 * @property string $brith              出生年月
 * @property int $type                  专家类型
 * @property string $job_title          头衔
 * @property string $job_name           职称
 * @property string $level              等级
 * @property string $employer           单位信息
 * @property string $attainment         成就 
 * @author Administrator
 */
class BasedataExpert extends Model{
    
    public static $sexToValue = [
        '1' => '男',
        '2' => '女',
    ];
    
    public $id;
    public $u_id;
    public $username;
    public $nickname;
    public $sex=1;
    public $phone;
    public $email = 'abc@163.com';
    public $personal_image;
    public $type;
    public $birth = 1979;
    public $job_title;
    public $job_name;
    public $level;
    public $employer;
    public $attainment;
    public $created_at;
    public $updated_at;
     
    public function rules() {
         return[
            [['username','nickname','phone'],'required'],
            [['username'],'username_unique'],
            [['sex'], 'integer'],
            [['attainment','birth'], 'string'],
            [['job_title', 'job_name', 'level', 'employer'], 'string', 'max' => 64]
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
    
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('rcoa/basedata', 'Username'),
            'nickname' => Yii::t('rcoa/basedata', 'Nickname'),
            'personal_image' => Yii::t('rcoa/basedata', 'Personal Image'),
            'sex' => Yii::t('rcoa/basedata', 'Sex'),
            'email' => Yii::t('rcoa/basedata', 'Email'),
            'phone' => Yii::t('rcoa/basedata', 'Phone'),
            'avatar' => Yii::t('rcoa/basedata', 'Avatar'),
            'u_id' => Yii::t('rcoa/basedata', 'U ID'),
            'type' => Yii::t('rcoa/basedata', 'Type'),
            'birth' => Yii::t('rcoa/basedata', 'Birth'),
            'job_title' => Yii::t('rcoa/basedata', 'Job Title'),
            'job_name' => Yii::t('rcoa/basedata', 'Job Name'),
            'level' => Yii::t('rcoa/basedata', 'Level'),
            'employer' => Yii::t('rcoa/basedata', 'Employer'),
            'attainment' => Yii::t('rcoa/basedata', 'Attainment'),
            'created_at' => Yii::t('rcoa/basedata', 'Credated At'),
            'updated_at' => Yii::t('rcoa/basedata', 'Updated At'),
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
            $model = new BasedataExpert();
            
            if ($user == null || $expert == null) {
                return $model;
            }
            $model->id = $user->id;
            $model->u_id = $user->id;
            $model->username = $user->username;
            $model->nickname = $user->nickname;
            $model->sex = $user->sex;
            $model->email = $user->email;
            $model->phone = $user->phone;
            $model->created_at = $user->created_at;
            $model->updated_at = $user->updated_at;

            $model->personal_image = $expert->personal_image;
            $model->type = $expert->type;
            $model->birth = $expert->birth;
            $model->job_title = $expert->job_title;
            $model->job_name = $expert->job_name;
            $model->level = $expert->level;
            $model->employer = $expert->employer;
            $model->attainment = $expert->attainment;

            return $model;
        } catch (Exception $ex) {
            throw new NotFoundHttpException("没有找到对应的专家数据！".$ex->getMessage());
        }
    }
    
    public function getIsNewRecord(){
        return !isset($this->u_id);
    }
    
    /**
     * 保存专家数据
     * 1、生成 User Model
     * 2、生成 Expert Model
     * 3、把 User 加到r_teachers 组
     * @return boolean
     * @throws HttpException
     */
    public function save(){
        try {
            $isNew = $this->getIsNewRecord();
            /** 获取提交上来的图片 */
            $upload = UploadedFile::getInstance($this, 'personal_image');
            if ($upload != null) {
                $uploadpath = $this->fileExists(Yii::getAlias('@filedata') . '/expert/personalImage/');
                $upload->saveAs($uploadpath . $this->username . '.jpg');
                $this->personal_image = '/filedata/expert/personalImage/' . $this->username . '.jpg';
            }else if ($this->personal_image == null) {
                $this->personal_image = '/filedata/expert/personalImage/' . ($this->sex == 1 ? 'teacher_man' : 'teacher_women') . '.jpg';
            }

            $trans = Yii::$app->db->beginTransaction();
            /**  创建系统用户 */
            /* @var $user User */
            $user = User::findOne(['username' => $this->username]);
            if ($user == null) {
                $user = $isNew ? new User() : User::findOne($this->u_id);
            }
            $user->scenario = $isNew ? User::SCENARIO_CREATE : User::SCENARIO_UPDATE;
            $user->username = $this->username;
            $user->nickname = $this->nickname;
            $user->sex = $this->sex;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->password = '123456';
            $user->password2 = '123456';
            $user->save();
            
            /** 创建专家数据 */
            $expert = $isNew ? new Expert() : Expert::findOne($this->u_id);
            $expert->u_id = $isNew ? $user->primaryKey : $user->id;
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

            if ($isNew) {
                /** 添加专家到【老师】角色 */
                if (!Yii::$app->authManager->checkAccess($user->id, RbacName::ROLE_TEACHERS)) {
                    Yii::$app->authManager->assign(\Yii::$app->authManager->getRole(RbacName::ROLE_TEACHERS), $user->id);
                }
            }
            $trans->commit();
            return true;
        } catch (Exception $ex) {
            $trans->rollBack();
            throw new HttpException('404', $ex->getMessage());
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
}
