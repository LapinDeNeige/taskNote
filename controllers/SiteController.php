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
		
		
		
		$model=new SignupForm();
		return $this->render('signup',['model'=>$model]);
		
		//if()
		
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
        if ($model->load(Yii::$app->request->post()) && $model->login()) {			
            return $this->redirect('index');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

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
		
		
		if(isset($desc) && isset($head))
		{
			$noteDbItem=new NoteDb();
			$noteDbItem->header=$head;
			$noteDbItem->description=$desc;
		
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
