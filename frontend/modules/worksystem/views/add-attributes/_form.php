<?php

use common\models\worksystem\WorksystemAttributes;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
?>

<div class="worksystem-add-attributes-form">

    <?php foreach($datas as $element): ?>
    
    <div class="form-group worksystem-attribute-<?= $element['id'] ?> required">
        
        <?= Html::label($element['name'], 'worksystem-attribute-'.$element['id'], [
            'class' => 'col-lg-1 col-md-1 control-label',
            'style' => 'color: #999999; font-weight: normal; padding-right: 0;',
        ]) ?>
        
        <div class="col-lg-10 col-md-10">
            <!-- 输入类型：手工录入 -->
            <?php if($element['input_type'] == WorksystemAttributes::HANDWORKINPUT): ?>
                
                <?php  if($element['type'] == WorksystemAttributes::UNIQUETYPE): ?>
                    <!-- 类型：唯一 -->
                    <?= Html::textInput('WorksystemAddAttributes[value]['.$element['id'].']', $element['value'], [
                        'id' => 'worksystem-attribute-'.$element['id'],
                        'class' => 'form-control',
                        'maxlength' => 255,
                        'placeholder' => '请输入值...',
                    ]) ?>
            
                <?php endif; ?>
            <!-- 输入类型：列表选择 -->
            <?php elseif($element['input_type'] == WorksystemAttributes::LISTSELECTINPUT): ?>
                
                <?php  if($element['type'] == WorksystemAttributes::SINGLESELECTIONTYPE): ?>
                    <!-- 类型：单选 -->
                    <?= Html::radioList('WorksystemAddAttributes[value]['.$element['id'].']', $element['value'], $element['value_list'], [
                        'id' => 'worksystem-attribute-'.$element['id'],
                        'itemOptions'=>[
                            'labelOptions'=>[
                                'style'=>[
                                    'margin-right'=>'30px',
                                    'margin-top' => '5px'
                                ]
                            ]
                        ], 
                    ]) ?>
                  
                <?php elseif($element['type'] == WorksystemAttributes::CHECKSTYPE): ?>
                   <!-- 类型：复选 -->
                   <?= Html::checkboxList('WorksystemAddAttributes[value]['.$element['id'].']', $element['value'], $element['value_list'], [
                       'id' => 'worksystem-attribute-'.$element['id'],
                       'itemOptions'=>[
                           'labelOptions'=>[
                               'style'=>[
                                   'margin-right'=>'30px',
                                   'margin-top' => '5px'
                               ]
                           ]
                       ], 
                   ]) ?>
            
                 <?php endif; ?>
            <!-- 输入类型：多行文本 -->
            <?php elseif($element['input_type'] == WorksystemAttributes::MULTILINETEXTINPUT): ?>
                
                <?= Html::textarea('WorksystemAddAttributes[value]['.$element['id'].']', $element['value'], ['rows' => 6,]) ?>
            
            <?php endif; ?>
        
            <?= Html::hiddenInput('WorksystemAddAttributes[index]['.$element['id'].']', $element['index']) ?>
            <?= Html::hiddenInput('WorksystemAddAttributes[is_delete]['.$element['id'].']', $element['is_delete']) ?>
            
        </div>
        
        <div class="col-lg-10 col-md-10"><div class="help-block"></div></div>
        
    </div>
    
    <?php endforeach; ?>
    
</div>

<?php

$js = <<<JS
       
       
JS;
    //$this->registerJs($js, View::POS_READY);
?>