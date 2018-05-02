<?php

namespace common\models\mconline;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%mcbs_activity_type}}".
 *
 * @property string $id         
 * @property string $name           类型名称
 * @property string $des            描述
 * @property string $icon_path      图标路径
 * @property string $created_at     创建时间
 * @property string $updated_at     更新时间
 */
class McbsActivityType extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%mcbs_activity_type}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['des', 'icon_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'des' => Yii::t('app', 'Des'),
            'icon_path' => Yii::t('app', 'Icon Path'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $icon_name = md5(time());
            //图片上传
            $upload = UploadedFile::getInstance($this, 'icon_path');
            if ($upload !== null) {
                $string = $upload->name;
                $array = explode('.', $string);
                //获取后缀名，默认名为.jpg
                $ext = count($array) == 0 ? 'jpg' : $array[count($array) - 1];
                $uploadpath = $this->fileExists(Yii::getAlias('@mconline') . '/web/upload/activity_type_icons/');
                $upload->saveAs($uploadpath . $icon_name . '.' . $ext);
                $this->icon_path = '/upload/activity_type_icons/' . $icon_name . '.' . $ext . '?r=' . rand(1, 10000);
            }
            if (trim($this->icon_path) == '') {
                $this->icon_path = $this->getOldAttribute('icon_path');
            }
            return true;
        }
        return false;
    }

    /**
     * 检查目标路径是否存在，不存即创建目标
     * @param string $uploadpath    目录路径
     * @return string
     */
    private function fileExists($uploadpath) {

        if (!file_exists($uploadpath)) {
            mkdir($uploadpath);
        }
        return $uploadpath;
    }

}
