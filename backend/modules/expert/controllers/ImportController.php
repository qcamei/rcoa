<?php

namespace backend\modules\expert\controllers;

use common\models\expert\Expert;
use common\models\User;
use wskeee\rbac\RbacName;
use wskeee\utils\ExcelUtil;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

class ImportController extends Controller
{
    
    //禁用csrf验证
    public $enableCsrfValidation = false;
    
    /**
     * 原数据
     * @var array 
     */
    private $columns = [];
    /* 用户与id映射 name => id */
    private $userMap = [];
    /* 用户团队成员 */
    private $teamMembers = [];
    /* 日志 */
    private $logs = [];
    public function behaviors()
    {
        return [
            //验证delete时为post传值
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                ],
            ],
        ];
    }
    /**
     * 上传文件自动导入
     */
    public function actionUpload(){
        $upload = UploadedFile::getInstanceByName('import-file');
        if($upload != null)
        {
            $string = $upload->name;
            $excelutil = new ExcelUtil();
            $excelutil->load($upload->tempName);
            
            $columns = $excelutil->getSheetDataForColumn()[0]['data'];
            for($i=0,$len=count($columns[0])-2;$i<$len;$i++){
                $vIndex = $i+2;
                $this->columns [] = [
                    'username'          =>  $columns[0][$vIndex],           //用户名
                    'nickname'          =>  $columns[1][$vIndex],           //老师昵称
                    'sex'               =>  $columns[2][$vIndex],           //性别
                    'ee'                =>  $columns[3][$vIndex],           //ee
                    'phone'             =>  $columns[4][$vIndex],           //手机号码
                    'email'             =>  $columns[5][$vIndex],           //邮箱
                    'birth'             =>  $columns[6][$vIndex],           //出生年份    
                    'job_title'         =>  $columns[7][$vIndex],           //头衔
                    'job_name'          =>  $columns[8][$vIndex],           //职称
                    'level'             =>  $columns[9][$vIndex],           //等级
                    'employer'          =>  $columns[10][$vIndex],          //单位信息
                    'attainment'        =>  $columns[11][$vIndex],          //主要成就
                ];
            }
            $code = 0;
            $msg = '';
            try{
                //检查用户数据
                $this->checkUser();
                //创建用户信息
                $this->createUserInfo();
                //插入专家数据
                $this->createExpertItem();
                //插入角色分配数据
                $this->createAssignmenttem();
            } catch (\Exception $ex) {
                $code = 1;
                $msg = $ex->getMessage();
            }
            return $this->render('upload_result',['code'=>$code,'msg'=>$msg,'logs'=>$this->logs,'columns'=>$this->columns]);    
        }
        return $this->render('upload');
    }
    
     /**
     * 检查用户数据是否健全
     */
    private function checkUser(){
        //用户名
        $usernames = ArrayHelper::getColumn($this->columns, 'username');
        $unFindUsers = [];
        
        //=======================
        // 数据查询
        //=======================
        //查寻所有用户id
        $result = (new Query())
                    ->select(['username', 'nickname'])
                    ->from(User::tableName())
                    ->where(['username' => array_unique($usernames)])
                    ->all();
        
        //所有用户映射 username => id
        $this->userMap = $userMap = ArrayHelper::map($result, 'username', 'nickname');
        
        //=======================
        // 查找所有未知用户
        //=======================
        foreach ($this->columns as $columns){
            //教师
            $teacherName = $columns['username'];
            if(!isset($userMap[$teacherName])){
                $unFindUsers[] = $columns['username'];
            }
        }
        //已经存在用户
        $existenceUser = array_flip($this->userMap);
        //已经存在用户数
        $existenceUserNum = count($this->userMap);
        //未知用户
        $unFindUser = array_unique($unFindUsers);
        //未知用户数
        $unFindNum = count($unFindUser);
        //重复用户
        $repeatUser = array_diff_assoc($unFindUsers, $unFindUser);
        //重复用户数
        $repeatUserNum = count($repeatUser);
        $checkResult = sprintf('检查完成，共有 %d 个用户，其中 %d 个为未知用户名：%s，%d 个为已存在用户名：%s， %d 个为重复用户名：%s', count($usernames), $unFindNum,  $unFindNum > 0 ? implode(', ', $unFindUser) : '无', $existenceUserNum, $existenceUserNum > 0 ? implode(', ', $existenceUser) : '无' , $repeatUserNum, $repeatUserNum > 0 ? implode(', ', $repeatUser) : '无');        
        $this->addLog('用户检查', $checkResult, $unFindNum==0);    
        
        if($unFindNum < 0){
            throw new Exception('未发现未知用户！');
            return;
        }
        
        if($repeatUserNum > 0){
            throw new Exception('发现重复用户！');
            return;
        }
    }
    
    
    /**
     * 添加用户信息数据
     * @return array
     */
    private function createUserInfo(){
        $tableName = User::tableName();
        //用户
        $usernames = ArrayHelper::getColumn($this->columns, 'username');
        $unFinds = [];
        
        //已存在数据
        $doneItems = (new Query())
                    ->select(['username', 'nickname'])
                    ->from($tableName)
                    ->where(['username' => array_unique($usernames)])
                    ->all(Yii::$app->db);
        
        $doneItems = ArrayHelper::map($doneItems, 'username', 'nickname');
        
        /* 组装数据 */
        $items = [];
        foreach ($this->columns as $column){
            $username = $column['username'];
            if(!isset($doneItems[$username])){
                $items[$column['username']] = [
                    'id'            =>  MD5(rand(1,10000) + time()),                    //id
                    'username'      =>  $column['username'],                            //用户名
                    'password'      =>  strtoupper(MD5(123456)),                        //密码
                    'nickname'      =>  $column['nickname'],                            //昵称
                    'avatar'        =>  '/filedata/avatars/default/'.($column['sex'] == '男' ? 'man' : 'women').rand(1, 25).'.jpg',     //头像
                    'sex'           =>  $column['sex'] == '男' ? 1 : 2,                 //性别
                    'ee'            =>  $column['ee'],                                  //ee
                    'phone'         =>  $column['phone'],                               //手机
                    'email'         =>  $column['email'],                               //邮箱
                    'created_at'    => time(),                                          //创建时间
                    'updated_at'    => time(),                                          //更新时间
                ];
            }
        }
       
        //插入新数据(过滤已存在数据)，并且返回插入的数据条数
        $data = $this->batchInsert($tableName, ['id', 'username', 'password', 'nickname', 'avatar', 'sex', 'ee', 'phone', 'email', 'created_at', 'updated_at'], $items);
        $this->addLog('用户数据',sprintf('数据创建完成！本次需要插入 %d 条，新增 %d 条！',count($usernames), $data['num']), $data['result']);
       
        if(!$data['result'])
        {
            throw new \Exception($data['msg']);
            return;
        }
        
        /* 查询新添加用户的id */
        $results = (new Query())
                    ->from($tableName)
                    ->where(['username' => ArrayHelper::getColumn($items, 'username')])
                    ->all();
        
        $results = ArrayHelper::map($results, 'username', 'id');
        
        /* 更新 */
        foreach ($this->columns AS &$column){
            $username = $column['username'];
            if(isset($results[$username]))
                $column['user_id'] = $results[$username];
            
        }
        /*$unFinds = array_unique($unFinds);
        if(count($unFinds)>0)
            $this->addLog('用户数据', sprintf('数据更新错误！有 %d 个用户名已存在：%s ', count($unFinds), '<span class="red">'.implode(',', $unFinds)).'</span>', 0);
        return $results;
        /*if(count($unFinds)>0)
            throw new Exception('数据更新失败！');*/
    }
    
     /**
      * 创建专家 数据 
      * @return type
      * @throws \Exception
      * @throws Exception
      */
    private function createExpertItem(){
        
        $tableName = Expert::tableName();
        $userIds = ArrayHelper::getColumn($this->columns, 'user_id');
        $unFinds = [];
        
        //已存在数据
        $doneItems = (new Query())
                    ->select(['u_id'])
                    ->from($tableName)
                    ->where(['u_id' => $userIds])
                    ->all(Yii::$app->db);
        
        $doneItems = ArrayHelper::map($doneItems, 'u_id', ''); 
        
        /* 组装数据 */
        $items = [];
       
        foreach ($this->columns as $column){
            $userId = isset($column['user_id']) ? $column['user_id'] : null;
            if(!isset($doneItems[$userId]) && !empty($userId)){
               $items[$column['user_id']] = [
                    'u_id'              =>  $column['user_id'],                 //引用用户id
                    'type'              =>  1,                                  //专家类型
                    'birth'             =>  $column['birth'],                   //出生年份
                    'personal_image'    =>  '/filedata/expert/personalImage/'.($column['sex'] == '男' ? 'teacher_man' : 'teacher_women').'.jpg',    //个人形象
                    'job_title'         =>  $column['job_title'],               //头衔
                    'job_name'          =>  $column['job_name'],                //职称
                    'level'             =>  $column['level'],                   //级别
                    'employer'          =>  $column['employer'],                //单位信息
                    'attainment'        =>  $column['attainment']               //主要成就
               ];
            }
        }
       
        //插入新数据(过滤已存在数据)，并且返回插入的数据条数
        $data = $this->batchInsert($tableName, ['u_id', 'type', 'birth', 'personal_image', 'job_title', 'job_name', 'level', 'employer', 'attainment'], $items);
        $this->addLog('专家库',sprintf('数据创建完成！本次需要插入 %d 条，新增 %d 条！',count($userIds), $data['num']), $data['result']);
       
        if(!$data['result'])
        {
            throw new \Exception($data['msg']);
            return;
        }        
    }
    
    /**
      * 创建角色分配 数据 
      * @param type $results            新插入用户表数据的结果
      * @return type
      * @throws \Exception
      * @throws Exception
      */
    private function createAssignmenttem()
    {
        $tableName = 'ccoa_auth_assignment';
        $userIds = ArrayHelper::getColumn($this->columns, 'user_id');
        $unFinds = [];
        
        //组装上传插入表数据
        /*$infos = [];
        foreach ($results as $value) {
            $infos[$value] =[
                'item_name' => RbacName::ROLE_TEACHERS,
                'user_id' => $value,
                'created_at' => time(),
            ];
        }*/
        
        //已存在数据
        $doneItems = (new Query())
                    ->select(['user_id'])
                    ->from($tableName)
                    ->where(['user_id' => $userIds])
                    ->all(Yii::$app->db);
        
        $doneItems = ArrayHelper::map($doneItems, 'user_id', ''); 
        
        /* 组装数据 */
        $items = [];
        foreach ($this->columns as $column){
            $userId = isset($column['user_id']) ? $column['user_id'] : null;
            if(!isset($doneItems[$userId]) && !empty($userId)){
               $items[$column['user_id']] = [
                    'item_name'     =>  RbacName::ROLE_TEACHERS,        //老师角色  
                    'user_id'       =>  $column['user_id'],             //引用用户id
                    'created_at'    =>  time(),                         //创建时间
               ];
            }
        }
        
        //插入新数据(过滤已存在数据)，并且返回插入的数据条数
        $data = $this->batchInsert($tableName, ['item_name', 'user_id', 'created_at'], $items);
        $this->addLog('角色分配',sprintf('数据创建完成！本次需要插入 %d 条，新增 %d 条！',count($userIds), $data['num']), $data['result']);
       
        if(!$data['result'])
        {
            throw new \Exception($data['msg']);
            return;
        }        
    }
    
    /**
     * 插入数据
     * @param type $table       表名
     * @param type $columns     所要插入的列名
     * @param type $rows        数据
     * @return array            成功插入的条数 [num,result]
     */
    private function batchInsert($table, $columns, $rows){
         /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        $number = 0;
        $result = 1;
        $msg = '';
        try
        {  
            $number = Yii::$app->db->createCommand()->batchInsert($table, $columns, $rows)->execute();
            $trans->commit();  //提交事务
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $number = -1;
            $result = 0;
            $msg = $ex->getMessage();
        }
        return ['num'=>$number,'result'=>$result,'msg'=>$msg];
    }
    
    /**
     * 添加日志记录
     * @param string $title         日志标题
     * @param string $data          数据
     * @param array $params         参数
     * @param int $result           结果 1成功，0失败
     */
    private function addLog($title,$data,$result=1){
        $this->logs[] = ['result'=>$result,'title'=>$title,'data'=>$data];
    }
}
