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
			<div class='cnt'>
			<?php
				if(count($dbModel)==0)
					echo Html::tag('i','No active records',['style'=>'color:grey']);
				
				else
				{
					foreach($dbModel as $d)
					{
						echo '<div class="header-container ">';
						echo Html::tag('h1',$d->header,['class'=>'header-inside header-txt','id'=>"header-".$d->id]);
							
						echo '</div>';
						echo '<div class="body-container" id="container-'.$d->id.'">';
							echo Html::tag('p',$d->description,['class'=>'body-inside container-txt container-txt-sz']);
							
							echo '<div class="container" >';
								printTags($d->tag);
								echo Html::a('Remove',Url::toRoute(['delete','id'=>$d->id]),['class'=>'container-btn container-pos']);
								echo Html::tag('button','Edit',['class'=>'container-btn container-pos','id'=>'edit-id','onclick'=>'displayEditDialog()','name'=>$d->id]);
							echo '</div>';
						echo '</div>'; 
					}
					
				}
				
			
				
				$formSearch=ActiveForm::begin(['id'=>'search-form','method'=>'get','action'=>Url::toRoute(['search'])]); //search
					echo '<div style="display:inline-flex;position:absolute;width:123px;left:55%;top:-10%;">';
						echo $formSearch->field($searchModel,'tag')->textInput(['style'=>'width:123px;maxlength:50;height:32px;','placeholder'=>'Search note','id'=>'search-txt']); 
						echo Html::submitButton('Search',['class'=>'btn','style'=>'background-color:blue;color:white;margin:20px;height:37px;']); 
					echo '</div>';
				ActiveForm::end();
				
			
				
				Modal::begin(['id'=>'modal-edit','title'=>'Edit post']);
					$form=ActiveForm::begin(['id'=>'edit-form','method'=>'post','action'=>Url::toRoute(['edit','id'=>Yii::$app->request->cookies->getValue('note-id')])]);
						echo $form->field($editNote,'header')->textInput(['style'=>'width:45%','maxlength'=>'50','id'=>'edit-header']);
						echo $form->field($editNote,'description')->textarea(['style'=>'width:45%;height:60%','maxlength'=>'50','id'=>'edit-desc']);
						echo $form->field($editNote,'tag')->textInput(['style'=>'width:45%','maxlength'=>'50','id'=>'edit-tag']);
						
						//echo $form->field($editNote,'id')->hiddenInput(['id'=>'hidden-id'])->label(false);
						
						echo Html::submitButton('Add',['class'=>'btn','style'=>'background-color:blue;color:white;']);
					ActiveForm::end();
				Modal::end();
				
				
				
				Modal::begin(['id'=>'modal-add','title'=>'Add new note']);
					$form=ActiveForm::begin(['id'=>'form','method'=>'post','action'=>Url::toRoute(['add','id'=>$id])]);
					
						echo $form->field($addNotes,'header')->textInput(['style'=>'width:45%','maxlength'=>'50']);
						echo $form->field($addNotes,'tag')->textInput(['style'=>'width:45%','maxlength'=>'50'])->label('Tag. Add tags using ; delimiter');
						echo $form->field($addNotes,'description')->textarea(['style'=>'width:45%;height:60%;','maxlength'=>'150']);
						
						//echo $form->field($addNotes,'id')->hiddenInput(['value'=>$id])->label(true);
						echo Html::submitButton('Add',['class'=>'btn','style'=>'background-color:blue;color:white;']);
					ActiveForm::end();
				Modal::end();
				
			?>
			</div>
        </div>

    </div>
</div>
