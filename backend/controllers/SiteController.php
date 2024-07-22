<?php
namespace backend\controllers;

use app\models\User;
use backend\models\Ad;
use common\models\LoginForm;
use common\models\VerifyOtpForm;
use common\models\Payment;
use common\models\Post;
use common\models\PostComment;
use common\models\Audio;
use common\models\Competition;
use common\models\Setting;
use common\models\EventTicketBooking;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use common\models\Coupon;
use common\models\Club;
use common\models\Event;
use common\models\Story;
use common\models\SupportRequest;
use common\models\UserLiveHistory;
use yii\web\ForbiddenHttpException;



/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
       
        return [
            'access' => [
                'class' => AccessControl::className(),
              
                'rules' => [
                    [
                        'actions' => ['login','verify-otp', 'error','ticket-view'],
                        'allow' => true,
                      //  'ips' => ['::1s','127..1.1', '19.68.1.11'], // Allowed IP addresses
                    ],
                    [
                        'actions' => ['logout', 'index','verify'],
                        'allow' => true,
                        'roles' => ['@'],
                       // 'ips' => ['::1s', '192.18.1.01'], // Allowed IP addresses
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        
        $modelAd = new Ad();
        
        $modelPost = new Post();
        $modelPostComment = new PostComment();
        $modelAudio = new Audio();
        
        $modelUser = new User();
        $modelPayment = new Payment();
        $modelCompetition = new Competition();
        $modelSetting = new Setting();
        $graphSetting = $modelSetting->getGraphSetting();
        $modelReels = new Audio();
        $modelClubs = new Club();
        $modelEvents = new Event();
        $modelCoupons = new Coupon();
        $modelStory = new Story();
        $modelSupportReq = new SupportRequest();
        $modelUserLiveHistory = new UserLiveHistory();
        $totalPost = $modelPost->getTotalPostCount();

        $totalComment = $modelPostComment->getTotalCommetCount();
        $totalAudio = $modelAudio->getTotalAudioCount();
        
        //$pendingJobCount = $modelAd->getPendingJobCount();
        $totalEarning = $modelPayment->getTotalEarning();

        $totalEarning = isset($totalEarning)?$totalEarning:0;

        $totalEarning = round($totalEarning);

        
        $totalEarningLastMonth = $modelPayment->getTotalEarningLastMonth();
        $totalEarningLastMonth = isset($totalEarningLastMonth)?$totalEarningLastMonth:0;

        $totalEarningLastMonth = round($totalEarningLastMonth);
        if($totalEarning>0){
            $lastMonthPercentage = round($totalEarningLastMonth/$totalEarning*100);
        }else{
            $lastMonthPercentage=0;
        }
       
       
        
        $earnings=['totalEarning'=>$totalEarning,'totalEarningLastMonth' =>$totalEarningLastMonth,'lastMonthPercentage'=>$lastMonthPercentage];
        
       $support = $modelSupportReq->getTotalSupportRequest();
       $liveHistory = $modelUserLiveHistory->getTotalLiveHistory(); 


        $userCount = $modelUser->getUserCount();
        $latestUsers = $modelUser->getLatestUsers();
        $competitionCount = $modelCompetition->getCompetitionCount();

        $reelCount = $modelPost->getTotalReelsCount();
        $clubCount = $modelClubs->getTotalClubCount();
        $eventCount = $modelEvents->getTotalEventCount();
        $couponCount = $modelCoupons->getTotalCouponCount();

       
        $firstGraph = $modelPost->getLastTweleveMonthPost();

        $userGraph = $modelUser->getLastTweleveMonthUser();

        $paymentGraph = $modelPayment->getLastTweleveMonthPayments();
        $clubGraph = $modelClubs->getLastTweleveMonthClub();
        $totalStory = $modelStory->getStoryTotalCount();
        $reelsGraph = $modelPost->getLastTweleveMonthReels();
        $storyGraph = $modelStory->getLastTweleveMonthStory();
        if(!$graphSetting){
            return $this->goHome();
        }
        $postLatest = $modelPost->getLatestPost();

        //print_r($paymentGraph);

        // print_r($activeJob);

        return $this->render('index', [
            'totalPost' => $totalPost,
            'totalComment' => $totalComment,
            'userCount' => $userCount,
            'totalCompetition' => $competitionCount,
            'reelCount' =>  $reelCount,
            'clubCount' =>  $clubCount,
            'eventCount' => $eventCount,
            'couponCount' => $couponCount,
            'firstGraph' => $firstGraph,
            'paymentGraph' => $paymentGraph,
            'userGraph' =>  $userGraph,
            'clubGraph' =>  $clubGraph,
            'totalStory' => $totalStory,
            'reelsGraph' => $reelsGraph,
            'storyGraph' => $storyGraph,
            'postLatest'=>$postLatest,
            'latestUsers'=>$latestUsers,
            'earnings'=>$earnings,
            'support' =>$support,
            'liveHistory'=>$liveHistory,
            

        ]);

    }


    public function actionVerify()
    {
        $this->layout = 'main-login';
     //   $model = new LoginForm();
        $model = new Setting();
        $model->scenario = 'verifyPurchaseCode';
        
        /*if (isset($_COOKIE["username"]) && isset($_COOKIE["password"])) {

            $username = $_COOKIE["username"];
            $password = $_COOKIE["password"];

        } else {

            $username = null;
            $password = null;
        }*/

        if ($model->load(Yii::$app->request->post()) &&  $model->validate()) {

            $result = $model->getSettingData();
            $result->user_p_id = $model->user_p_id;
            if($result->save()){
                 Yii::$app->session->setFlash('success',  'You have sussessfull verified');
                 return $this->goBack();
            }

            /*$user = User::findByUsername($model->username);

            $data = Yii::$app->request->post();
            if ($user) {
                if ($user->role == User::ROLE_ADMIN || $user->role == User::ROLE_SUBADMIN) {

                    if ($model->login()) {
                        $user->last_active = time();
                        $user->save(false);
                        $modelSetting->updateSettingData();
                        //echo 'loogged';
                        //die;
                        //echo '<pre>'; print_r($data['LoginForm']); exit;
                        if ($data['LoginForm']['rememberMe'] == 1) {
                            $hour = time() + 3600 * 24 * 30;
                            setcookie('username', $data['LoginForm']['username'], $hour);
                            setcookie('password', $data['LoginForm']['password'], $hour);
                        }
                        //    Yii::$app->session->setFlash('success',  'You have sussessfull loggedin');
                        return $this->goBack();
                    } else {

                        Yii::$app->session->setFlash('warning', "Invalid Data.");
                        return $this->goBack();
                    }
                } else {
                    Yii::$app->session->setFlash('warning', "Invalid Data.");
                    return $this->goBack();

                }
            } else {
                Yii::$app->session->setFlash('warning', "Invalid Data.");
                return $this->goBack();
            }*/
            //print_r($model->errors());

        } else {
            //$model->password = '';
            //print_r($model->errors());
            $errors = $model->errors;
            print_r($errors);

            return $this->render('verify', [
                'model' => $model
               // 'username' => $username,
              // 'password' => $password,
            ]);
        }

    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        $modelSetting = new Setting();

        

        if (isset($_COOKIE["username"]) && isset($_COOKIE["password"])) {

            $username = $_COOKIE["username"];
            $password = $_COOKIE["password"];

        } else {

            $username = null;
            $password = null;
        }

        if ($model->load(Yii::$app->request->post())) {

            $user = User::findByUsername($model->username);
            
            $data = Yii::$app->request->post();
            if ($user) {
                if ($user->role == User::ROLE_ADMIN || $user->role == User::ROLE_SUBADMIN) {
                    $resUser = $model->login();
                    if($resUser){
                        $rememberMe = $data['LoginForm']['rememberMe'];

                        if ($rememberMe == 1) {
                            $hour = time() + 3600 * 24 * 30;
                            setcookie('username', $data['LoginForm']['username'], $hour);
                            setcookie('password', $data['LoginForm']['password'], $hour);
                        }
                        
                        $settingData = $modelSetting->getSettingData();
                        $isTwoFactorAuth = $settingData->is_two_factor_auth;

                        if($isTwoFactorAuth){
                            $session = Yii::$app->session;
                            $session->set('loguser', $resUser);
                            $session->set('rememberMe', $rememberMe);
                            $otp = mt_rand(1000, 9999);
                            $token = md5(time() . rand(10, 100));
                            $expirytTime = time() + 900;
                        
                            $token = $token . '_' . $expirytTime;
                            $user->password_reset_token = $token;
                            $user->verification_token = $otp;

                            if ($user->save(false)) {
                                $fromMail = Yii::$app->params['senderEmail'];
                                $fromName = Yii::$app->params['senderName'];
                                $from = array($fromMail => $fromName);
                                Yii::$app->mailer->compose()
                                    ->setSubject('Admin Login confirmation')
                                    ->setFrom($from)
                                    ->setTo($user->email)
                                    ->setHtmlBody('Hi ' . $user->username . '<br>Please use following OTP Code confirm your admin login.<br> OTP Code is : ' . $otp)
                                    ->send();
                                    
                                    return $this->redirect(['verify-otp', 'token' => $token]);

                            }

                        }else{
                            Yii::$app->user->login($resUser, $rememberMe ? 3600 * 24 * 30 : 0);
                            $user->last_active = time();
                            $user->save(false);
                            $modelSetting->updateSettingData();
                            return $this->goBack();
                        }
                       
                    } else {

                        Yii::$app->session->setFlash('warning', "Invalid Data.");
                        return $this->goBack();
                    }
                } else {
                    Yii::$app->session->setFlash('warning', "Invalid Data.");
                    return $this->goBack();

                }
            } else {
                Yii::$app->session->setFlash('warning', "Invalid Data.");
                return $this->goBack();
            }

        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
                'username' => $username,
                'password' => $password,
            ]);
        }

    }

    public function actionVerifyOtp()
    {
        $this->layout = 'main-login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new VerifyOtpForm();
        $modelUser = new User();
        $modelSetting = new Setting();
      
        if ($model->load(Yii::$app->request->post())) {
            $token = @Yii::$app->request->get('token');
            $otp =  $model->otp;
            $tokenExprity = @explode('_', $token)[1];
            
            if (time() > $tokenExprity) {
               
               Yii::$app->session->setFlash('error', "Your token has been expired.");
               return $this->refresh();
                
            }
          
            $user = $modelUser->find()->where(['password_reset_token' => $token, 'verification_token' => $otp, 'status' => User::STATUS_ACTIVE, 'role' => [User::ROLE_ADMIN,User::ROLE_SUBADMIN]])->one();
            if ($user) {
                $user->password_reset_token = null;
                $user->verification_token = null;
                $user->last_active = time();
                if ($user->save(false)) {
                    $session = Yii::$app->session;
                    $user =  $session->get('loguser');
                    $rememberMe =  $session->get('rememberMe');
                    $modelSetting->updateSettingData();
                    Yii::$app->user->login($user, $rememberMe ? 3600 * 24 * 30 : 0);
                    return $this->goHome();

                }
            }else{
              
               Yii::$app->session->setFlash('error', "Invalid OTP.");
             
            }
        } 
        return $this->render('verify-otp', [
            'model' => $model
        ]);
        

    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTicketView($id)
    {

        $this->layout = 'main-login';

        $modelEventTicketBooking = new EventTicketBooking();

        $model= $modelEventTicketBooking->findOne($id);
      

        
        
      //  $model='';

        return $this->render('ticket-view', [
            'model' => $model
            
        ]);


    }



}
