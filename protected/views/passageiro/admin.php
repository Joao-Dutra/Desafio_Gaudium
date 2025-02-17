<?php
/* @var $this PassageiroController */
/* @var $model Passageiro */

$this->breadcrumbs = array(
	'Passageiros' => array('index'),
	'Manage',
);

$this->menu = array(
	array('label' => 'List Passageiro', 'url' => array('index')),
	array('label' => 'Create Passageiro', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#passageiro-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Passageiros</h1>

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
	'id' => 'passageiro-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		'id',
		'nome',
		'nascimento',
		'email',
		'telefone',
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
					'url' => 'Yii::app()->createUrl("passageiro/alterarStatus", array("id"=>$data->id))',
				),
			),
		),
	),
)); ?>