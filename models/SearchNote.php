<?php
namespace app\models;

use app\models;
use yii\base\Model;

class SearchNote extends Model
{
	public $searchTag;
	public function rules()
	{
		return 
		[
			[['searchNote'],'required'],
		];
	}

}	

?>