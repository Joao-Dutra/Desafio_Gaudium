<?php
/* @var $this MotoristaController */
/* @var $model Motorista */

$this->breadcrumbs=array(
    'Motoristas'=>array('admin'),
    'Alterar Status',
);
?>

<h1>Alterar Status do Motorista</h1>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'motorista-status-form',
        'enableAjaxValidation'=>false,
    )); ?>

    <p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropDownList($model,'status',array('A'=>'Ativo','I'=>'Inativo')); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Salvar'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
