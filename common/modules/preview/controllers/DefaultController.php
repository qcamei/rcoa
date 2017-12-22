<?php

namespace common\modules\preview\controllers;

use common\models\mconline\McbsActivityFile;
use wskeee\webuploader\models\Uploadfile;
use Yii;
use yii\db\Query;
use yii\web\Controller;

/**
 * Default controller for the `preview` module
 */
class DefaultController extends Controller {

    public $layout = "main-preview";
    
    //可预览文件格式
    public static $suffixs = [
        'mp4' => ['mp4'],
        'doc' => ['doc','docx','ppt','pptx','xls','xlsx','pdf','txt','zip','rar','7z'],
        'img' => ['bmp','jpg','jpeg','png','gif','pcx','tiff']
    ];

    /**
     * 
     * @return mixed
     */
    public function actionIndex() {
        $pramas = Yii::$app->request->queryParams;
        $file_id = $pramas['file_id'];
        $checkupLook = $this->checkupLook($file_id);
        $query = $this->getData();
        if ($checkupLook == 'doc') {
            return $this->render('preview-doc', [
                        'doc' => $query,
            ]);
        } elseif ($checkupLook == 'img') {
            return $this->render('preview-img', [
                        'img' => $query,
            ]);
        } elseif ($checkupLook == 'mp4') {
            return $this->render('preview-video', [
                        'video' => $query,
            ]);
        }
        return $this->render('preview-info', [
                    'other' => $query,
        ]);
    }

    /**
     * 检查文件类型
     * @param integer $file_id
     * @return mix
     */
    public static function checkupLook($file_id) {
        $query = (new Query())
                ->select(['Uploadfile.name', '(Uploadfile.size / (1024 * 1024 * 1024)) AS size', 'file_id', 'activity_id'])
                ->from(['Uploadfile' => Uploadfile::tableName()])
                ->leftJoin(['ActivityFile' => McbsActivityFile::tableName()], 'ActivityFile.file_id = Uploadfile.id')
                ->where(['Uploadfile.id' => $file_id])
                ->one();

        $suffixName = pathinfo($query['name'], PATHINFO_EXTENSION);     //获取后缀名
        $fizeSize = $query['size'];                                     //获取文件大小
        $type = '10';
        foreach(self::$suffixs as $key => $names){
            !in_array($suffixName,$names) ? : $type = $key;
        }
        if($type == 'doc' && $fizeSize > 5)
            $type = '10';
        return $type;
    }

    /**
     * 根据传过来的file_id查找相应的内容
     * @return array
     */
    public function getData() {
        $pramas = Yii::$app->request->queryParams;
        $file_id = $pramas['file_id'];
        $query = (new Query())
                ->select(['Uploadfile.name', 'Uploadfile.path', '(Uploadfile.size / (1024 * 1024 * 1024)) AS size',
                    'file_id', 'activity_id'])
                ->from(['Uploadfile' => Uploadfile::tableName()])
                ->leftJoin(['ActivityFile' => McbsActivityFile::tableName()], 'ActivityFile.file_id = Uploadfile.id')
                ->where(['Uploadfile.id' => $file_id])
                ->one();

        return $query;
    }

    /**
     * 获取文件类型
     * @return type
     */
    public function getCheckupFile() {
        $data = $this->getData();
        // 文件名（含后缀名）
        $fileName = $data['name'];
        $suffixName = pathinfo($fileName, PATHINFO_EXTENSION);

        return $suffixName;
    }

    /**
     * 检查文件是否超过5M
     * @return boolean
     */
    public function checkupSize() {
        $data = $this->getData();
        $fizeSize = $data['size'];
        if ($fizeSize > 5) {
            return false;
        }
        return true;
    }

}
