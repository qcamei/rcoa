<?php

use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('rcoa/demand', 'Demand Checks');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="demand-check-index">
    
    <?php if(empty($checks)): ?>
       没有找到数据。
    <?php else: ?>
    <table class="table table-striped table-list">
        
        <thead>
            <tr>
                <th style="width: 80px;">标题</th>
                <th style="min-width: 150px;">内容</th>
                <th class="hidden-xs" style="width: 250px;">备注</th>
                <th style="width: 85px;">时间</th>
                <th class="hidden-xs" style="width: 80px;">操作人</th>
                <th style="width: 70px;">操作</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach ($checks as $check): ?>
            <tr>
                <td><?= $check['title'] ?></td>
                <td class="course-name"><?= $check['content'] ?></td>
                <td class="course-name hidden-xs"><?= $check['des'] ?></td>
                <td style="font-size: 10px; padding: 2px 8px;"><?= $check['time'] ?></td>
                <td class="hidden-xs"><?= $check['name'] ?></td>
                <td style="padding: 2px 8px;">
                    <?= Html::a('查看', ['view', 'id' => $check['id']], ['class' => 'btn btn-default btn-small', 'onclick' => 'return checkView($(this));']) ?>
                </td>
            </tr>
                <?php if(isset($checkReplies[$check['id']])): ?>
                <tr>
                    <td><?= $checkReplies[$check['id']]['title'] ?></td>
                    <td style="padding: 2px 8px;">
                        <?php if($checkReplies[$check['id']]['pass'] == true): ?>
                        <span class="btn btn-success btn-sm">通过</span>
                        <?php else: ?>
                        <span class="btn btn-danger btn-sm">不通过</span>
                        <?php endif; ?>
                    </td>
                    <td class="course-name hidden-xs"><?= $checkReplies[$check['id']]['des'] ?></td>
                    <td style="font-size: 10px; padding: 2px 8px;"><?= $checkReplies[$check['id']]['time'] ?></td>
                    <td class="hidden-xs"><?= $checkReplies[$check['id']]['name'] ?></td>
                    <td style="padding: 2px 8px;">
                        <?= Html::a('查看', ['check-reply/view', 'id' => $checkReplies[$check['id']]['id']], ['class' => 'btn btn-default btn-small', 'onclick' => 'return checkView($(this));']) ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
        
    </table>
    
    <?php endif; ?>
    
</div>


<?php
$js =   
<<<JS
    /** 查看审核和回复记录 */
    window.checkView = function(elem){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(elem).attr("href"));
        return false;
    }
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
?>