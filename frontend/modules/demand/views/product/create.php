<?php

use common\models\demand\DemandTaskProduct;
use yii\web\View;


/* @var $this View */
/* @var $model DemandTaskProduct */


?>
<div class="demand-task-product-create">

    <?= $this->render('_form', [
        'model' => $model,
        'productId' => $productId,
    ]) ?>

</div>
