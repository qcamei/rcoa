<?php

namespace common\models\shoot;

use common\models\expert\Expert;
use common\models\RmsSysData;
use common\models\shoot\ShootAppraise;
use common\models\shoot\ShootAppraiseResult;
use common\models\shoot\ShootHistory;
use common\models\shoot\ShootSite;
use common\models\User;
use common\wskeee\job\JobManager;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\FWItem;
use wskeee\rbac\RbacName;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "{{%shoot_bookdetail}}".
 *
 * @property integer $id
 * @property integer $site_id   场地ID
 * @property string $fw_college 项目
 * @property string $fw_project 子项目
 * @property string $fw_course  课程
 * @property integer $lession_time  时长
 * @property string $u_teacher     老师
 * @property string $u_contacter   接洽人
 * @property string $u_booker      预约人
 * @property string $u_shoot_man   摄影师
 * @property integer $book_time     拍摄时间
 * @property integer $index         拍摄时段
 * @property integer $shoot_mode    
 * @property integer $photograph
 * @property integer $status        状态
 * @property string $create_by     创建者
 * @property integer $created_at    创建时间
 * @property integer $updated_at    修改时间
 * @property integer $ver
 * @property string $remark         备注
 * @property string $start_time     开始时间
 * @property string $business_id    学历ID
 *
 * @property ShootAppraise[] $shootAppraises  评价题目
 * @property ShootAppraiseResult[] $shootAppraiseResults    评价结束
 * @property RmsSysData $business    获取学历
 * @property User $booker      获取预约人
 * @property RmsProjectSysData $fwCollege    获取项目
 * @property User $contacter   获取接洽人
 * @property RmsProjectSysData $fwCourse     获取课程
 * @property User $createBy     
 * @property RmsProjectSysData $fwProject    获取子项目
 * @property User $shootMan        获取摄影师
 * @property ShootSite $site        获取场地
 * @property Expert $teacher        获取老师
 * @property ShootHistory $history    获取单条历史记录
 * @property ShootHistory[] $histories     获取历史记录
 */
class ShootBookdetail extends ActiveRecord
{
    /** 预约超时限制  */
    const BOOKING_TIMEOUT = 2*60;
    /** 失约超时 */
    const STATUS_BREAK_PROMISE_TIMEOUT = 72*60*60;
    
    /** 默认状态 未预约 */
    const STATUS_DEFAULT = 0;
    /** 预约进行中 */
    const STATUS_BOOKING = 1;
    /** 委派状态,任务刚发出 */
    const STATUS_ASSIGN = 5;
    /** 拍摄中状态,已经分派摄影师，等待拍摄完成后评价 */
    const STATUS_SHOOTING = 10;
    /** 完成拍摄 评价状态 */
    const STATUS_APPRAISE = 13;
    /** 已完成,评价完成，任务结束 */
    const STATUS_COMPLETED = 15;
    /** 已失约,因其它问题导致在预定时间里没能完成拍摄任务，失约 */
    const STATUS_BREAK_PROMISE = 20;
    /** 已取消,因客观原因需要改期或者取消原定的拍摄任务，需要提前2天操作 */
    const STATUS_CANCEL = 99;

    /** 拍摄模式-标清 */
    const SHOOT_MODE_SD = 1;
    /** 拍摄模式-高清 */
    const SHOOT_MODE_HD = 2;
    
    /** 时段 上午 */
    const TIME_INDEX_MORNING = 0;
    /** 时段 下午 */
    const TIME_INDEX_AFTERNOON = 1;
    /** 时段 晚上 */
    const TIME_INDEX_NIGHT = 2;
    /**默认开始时间 上午*/
    const START_TIME_MORNING = '09:15';
    /**默认开始时间 下午午*/
    const START_TIME_AFTERNOON = '13:45';
    /**默认开始时间 晚上*/
    const START_TIME_NIGHT = '19:00';
    /* 临时创建场景 */
    const SCENARIO_TEMP_CREATE = 'tempCreate';

