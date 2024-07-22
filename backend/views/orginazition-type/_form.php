<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>

<div class="countryy-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList($model->getStatusDropDownData()); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$js=<<< JS
  //  alert('a')
        
  $(document).ready(function(){
    function hideShowDiv() {
        var inputValue = $("#livetv-is_paid"). val();
        
        if(inputValue==1){
            
            $('#coin_text_block').slideDown();
        }else{
            
            $('#coin_text_block').slideUp();
            
        }
    }
    
    $('#livetv-is_paid').change(function(){
        hideShowDiv();
       
    });
    hideShowDiv();

});
JS;
$this->registerJs($js,\yii\web\view::POS_READY);
?>
