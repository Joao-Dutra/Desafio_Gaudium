<?php
/* @var $this MotoristaController */
/* @var $model Motorista */

$this->breadcrumbs = array(
	'Motoristas' => array('index'),
	'Manage',
);

$this->menu = array(
	array('label' => 'List Motorista', 'url' => array('index')),
	array('label' => 'Create Motorista', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#motorista-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Motoristas</h1>

<p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
	or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search', array(
		'model' => $model,
	)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'motorista-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		'id',
		'nome',
		'nascimento',
		'email',
		'telefone',
		'placa',
		array(
			'name' => 'status',
			'value' => '$data->status === "A" ? "Ativo" : "Inativo"',
			'filter' => array('A' => 'Ativo', 'I' => 'Inativo'),
		),
		array(
			'class' => 'CButtonColumn',
			'template' => '{view} {update} {delete} {status}',
			'buttons' => array(
				'status' => array(
					'label' => 'Alterar Status',
					'url' => 'Yii::app()->createUrl("motorista/alterarStatus", array("id"=>$data->id))',
				),
			),
		),
	),
)); ?>