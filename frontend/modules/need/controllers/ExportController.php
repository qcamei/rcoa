<?php

namespace frontend\modules\need\controllers;

use common\models\need\NeedTask;
use common\models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class ExportController extends Controller
{
    public function actionIndex()
    {
        throw new NotFoundHttpException('未找到页面');
    }
    
    /**
     * 成本数据导出
     * @return mix
     */
    public function actionRun()
    {
        /* @var $request Request */
        $request = Yii::$app->getRequest();
        $type = $request->getQueryParam('type');                //统计类型
        $dateRange = $request->getQueryParam('dateRange');      //时间条件

        /* @var $query Query */
        $query = (new Query())->where(['NeedTask.status' => NeedTask::STATUS_FINISHED]);  //已完成的数据
        /* 当时间段参数不为空时 */
        if($dateRange != null){
            $dateRange_Arr = explode(" - ",$dateRange);
            $query->andFilterWhere(['between', 'NeedTask.finish_time', strtotime($dateRange_Arr[0]), strtotime($dateRange_Arr[1])]);
        }
        
        $totalCost = StatisticsController::getTotalCost($query);                 //总成本
        $totalWorikitemCost = StatisticsController::getWorikitemCost($query);    //内容总成本（不含绩效）
        $business = StatisticsController::getStatisticsByBusiness($query);       //按行业统计成本
        $layer = StatisticsController::getStatisticsByLayer($query);             //按层次类型统计成本
        $profession = StatisticsController::getStatisticsByProfession($query);   //按专业工种统计成本
        $presonal = StatisticsController::getTotalCostByPresonal($query);        //按人统计成本
        $workitems = StatisticsController::getStatisticsByWorkitem($query);      //按工作项统计

        if($totalCost['total_cost'] != null){
            $datas = [
                'business' => $business,
                'layer' => $layer,
                'profession' => $profession,
            ];

            if($type == 0){
                $this->saveDatasCost($totalCost, $datas, $dateRange);
            }elseif ($type == 1) {
                $this->savePresonalCost($totalCost, $presonal, $dateRange);
            } else {
                $this->saveItemsCost($totalWorikitemCost, $workitems, $dateRange);
            }
        } else {
            throw new NotFoundHttpException('数据为空！不能导出');
        }
    }
    
    /**
     * 绩效数据导出
     * @return mix
     */
    public function actionBonusRun()
    {
        $dateRange = Yii::$app->getRequest()->getQueryParam('dateRange');      //时间条件

        /* @var $query Query */
        $query = (new Query())->where(['NeedTask.status' => NeedTask::STATUS_FINISHED]);  //已完成的数据
        /* 当时间段参数不为空时 */
        if($dateRange != null){
            $dateRange_Arr = explode(" - ",$dateRange);
            $query->andFilterWhere(['between', 'NeedTask.finish_time', strtotime($dateRange_Arr[0]), strtotime($dateRange_Arr[1])]);
        }
        
        $totalBonus = StatisticsController::getTotalBonus($query);      //总绩效
        $bonuss = StatisticsController::getBonusByPresonal($query);     //根据人统计绩效
        
        if($totalBonus['total_bonus'] != null){
            $this->saveBonus($totalBonus, $bonuss, $dateRange);
        } else {
            throw new NotFoundHttpException('数据为空！不能导出');
        }
    }

    /**
     * 个人明细数据导出
     * @return mix
     */
    public function actionPersonalRun()
    {
        /* @var $request Request */
        $request = Yii::$app->getRequest();
        $type = $request->getQueryParam('type');
        $dateRange = $request->getQueryParam('dateRange');
        /** 个人名称 */
        $username = $request->getQueryParam('username');
        
        /* @var $query Query */
        $query = (new Query())->where(['NeedTask.status' => NeedTask::STATUS_FINISHED]) //已完成的数据
                ->andFilterWhere(['NeedTask.receive_by' => $username]);             
        
        /* 当时间段参数不为空时 */
        if($dateRange != null){
            $dateRange_Arr = explode(" - ",$dateRange);
            $query->andFilterWhere(['between', 'NeedTask.finish_time', strtotime($dateRange_Arr[0]), strtotime($dateRange_Arr[1])]);
        }
        /* 当承接人参数不为空时 */
        if ($username != null){
            $username = User::findOne(['id' => $username])->nickname;
        }

        $taskCost = StatisticsController::getTaskCost($query);       //根据成本统计
        $taskBonus = StatisticsController::getTaskBonus($query);     //根据绩效统计
        
        if(!empty($taskCost)){
            if($type == 0){
                $this->savePresonalDetailsCost($taskCost, $dateRange, $username);
            } else {
                $this->savePresonalDetailsBonus($taskBonus, $dateRange, $username);
            }
        } else {
            throw new NotFoundHttpException('数据为空！不能导出');
        }
    }
    
    /**
     * 导出按分类统计成本
     * @param array $totalCost  总成本
     * @param array $datas      内容数据
     * @param string $dateRange 时间段
     */
    private function saveDatasCost($totalCost, $datas, $dateRange)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');
        /**第一页**/
        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', '时间段')->setCellValue('B1', $dateRange)->mergeCells('B1:C1');
        $spreadsheet->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(28);
        // Miscellaneous glyphs, UTF-8
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A3', '行业')->setCellValue('B3', '比值（%）')->setCellValue('C3', '成本（元）');

        $startRow = 4;
        foreach ($datas['business'] as $key => $data) {
            $columnIndex = 1;
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnIndex, $key+$startRow, $data['name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, round($data['value']/$totalCost['total_cost']*100,2).'%')
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, ('￥'.$data['value']));
        }
        $endRow = $key+$startRow+2;
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$endRow", '总')->setCellValue("C$endRow", ('￥'.$totalCost['total_cost']));

        //设置列宽
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        //设置背景颜色
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFill()->getStartColor()->setARGB('808080');
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:C$endRow")->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:C$endRow")->getFill()->getStartColor()->setARGB('d9d9d9');
        
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('行业');
        
        /**第二页**/
        // Create a new worksheet, after the default sheet
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1)
            ->setCellValue('A1', '时间段')->setCellValue('B1', $dateRange)->mergeCells('B1:C1');
        $spreadsheet->setActiveSheetIndex(1)->getRowDimension(1)->setRowHeight(28);
        // Miscellaneous glyphs, UTF-8
        $spreadsheet->setActiveSheetIndex(1)
            ->setCellValue('A3', '层次、类型')->setCellValue('B3', '比值（%）')->setCellValue('C3', '成本（元）');
        
        $startRowTwo = 4;
        foreach ($datas['layer'] as $key => $data) {
            $columnIndex = 1;
            $spreadsheet->setActiveSheetIndex(1)
                    ->setCellValueByColumnAndRow($columnIndex, $key+$startRowTwo, $data['name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRowTwo, round($data['value']/$totalCost['total_cost']*100,2).'%')
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRowTwo, ('￥'.$data['value']));
        }
        $endRowTwo = $key+$startRowTwo+2;
        $spreadsheet->setActiveSheetIndex(1)
                    ->setCellValue("A$endRowTwo", '总')->setCellValue("C$endRowTwo", ('￥'.$totalCost['total_cost']));
        //设置列宽
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        //设置背景颜色
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFill()->getStartColor()->setARGB('808080');
        $spreadsheet->getActiveSheet()->getStyle("A$endRowTwo:C$endRowTwo")->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle("A$endRowTwo:C$endRowTwo")->getFill()->getStartColor()->setARGB('d9d9d9');
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('层次、类型');
        
        /**第三页**/
        // Create a new worksheet, after the default sheet
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(2)
            ->setCellValue('A1', '时间段')->setCellValue('B1', $dateRange)->mergeCells('B1:C1');
        $spreadsheet->setActiveSheetIndex(2)->getRowDimension(1)->setRowHeight(28);
        // Miscellaneous glyphs, UTF-8
        $spreadsheet->setActiveSheetIndex(2)
            ->setCellValue('A3', '专业、工种')->setCellValue('B3', '比值（%）')->setCellValue('C3', '成本（元）');
        
        $startRowThree = 4;
        foreach ($datas['profession'] as $key => $data) {
            $columnIndex = 1;
            $spreadsheet->setActiveSheetIndex(2)
                    ->setCellValueByColumnAndRow($columnIndex, $key+$startRowThree, $data['name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRowThree, round($data['value']/$totalCost['total_cost']*100,2).'%')
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRowThree, ('￥'.$data['value']));
        }
        $endRowThree = $key+$startRowTwo+2;
        $spreadsheet->setActiveSheetIndex(2)
                    ->setCellValue("A$endRowThree", '总')->setCellValue("C$endRowThree", ('￥'.$totalCost['total_cost']));
        //设置列宽
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        //设置背景颜色
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFill()->getStartColor()->setARGB('808080');
        $spreadsheet->getActiveSheet()->getStyle("A$endRowThree:C$endRowThree")->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle("C$endRowThree:C$endRowThree")->getFill()->getStartColor()->setARGB('d9d9d9');
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('专业、工种');

        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="按分类统计成本.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    
    /**
     * 导出按人统计成本
     * @param array $totalCost  总成本
     * @param array $datas      内容数据
     * @param string $dateRange 时间段
     */
    private function savePresonalCost($totalCost, $datas, $dateRange)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', '时间段')->setCellValue('B1', $dateRange)
            ->mergeCells('B1:C1');
        $spreadsheet->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(28);
        // Miscellaneous glyphs, UTF-8
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A3', '人员名称')->setCellValue('B3', '比值（%）')->setCellValue('C3', '成本（元）');

        $startRow = 4;
        foreach ($datas as $key => $data) {
            $columnIndex = 1;
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnIndex, $key+$startRow, $data['name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, round($data['value']/$totalCost['total_cost']*100,2).'%')
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, ('￥'.$data['value']));
        }
        $endRow = $key+$startRow+2;
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$endRow", '总')->setCellValue("C$endRow", ('￥'.$totalCost['total_cost']));

        //设置列宽
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        //设置背景颜色
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A3:C3')->getFill()->getStartColor()->setARGB('808080');
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:C$endRow")->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:C$endRow")->getFill()->getStartColor()->setARGB('d9d9d9');
        
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('按人统计');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="按人统计成本.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    
    /**
     * 导出按内容统计成本
     * @param array $totalWorikitemCost  总成本(不含绩效)
     * @param array $workitems  内容数据
     * @param string $dateRange 时间段
     */
    private function saveItemsCost($totalWorikitemCost, $workitems, $dateRange)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', '时间段')->setCellValue('B1', $dateRange)
            ->mergeCells('B1:E1');
        $spreadsheet->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(28);
        // Miscellaneous glyphs, UTF-8
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A3', '内容')->setCellValue('B3', '比值（%）')->setCellValue('C3', '新建成本（元）')
                ->setCellValue('D3', '改造成本（元）')->setCellValue('E3', '合计成本（元）');
        $i = -1;
        $startRow = 4;
        foreach ($workitems as $key => $workitem) {
            $i++;
            $columnIndex = 1;
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnIndex, $i+$startRow, $key)
                    ->setCellValueByColumnAndRow(++$columnIndex, $i+$startRow, round(((empty($workitem['新建'])? '0' : $workitem['新建'])+(empty($workitem['改造'])? '0' : $workitem['改造']))/$totalWorikitemCost['value']*100,2).'%')
                    ->setCellValueByColumnAndRow(++$columnIndex, $i+$startRow, empty($workitem['新建'])? '0' : $workitem['新建'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $i+$startRow, empty($workitem['改造'])? '0' : $workitem['改造'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $i+$startRow, (empty($workitem['新建'])? '0' : $workitem['新建'])+(empty($workitem['改造'])? '0' : $workitem['改造']));
            
        }
        $listRow = $i+$startRow+1;
        $endRow = $i+$startRow+2;
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$endRow", '总')
                    ->setCellValue("C$endRow", "=SUM(C$startRow:C$listRow)")
                    ->setCellValue("D$endRow", "=SUM(D$startRow:D$listRow)")
                    ->setCellValue("E$endRow", "=SUM(E$startRow:E$listRow)");

        //设置列宽
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getStyle('A3:E3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        //设置背景颜色
        $spreadsheet->getActiveSheet()->getStyle('A3:E3')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A3:E3')->getFill()->getStartColor()->setARGB('808080');
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:E$endRow")->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:E$endRow")->getFill()->getStartColor()->setARGB('d9d9d9');
        
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('按内容统计');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="按内容统计成本.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    /**
     * 导出绩效统计
     * @param array $totalBonus 总绩效
     * @param array $bonuss     绩效
     * @param string $dateRange 时段
     */
    private function saveBonus($totalBonus, $bonuss, $dateRange)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', '时间段')->setCellValue('B1', $dateRange);
        $spreadsheet->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(28);
        // Miscellaneous glyphs, UTF-8
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A3', '内容')->setCellValue('B3', '绩效（元）');

        $startRow = 4;
        foreach ($bonuss as $key => $bonus) {
            $columnIndex = 1;
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnIndex, $key+$startRow, $bonus['name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, ('￥'.$bonus['value']));
        }
        $endRow = $key+$startRow+2;
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$endRow", '总')->setCellValue("B$endRow", ('￥'.$totalBonus['total_bonus']));

        //设置列宽
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet()->getStyle('A3:B3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        //设置背景颜色
        $spreadsheet->getActiveSheet()->getStyle('A3:B3')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A3:B3')->getFill()->getStartColor()->setARGB('808080');
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:B$endRow")->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:B$endRow")->getFill()->getStartColor()->setARGB('d9d9d9');
        
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('统计绩效');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="绩效统计.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    
    /**
     * 导出个人成本详情统计
     * @param array $taskCost   成本
     * @param string $dateRange 时段
     * @param string $username  用户名
     */
    private function savePresonalDetailsCost($taskCost, $dateRange, $username)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', '时间段')->setCellValue('B1', $dateRange)->mergeCells('B1:H1');
        $spreadsheet->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(26);
        $spreadsheet->setActiveSheetIndex(0)->getRowDimension(2)->setRowHeight(26);
        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', '目标对象')->setCellValue('B2', $username)->mergeCells('B2:H2');
        // Miscellaneous glyphs, UTF-8
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A3', '行业')->setCellValue('B3', '层次/类型')->setCellValue('C3', '专业/工种')
                ->setCellValue('D3', '课程名称')->setCellValue('E3', '需求名称')->setCellValue('F3', '完成时间')
                ->setCellValue('G3', '承接人')->setCellValue('H3', '实际成本（元）');

        $startRow = 4;
        foreach ($taskCost as $key => $data) {
            $columnIndex = 1;
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnIndex, $key+$startRow, $data['business_name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['layer_name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['Profession_name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['course_name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['task_name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, date('Y/m/d H:i',$data['finish_time']))
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['nickname'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['reality_cost']);
        }
        $listRow = $key+$startRow+1;
        $endRow = $key+$startRow+2;
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$endRow", '总')
                    ->setCellValue("H$endRow", "=SUM(H$startRow:H$listRow)");

        //设置列宽
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        //设置背景颜色
        $spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFill()->getStartColor()->setARGB('808080');
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:H$endRow")->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:H$endRow")->getFill()->getStartColor()->setARGB('d9d9d9');
        $spreadsheet->getActiveSheet()->getStyle("A1:A2")->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle("A1:A2")->getFill()->getStartColor()->setARGB('d9d9d9');
        
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('统计个人明细');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="个人明细-成本.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    
    /**
     * 导出个人绩效详情统计
     * @param array $taskBonus      绩效
     * @param string $dateRange     时间段
     * @param string $username      用户名
     */
    private function savePresonalDetailsBonus($taskBonus, $dateRange, $username)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', '时间段')->setCellValue('B1', $dateRange)->mergeCells('B1:J1');
        $spreadsheet->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(26);
        $spreadsheet->setActiveSheetIndex(0)->getRowDimension(2)->setRowHeight(26);
        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', '目标对象')->setCellValue('B2', $username)->mergeCells('B2:J2');
        // Miscellaneous glyphs, UTF-8
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A3', '层次/类型')->setCellValue('B3', '专业/工种')->setCellValue('C3', '课程名称')
                ->setCellValue('D3', '需求名称')->setCellValue('E3', '完成时间')->setCellValue('F3', '承接人')
                ->setCellValue('G3', '实际内容成本（元）')->setCellValue('H3', '实际绩效（元）')->setCellValue('I3', '绩效比值（%）')
                ->setCellValue('J3', '个人绩效（元）');

        $startRow = 4;
        foreach ($taskBonus as $key => $data) {
            $columnIndex = 1;
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($columnIndex, $key+$startRow, $data['layer_name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['Profession_name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['course_name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['task_name'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, date('Y/m/d H:i',$data['finish_time']))
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['nickname'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['reality_cost'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['reality_bonus'])
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, (($data['performance_percent']*100).'%'))
                    ->setCellValueByColumnAndRow(++$columnIndex, $key+$startRow, $data['personal_bonus']);
        }
        $listRow = $key+$startRow+1;
        $endRow = $key+$startRow+2;
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$endRow", '总')
                    ->setCellValue("G$endRow", "=SUM(G$startRow:G$listRow)")
                    ->setCellValue("H$endRow", "=SUM(H$startRow:H$listRow)")
                    ->setCellValue("J$endRow", "=SUM(J$startRow:J$listRow)");

        //设置列宽
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getStyle('A3:J3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        //设置背景颜色
        $spreadsheet->getActiveSheet()->getStyle('A3:J3')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A3:J3')->getFill()->getStartColor()->setARGB('808080');
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:J$endRow")->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle("A$endRow:J$endRow")->getFill()->getStartColor()->setARGB('d9d9d9');
        $spreadsheet->getActiveSheet()->getStyle("A1:A2")->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle("A1:A2")->getFill()->getStartColor()->setARGB('d9d9d9');
        
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('统计个人明细');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="个人明细-绩效.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
