<?php

use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskType;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\helpers\Html;
use yii\web\View;
    
/* @var $model WorksystemTask */
/* @var $element WorksystemTaskType */

?>

<div class="modal fade myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">任务类型选择</h4>
            </div>
            
            <div class="modal-body" id="myModalBody">
                <div class="container">
                    
                <?php foreach ($taskTypes as $element): ?>
                
                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-6">
                        <a class="selected clickselected" data-dismiss="modal" aria-label="Close" data-value="<?= $element->id ?>">
                            <div class="worksystem-task-type">
                                <?= Html::img([$element->icon], ['width' => '80', 'height' => '60']); ?>
                                <p class="task-type-name"><span><?= $element->name ?></span></p>
                            </div>
                        </a>
                    </div>
                    
                <?php endforeach;?>
                    
                </div>
            </div>
            
       </div>
    </div> 
</div>

<?php
$js = 
<<<JS
    
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>