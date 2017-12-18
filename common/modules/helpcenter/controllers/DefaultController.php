<?php

namespace common\modules\helpcenter\controllers;

use common\models\helpcenter\Post;
use common\models\helpcenter\PostAppraise;
use common\models\helpcenter\PostCategory;
use common\models\helpcenter\PostComment;
use common\models\helpcenter\searchs\PostCommentSearch;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\widgets\Menu;

/**
 * Default controller for the `helpcenter` module
 */
class DefaultController extends Controller {

    public $layout = "main";

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }
    
    /**
     * Renders the index view for the module
     * @return mixed
     */
    public function actionView() {
        $id = Yii::$app->request->queryParams['id'];
        $number = (new Query())->from(PostComment::tableName())->where(['post_id'=>$id])->count();
        $model = $this->getPostContents($id);
        //是否点赞
        $isLike = PostAppraise::findOne([
            'post_id' => $id,
            'user_id' => Yii::$app->user->id,
            'result' => '1',]);
        //是否踩
        $isUnlike = PostAppraise::findOne([
            'post_id' => $id,
            'user_id' => Yii::$app->user->id,
            'result' => '2',]);

        $view_count = Post::findOne($id);
        $view_count->view_count += 1;           //打开文章时查看次数+1
        $view_count->save(FALSE,['view_count']);
        return $this->render('view', [
            'model' => $model,
            'number' => $number,                //评论数量
            'isLike' => $isLike != null,
            'isUnlike' => $isUnlike != null,
        ]);
    }

    /**
     * 获取留言内容
     * Lists all PostComment models.
     * @return mixed
     */
    public function actionMesIndex($post_id)
    {
        $searchModel = new PostCommentSearch();
        
        return $this->renderAjax('mes-index', [
            'dataProvider' => $searchModel->search(['post_id'=>$post_id])
        ]);
    }
    
    /**
     * 创建评论
     * Creates a new PostComment model.
     * @return mixed
     */
    public function actionCreateMessage($post_id)
    {
        $model = new PostComment(['post_id'=>$post_id,'created_by'=> \Yii::$app->user->id]);
        $model->loadDefaultValues();
        $num = 0;
        $comment_count = Post::findOne($post_id);
        if(Yii::$app->request->isPost){
            Yii::$app->getResponse()->format = 'json';
            $result = $this->CreateMessage($model,Yii::$app->request->post());
            $comment_count->comment_count ++;
            $comment_count->update();
            return [
               'code'=> $result ? 200 : 404,
               'num' => $result ? $num + 1: $num,
               'message' => ''
            ];
        } else {
            return $this->goBack(['view', 'id' => $post_id]);
        }
    }
    
    /**
     * 添加评论操作
     * @throws Exception
     */
    public function CreateMessage($model,$post)
    {
        $model->content = ArrayHelper::getValue($post, 'content');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if ($model->save()) {
                
            } else {
                throw new Exception($model->getErrors());
            }
            $trans->commit();  //提交事务
            return true;
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            return false;
        }
    }
    
    /**
     * 组装菜单
     * @return array
     */
    public static function getMenu() {
        $menus = self::getCategories(null)->all();
        $menuItems = [];
        foreach ($menus as $_menu) {
            if ($_menu->parent_id == 0) {
                $children = self::getChildrenMenu($menus, $_menu->id);
                $item = [
                    'label' => $_menu->name,
                ];
                if (count($children) > 0) {
                    $item['url'] = $_menu->href;
                    $item['items'] = $children;
                } else {
                    $item['url'] = [$_menu->href];
                }
                $item['icon'] = $_menu->icon;
                $menuItems[] = $item;
            }
        }
//      exit;
        return $menuItems;
    }

    /**
     * 获取所有菜单
     * @param integer $level        等级
     * @return model Menu
     */
    public static function getCategories($level = 1) {
        $parentCats = PostCategory::find()
                ->from(['PostCategory' => PostCategory::tableName()]);
        $parentCats->leftJoin(['Post' => Post::tableName()], 'Post.category_id = PostCategory.id');
        $parentCats->where(['PostCategory.is_show' => true,'app_id' => 'app-mconline'])
                ->andFilterWhere(['level' => $level]);
        
        return $parentCats;
    }

    /**
     * 获取二级菜单
     * @param Menu $menu
     * @param array $allMenus  获取所有菜单
     * @param type $parnet_id
     * @return array
     */
    private static function getChildrenMenu($allMenus, $parent_id) {
        $items = [];
        foreach ($allMenus as $menu) {
            /* @var $menu Menu */
            if ($menu->parent_id == $parent_id) {
                $children = self::getPosts($menu->parent_id);
//                var_dump($children);
                $item = [
                    'label' => $menu->name,
                ];
                if (count($children) > 0) {
                    $item['url'] = $menu->href;
                    $item['items'] = $children;
                } else {
                    $item['url'] = [$menu->href];
                }
                $item['icon'] = $menu->icon;
                $items[] = $item;
            }
        }
        
        return $items;
    }

    /**
     * 获取所有文章（菜单）
     * @return array
     */
    public static function getPosts($category_id) {
        $posts = Post::find()
                        ->from(['Post' => Post::tableName()])
                        ->where([
                            'is_show' => true,
                        ])->all();
        $items = [];
        foreach ($posts as $menu) {
            /* @var $menu Menu */
            if ($menu->category_id == $category_id) {
                $items[] = [
                    'label' => $menu->name,
                    'url' => ['/helpcenter/default/view', 'id'=>$menu->id],
                    'icon' => 'file-text-o',
                ];
            }
        }
        return $items;
    }

    /**
     * 获取文章内容
     * @param int $id
     * @return array
     */
    public function getPostContents($id){
        $postContents = (new Query())
                    ->from(['Post' => Post::tableName()])
                    ->where([
                        'is_show' => true,
                        'id' => $id,
                    ])->one();
        
        return $postContents;
    }
    
}
