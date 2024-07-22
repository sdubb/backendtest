<?php

namespace backend\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Interest;
use backend\models\InterestSearch;
use yii\web\UploadedFile;

/**
 * 
 */
class InterestController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all  models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InterestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Countryy model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model  = $this->findModel($id);
        
        return $this->render('view', [
            'model' =>   $model
        ]);
    }

    /**
     * Creates a new Countryy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
       
        $model = new Interest();
      
        $model->scenario = 'createMainCategory';

        if ($model->load(Yii::$app->request->post()) ) {
            $modelUser = new User();
            $modelUser->checkPageAccess();
            
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if($model->validate()){
                if($model->imageFile){
                    
                    $type =  Yii::$app->fileUpload::TYPE_INTEREST;
                    $files = Yii::$app->fileUpload->uploadFile($model->imageFile,$type,false);
                    $model->image 		= 	  $files[0]['file']; 
                    
                }

                if($model->save()){
                    Yii::$app->session->setFlash('success', "Interest created successfully");
                    return $this->redirect(['index']);
                }
            }
            
        }

        return $this->render('create', [
            'model' => $model
            
        ]);
    }

    /**
     * Updates an existing Countryy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        //echo Yii::$app->urlManagerFrontend->baseUrl;
        $model = $this->findModel($id);

        $model->scenario = 'updateMainCategory';
       
        //if ($model->load(Yii::$app->request->post()) && $model->save()) {


        //if($model->load(Yii::$app->request->post()) && $model->validate()) {
        if($model->load(Yii::$app->request->post())){
            $modelUser = new User();
            $modelUser->checkPageAccess();
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if($model->imageFile){
                
                $type =  Yii::$app->fileUpload::TYPE_INTEREST;
                $files = Yii::$app->fileUpload->uploadFile($model->imageFile,$type,false);
                $model->image 		= 	  $files[0]['file']; 
                
            }
           
          
            if($model->save(false)){
                Yii::$app->session->setFlash('success', "Interest updated successfully");
                return $this->redirect(['index']);
            };
                
        }
       
        return $this->render('update', [
            'model' => $model
       
        ]);
    }

   

    /**
     * Deletes an existing Countryy model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $modelUser = new User();
        $modelUser->checkPageAccess();
        $userModel= $this->findModel($id);
        $userModel->status =  Interest::STATUS_DELETED;
        if($userModel->save(false)){

            Yii::$app->session->setFlash('success', "Interest deleted successfully");

            return $this->redirect(['index']);
        }
        
    }

    /**
     * Finds the Countryy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Countryy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Interest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}