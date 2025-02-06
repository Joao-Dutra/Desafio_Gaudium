<div class="wide form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'get',
    )); ?>

    <div class="row">
        <?php echo $form->label($model,'data_inicio'); ?>
        <?php echo $form->textField($model,'data_inicio', array('placeholder' => 'YYYY-MM-DD')); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Buscar'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
