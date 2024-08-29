<?php

namespace app\controllers;


use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\web\Application;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\helpers\Url;

use app\models\NoteDb;
use app\models\AddNote;
use app\models\EditNote;
use app\models\Auth;

use app\models\SignupForm;
use app\models\SignupUsers;
use app\models\SearchNote;
//


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
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
			'auth'=>[
				'class'=>'yii\authclient\AuthAction',
				'successCallback'=>[$this,'onAuthSuccess'],
			
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
		if(Yii::$app->user->isGuest )
			return $this->redirect('login');
		
		
		$notes=new AddNote();
		$editNotes=new EditNote();
		$searchModel=new SearchNote();
		
		$id=Yii::$app->session->get('id');
			
		$query=NoteDb::find()->where(['user_id'=>$id])->all();
		
		return $this->render('index',['dbModel'=>$query,'addNotes'=>$notes,'editNote'=>$editNotes,'id'=>$id,'searchModel'=>$searchModel]);
    }
	
	public function actionSearch()
	{
		if(Yii::$app->user->isGuest)
			return $this->redirect('login');
		
		$model=new SearchNote();
		
		if($model->load(Yii::$app->request->get()))
		{	
			$id=Yii::$app->session->get('id');
			$query=null;
	
			
			if(empty($model->tag))
				$query=NoteDb::find()->where(['user_id'=>$id])->all();
			
			else
				$query=NoteDb::find()->where(['user_id'=>$id])->andWhere(['like','tag',$model->tag])->all();
				
			
			
			$addNote=new AddNote();
			$editNote=new EditNote();
			$searchModel=new SearchNote();
			
			return $this->render('index',['dbModel'=>$query,'addNotes'=>$addNote,'editNote'=>$editNote,'id'=>$id,'searchModel'=>$searchModel]);
			
		}
		else
			return $this->redirect('index');
	}
	public function actionSignup()
	{
		if (!Yii::$app->user->isGuest) {
            return $this->redirect('index');
        }
		
		
		$model=new SignupForm();
		if($model->load(Yii::$app->request->post())) 
		{
			if($model->signup())
			{
				//if($model->login())		
				//{
				Yii::$app->session->setFlash('success','Your account has been created');
				$this->redirect('login');
				//}
				
			}
			else
				Yii::$app->session->setFlash('error','This account already exists');
			
		}
		//return $this->redirect('success');
		return $this->render('signup',['model'=>$model]);
			
	}
	
    /**
     * Login action.
     *
     * @return Response|string
     */
	 
    public function actionLogin()
    {
		$model = new LoginForm();
		$id=SignupUsers::findIdByUserName($model->username);
		
		//$url=Url::toRoute(['index','id'=>$id]);
		
        if (!Yii::$app->user->isGuest) {
			
            return $this->redirect('index');
        }
		
        if ($model->load(Yii::$app->request->post())) {
				if($model->login())
					$this->redirect('index');
				else
				{
					Yii::$app->session->setFlash('error','Incorrect password');
					$model->password = '';
				}
        }
		return $this->render('login', ['model' => $model]);
		
		
    }
	
	
	public function actionEdit($id)
	{
		if(Yii::$app->user->isGuest)
			return $this->redirect('login');
		
		$editModel=new EditNote();
		
		if($editModel->load(Yii::$app->request->post()))
		{
			//$id=Yii::$app->request->cookies->getValue('note-id');
			
			$noteDb=NoteDb::find()->where(['id'=>$id])->one();
			if($noteDb!=null)
			{
				$noteDb->header=$editModel->header;
				$noteDb->description=$editModel->description;
				$noteDb->tag=$editModel->tag;
				$noteDb->update();
			}
		}
		return $this->redirect('index');
	}
	
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect('login');
    }

  public function actionAdd($id) //
  {
		$model=new AddNote();
		
		if($model->load(Yii::$app->request->post()))
		{
			//$req=Yii::$app->request;
			
			$noteDbItem=new NoteDb();
			$noteDbItem->header=$model->header;
			$noteDbItem->description=$model->description;
			$noteDbItem->tag=$model->tag;
			$noteDbItem->user_id=$id;
		
			$noteDbItem->save();
			
			Yii::$app->session->setFlash('success','added sucessfully');
		}
		else
			Yii::$app->session->setFlash('error','error adding');
		
		return $this->redirect('index');
  }
	public function actionDelete($id)
	{
		$noteDbItem=NoteDb::findOne($id);
		$noteDbItem->delete();
		
		
		return $this->redirect('index');
		
	}
	/*
	public function onAuthSuccess($client)
	{
		$attr=$client->getUserAttributes();
		
		$result=Auth::find()->where(
		['source'=>$client->getId(),
		'source_id'=>$attributes['id'],
		])->one();
		//if(Yii::$app->user->is)
	}
	*/
	
}
