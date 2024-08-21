<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;

use yii\authclient\clients\VKontakte;
/** @var yii\web\View $this */

$this->title = 'My Yii Application';
function printTags($tags)
{
	if(isset($tags))
	{
		$tagsArr=explode(';',$tags);
		foreach($tagsArr as $tg)
			echo Html::tag('div','#'.$tg,['class'=>'container-pos container-tag']);
	}
}


?>

<div class="site-index">
    <div class="body-content">

        <div class="row">
			<span>
				<?=Html::tag('h1','Your notes',['style'=>'position:absolute;left:40%;']) ?>
			</span>
			
			<div class='cnt'>
			<?php
				if(count($dbModel)==0)
					echo Html::tag('i','No active records',['style'=>'color:grey']);
				
				else
				{
					foreach($dbModel as $d)
					{
						echo '<div class="header-container">';
						echo Html::tag('h1',$d->header,['class'=>'header-inside header-txt']);
							
						echo '</div>';
						echo '<div class="body-container">';
							echo Html::tag('p',$d->description,['class'=>'body-inside container-txt container-txt-sz']);
							
							$url=Url::toRoute(['delete','id'=>$d->id]);
							echo '<div class="container">';
								echo Html::a('Remove',$url,['class'=>'container-btn container-pos']);
								//
								//echo Html::tag('div','#tag',['class'=>'container-pos container-tag']);
								printTags($d->tag);
							echo '</div>';
						echo '</div>'; 
					}
				}
				Modal::begin(['id'=>'modal-add','title'=>'Add new note']);
					$form=ActiveForm::begin(['id'=>'form','method'=>'post','action'=>['add']]);
					
						echo $form->field($addNotes,'header')->textInput(['style'=>'width:45%','maxlength'=>'50','name'=>'header']);
						echo $form->field($addNotes,'tag')->textInput(['style'=>'width:45%','maxlength'=>'50','name'=>'tag']);
						echo $form->field($addNotes,'description')->textarea(['style'=>'width:45%;height:60%;','maxlength'=>'150','name'=>'description']);
						
						echo Html::submitButton('Add',['class'=>'btn','style'=>'background-color:blue;color:white;']);
					ActiveForm::end();
				Modal::end();
			?>
			</div>
        </div>

    </div>
</div>
