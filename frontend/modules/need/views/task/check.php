<?php

use common\models\need\NeedContent;
use common\models\need\NeedTask;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model NeedTask */
/* @var $contentModel NeedContent */

$this->title = '验收';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Task Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="need-task-audit">

    <div class="modal-dialog" role="document" style="width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 frame">
    
                    <div class="col-xs-12 title">
                        <i class="glyphicon glyphicon-tasks"></i>
                        <span><?= Yii::t('app', '内容') ?></span>
                    </div>

                    <table class="table table-list table-bordered table-frame table-view">
                        
                        <thead>
                            <tr class="rows">
                                <th style="width: 90px; padding: 8px 4px"></th>
                                <th style="width: 60px; padding: 8px 4px"><?= Yii::t('app', 'Is New') ?></th>
                                <th style="width: 70px; padding: 8px 4px"><?= Yii::t('app', 'Price') ?></th>
                                <th style="width: 100px; padding: 8px 4px"><?= Yii::t('app', 'Plan Num') ?></th>
                                <th style="width: 100px; padding: 8px 4px"><?= Yii::t('app', 'Reality Num') ?></th>
                                <th style="width: 115px; padding: 8px 4px"><?= Yii::t('app', 'Plan Cost') ?></th>
                                <th style="width: 115px; padding: 8px 4px"><?= Yii::t('app', 'Reality Cost') ?></th>
                                <th style="width: 115px; padding: 8px 4px"><?= Yii::t('app', 'D Value') ?></th>
                                <th style="width: 115px; padding: 8px 4px"><?= Yii::t('app', 'Contrast') ?></th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            
                            <?php foreach ($dataProvider->allModels as $contentModel):  ?>
                            <tr>
                                <td style="padding: 8px 4px"><?= $contentModel->workitem->name ?></td>
                                <td style="padding: 8px 4px"><?= !$contentModel->is_new ? '新建' : '改造'; ?></td>
                                <td style="padding: 8px 4px">￥<span><?= $contentModel->price ?></span></td>
                                <td style="padding: 8px 4px"><?= $contentModel->plan_num . ' ' . $contentModel->workitem->unit ?></td>
                                <td style="padding: 8px 4px"><?= $contentModel->reality_num . ' ' . $contentModel->workitem->unit ?></td>
                                <td style="padding: 8px 4px">￥
                                    <?php 
                                        $plan_total = $contentModel->price * $contentModel->plan_num;
                                        echo number_format($plan_total, 2, '.', '') 
                                    ?>
                                </td>
                                <td style="padding: 8px 4px">
                                    <?php
                                        $reality_total = $contentModel->price * $contentModel->reality_num;
                                        $isAsc = $reality_total > $plan_total;
                                        $isDesc = $reality_total < $plan_total;
                                        echo $isAsc ?  '<span class="danger">￥' . number_format($reality_total, 2, '.', '') . ' ↑</span>' :
                                            ($isDesc ? '<span class="primary">￥' . number_format($reality_total, 2, '.', '') . ' ↓</span>' : 
                                                '￥' . number_format($reality_total, 2, '.', ''));
                                    ?>
                                </td>
                                <td style="padding: 8px 4px">
                                    <?php
                                        $dValue = $reality_total - $plan_total;
                                        echo $dValue > 0 ?  '<span class="danger"> +' . $dValue . '</span>' :
                                            ($dValue < 0 ? '<span class="primary">' . $dValue . '</span>' : $dValue);
                                    ?>
                                </td>
                                <td style="padding: 8px 4px">
                                    <?php
                                        echo $contentModel->plan_num > 0 && ($contentModel->reality_num > $contentModel->plan_num || $contentModel->reality_num < $contentModel->plan_num) ?  
                                        '<i class="fa fa-info-circle warning"></i>' :  ($contentModel->plan_num == 0 && $contentModel->reality_num > $contentModel->plan_num ? 
                                          '<i class="fa fa-plus-circle primary"></i>' : '<i class="fa fa-check-circle success"></i>');
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            <tr>
                                <td style="padding: 8px 4px">总</td>
                                <td style="padding: 8px 4px">-</td>
                                <td style="padding: 8px 4px">-</td>
                                <td style="padding: 8px 4px">-</td>
                                <td style="padding: 8px 4px">-</td>
                                <td style="padding: 8px 4px">￥
                                    <?= number_format($model->plan_content_cost, 2, '.', '') ?>
                                </td>
                                <td style="padding: 8px 4px">
                                    <?php
                                        $isAsc = $model->reality_content_cost > $model->plan_content_cost;
                                        $isDesc = $model->reality_content_cost < $model->plan_content_cost;
                                        echo $isAsc ?  '<span class="danger">￥' . number_format($model->reality_content_cost, 2, '.', '') . ' ↑</span>' :
                                            ($isDesc ? '<span class="primary">￥' . number_format($model->reality_content_cost, 2, '.', '') . ' ↓</span>' : 
                                                '￥' . number_format($model->reality_content_cost, 2, '.', ''));
                                    ?>
                                </td>
                                <td style="padding: 8px 4px">
                                    <?php
                                        $dValue = $model->reality_content_cost - $model->plan_content_cost;
                                        echo $dValue > 0 ?  '<span class="danger"> +' . $dValue . '</span>' :
                                            ($dValue < 0 ? '<span class="primary">' . $dValue . '</span>' : $dValue);
                                    ?>
                                </td>
                                <td style="padding: 8px 4px">
                                    <?php
                                        echo $model->reality_content_cost > $model->plan_content_cost || $model->reality_content_cost < $model->plan_content_cost ?  
                                        '<i class="fa fa-info-circle warning"></i>' : '<i class="fa fa-check-circle success"></i>';
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                        
                    </table>
                   
                    <div class="tip">
                        注意： 
                        <span><i class="fa fa-check-circle success"></i>与预计一致</span> 
                        <span><i class="fa fa-info-circle warning"></i>与预计不一致</span>
                        <span><i class="fa fa-plus-circle primary"></i>新增</span>
                        <span><i class="danger">↑</i>成本增加</span>
                        <span><i class="primary">↓</i>成本下降</span>
                    </div>

                </div>
                
                <div class="col-lg-12 col-md-12 field-needtask-save_path save-path">
                    <label class="col-lg-1 col-md-1 form-label" for="needtask-save_path">成品路径：</label>
                    <div class="col-lg-11 col-md-11 form-label"><?= Html::a($model->save_path) ?></div>
                </div>
                
                <?php $form = ActiveForm::begin(['options'=>['id' => 'need-task-form','class'=>'form-horizontal']]); ?>

                <div class="form-group field-needtask-result">
                    <label class="col-lg-1 col-md-1 control-label form-label" for="needtask-result">验收结果：</label>
                    <div class="col-lg-11 col-md-11">
                        <?= Html::radioList('result', 0, [ 1 => '通过', 0 => '不通过'], [
                            'itemOptions'=>[
                                'labelOptions'=>[
                                    'style'=>['margin'=>'5px 30px 5px 0']
                                ]
                            ]    
                        ]) ?>
                    </div>
                </div>
                <div class="form-group field-needtask-remarks">
                    <label class="col-lg-1 col-md-1 control-label form-label" for="needtask-remarks">备注：</label>
                    <div class="col-lg-11 col-md-11">
                        <?= Html::textarea('remarks', '无', ['rows' => 8, 'class' => 'form-control']) ?>
                    </div>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <?= Html::button(Yii::t('app', 'Confirm'), [
                    'id' => 'submitsave','class' => 'btn btn-primary','data-dismiss' => 'modal','aria-label' => 'Close'
                ]) ?>
            </div>
       </div>
    </div>

</div>

<?php
$js = 
<<<JS
   
    //提交表单
    $("#submitsave").click(function(){
        $('#need-task-form').submit();
    });   
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>