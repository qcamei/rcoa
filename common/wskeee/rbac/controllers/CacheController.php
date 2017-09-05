<?php

namespace wskeee\rbac\controllers;

use common\models\System;
use Yii;
use yii\db\Query;
use yii\web\Controller;

class CacheController extends Controller
{
    
    public function actionIndex()
    {
       $cache = \Yii::$app->cache;
       $cache->flush();
       $cache->cachePath = '../../frontend/runtime/cache';   
       $cache->gc(true, false);
       return $this->render('index');
    }
}
