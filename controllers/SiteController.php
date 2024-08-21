<?php

namespace app\controllers;


use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;


use app\models\NoteDb;
use app\models\AddNote;
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
    public function actionIndex($id=null)
    {
		if(Yii::$app->user->isGuest ||$id==null)
		{
			return $this->redirect('login');
		}
		else
		{	
			$notes=new AddNote();
			$query=NoteDb::find()->where(['user_id'=>$id])->all();
			
			return $this->render('index',['dbModel'=>$query,'addNotes'=>$notes]);
		}
    }
	public function actionSignup()
	{
		if (!Yii::$app->user->isGuest) {
            return $this->redirect('index');
        }
		
		
		$model=new SignupForm();
		if($model->load(Yii::$app->request->post()) && $model->signup()) 
		{
			return $this->redirect('success');
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
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('index');
        }

        $model = new LoginForm();
		
        if ($model->load(Yii::$app->request->post())) {
				if($model->login())
				{
					$notes=new AddNote();
					$query=NoteDb::find()->where(['user_id'=>$id])->all();
					
					return $this->redirect('index'); //$this->render
				}
				else
				{
					//if((null!=Yii::$app->request->post('username')) ||null!=Yii::$app->request->post('password'))
					Yii::$app->session->setFlash('error','Incorrect password');
					$model->password = '';
				}
        }
		return $this->render('login', ['model' => $model,]);
		
		
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

        return $this->goHome();
    }

  public function actionAdd($id)
  {
		//$desc=$_POST['description'];
		//$head=$_POST['header'];
		//$tag=$_POST['tag'];
		$model=new AddNote();
		
		if($model->load(Yii::$app->request->post()))
		{
			$noteDbItem=new NoteDb();
			$noteDbItem->header=$model->header;
			$noteDbItem->description=$model->description;
			$noteDbItem->tag=$model->tag;
			$noteDbItem->user_id=$id;
		
			$noteDbItem->save();
		}
		
		return $this->goHome();
  }
	public function actionDelete($id)
	{
		$noteDbItem=NoteDb::findOne($id);
		$noteDbItem->delete();
		
		return $this->redirect('index');
		
	}
	
	public function onAuthSuccess($client)
	{
		$attr=$client->getUserAttributes();
		
		$result=Auth::find()->where(
		['source'=>$client->getId(),
		'source_id'=>$attributes['id'],
		])->one();
		//if(Yii::$app->user->is)
		
		
		
		
	}
}
