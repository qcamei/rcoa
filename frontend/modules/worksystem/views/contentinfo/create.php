<?php

use common\models\worksystem\WorksystemContent;
use common\models\worksystem\WorksystemContentinfo;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model WorksystemContentinfo */

$this->title = Yii::t('rcoa/worksystem', 'Create Worksystem Contentinfo');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Contentinfos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="worksystem worksystem-contentinfo-create contentinfo">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">内容选择</h4>
            </div>
            <div class="modal-body">
                <div class="container">

                    <div class="col-xs-12">
                        <label id="label-item_type_id" for="item_type_id" class="col-xs-1 control-label">
                            <?= Yii::t('rcoa/worksystem', 'Type Name'); ?>
                        </label>
                        <div class="col-xs-3" style="padding: 7px 10px">
                            <?= Select2::widget([
                                'id' => 'Worksystemcontent-type_name',
                                'name' => 'WorksystemContent[type_name]',
                                'data' => $typeNames,
                                'options' => [
                                    'placeholder' => '请选择...',
                                ],
                            ]); ?>
                        </div>
                    </div>

                    <div class="col-xs-12 Worksystemcontent-is_new">
                        <label id="label-item_type_id" for="item_type_id" class="col-xs-1 control-label">
                            <?= Yii::t('rcoa/worksystem', 'Build Mode'); ?>
                        </label>
                        <div class="col-xs-3" style="padding: 7px 10px">
                            <?= Html::radioList('WorksystemContent[is_new]', WorksystemContent::MODE_NEWLYBUILD, WorksystemContent::$modeName, [
                                'id' => 'Worksystemcontent-is_new',
                                'itemOptions'=>[
                                    'labelOptions'=>[
                                        'style'=>[
                                            'margin-right'=>'30px',
                                            'margin-top' => '5px'
                                        ]
                                    ]
                                ],
                            ]) ?>
                        </div>
                        <div class="col-xs-10"><div class="help-block"></div></div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button id="submit-save" class="btn btn-primary" data-dismiss="modal" aria-label="Close">确认</button>
            </div>
       </div>
    </div>

</div>

<?php
$js =   
<<<JS
    create_table();
JS;
    $this->registerJs($js,  View::POS_READY);
?>