<?php

use common\models\scene\SceneBook;
use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
    
/* @var $model SceneBook */

?>

<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= Html::encode('选择时段预约') ?></h4>
        </div>
        <div class="modal-body scene">
            <p><span class="site">场地：【<?= $model->sceneSite->name ?>】</span></p>
            <span class="time">时间：<?= $model->date ?> 当天预约的时段</span>
            <div class="time-index-map">
                <div class="checkboxList">
                <?php
                    $dayExistBook = array_keys($dayExistBook);
                    foreach(SceneBook::$timeIndexMaps as $key => $value){
                        echo '<label style="margin-right: 30px;">';
                            echo "<input type=\"checkbox\" name=\"multi_period[]\" value=\"{$key}\"".($key != $model->time_index ? '' : 'checked ') . (!in_array($key, $dayExistBook) ? '' : 'disabled ').">";
                            echo " ".$value;
                        echo '</label>';
                    } 
                ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?= Html::button(Yii::t('app', 'Submit'), [
                'id'=>'submitsave','class'=>'btn btn-primary',
                'data-dismiss'=>'modal','aria-label'=>'Close',
                'onclick' => 'submitsave();'
            ]) ?>
        </div>
   </div>
</div>

<?php
$js = 
<<<JS
    
    window.submitsave = function(){
        var chk_value = []; 
        $('.time-index-map input:checked').each(function(){ 
            chk_value.push($(this).val()); 
        }); 
        $("#multi-period").val(JSON.stringify(chk_value));
        $("#scene-book-form").submit();
    }
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>