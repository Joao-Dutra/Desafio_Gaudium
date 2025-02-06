<?php
/* @var $this PassageiroController */
/* @var $model Passageiro */

$this->breadcrumbs=array(
    'Passageiros'=>array('admin'),
    'Alterar Status',
);
?>

<h1>Alterar Status do Passageiro</h1>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'passageiro-status-form',
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
