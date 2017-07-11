<?php

use common\models\worksystem\WorksystemOperation;
use frontend\modules\worksystem\assets\WorksystemAssets;
use frontend\modules\worksystem\utils\WorksystemOperationHtml;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemOperation */
/* @var $_wsOp WorksystemOperationHtml */

//$this->title = Yii::t('rcoa/worksystem', 'Worksystem Operations');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem worksystem-operation-index">

    <h4><?= Html::encode('操作记录') ?></h4>
    
    <table class="table table-striped table-list">

        <thead>
            <tr>
                <th style="width: 14%"><?= Yii::t('rcoa/worksystem', 'Title') ?></th>
                <th style="width: 35%"><?= Yii::t('rcoa/worksystem', 'Content') ?></th>
                <th class="hidden-xs" style="width: 35%"><?= Yii::t('rcoa/worksystem', 'Des') ?></th>
                <th class="hidden-xs" style="width: 8%"><?= Yii::t('rcoa/worksystem', 'Time') ?></th>
                <th class="hidden-xs" style="width: 8%"><?= Yii::t('rcoa/worksystem', 'Operation People') ?></th>
                <th style="width: 1%"><?= Yii::t('rcoa', 'Operating') ?></th>
            </tr>
        </thead>

        <tbody>
            
            <?php if($allModels == null): ?>
            <tr>
                <td colspan="6"><div class="empty">没有找到数据。</div></td>
            </tr>
            <?php else: ?>
                <?php foreach($allModels as $model): ?>

                <tr>
                    <td><?= $model->title ?></td>
                    <?= $_wsOp->getOperationTypeHtml($model->controller_action, $model->content); ?>
                    <td class="course-name hidden-xs"><?= $model->des ?></td>
                    <td class="hidden-xs" style="font-size: 10px; padding: 2px 8px;"><?= date('Y-m-d H:i', $model->created_at) ?></td>
                    <td class="hidden-xs"><?= $model->createBy->nickname ?></td>
                    <td style="padding: 4px 8px;"><?= Html::a('查看', ['operation/view', 'id' => $model->id], ['class' => 'btn btn-default btn-sm', 'onclick' => 'view($(this)); return false;']) ?></td>
                </tr>

                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

    </table>    
            
</div>

<?php
$js = 
<<<JS
      
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。 */
    $('.myModal').on('hidden.bs.modal', function(){
        $(".myModal").html("");
    }); 
    
    /** 查看操作 弹出模态框 */
    window.view = function(elem){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(elem).attr("href"));
    }
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>