    /** 状态列表 */
    public $statusMap = [
        self::STATUS_DEFAULT => '未预约',
        self::STATUS_BOOKING => '预约中',
        self::STATUS_ASSIGN => '待指派',
        self::STATUS_SHOOTING => '待评价',
        self::STATUS_APPRAISE => '评价中',
        self::STATUS_COMPLETED => '已完成',
        self::STATUS_BREAK_PROMISE => '已失约',
        self::STATUS_CANCEL => '已取消',
    ];
    
    /** 拍摄模式列表 */
    public static $shootModeMap =[
        self::SHOOT_MODE_SD => '标清',
        self::SHOOT_MODE_HD => '高清',
    ];
    
    /** 时间段名称 */
    public $timeIndexMap = [
        self::TIME_INDEX_MORNING => '上',
        self::TIME_INDEX_AFTERNOON => '下',
        self::TIME_INDEX_NIGHT => '晚',
    ];
    
    /** 老师名称 */
    public $teacher_name;
    /** 老师电话 */
    public $teacher_phone;
    /** 老师邮箱 */
    public $teacher_email;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shoot_bookdetail}}';
       
    }

    public function scenarios() {
        return [
            self::SCENARIO_DEFAULT => ['site_id','fw_college', 'fw_project', 'fw_course', 
                'lession_time', 'u_teacher', 'u_contacter', 
                'u_booker','u_shoot_man' ,'book_time', 'index', 'shoot_mode',
                'photograph', 'status', 'created_at', 'updated_at', 'ver','create_by','remark','start_time', 'business_id'],
            self::SCENARIO_TEMP_CREATE => ['site_id', 
                'lession_time', 'u_contacter', 
                'u_booker','book_time', 'index', 'shoot_mode',
                'photograph', 'status', 'created_at', 'updated_at', 'ver','create_by'],
        ];
    }
    
    public function behaviors() {
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
            [['site_id', 'lession_time',  'book_time', 'index', 'shoot_mode', 'photograph', 'status', 'created_at', 'updated_at', 'ver'], 'integer'],
            [['u_teacher', 'u_contacte', 'u_booker', 'u_shoot_man', 'create_by'], 'string', 'max' => 36],
            [['fw_college', 'fw_project', 'fw_course', 'business_id'], 'string', 'max' => 32],
            [['u_booker',  'create_by', 'u_teacher', 'fw_college', 'fw_project', 'fw_course', 'business_id'], 'required'],
            [['remark'], 'string', 'max' => 255],
            [['start_time'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rcoa', 'ID'),
            'site_id' => Yii::t('rcoa', 'Site ID'),
            'fw_college' => Yii::t('rcoa', 'Fw College'),
            'fw_project' => Yii::t('rcoa', 'Fw Project'),
            'fw_course' => Yii::t('rcoa', 'Fw Course'),
            'lession_time' => Yii::t('rcoa', 'Lession Time'),
            'personal_image' => Yii::t('rcoa', 'Personal Image'),
            'u_teacher' => Yii::t('rcoa', 'Tearcher'),
            'teacher_phone' => Yii::t('rcoa', 'Phone'),
            'teacher_email' => Yii::t('rcoa', 'Email'),
            'u_contacter' => Yii::t('rcoa', 'Contacter'),
            'u_booker' => Yii::t('rcoa', 'Booker'),
            'u_shoot_man' => Yii::t('rcoa', 'Shoot Man'),
            'book_time' => Yii::t('rcoa', 'Book Time'),
            'index' => Yii::t('rcoa', 'Index'),
            'shoot_mode' => Yii::t('rcoa', 'Shoot Mode'),
            'photograph' => Yii::t('rcoa', 'Photograph'),
            'status' => Yii::t('rcoa', 'Status'),
            'create_by' => Yii::t('rcoa', 'Create By'),
            'created_at' => Yii::t('rcoa', 'Created At'),
            'updated_at' => Yii::t('rcoa', 'Updated At'),
            'ver' => Yii::t('rcoa', 'Ver'),
            'remark' => Yii::t('rcoa', 'Remark'),
            'start_time' => Yii::t('rcoa', 'Start Time'),
            'business_id' => Yii::t('rcoa', 'Business'),
        ];
    }

     public function optimisticLock() {
        return 'ver';
    }
    
     public function afterFind() {
        
        if($this->getIsBooking() && (time() - $this->updated_at > self::BOOKING_TIMEOUT))
            $this->status = self::STATUS_DEFAULT;
        
         /*　超过3天未评价和未指派为【失约】状态　*/
        if(($this->getIsAssign() || $this->getIsStausShootIng()) && ((time() - $this->book_time) > self::STATUS_BREAK_PROMISE_TIMEOUT) && $this->getIsValid()){ 
            $count = ShootAppraiseResult::find()
                    ->where(['b_id'=>$this->id])
                    ->count();
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            $trans = Yii::$app->db->beginTransaction();
            try
            {
                /** 设置只有一个人评价超过3天自动为另一个人评价 */
                if($count > 0){
                    $values = [];  
                    $info = $this->getAppraiseInfo();  
                    $unAppRole = $info[RbacName::ROLE_SHOOT_MAN]['hasDo'] == false ? RbacName::ROLE_SHOOT_MAN : RbacName::ROLE_CONTACT;
                    $unUserId =  $info[RbacName::ROLE_SHOOT_MAN]['hasDo'] == false ? $this->u_contacter : $this->u_shoot_man;

                    foreach($this->appraises as $appraise)
                    {  
                        if($appraise->role_name == $unAppRole)
                            $values[] = [$this->id,$unUserId,$unAppRole,$appraise->q_id,$appraise->value];
                    }
                    $this->status = self::STATUS_COMPLETED;
                    Yii::$app->db->createCommand()->batchInsert(ShootAppraiseResult::tableName(), 
                            ['b_id','u_id','role_name','q_id','value'], $values)->execute();
                    $jobManager->updateJob(2,$this->id,['progress'=> 100, 'status'=>$this->getStatusName()]);
                }  else {
                    $this->status = self::STATUS_BREAK_PROMISE;
                    $jobManager->updateJob(2,$this->id,['progress'=> 100, 'status'=>$this->getStatusName()]); 
                }
                $this->save(false,['status']);
                $trans->commit();
                unset($this->appraiseResults);
            } catch (Exception $ex) {
                $trans->rollBack();
            }  
        }
   
        parent::afterFind();
    }
    
    /**
     * 获取单条历史记录
     * @return ActiveQuery
     */
    public function getHistory()
    {
        return $this->hasOne(ShootHistory::className(), ['b_id' => 'id'])
                ->orderBy('id DESC');
    }
    
    /**
     * 获取所有历史记录
     * @return ActiveQuery
     */
    public function getHistorys()
    {
        return $this->hasMany(ShootHistory::className(), ['b_id' => 'id']);
    }
    
    
    /**
     * 获取学历
     * @return ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(RmsSysData::className(), ['SYS_DATA_ID' => 'business_id']);
    }

    
    /**
     * 获取场地
     * @return ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(ShootSite::className(), ['id' => 'site_id']);
    }
    
    /**
     * 获取课程
     * @return FWItem
     */
    public function getFwCourse()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return $fwManager->getItemById($this->fw_course);
    }

    /**
     * 获取子项目
     * @return FWItem
     */
    public function getFwProject()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return $fwManager->getItemById($this->fw_project);
    }
    
    /**
     * 获取项目
     * @return FWItem
     */
    public function getFwCollege()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return $fwManager->getItemById($this->fw_college);
    }
    
    /**
     * 获取预约人
     * @return ActiveQuery
     */
    public function getBooker()
    {
        return $this->hasOne(User::className(), ['id' => 'u_booker']);
    }

    /**
     * 获取接洽人
     * @return ActiveQuery
     */
    public function getContacter()
    {
        return $this->hasOne(User::className(), ['id' => 'u_contacter']);
    }
    /**
     * 获取老师
     * @return ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Expert::className(), ['u_id' => 'u_teacher']);
    }
    
    /**
     * 获取摄影师
     * @return ActiveQuery
     */
    public function getShootMan()
    {
        return $this->hasOne(User::className(), ['id' => 'u_shoot_man']);
    }
    /**
     * 获取所有评价结果
     * @return ActiveQuery
     */
    public function getAppraiseResults()
    {
        return $this->hasMany(ShootAppraiseResult::className(), ['b_id'=>'id']);
    }
    
    /**
     * 获取所有评价题目
     * @return ActiveQuery
     */
    public function getAppraises()
    {
        return $this->hasMany(ShootAppraise::className(), ['b_id'=>'id']);
    }
    
    /**
     * 获取评价详细数据
     * @return array(u_contacter=>['hasDo'=>true,sum=>0,all=>1],u_shoot_man=>[...])
     * 
     */
    public function getAppraiseInfo()
    {
        $result=[
            RbacName::ROLE_CONTACT=>[
                'hasDo'=>false,
                'sum'=>0,
                'all'=>0,
            ],
            RbacName::ROLE_SHOOT_MAN=>[
                'hasDo'=>false,
                'sum'=>0,
                'all'=>0,
            ],
        ];
        
        /* @var $aResult ShootAppraiseResult */
        foreach ($this->appraiseResults as $aResult)
        {
            $result[$aResult->role_name]['sum'] += $aResult->value;
            if($result[$aResult->role_name]['hasDo'] == false)
                $result[$aResult->role_name]['hasDo'] = true;
        }
        /* @var $appraise ShootAppraise */
        foreach ($this->appraises as $appraise)
        {
            if(isset($result[$appraise->role_name]))
                $result[$appraise->role_name]['all'] += $appraise->value;
        }
        
        return $result;
    }
    
    /**
     * 获取状态显示
     * @return string
     */
    public function getStatusName()
    {
        return $this->statusMap[$this->status];
    }
    
    /**
     * 获取是否为【未预约】
     * @return bool
     */
    public function getIsNew()
    {
        return $this->status == self::STATUS_DEFAULT;
    }
    
    /**
     * 获取是滞为有效果数据
     * 新建或者临时创建为无效数据
     */
    public function getIsValid()
    {
        return $this->status != self::STATUS_DEFAULT && $this->status != self::STATUS_BOOKING;
    }
    
    /**
     * 获取是否在【预约中】状态
     * @return bool 
     */
    public function getIsBooking()
    {
        return $this->status == self::STATUS_BOOKING;
    }
    
    /**
     * 获取是否在【待指派】状态
     */
    public function getIsAssign()
    {
        return $this->status == self::STATUS_ASSIGN;
    }
    
    /**
     * 获取是否在【评价中】状态
     */
    public function getIsAppraise()
    {
        return $this->status == self::STATUS_APPRAISE;
    }
    
    /**
     * 获取是否在【待评价】状态
     */
    public function getIsStausShootIng()
    {
        return $this->status == self::STATUS_SHOOTING;
    }
    /**
     * 获取是否在【已失约】状态
     */
    public function getIsStatusBreakPromise()
    {
        return $this->status == self::STATUS_BREAK_PROMISE;
    }
    
    /**
     * 获取是否在【已完成】状态
     */
    public function getIsStatusCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }
    
    /**
     * 获取是否在【已取消】状态
     */
    public function getIsStatusCancel()
    {
        return $this->status == self::STATUS_CANCEL;
    }

    /**
     * 获取预约锁定剩余时间
     * @return int 秒
     */
    public function getBookTimeRemaining()
    {
        if($this->getIsBooking())
            return self::BOOKING_TIMEOUT - (time() - $this->updated_at);
        else
            return 0;
    }
    
    
    /**
     * 获取是否在可以执行指派操作
     */
    public function canAssign()
    {
        return $this->status <= self::STATUS_SHOOTING;
    }
    
    /**
     * 获取是否可以执行更新操作
     */
    public function canEdit()
    {
        return $this->status < self::STATUS_SHOOTING;
    }
    
    /**
     * 获取是否可以执行/查看【评价】操作
     */
    public function canAppraise()
    {
        return $this->status >= self::STATUS_SHOOTING;
    }
    
    /**
     * 获取拍摄模式
     * @return string
     */
    public function getShootModeName()
    {
        return self::$shootModeMap[$this->shoot_mode];
    }
    
    /**
     * 获取时间段名称
     * @return string
     */
    public function getTimeIndexName()
    {
        return $this->timeIndexMap[$this->index];
    }
    
}
