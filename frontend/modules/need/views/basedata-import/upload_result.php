<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $form ActiveForm */

$types = [
    1 => '层次/类型',
    2 => '专业/工种',
    3 => '课程',
];
$yes = '<span class="glyphicon glyphicon-ok-sign" style="color:#5cb85c"></span>';
$no = '<span class="glyphicon glyphicon-remove-sign" style="color:#d9534f"></span>';

$all_nums = 0;      //总数
$succss_num = 0;    //成功
$fail_num = 0;      //失败
$isExit_num = 0;    //已存
//计算
foreach($datas as $item){
    $all_nums ++;
    if($item['isExit']){
        $isExit_num++;
    }else{
        //成功 加'成功'数，失败加'失败'数
        $result == 1 ? $succss_num++ : $fail_num++;
    }
}

$this->title = Yii::t('app', '{Import}{Result}',[
    'Import' => Yii::t('app', 'Import'),
    'Result' => Yii::t('app', 'Result'),
]);

?>

<div id="import-log-container" class="main">
    <div style="margin: 0px 0px 10px 0px">
        <?= Html::a('返回', 'upload', ['class' => 'btn btn-default']) ?>
        <?= Html::a('重试', '', ['class' => 'btn btn-primary', 'onclick' => 'return retry()']) ?>
    </div>
    
    <div class="level1">
        <div style="background-color: #f2f2f2;padding: 10px;">导入结果：<?= ($result == 1 ? $yes : $no)."（共 $all_nums ，已存在 $isExit_num ，成功 $succss_num ，失败 $fail_num ）" ?>
        </div>
        <table border="1" class="table table-striped table-bordered">
            <tr>
                <th>名称</th>
                <th>类型</th>
                <th>结果</th>
                <th>原因</th>
            </tr>
            
            <?php foreach ($datas as $index => $item): ?>
            <tr>
                <td><?= $index;?></td>
                <td><?= $types[$item['level']];?></td>
                <td>
                    <?php 
                        if(!$item['isExit']){
                            echo $result == 1 ? $yes : $no;
                        }
                    ?>
                </td>
                <td>
                    <?php 
                        if($item['isExit']){
                            echo '已存在！';
                        }else if(!$item['hasRbac']){
                            echo '无权限！';
                        }else{
                            echo $result == 1 ? '已创建' : '失败';
                        }
                    ?>
                </td>
             </tr>
            <?php endforeach; ?>
        </table>
    </div>
    
    <div class="level2">
        
    </div>
    
    <div class="level3">
        
    </div>
</div>
<script>
    function retry(){
        window.location.reload();
        return false;
    }
</script>