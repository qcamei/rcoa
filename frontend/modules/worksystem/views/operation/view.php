<?php

use common\models\worksystem\WorksystemOperation;
use frontend\modules\worksystem\assets\WorksystemAssets;
use frontend\modules\worksystem\utils\WorksystemOperationHtml;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemOperation */
/* @var $_wsOp WorksystemOperationHtml */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Operations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem worksystem-operation-view">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($model->title) ?></h4>
            </div>
            <div class="modal-body">
                    
                <table id="w0" class="table table-striped table-bordered detail-view">
                    <tbody>
                        
                        <tr>
                            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Title') ?></th>
                            <td class="viewdetail-td"><?= $model->title ?></td>
                        </tr>
                        <tr>
                            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Content') ?></th>
                            <?= $_wsOp->getOperationTypeHtml($model->controller_action, $model->content) ?>
                        </tr>
                        <tr>
                            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Des') ?></th>
                            <td class="viewdetail-td"><?= str_replace("\r\n", "<br/>", $model->des) ?></td>
                        </tr>
                        <tr>
                            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Time') ?></th>
                            <td class="viewdetail-td"><?= date('Y-m-d H:i', $model->created_at) ?></td>
                        </tr>
                        <tr>
                            <th class="viewdetail-th"><?= Yii::t('rcoa/worksystem', 'Operation People') ?></th>
                            <td class="viewdetail-td"><?= $model->createBy->nickname ?></td>
                        </tr>
                        
                    </tbody>
                </table>
                
            </div>
            <div class="modal-footer">
                <button id="submit-save" class="btn btn-default" data-dismiss="modal" aria-label="Close">关闭</button>
            </div>
       </div>
    </div>

</div>

<?php
    WorksystemAssets::register($this);
?>