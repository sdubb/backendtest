<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
#use api\modules\v1\models\FavoriteAd;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
#use api\modules\v1\models\Ad;
use api\modules\v1\models\UserFavorite;
use api\modules\v1\models\Coupon;
use api\modules\v1\models\Business;
use api\modules\v1\models\User;
use api\modules\v1\models\Post;


class FavoriteController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\userFavorite';

    public function actions()
    {
        $actions = parent::actions();

        // disable default actions
        unset($actions['create'], $actions['update'], $actions['index'], $actions['delete'], $actions['view']);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except' => [],
            'authMethods' => [
                HttpBearerAuth::className()
            ],
        ];
        return $behaviors;
    }

    /* public function actionIndex()
     {
         $userId = Yii::$app->user->identity->id;
        $model = new Ad();
         $result  = $model->find()
             ->joinWith('locations')
             ->joinWith('favorite')
             ->select(['ad.id','ad.user_id','ad.category_id','ad.title','ad.sub_category_id','ad.status','ad.phone','ad.price','ad.view','ad.description','featured','currency','is_banner_ad','package_banner_id','ad.created_at'])
             ->where(['favorite_ad.user_id'=>$userId])
             ->all();
        
         
         $response['message']='Favorite list found successfully';
         $response['ad']=$result;
         return $response;
        
     }
 */

    public function actionAddFavorite()
    {

        $userId = Yii::$app->user->identity->id;
        $model = new Coupon();
        $modelBusiness = new Business();
        $modelUserFavorite = new UserFavorite();
        $modelUser = new User();
        $modelPost = new Post();
        $modelUserFavorite->scenario = 'create';
        if (Yii::$app->request->isPost) {

            $modelUserFavorite->load(Yii::$app->getRequest()->getBodyParams(), '');

            if (!$modelUserFavorite->validate()) {

                $response['statusCode'] = 422;
                $response['errors'] = $modelUserFavorite->errors;
                return $response;
            }

            $referenceId = @(int) $modelUserFavorite->reference_id;
            $type = @(int) $modelUserFavorite->type;
            if ($type == UserFavorite::TYPE_COUPON) {
                $result = $model->findOne($referenceId);

            } elseif ($type == UserFavorite::TYPE_BUSINESS) {
                $result = $modelBusiness->findOne($referenceId);

            } elseif ($type == UserFavorite::TYPE_POST) {
                $result = $modelPost->findOne($referenceId);
            }



            if (!$result) {
                $response['statusCode'] = 422;
                $errors['message'][] = Yii::$app->params['apiMessage']['common']['noRecord'];
                $response['errors'] = $errors;
                return $response;

            }

            $resultCount = $modelUserFavorite->find()->where(['user_id' => $userId, 'reference_id' => $referenceId, 'type' => $type])->count();

            if ($resultCount > 0) {
                $response['statusCode'] = 422;
                $errors['message'][] = Yii::$app->params['apiMessage']['favorite']['alreadyFavorite'];
                $response['errors'] = $errors;
                return $response;

            }

            //resultUser
            $modelUserFavorite->user_id = $userId;
            $modelUserFavorite->reference_id = $referenceId;

            if ($modelUserFavorite->save()) {


                $response['message'] = Yii::$app->params['apiMessage']['favorite']['AddFavorite'];
                return $response;
            } else {

                $response['statusCode'] = 422;
                $errors['message'][] = Yii::$app->params['apiMessage']['common']['actionFailed'];
                $response['errors'] = $errors;
                return $response;

            }


        }

    }

    public function actionRemoveFavorite()
    {
        $userId = Yii::$app->user->identity->id;
        $model                  = new Coupon();
        $modelBusiness          = new Business();
        $modelUserFavorite      = new UserFavorite();
        $modelUser              = new User();
        $modelPost              = new Post();
        $modelUserFavorite->scenario = 'removeFavorite';
        if (Yii::$app->request->isPost) {
            $modelUserFavorite->load(Yii::$app->getRequest()->getBodyParams(), '');
            if (!$modelUserFavorite->validate()) {
                $response['statusCode'] = 422;
                $response['errors'] = $modelUserFavorite->errors;
                return $response;
            }
            $referenceId = @(int) $modelUserFavorite->reference_id;
            $type = @(int) $modelUserFavorite->type;

            if ($type == UserFavorite::TYPE_COUPON) {
                $result = $model->findOne($referenceId);
            } elseif ($type == UserFavorite::TYPE_BUSINESS) {
                $result = $modelBusiness->findOne($referenceId);
            } elseif ($type == UserFavorite::TYPE_POST) {
                $result = $modelPost->findOne($referenceId);
            }




            if (!$result) {

                $response['statusCode'] = 422;
                $errors['message'][] = Yii::$app->params['apiMessage']['common']['noRecord'];
                $response['errors'] = $errors;
                return $response;

            }


            $resultFavorite = $modelUserFavorite->find()->where(['user_id' => $userId, 'reference_id' => $referenceId, 'type' => $type])->one();

            if (!$resultFavorite) {

                $response['statusCode'] = 422;
                $errors['message'][] = Yii::$app->params['apiMessage']['common']['noRecord'];
                $response['errors'] = $errors;
                return $response;

            }



            if ($resultFavorite->delete()) {


                $response['message'] = Yii::$app->params['apiMessage']['favorite']['removedFavorite'];
                return $response;
            } else {

                $response['statusCode'] = 422;
                $errors['message'][] = Yii::$app->params['apiMessage']['common']['actionFailed'];
                $response['errors'] = $errors;
                return $response;

            }


        }

    }
    /*
        public function actionCreate()
        {
           
            $model = new FavoriteAd();
            $userId = Yii::$app->user->identity->id;
            $model->scenario ='create';
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            if(!$model->validate()) {
                $response['statusCode']=422;
                $response['errors']=$model->errors;
            
                return $response;
            }


            $adId =  @(int) $model->ad_id;
           
            $totalCount = $model->find()->where(['ad_id'=>$adId, 'user_id'=>$userId])->count();
            if($totalCount>0){
               $response['statusCode']=422;
              $response['message']='You have already added in favorite list this ad';
              return $response; 
     
            }
     
             if($model->save(false)){
                 $response['message']='Ad added in favorite list successfully';
                 return $response; 
             }else{
                 $response['statusCode']=422;
                 $response['message']='Ad not added in favorite list successfully';
                 return $response; 
     
     
             }
            
        }

        public function actionDeleteList()
        {
           
            $model = new FavoriteAd();
            $userId = Yii::$app->user->identity->id;
            $model->scenario ='create';
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            if(!$model->validate()) {
                $response['statusCode']=422;
                $response['errors']=$model->errors;
            
                return $response;
            }


            $adId =  @(int) $model->ad_id;
           $result =  $model->find()->where(['ad_id'=>$adId, 'user_id'=>$userId])->one();
            if(isset($result->id)){
                if($result->delete()){
           
                    $response['message']='Favorite removed from list successfully';
                    return $response; 
                }else{
                    $response['statusCode']=422;
                    $response['message']='Favorite not removed successfully';
                    return $response; 


                }

            }else{
                $response['statusCode']=422;
                $response['message']='Action not performed';
                return $response; 


            }
           
           
            

            
            
        }
        */




}