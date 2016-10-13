<?php

namespace frontend\modules\teamwork\controllers;

use common\models\Position;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\teamwork\CourseLink;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use common\models\teamwork\CourseProducer;
use common\models\teamwork\ItemManage;
use common\models\User;
use PHPExcel;
use PHPExcel_IOFactory;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\utils\DateUtil;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class ExportController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionRun(){
        $courses = $this->findCourses(Yii::$app->getRequest());
        $courseIds = ArrayHelper::getColumn($courses, 'course_id');
        $devs = $this->findDever($courseIds);
        $progress = $this->findCourseProgress($courseIds);
        $this->save($courses, $devs, $progress);
        return $this->render('index');
    }
    
    /**
     * 查找课程
     * @param Request $request 条件参数
     */
    private function findCourses($request){
        /** 行业 */
        $item_type_id = $request->getQueryParam('item_type_id');
        /** 层次/类型 */
        $item_id = $request->getQueryParam('item_id');
        /** 专业/工种 */
        $item_child_id = $request->getQueryParam('item_child_id');
        /** 状态 */
        $status = $request->getQueryParam('status');
        /** 团队 */
        $team = $request->getQueryParam('team_id');
        
        /* @var $query Query */
        $query = (new Query())
                ->select([
                    "Course.id AS course_id",
                    "ItemType.name AS itemType",                        //项目行业
                    "FwItemA.name AS item_id",                          //层次/类型
                    "FwItemB.name AS item_child_id",                    //专业/工种
                    "FwItemC.name AS course_name",                      //课程名
                    'TeacherUser.nickname AS teacher',                  //主讲老师
                    'Course.credit AS credit',                          //学分
                    'Course.lession_time AS lession_time',              //学时
                    'Course.video_length AS video_length',              //视频时长(数字，需要用php转换成时间格式)
                    'Course.question_mete AS question_mete',            //题目数
                    'Course.case_number AS case_number',                //案例数
                    'Course.activity_number AS activity_number',        //活动数
                    
                    'Team.name AS team',                                //团队
                    //"GROUP_CONCAT(DevUser.nickname,'（',Position.`name`,'）' ORDER BY TeamMember.`index`) AS course_dev",
                    'OpsUser.nickname as opser',                        //运维人    
                    'Course.plan_start_time as plan_start_time',        //计划开始时间
                    'Course.plan_end_time as plan_end_time',            //计划结束时间
                    'Course.real_start_time as real_start_time',        //实际开始时间
                    'Course.real_carry_out as real_carry_out',          //实际完成时间
                    'Course.`status` as `status`',                      //状态
                    'Course.path as path',                              //课程服务器保存路径

                    'Course.des as des'                                 //课程描述
                    ])
                ->from(['Course'=>CourseManage::tableName()])
                ->leftJoin(['Team'=>  Team::tableName()],'Course.team_id = Team.id')                    //团队
                ->leftJoin(['Item'=>ItemManage::tableName()], 'Course.project_id = Item.id')            //课程关联的项目模型
                
                ->leftJoin(['ItemType'=>  ItemType::tableName()],'Item.item_type_id = ItemType.id')     //项目行业
                ->leftJoin(['FwItemA'=> Item::tableName()],'Item.item_id = FwItemA.id')                 //层次/类型
                ->leftJoin(['FwItemB'=> Item::tableName()],'Item.item_child_id = FwItemB.id')           //专业/工种
                ->leftJoin(['FwItemC'=> Item::tableName()], 'Course.course_id = FwItemC.id')            //课程名
                
                ->leftJoin(['TeacherUser'=> User::tableName()], 'Course.teacher = TeacherUser.id')      //老师
                ->leftJoin(['TeamMember'=> TeamMember::tableName()], 'Course.course_ops = TeamMember.id')           //团队成员
                ->leftJoin(['OpsUser'=> User::tableName()], 'TeamMember.u_id = OpsUser.id')             //运维人
                
                ->andFilterWhere(['Course.status'=>$status])
                ->andFilterWhere(['Course.`team_id`'=>$team])
                ->andFilterWhere(['Item.`item_type_id`'=>$item_type_id])                            //行业          条件
                ->andFilterWhere(['Item.`item_id`'=>$item_id])                                      //层次/类型     条件
                ->andFilterWhere(['Item.`item_child_id`'=>$item_child_id]);                         //专业/工种     条件
        /* 当时间段参数不为空时 */
        if($dateRange = $request->getQueryParam('dateRange')){
            $dateRange_Arr = explode(" - ",$dateRange);
            //下面所有例子设置时间段为 2016-08-01 到 2016-08-31
            if($status == CourseManage::STATUS_WAIT_START)
            {
                /*
                 * 状态=待开始 AND created_at(创建时间)<=指定时间段最大值
                 * 如：统计 到 2016-08-31 号还没有【开始】的课程
                 * 注：【去年建】的课程到 2016-08-31 还【未开始】也会统计在内。
                 */
                $query->andFilterWhere(['<=','Course.created_at',strtotime($dateRange_Arr[1])]);
            }else if($status == CourseManage::STATUS_NORMAL)
            {
                /*
                 * 状态=在建中 AND real_start_time(实际开始时间)<=指定时间段最大值
                 * 如：统计 到 2016-08-31 号还在【建设中】的课程
                 * 注：【去年开始】的课程到 2016-08-31 还【未完成】也会统计在内。
                 */
                //状态=在建中 AND real_start_time(实际开始时间)<=指定时间最大值，如：统计【指定最大值时间】还在【建设中】的课程
                $query->andFilterWhere(['<=','Course.real_start_time',strtotime($dateRange_Arr[1])]);
            }else if($status == CourseManage::STATUS_CARRY_OUT)
            {
                /*
                 * 状态=已完成 AND 指定时间最小值<real_carry_out(实际完成时间)<指定时间段最大值
                 * 如：统计 2016-08-01 到 2016-08-31 内完成的课程
                 */
                $query->andFilterWhere(['between','Course.real_carry_out',$dateRange_Arr[0],$dateRange_Arr[1]]);
            }else{
                /**
                 * 状态为空时，每个条件都加上对应状态
                 * 条件为或者关系，只要满足其中一条规则即可统计在内
                 */
                $query->orFilterWhere(['and',"Course.status=".CourseManage::STATUS_WAIT_START,  ['<=','Course.created_at',strtotime($dateRange_Arr[1])]]);
                $query->orFilterWhere(['and',"Course.status=".CourseManage::STATUS_NORMAL,      ['<=','Course.real_start_time',strtotime($dateRange_Arr[1])]]);
                $query->orFilterWhere(['and',"Course.status=".CourseManage::STATUS_CARRY_OUT,   ['between','Course.real_carry_out',$dateRange_Arr[0],$dateRange_Arr[1]]]);
            }
        }
        
        $query->orderBy("ItemType");
        return $query->all(Yii::$app->db);
    }
    
    /**
     * 查询课程开发人员
     * @param Array $courseIds 
     * @return Array [course_id:course_dev]
     */
    private function findDever($courseIds){
        /* 查找开发人员 */
        $devs = (new Query())
                ->select([
                    'Producer.course_id AS course_id',
                    "GROUP_CONCAT(DevUser.nickname,'（',Position.`name`,'）' ORDER BY TeamMember.`index`) AS course_dev",   //合并分组里所有开发人员： 名称（岗位）,,,名称（岗位）
                    ])
                ->from(['Producer'=>CourseProducer::tableName()])                                               //课程关联开发员工
                ->leftJoin(['TeamMember'=> TeamMember::tableName()], 'Producer.producer = TeamMember.id')     //团队成员
                ->leftJoin(['Position'=> Position::tableName()], 'Position.id = TeamMember.position_id')        //岗位
                ->leftJoin(['DevUser'=> User::tableName()], 'DevUser.id = TeamMember.u_id')                     //开发人账号信息
                ->where(['in','Producer.course_id',  $courseIds])
                ->groupBy("Producer.course_id")
                ->all(Yii::$app->db);
        return ArrayHelper::map($devs, 'course_id', 'course_dev');
    }
    
    /**
     * 查询课程进度
     * @param Array $courseIds  课程id
     * @return Array [course_id:progress]
     */
    private function findCourseProgress($courseIds){
        
        /**
         * 先查出相关课程的阶段进度
         * 
         * 进度计算规则：
         * 环节 = 环节完成数/环节总数
         * 阶段 = 所有环节进度/环节数 * 阶段所占百分比
         */
        $phaseQuery = (new Query())
                ->select([
                    'CoursePhase.id AS phase_id',
                    'CoursePhase.course_id',
                    'SUM(CourseLink.completed/CourseLink.total)/COUNT(*) * CoursePhase.weights AS progress'
                    ])
                ->from(['CourseLink' => CourseLink::tableName()])
                ->leftJoin(['CoursePhase'=> CoursePhase::tableName()],'CourseLink.course_phase_id = CoursePhase.id')
                ->andWhere(['CourseLink.is_delete'=>'N'])
                ->andWhere(['in','CoursePhase.course_id',$courseIds])
                ->groupBy("CoursePhase.id");
        
        /**
         * 查询课程进度
         * 
         * 课程进度 = 所有阶段进度之和/阶段数
         */
        $courseProgress = (new Query())
                ->select([
                    'PhaseProgress.course_id',
                    'SUM(PhaseProgress.progress) AS progress'
                ])
                ->from(['PhaseProgress'=>$phaseQuery])
                ->groupBy('PhaseProgress.course_id')
                ->all(\Yii::$app->db);
        return ArrayHelper::map($courseProgress, 'course_id', 'progress');
    }
    
    /**
     * 查询项目进度
     * @param int $projectId    项目id
     */
    private function findProjectProgress($projectId){
        $courseIds = (new Query())
                ->select(['id'])
                ->from(CourseManage::tableName())
                ->andWhere(['project_id'=>$projectId])
                ->all(\Yii::$app->db);
        $courseProgress = $this->findCourseProgress($courseIds);
        return array_values($courseProgress);
    }
    
    /**
     * 保存课程数据
     * @param type $courses      课程数据    [{Course}]
     * @param type $devs        开发人员    [course_id:xxxx]
     * @param type $progress    课程进度    [course_id:xx]
     */
    private function save($courses,$devs,$progress){
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("wskeee")
                                    ->setLastModifiedBy("wskeee")
                                    ->setTitle("课程导出数据")
                                    ->setSubject("项目管理课程数据")
                                    ->setDescription("a")
                                    ->setKeywords("项目管理课程数据")
                                    ->setCategory("项目管理");
        //添加1级标题 
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', '基本信息')->setCellValue('L1', '开发信息')->setCellValue('V1', '其它信息')
                    ->mergeCells('A1:K1')->mergeCells('L1:U1')->mergeCells('V1:W1');
        
        $objPHPExcel->getActiveSheet()
                    ->getRowDimension()->setRowHeight(28);
        //添加2级标题
        $objPHPExcel->getActiveSheet()
                ->setCellValue('A2', '行业') ->setCellValue('B2', '层次/类型') ->setCellValue('C2', '业/工种') ->setCellValue('D2', '课程名称')
                ->setCellValue('E2', '主讲讲师') ->setCellValue('F2', '学分') ->setCellValue('G2', '学时') ->setCellValue('H2', '视频时长')
                ->setCellValue('I2', '题量') ->setCellValue('J2', '案例数') ->setCellValue('K2', '活动数') ->setCellValue('L2', '开发团队')
                ->setCellValue('M2', '开发人员') ->setCellValue('N2', '运维人员') ->setCellValue('O2', '计划开始时间') ->setCellValue('P2', '计划结束时间')
                ->setCellValue('Q2', '实际开始时间') ->setCellValue('R2', '实际完成时间') ->setCellValue('S2', '当前进度') ->setCellValue('T2', '状态')
                ->setCellValue('U2', '存储路径') ->setCellValue('V2', '课程描述') ->setCellValue('W2', '查看地址');
        $startRow = 3;
        foreach ($courses as $index => $course){
            $columnIndex = 0;
            $objPHPExcel->getActiveSheet()
                        ->setCellValueByColumnAndRow($columnIndex, $index+$startRow, $course['itemType'])                   //行业
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['item_id'])                  //层次/类型
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['item_child_id'])            //专业/工种
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['course_name'])              //课程名
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['teacher'])                  //主讲老师
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['credit'])                   //学分
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['lession_time'])             //学时
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, DateUtil::intToTime($course['video_length']))             //视频时长
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['question_mete'])            //题目数
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['case_number'])              //案例数
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['activity_number'])          //活动数
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['team'])                     //团队
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $devs[$course['course_id']])         //开发人员    
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['opser'])                    //运维人    
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['plan_start_time'])          //计划开始时间
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['plan_end_time'])            //计划结束时间
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['real_start_time'])          //实际开始时间
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['real_carry_out'])           //实际完成时间
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, floor($progress[$course['course_id']]*100).'%')          //进度
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, CourseManage::$statusName[$course['status']])            //状态
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['path'])                     //课程服务器保存路径
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, $course['des'])                     //课程描述
                        ->setCellValueByColumnAndRow(++$columnIndex, $index+$startRow, 'http://ccoa.gzedu.net/teamwork/course/view?id='.$course['course_id']);                     //课程连接地址
        }
         
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="导出成绩.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
