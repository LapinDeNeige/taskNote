<?php
namespace app\models;
use Yii;
use yii\base\model;

//use yii\models\SignupUsers;
class SignupForm extends Model
{
	public $name;
	public $password;
	
	public $_user;
	public $userId;
	public function rules()
	{
		return [
		[['name','password'],'required'],
		['password','validatePassword'],
		
		
		];
		
	}
	
	public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
	
	public function signup()
	{
		$resultName=SignupUsers::find()->where(['name'=>$this->name])->all();
		
		if($resultName==null)
		{
			$newUser=new SignupUsers();
			$newUser->name=$this->name;
			$newUser->password=Yii::$app->getSecurity()->generatePasswordHash($this->password);
			$newUser->save(false);
			
			$this->userId=$newUser->id;
			
			return $newUser;
		}
		
		return false;
		
	}
	public function login()
	{
		$resultUser=$this->getUser();
		if($resultUser!=null)
		{
			return Yii::$app->user->login($resultUser);
		}
		return false;
	}
	public function getUser()
	{
		if($this->_user==false)
			$this->_user=SignupUsers::find()->where(['name'=>$this->name])->one();
		
		return $this->_user;
	}
	
	
	
}


?>