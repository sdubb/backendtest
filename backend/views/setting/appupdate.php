
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Countryy */

$this->title = 'Setting ';
$this->params['breadcrumbs'][] = ['label' => 'Setting', 'url' => ['index']];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <?= $this->render('app', [
                    'model' => $model
                    
                ]) ?>
            </div>
        </div>
    </div>
</div>