<?php
namespace frontend\controllers;

use common\models\demand\DemandTask;
use common\models\expert\Expert;
use common\models\LoginForm;
use common\models\teamwork\CourseManage;
use common\models\User;
use Detection\MobileDetect;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use const YII_ENV_TEST;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $detect = new MobileDetect();
        $total = $this->getDemandTaskNewCount(DemandTask::$defaultStatus);
        $teamwork = $this->getTeamworkNewCount();
        return $this->render(!$detect->isMobile() ? 'index' : 'wap_index',[
            'total' => $total <= 999 ? $total : 999  ,
            'teamwork' => $teamwork <= 999 ? $teamwork : 999,
            'undertakeCount' => $this->getDemandTaskNewCount(DemandTask::STATUS_UNDERTAKE),
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $detect = new MobileDetect();
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render(!$detect->isMobile() ? 'login' : 'wap_login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    
    /**
     * 修改我的属性
     * @return mixed
     */
    public function actionResetInfo()
    {
        if (\Yii::$app->user->isGuest) 
            return $this->goHome();
        
        $model = User::findOne(\Yii::$app->user->id);
        $model->scenario = User::SCENARIO_UPDATE;
        if($model->load(Yii::$app->request->post()))
        {
            if($model->save())
                return $this->redirect(['index']);
            else
                Yii::error ($model->errors);
        }else
        {
            $model->password = '';
            return $this->render('resetInfo',[
                'model' => $model,
            ]);
        }
    }
    
    /**
     * 获取所有新的需求任务总数
     */
    public function getDemandTaskNewCount($status)
    {
        return  DemandTask::find()
                ->select(['Demand_task.id'])
                ->from(['Demand_task' => DemandTask::tableName()])
                ->where(['Demand_task.status' => $status])
                ->count();
    }
   
    /**
     * 获取所有进度总数
     */
    public function getTeamworkNewCount()
    {
        return CourseManage::find()
                ->select(['Course.id'])
                ->from(['Course' => CourseManage::tableName()])
                ->where(['!=', 'Course.status', CourseManage::STATUS_CARRY_OUT])
                ->count();
    }
    
    /*public function beforeAction($action) {
        if(Yii::$app->params['isLocalEnvironment'] == 'YES'){
            return true;
        }
        //  return true;
        $wxWapId = Yii::$app->controller->id;     //当前控制器
        var_dump($wxWapId);exit;
        if(Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'error'){
            return true;
        }
 
        $detect = new MobileDetect();
        $realWxWapId = '';
        if($detect->isMobile()) {
            if($detect->isWeixin()){                                     //是微信浏览器
                if(stripos($wxWapId,"wx/") === 0 ){                         //在控制器里面查找到wx
                    return true;
                }else if(stripos($wxWapId,"wap/") === 0 ){                  //是手机网页的浏览器
                    $realWxWapId = str_replace('wap/', 'wx/', $wxWapId);    //替换目录
                }else{
                    $realWxWapId = 'wx/'.$wxWapId;                          //加深目录
                }
            }else{                                      //其他手机浏览器
                if(stripos($wxWapId,"wap/") === 0 ){    //在控制器里面查找到wx
                    return true;
                }else if(stripos($wxWapId,"wx/") === 0 ){                   //是手机网页的浏览器
                    $realWxWapId = str_replace('wx/', 'wap/', $wxWapId);    //替换目录
                }else{
                    $realWxWapId = 'wap/'.$wxWapId;                         //加深目录
                }
            }
        }else{          //PC端
            if(stripos($wxWapId,"wx/") === 0 ){             //在控制器里面查找到wx
                $realWxWapId = str_replace('wx/', '', $wxWapId);        //替换目录
            }else if(stripos($wxWapId,"wap/") === 0 ){                  //是手机网页的浏览器
                $realWxWapId = str_replace('wap/', '', $wxWapId);       //替换目录
            }else{
                return true;
            }
        }
        if($realWxWapId){                       //需要跳转--组装新的URL
            if($_SERVER['QUERY_STRING']){       //有查询字符串
                $realWxWapPcUrl = 'http://'.$_SERVER['HTTP_HOST'].'/'.$realWxWapId.'/'.Yii::$app->controller->action->id.'?'.$_SERVER['QUERY_STRING'];
            }else{
                $realWxWapPcUrl = '/'.$realWxWapId.'/'.Yii::$app->controller->action->id;
            }
            $this->redirect($realWxWapPcUrl);        //跳转
        }
    }*/
}
