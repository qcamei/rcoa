<?php

use common\wskeee\framework\assets\FrameworkImportAsset;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
/* @var $this View */
/* @var $form ActiveForm */
?>
<h1>项目基础数据导入结果</h1>

<div id="import-log-container">
    
</div>
<?php 
    $log = json_encode(['code'=>$code,'msg'=>$msg,'logs'=>$logs]);
    $courses = json_encode($courses);
    $pushURL = Url::toRoute('create',true);
    $js = <<<JS
            var _import = new Wskeee.framework.Import({
                'pushURL':"$pushURL",
                'maxPost':100
            },$courses);
            _import.addLog($log);
            _import.push();
JS;
    $this->registerJs($js);
    FrameworkImportAsset::register($this);
?>
