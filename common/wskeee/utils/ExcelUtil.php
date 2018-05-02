<?php
namespace wskeee\utils;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Reader_IReader;
use PHPExcel_Worksheet;

/**
 * ms excel 表格工具
 * 
 * @property PHPExcel_Reader_IReader $PHPReader 文件读取器
 * @property PHPExcel $PHPExcel 表格对象
 * 
 * @author Administrator
 */
class ExcelUtil {
    
    public $PHPReader;
    public $PHPExcel;
    
    //--------------------------------------------------------------------------
    //
    // public method
    //
    //--------------------------------------------------------------------------
    /**
     * 加载表格
     * @param type $pFilename 文件路径
     */
    public function load($pFilename){
        $this->PHPReader = PHPExcel_IOFactory::createReaderForFile($pFilename);
        $this->PHPExcel = $this->PHPReader->load($pFilename);
    }
    
    /**
     * 以列的方式返回所有数据
     * @param boolean $fillLastForNullValue 自动为空值填充为上一个值
     */
    public function getSheetDataForColumn($fillLastForNullValue = 1){
        /* @var  $currentSheet PHPExcel_Worksheet*/
        foreach ($this->PHPExcel->getAllSheets() as $currentSheet){
            $result = [];
            foreach($currentSheet->getColumnIterator() as $column){
                // 拿到行中的cell迭代器
                $cellIterator = $column->getCellIterator(); 
                // 设置cell迭代器，遍历所有cell，哪怕cell没有值
                $cellIterator->setIterateOnlyExistingCells(false); 
                $columnVal = [];
                $lastValue = null;
                foreach ($cellIterator as $cell) {
                    $value = $cell->getValue();
                    $columnVal[] = $value ? $value : ($fillLastForNullValue ? $lastValue : null);
                    if($fillLastForNullValue && $value)
                        $lastValue = $value;
                }
                $result [] = $columnVal;
            }
            $allResult [] = [
                'name'=>$currentSheet->getTitle(),//表名
                'data'=>$result
            ];
        }
        return $allResult;
    }
    
    /**
     * 以行的方式返回所有数据
     */
    public function getSheetDataForRow(){
        /* @var  $currentSheet PHPExcel_Worksheet*/
        foreach ($this->PHPExcel->getAllSheets() as $currentSheet){
            $result = [];
            foreach ($currentSheet->getRowIterator() as $row) { 
               // 拿到行中的cell迭代器
                $cellIterator = $row->getCellIterator(); 
                // 设置cell迭代器，遍历所有cell，哪怕cell没有值
                $cellIterator->setIterateOnlyExistingCells(false); 
                $lineVal = [];
                $lastValue;
                foreach ($cellIterator as $cell) {
                    $value = $cell->getValue();
                    $lineVal[] = $value;
                }
                $result [] = $lineVal;
            }
            $allResult [] = [
                'name'=>$currentSheet->getTitle(),//表名
                'data'=>$result
            ];
        }
        return $allResult;
    }
}
