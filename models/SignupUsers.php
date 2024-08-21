<?php
namespace app\models;
use yii\db\ActiveRecord;

class SignupUsers extends ActiveRecord
{
	public static function tableName()
	{
		return 'users';
	}
	

}


?>