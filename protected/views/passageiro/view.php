<?php
/* @var $this PassageiroController */
/* @var $model Passageiro */

$this->breadcrumbs = array(
	'Passageiros' => array('index'),
	$model->id,
);

$this->menu = array(
	array('label' => 'List Passageiro', 'url' => array('index')),
	array('label' => 'Create Passageiro', 'url' => array('create')),
	array('label' => 'Update Passageiro', 'url' => array('update', 'id' => $model->id)),
	array('label' => 'Delete Passageiro', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
	array('label' => 'Manage Passageiro', 'url' => array('admin')),
);
?>

<h1>View Passageiro #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
		'id',
		'nome',
		'nascimento',
		'email',
		'telefone',
		'status',
		'data_hora_status',
		'obs',
	),
)); ?>

<h2><br>Últimas 5 Corridas</h2>

<?php if (!empty($corridas)): ?>
	<table border="1" width="100%" style="border-collapse: collapse;">
		<tr>
			<th>Data/Hora de Início</th>
			<th>Destino</th>
			<th>Status</th>
		</tr>
		<?php foreach ($corridas as $corrida): ?>
			<tr>
				<td><?php echo CHtml::encode($corrida->data_inicio); ?></td>
				<td><?php echo CHtml::encode($corrida->destino_endereco); ?></td>
				<td><?php echo CHtml::encode($corrida->status); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>O passageiro ainda não realizou corridas.</p>
<?php endif; ?>