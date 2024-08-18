<?php
namespace app\models;

use app\models;
use yii\base\Model;

class AddNote extends Model
{
	public $description;
	public $header;
	
	public function rules()
	{
		return [
			[['description','header'],'required'],
		];
		
	}
	
}


?>