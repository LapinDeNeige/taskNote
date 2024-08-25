<?php
namespace app\models;

use app\models;
use yii\base\Model;

class EditNote extends Model
{
	public $header;
	public $tag;
	public $description;
	//public $id;
	
	public function rules()
	{
		return [
			[['description','header','tag'],'required'],

		];
		
	}
	
	
}	


?>