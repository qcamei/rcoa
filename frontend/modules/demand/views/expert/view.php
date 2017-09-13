<?php

use frontend\modules\demand\models\BasedataExpert;
use wskeee\rbac\components\ResourceHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model BasedataExpert */

$this->title = Yii::t('rcoa/basedata', 'Details').'：'.$model->nickname;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container expert-view">

    <p>
        <?= ResourceHelper::a(Yii::t('rcoa/basedata', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= ResourceHelper::a(Yii::t('rcoa/basedata', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/basedata', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head">基本信息</span>','value'=>''],
            [
                'attribute'=>'personal_image',
                'format'=>'raw',
                'value'=> Html::a(Html::img($model->personal_image,['width'=>'128px']), $model->personal_image),
            ],
            'u_id',
            'username',
            'nickname',
            [
                'attribute'=>'sex',
                'value'=>BasedataExpert::$sexToValue[$model->sex],
            ],
            'birth',
            ['label' => '<span class="btn-block viewdetail-th-head">工作信息</span>','value'=>''],        
            'phone',
            'email',
            'job_title',
            'job_name',
            'employer',
            'attainment:ntext',
            ['label' => '<span class="btn-block viewdetail-th-head">其它信息</span>','value'=>''], 
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
