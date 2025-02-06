<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>

<h1>Bem-vindo ao <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Utilize os links abaixo para acessar as funcionalidades do sistema:</p>

<ul>
    <li><?php echo CHtml::link('Listar Passageiros', array('passageiro/index')); ?></li>
    <li><?php echo CHtml::link('Listar Motoristas', array('motorista/index')); ?></li>
    <li><?php echo CHtml::link('Listar Corridas', array('corrida/admin')); ?></li>
</ul>

<p>Para mais informações sobre como configurar e expandir esta aplicação, visite a 
<a href="https://www.yiiframework.com/doc/">documentação do Yii</a>.</p>
