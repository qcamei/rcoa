<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\workitem\WorkitemCabinet */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->workitem->name, 'url' => ["/workitem/default/view?id=$model->workitem_id"]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workitem-cabinet-view">

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/workitem', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'workitem_id',
            'index',
            'name',
            'title',
            'type',
            [
                'attribute' => 'poster',
                'format' => 'raw',
                'value'=> function($itemModel){
                    return $itemModel->poster == '' ? null : Html::tag('img','',['src'=>WEB_ROOT.$itemModel->poster,'style' => ['width'=>'320px']]);
                }
            ],
           [
                'attribute' => 'path',
                'format' => 'raw',
                'value'=> function($itemModel){
                    $path = ($itemModel->type == 'image' ? WEB_ROOT : '').$itemModel->path;
                    return Html::tag($itemModel->type ,'',[
                        'src' => $path,
                        'controls' => 'controls',
                        'style' => ['width'=>'320px']]);
                }
            ],
            'content',
            'is_delete',
        ],
    ]) ?>

</div>
