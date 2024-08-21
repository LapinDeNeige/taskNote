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
    public function actionIndex()
    {
		if(Yii::$app->user->isGuest)
		{
			return $this->redirect('site/login');
		}
		else
		{	
			$notes=new AddNote();
			
			$query=NoteDb::find()->all();
			return $this->render('index',['dbModel'=>$query,'addNotes'=>$notes]);
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
			
		}
		//return $this->render('signup',['model'=>$model]);
		
		//if()
		
	}
	public function actionRegister()
	{
		$name=$_POST['name'];
		$pass=$_POST['password'];
		if(isset($name) && isset($pass))
		{
			$resultName=SignupUsers::find($name)->where(['name'=>$name])->all();
			if($resultName==null)
			{
				$newUser=new SignupUsers();
				$newUser->name=$name;
				$newUser->password=Yii::$app->getSecurity()->generatePasswordHash($pass);
				
				$newUser->save(false);
				
				//return $this->redirect('signup');
			}
		}
		return $this->redirect('signup');
	}
    /**
     * Login action.
     *
     * @return Response|string
     */
	 /*
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('index');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {			
            return $this->redirect('index');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
	*/
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
            return $this->redirect('index');
        }
		
		
		if(isset($_POST['username']) &&isset($_POST['password']))
		{
			$user=$_POST['username'];
			$pass=$_POST['password'];
			
			$resultAuth=SignupUsers::findOne($user)->all();
			
			if($resultAuth != null)
			{
				$hashedPass=$resultAuth->password;
				
				if(Yii::$app->getSecurity()->validatePassword($pass,$hashedPass))
					return $this->redirect('index');
			}
			
		}
		return $this->redirect('signup');
		
	}
	public function action
	
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

  public function actionAdd()
  {
		$desc=$_POST['description'];
		$head=$_POST['header'];
		$tag=$_POST['tag'];
		
		if(isset($desc) && isset($head) && isset($tag))
		{
			$noteDbItem=new NoteDb();
			$noteDbItem->header=$head;
			$noteDbItem->description=$desc;
			$noteDbItem->tag=$tag;
		
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
