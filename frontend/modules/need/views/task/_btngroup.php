<?php

use common\models\need\NeedTask;
use wskeee\rbac\components\ResourceHelper;
use yii\web\View;
    
/* @var $model NeedTask */

?>
<?php
    /**
     * $btnHtml = [
     *     [
     *         controller => 控制器，
     *         action => 行为，
     *         name  => 按钮名称，
     *         url  =>  按钮url，
     *         options  => 按钮属性，
     *         symbol => html字符符号：&nbsp;，
     *         conditions  => 按钮显示条件，
     *         adminOptions  => 按钮管理选项，
     *     ],
     * ]
     */
    $btnHtml = '';
    $controllerId = Yii::$app->controller->id;
    $actionId = Yii::$app->controller->action->id;
    $btnGroups = [
        //返回按钮
        [
            'controller' => 'task',
            'action' => ['create', 'update'],
            'name' => Yii::t('rcoa', 'Back'),
            'url' => isset($params) ? $params : ['back'],
            'options' => ['class' => 'btn btn-default'],
            'symbol' => '&nbsp;',
            'conditions' => true,
            'adminOptions' => true,
        ],
        //创建按钮
        [
            'controller' => 'task',
            'action' => 'create',
            'name' => Yii::t('rcoa', 'Create'),
            'url' => ['create'],
            'options' => ['class' => 'btn btn-success', 'onclick' => 'submitForm(); return false'],
            'symbol' => '&nbsp;',
            'conditions' => $model->getIsDefault(),
            'adminOptions' => true,
        ],
        //更新按钮
        [
            'controller' => 'task',
            'action' => 'update',
            'name' => Yii::t('rcoa', 'Update'),
            'url' => ['update'],
            'options' => ['class' => 'btn btn-primary', 'onclick' => 'submitForm(); return false'],
            'symbol' => '&nbsp;',
            'conditions' => $model->getIsCreateing() || $model->getIsChangeAudit() || $model->getIsWaitReceive(),
            'adminOptions' => true,
        ],
        /** 
         * 发布人 角色按钮组
         */
        //编辑按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => Yii::t('rcoa', 'Edit'),
            'url' => ['update', 'id' => $model->id],
            'options' => ['class' => 'btn btn-primary'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && ($model->getIsCreateing() || $model->getIsChangeAudit()) && $model->created_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        //提交审核按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '提交审核',
            'url' => ['submit', 'id' => $model->id],
            'options' => ['class' => 'btn btn-info'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && !empty($model->audit_by) && ($model->getIsCreateing() || $model->getIsChangeAudit()) && $model->created_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        //取消审核按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '取消审核',
            'url' => ['cancel', 'id' => $model->id],
            'options' => ['class' => 'btn btn-danger'],
            'symbol' => '&nbsp;',
            'conditions' =>  !$model->is_del && !empty($model->audit_by) && $model->getIsAuditing() && $model->created_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        //发布按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '发布',
            'url' => ['publish', 'id' => $model->id],
            'options' => ['class' => 'btn btn-info'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && $model->audit_by == null && ($model->getIsCreateing() || $model->getIsChangeAudit()) && $model->created_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        //取消发布按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '取消发布',
            'url' => ['cancel-publish', 'id' => $model->id],
            'options' => ['class' => 'btn btn-danger'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && $model->audit_by == null && $model->getIsWaitReceive() && $model->created_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        //验收按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '验收',
            'url' => ['check', 'id' => $model->id],
            'options' => ['class' => 'btn btn-success', 'onclick' => 'showModal($(this)); return false'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && $model->getIsChecking() && $model->created_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        /**
         * 审核人 角色按钮组
         */
        //审核按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '审核',
            'url' => ['audit', 'id' => $model->id],
            'options' => ['class' => 'btn btn-info', 'onclick' => 'showModal($(this)); return false'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && $model->getIsAuditing() && $model->audit_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        /**
         * 承接人 角色按钮组
         */
        //承接按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '承接',
            'url' => ['receive', 'id' => $model->id],
            'options' => ['class' => 'btn btn-primary'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && $model->getIsWaitReceive(), //&& (isset($isHasReceive) ? $isHasReceive : false),
            'adminOptions' => true,
        ],
        //开始制作按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '开始制作',
            'url' => ['start', 'id' => $model->id],
            'options' => ['class' => 'btn btn-success'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && $model->getIsWaitStart() && $model->receive_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        //转让按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '转让',
            'url' => ['transfer', 'id' => $model->id],
            'options' => ['class' => 'btn btn-info', 'onclick' => 'showModal($(this)); return false'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && ($model->getIsWaitStart() || $model->getIsDeveloping() || $model->getIsChangeCheck()) && $model->receive_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        //进度按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '进度',
            'url' => ['content/create', 'need_task_id' => $model->id, 'isNewRecord' => false],
            'options' => ['class' => 'btn btn-primary', 'onclick' => 'showModal($(this)); return false'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && ($model->getIsDeveloping() || $model->getIsChangeCheck()) && $model->receive_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        //取消验收按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '取消验收',
            'url' => ['content/cancel', 'id' => $model->id],
            'options' => ['class' => 'btn btn-danger'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && $model->getIsChecking() && $model->receive_by == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        /**
         * 发布人和承接人 角色按钮组
         */
        //取消按钮
        [
            'controller' => 'task',
            'action' => 'view',
            'name' => '取消',
            'url' => ['delete', 'id' => $model->id],
            'options' => ['class' => 'btn btn-danger', 'onclick' => 'showModal($(this)); return false'],
            'symbol' => '&nbsp;',
            'conditions' => !$model->is_del && !$model->getIsFinished() ? ($model->status < NeedTask::STATUS_WAITSTART && $model->created_by == Yii::$app->user->id ? true : (
                    $model->status > NeedTask::STATUS_WAITRECEIVE && $model->receive_by == Yii::$app->user->id ? true : false)) : false,
            'adminOptions' => true,
        ],
    ];
    
    foreach ($btnGroups as $item) {
        $conditions = is_array($item['action']) ? in_array($actionId, $item['action']) : $item['action'] == $actionId;
        if($item['controller'] == $controllerId && $conditions){
            $btnHtml .= ResourceHelper::a($item['name'], $item['url'], $item['options'], $item['conditions']).($item['conditions'] ? $item['symbol'] : null);
        }
    }
?>

<?php if(str_replace('&nbsp;', '', $btnHtml) != null): ?>

<div class="controlbar">
    <div class="container">
        <?= $btnHtml ?>
    </div>
</div>

<?php endif; ?>


<?php
$js = 
<<<JS
    if("$actionId" != "view"){
        window.onloadUploader();    //加载文件上传
    }
    //提交表单
    window.submitForm = function(){
        var tablelen = $('#need-content .table > tbody > tr').length;
        var attlen = $('#need-attachments .table > tbody > tr').length;
        var hasClass = $('#need-content .table > tbody > tr div').hasClass('empty');
        //判断开发内容是否为空
        if(tablelen <= 0 || hasClass){
            $('.field-need-content-plan_num').addClass('has-error');
            $('.field-need-content-plan_num .help-block').html('开发内容不能为空。');
            return;
        }
        //判断是否有未上传的附件
        if(attlen > 0 && tijiao()){
            $('.field-need-attachments-upload_file_id').addClass('has-error');
            $('.field-need-attachments-upload_file_id .help-block').html('附件文件还未上传。');
            return;
        }
        $('#need-task-form').submit();
    }
   
    //显示模态框
    window.showModal = function(elem){
        $(".myModal").html("");
        $('.myModal').modal("show").load(elem.attr("href"));
        return false;
    }    
    //修改开发内容
    window.updataContent = function(elem){
        var planNum = elem.val();
        var price = elem.parent().prev().children().text();
        var costNumber = Number(planNum) * Number(price);
        $.post('../content/update?id=' + elem.attr("data-id"), {'plan_num': planNum}, function(rel){
            if(rel['code'] == '200'){
                elem.parent().next().children().text(number_format(costNumber, 2, '.', ''));
                countTotalCost();
            }
        });
        return false;
    }
    //修改开发人员
    window.updataDeveloper = function(elem){
        var id = parseInt(elem.attr("id").substring(1));
        var performancePercent = elem.val();
        $.post('../user/update?id=' + id, {'performance_percent': performancePercent}, function(rel){
            if(rel['code'] == '200'){
                $("#developer").load("../user/index?need_task_id=$model->id");
                $("#needtasklog").load("../log/index?need_task_id=$model->id");
            }
        });
        return false;
    }
    //删除开发内容
    window.deleteContent = function(elem){
        $.post(elem.attr("href"), function(rel){
            if(rel['code'] == '200'){
                elem.parent('td').parent('tr').remove();
                countTotalCost();
            }
        });
        return false;
    }
    //删除开发人员
    window.deleteDeveloper = function(elem){
        $.post(elem.attr("href"), function(rel){
            if(rel['code'] == '200'){
                $("#developer").load("../user/index?need_task_id=$model->id");
                $("#needtasklog").load("../log/index?need_task_id=$model->id");
            }
        });
        return false;
    }
    //计算总成本
    function countTotalCost(){
        var totalCost = 0;
        $('.cost').each(function(){
            totalCost += Number($(this).text());
        });
        $('#total-cost > span').text(number_format(totalCost, 2, '.', ''));
        $('#total-cost > input').val(totalCost);
    };
    //数字格式化 
    function number_format(number, decimals, dec_point, thousands_sep) {  
        var n = !isFinite(+number) ? 0 : +number,  
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),  
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,  
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,  
            s = '',  
            toFixedFix = function (n, prec) {  
                var k = Math.pow(10, prec);  
                return '' + Math.round(n * k) / k;        };  
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;  
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');  
        if (s[0].length > 3) {  
            s[0] = s[0].replace(/(\d)(?=(?:\d{3})+$)/g, '$1'+sep);  
        }  
        if ((s[1] || '').length < prec) {  
            s[1] = s[1] || '';  
            s[1] += new Array(prec - s[1].length + 1).join('0');  
        }      
        return s.join(dec);  
    } 
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>