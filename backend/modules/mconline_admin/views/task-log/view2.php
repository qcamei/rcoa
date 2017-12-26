<?php

use common\models\ScheduledTaskLog;

    /* @var $model ScheduledTaskLog */
    echo $this->render("_log_".$model->type."_view",['model' => $model]);
?>