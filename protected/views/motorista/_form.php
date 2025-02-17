<?php
/* @var $this MotoristaController */
/* @var $model Motorista */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'motorista-form',
		'enableAjaxValidation' => false,
	)); ?>

	<p class="note">Os campos com <span class="required">*</span> são obrigatórios.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'nome'); ?>
		<?php echo $form->textField($model, 'nome', array('size' => 60, 'maxlength' => 100, 'placeholder' => 'Nome Completo')); ?>
		<?php echo $form->error($model, 'nome'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'nascimento'); ?>
		<?php echo $form->textField($model, 'nascimento', array('placeholder' => 'YYYY-MM-DD', 'class' => 'date-mask')); ?>
		<?php echo $form->error($model, 'nascimento'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model, 'email'); ?>
		<?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 100, 'placeholder' => 'seuemail@exemplo.com')); ?>
		<?php echo $form->error($model, 'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'telefone'); ?>
		<?php echo $form->textField($model, 'telefone', array('size' => 20, 'maxlength' => 20, 'placeholder' => '+55 (01) 98765-4321', 'class' => 'phone-mask')); ?>
		<?php echo $form->error($model, 'telefone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'placa'); ?>
		<?php echo $form->textField($model, 'placa', array('size' => 20, 'maxlength' => 10, 'placeholder' => 'AAA-9999 ou AAA9A99')); ?>
		<?php echo $form->error($model, 'placa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'obs'); ?>
		<?php echo $form->textField($model, 'obs', array('size' => 60, 'maxlength' => 200, 'placeholder' => 'Observação opcional...')); ?>
		<?php echo $form->error($model, 'obs'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Cadastrar' : 'Salvar'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
	$(document).ready(function() {
		$('.phone-mask').mask('+55 (00) 00000-0000');
		$('.date-mask').mask('0000-00-00');
	});
</script>