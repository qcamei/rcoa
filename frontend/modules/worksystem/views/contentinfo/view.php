<?php

use common\models\worksystem\WorksystemContent;
use common\models\worksystem\WorksystemContentinfo;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemContentinfo */

//$this->title = $model->id;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Contentinfos'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem worksystem-contentinfo-view">

    <h4><?= Html::encode('内容信息') ?></h4>
    
    <table class="table table-striped table-list">

        <thead>
            <tr>
                <th style="width: 12.5%"><?= Yii::t('rcoa/worksystem', 'Type Name') ?></th>
                <th style="width: 12.5%"><?= Yii::t('rcoa/worksystem', 'Is New') ?></th>
                <th style="width: 15%"><?= Yii::t('rcoa/worksystem', 'Price') ?><span class="reference">（参考）</span></th>
                <th style="width: 10%"><?= Yii::t('rcoa/worksystem', 'Budget Number') ?></th>
                <th style="width: 17%"><?= Yii::t('rcoa/worksystem', 'Budget Cost') ?><span class="reference">（单价×数量）</span></th>
                <th style="width: 10%"><?= Yii::t('rcoa/worksystem', 'Reality Number') ?></th>
                <th style="width: 17%"><?= Yii::t('rcoa/worksystem', 'Reality Cost') ?><span class="reference">（单价×数量）</span></th>
                <th style="width: 6%"></th>
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
                    <td><?= $model->worksystemContent->type_name ?></td>
                    <?php if($model->is_new == WorksystemContent::MODE_NEWLYBUILD): ?>
                    <td>新建</td>
                    <td><?= $model->price ?><span class="reference">（<?= $model->worksystemContent->price_new ?>/<?= $model->worksystemContent->unit ?>）</span></td>
                    <?php else: ?>
                    <td>改造</td>
                    <td><?= $model->price ?><span class="reference">（<?= $model->worksystemContent->price_remould ?>/<?= $model->worksystemContent->unit ?>）</span></td>
                    <?php endif; ?>
                    <td><?= $model->budget_number ?><span class="reference"><?= $model->worksystemContent->unit ?></span></td>
                    <td>￥<?= number_format($model->budget_cost, 2, '.', ',') ?></td>
                    <td><?= $model->reality_number ?><span class="reference"><?= $model->worksystemContent->unit ?></span></td>
                    <td>￥<?= number_format($model->reality_cost, 2, '.', ',') ?></td>
                    <td style="padding: 4px 8px;"><?= Html::a('查看', '', ['class' => 'btn btn-default btn-sm']) ?></td>
                </tr>

                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

    </table>    
        
    <div class="cost">
          
        <span class="reference"><b>总成本（预计/实际）：￥<?= number_format($model->worksystemTask->budget_cost, 2, '.', ',') ?> / ￥<?= number_format($model->worksystemTask->reality_cost, 2, '.', ',') ?></b></span>
         
    </div>
    
</div>

<?php
    WorksystemAssets::register($this);
?>