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
    public function actionIndex($id)
    {
		if(Yii::$app->user->isGuest )
		{
			return $this->redirect('login');
		}
		else
		{	
			$notes=new AddNote();
			$editNotes=new EditNote();
			
			$query=NoteDb::find()->where(['user_id'=>$id])->all();
			//$url=Url::to(['']);
			
			return $this->render('index',['dbModel'=>$query,'addNotes'=>$notes,
			'editNote'=>$editNotes,'id'=>$id]);
			
		}
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
				if($model->login())
				{
					$url=Url::toRoute(['index','id'=>$model->userId]);
					$this->redirect($url);
				}
				//Yii::$app->session->setFlash('success','Your account has been created');
				//$this->redirect('login');
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
		
		$url=Url::toRoute(['index','id'=>$id]);
		
        if (!Yii::$app->user->isGuest) {
			
            return $this->redirect($url);
        }
		
        if ($model->load(Yii::$app->request->post())) {
				if($model->login())
				{
					$this->redirect($url);
					//Yii::$app->session->setFlash('success','Login sucessful');
				}
				else
				{
					//if((null!=Yii::$app->request->post('username')) ||null!=Yii::$app->request->post('password'))
					Yii::$app->session->setFlash('error','Incorrect password');
					$model->password = '';
				}
        }
		return $this->render('login', ['model' => $model]);
		
		
    }
	
	
	//public function action
	
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
		
		
		$url=Url::toRoute('index',['id'=>'0']); //$id
		return $this->redirect($url);
  }
	public function actionDelete($id)
	{
		$noteDbItem=NoteDb::findOne($id);
		$noteDbItem->delete();
		
		$url=Url::toRoute('index',['id'=>$id]);
		return $this->redirect($url);
		
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
