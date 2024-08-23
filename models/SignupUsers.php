<?php
namespace app\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

class SignupUsers extends ActiveRecord implements IdentityInterface
{
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
	
	public static function tableName()
	{
		return 'users';
	}
	public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
	
	 public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }
	public static function findItentity($id)
	{
		return static::findOne(['id'=>$id]);
		
	}
	public static function findByUsername($username)
	{
		return static::findOne(['name'=>$username]);
	}
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password,$this->password);
	}
	public static function findIdentityByAccessToken($token,$type=null)
	{
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}
	public  function getId()
	{
		return $this->getPrimaryKey();
	}
	public static function findIdentity($id)
	{
		return static::findOne(['id'=>$id]);
	}
	public function getAuthKey()
	{
		return $this->auth_key;
	}
	public function validateAuthKey($authKey)
	{
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}
	public static  function findIdByUserName($username)
	{
		$result=static::find()->where(['name'=>$username])->one();
		if($result)
			return $result->id;
			
		
	}
	
}


?>