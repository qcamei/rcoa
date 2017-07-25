<?php

use common\models\worksystem\WorksystemContent;
use common\models\worksystem\WorksystemContentinfo;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemContentinfo */

//$this->title = Yii::t('rcoa/worksystem', 'Worksystem Contentinfos');
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="worksystem worksystem-contentinfo-index">
    
    <h4><?= Html::encode('内容信息') ?></h4>
    
    <table class="table table-striped table-list">

        <thead>
            <tr>
                <th style="width: 11.5%;"><?= Yii::t('rcoa/worksystem', 'Type Name') ?></th>
                <th style="width: 12.5%"><?= Yii::t('rcoa/worksystem', 'Is New') ?></th>
                <th class="hidden-xs" style="width: 151px;"><?= Yii::t('rcoa/worksystem', 'Price') ?><span class="reference">（参考）</span></th>
                <th style="width: 20%;"><?= Yii::t('rcoa/worksystem', 'Budget Number') ?></th>
                <th class="hidden-xs" style="width: 167px"><?= Yii::t('rcoa/worksystem', 'Budget Cost') ?><span class="reference">（单价×数量）</span></th>
                <th style="width: 20%;"><?= Yii::t('rcoa/worksystem', 'Reality Number') ?></th>
                <th class="hidden-xs" style="width: 167px;"><?= Yii::t('rcoa/worksystem', 'Reality Cost') ?><span class="reference">（单价×数量）</span></th>
                <th style="width: 50px;"></th>
            </tr>
        </thead>

        <tbody>
            <?php if($allModels == null): ?>
            <tr>
                <td colspan="8"><div class="empty">没有找到数据。</div></td>
            </tr>
            <?php else: ?>
                <?php foreach($allModels as $model): ?>

                <tr>
                    <td class="course-name"><?= $model->worksystemContent->type_name ?></td>
                    <?php if($model->is_new == WorksystemContent::MODE_NEWLYBUILD): ?>
                    <td>新建</td>
                    <td class="hidden-xs"><?= $model->price ?><span class="reference">（<?= $model->worksystemContent->price_new ?>/<?= $model->worksystemContent->unit ?>）</span></td>
                    <?php else: ?>
                    <td>改造</td>
                    <td class="hidden-xs"><?= $model->price ?><span class="reference">（<?= $model->worksystemContent->price_remould ?>/<?= $model->worksystemContent->unit ?>）</span></td>
                    <?php endif; ?>
                    <td><?= $model->budget_number ?><span class="reference"><?= $model->worksystemContent->unit ?></span></td>
                    <td class="hidden-xs">￥<?= number_format($model->budget_cost, 2, '.', ',') ?></td>
                    <?php if($model->reality_number != 0): ?>
                    <td><?= $model->reality_number ?><span class="reference"><?= $model->worksystemContent->unit ?></span></td>
                    <?php else: ?>
                    <td><span class="not-set">(未设置)</span></td>
                    <?php endif;?>
                    <?php if($model->reality_cost != 0): ?>
                    <td class="hidden-xs"> ￥<?= number_format($model->reality_cost, 2, '.', ',') ?></td>
                    <?php else: ?>
                    <td class="hidden-xs"><span class="not-set">(未设置)</span></td>
                    <?php endif;?>
                    <td style="padding: 4px 2px;"><?= Html::a('查看', ['contentinfo/view', 'id' => $model->id], ['class' => 'btn btn-default btn-sm', 'onclick' => 'view($(this)); return false;']) ?></td>
                </tr>

                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

    </table>    
        
    <div class="cost">
        <?php if(!empty($model->worksystem_task_id)): ?>  
        <span class="reference"><b>总成本（预计/实际）：￥<?= number_format($model->worksystemTask->budget_cost, 2, '.', ',') ?> / ￥<?= number_format($model->worksystemTask->reality_cost, 2, '.', ',') ?></b></span>
        <?php else: ?>
        <span class="reference"><b>总成本（预计/实际）：￥0.00/￥0.00</b></span>
        <?php endif; ?>
    </div>
            
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