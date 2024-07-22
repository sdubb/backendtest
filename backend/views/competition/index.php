<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Competition';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12"><div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="pull-right m-bottom"><?= Html::a('Create', ['create'], ['class' => 'btn btn-success pull-right ']) ?></div>
                <div style="clear:both"></div>


                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'title',
                        'start_date:datetime',
                        'end_date:datetime',
                        'joining_fee',
                        [
                            'label'  => 'Competition User',
                            'value'  => function ($data) {
                                return count($data->competitionUser);
                            },
                            'format'=>'raw'
                        ],
                        [
                            'attribute'  => 'status',
                            'value'  => function ($data) {
                                return $data->statusButton;
                            },
                            'format'=>'raw'
                        ],
                        
                        [
							'class' => 'yii\grid\ActionColumn',
							 'header' => 'Action',
                             'template' => '{view} {update} {delete}',
                         ],
                    
                    ],
                    'tableOptions' => [
                        'id' => 'theDatatable',
                        'class' => 'table table-striped table-bordered table-hover',
                    ],
                ]); ?>
            </div>


        </div>
        <!-- /.box -->



        <!-- /.col -->
    </div>
</div>