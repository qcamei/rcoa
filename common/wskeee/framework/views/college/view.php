<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\College */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/framework', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="college-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'des',
            ['attribute'=>'created_at','value'=>  date('Y/m/d H:i:s',$model->created_at)],
            ['attribute'=>'updated_at','value'=>  date('Y/m/d H:i:s',$model->updated_at)],
        ],
    ]) ?>
    
    <p>
        <?= Html::a(Yii::t('rcoa/framework', 'Create Project'), 
                ['project/create','parent_id'=>$model->id], 
                ['class' => 'btn btn-success'/*, 'data' => ['method' => 'post']*/]) ?>
    </p>

    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'name',
            'des',

            [
                'class' => yii\grid\ActionColumn::className(),
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['project/view', 'id' => $key], $options);
                    },
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['project/update', 'id' => $key], $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '0',
                            'data-method' => 'post'
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['project/delete', 'id' => $key], $options);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
