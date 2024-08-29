<?php
namespace app\models;

use app\models;
use yii\base\Model;

class SearchNote extends Model
{
	public $tag;
	public function rules()
	{
		return 
		[
			[['tag'],'required'],
		];
	}

}	

?>