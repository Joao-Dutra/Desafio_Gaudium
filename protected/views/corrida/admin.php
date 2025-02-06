<?php
/* @var $this CorridaController */
/* @var $model Corrida */

$this->breadcrumbs=array(
    'Corridas'=>array('admin'),
    'Consulta Geral',
);

?>

<h1>Consulta Geral de Corridas</h1>

<p>
Filtre as corridas por data de início. As corridas em andamento aparecem primeiro.
</p>

<div class="search-form">
    <?php $this->renderPartial('_search', array('model' => $model)); ?>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'corrida-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        array(
            'name'=>'status',
            'value'=>'$data->status',
            'filter'=>array('Em andamento'=>'Em andamento', 'Finalizada'=>'Finalizada', 'Não Atendida'=>'Não Atendida'),
        ),
        'data_inicio',
        array(
            'name'=>'motorista_id',
            'value'=>'$data->motorista->nome',
            'filter'=>CHtml::listData(Motorista::model()->findAll(), 'id', 'nome'),
        ),
        array(
            'name'=>'passageiro_id',
            'value'=>'$data->passageiro->nome',
            'filter'=>CHtml::listData(Passageiro::model()->findAll(), 'id', 'nome'),
        ),
        'origem_endereco',
        'destino_endereco',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{view}',
            'buttons'=>array(
                'view'=>array(
                    'label'=>'Ver Detalhes',
                    'url'=>'Yii::app()->createUrl("corrida/view", array("id"=>$data->id))',
                ),
            ),
        ),
    ),
)); ?>
