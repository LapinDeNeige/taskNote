<?php
namespace app\models;
use Yii;
use yii\base\model;

class SignupForm extends Model
{
	public $name;
	public $password;
	
	
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
		
		
	}
	
}


?>