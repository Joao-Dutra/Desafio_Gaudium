<?php
/* @var $this CorridaController */
/* @var $model Corrida */

$this->breadcrumbs = array(
    'Corridas' => array('admin'),
    'Detalhes da Corrida',
);

?>

<h1>Detalhes da Corrida #<?php echo $model->id; ?></h1>

<table class="detail-view">
    <tr>
        <th>ID</th>
        <td><?php echo CHtml::encode($model->id); ?></td>
    </tr>
    <tr>
        <th>Passageiro</th>
        <td><?php echo CHtml::encode($model->passageiro->nome); ?></td>
    </tr>
    <tr>
        <th>Motorista</th>
        <td><?php echo CHtml::encode($model->motorista->nome); ?></td>
    </tr>
    <tr>
        <th>Origem</th>
        <td><?php echo CHtml::encode($model->origem_endereco); ?></td>
    </tr>
    <tr>
        <th>Destino</th>
        <td><?php echo CHtml::encode($model->destino_endereco); ?></td>
    </tr>
    <tr>
        <th>Data/Hora de Início</th>
        <td><?php echo CHtml::encode($model->data_inicio); ?></td>
    </tr>
    <tr>
        <th>Previsão de Chegada</th>
        <td><?php echo CHtml::encode($model->previsao_chegada_destino); ?></td>
    </tr>
    <tr>
        <th>Data/Hora de Fim</th>
        <td><?php echo CHtml::encode($model->data_fim); ?></td>
    </tr>
    <tr>
        <th>Tarifa</th>
        <td>R$ <?php echo CHtml::encode(number_format($model->tarifa, 2, ',', '.')); ?></td>
    </tr>
    <tr>
        <th>Status</th>
        <td><?php echo CHtml::encode($model->status); ?></td>
    </tr>
</table>
