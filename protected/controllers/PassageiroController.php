<?php

class PassageiroController extends Controller
{
	public $layout = '//layouts/column2';

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', 'actions' => array('index', 'view'), 'users' => array('*')),
			array('allow', 'actions' => array('create', 'update'), 'users' => array('@')),
			array('allow', 'actions' => array('admin', 'delete'), 'users' => array('admin')),
			array('deny', 'users' => array('*')),
		);
	}

	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	private function savePassageiro($model)
	{
		if (isset($_POST['Passageiro'])) { 
			$model->attributes = $_POST['Passageiro'];
			if ($model->save()) {
				$this->redirect(array('view', 'id' => $model->id));
			}
		}

		$this->render($model->isNewRecord ? 'create' : 'update', array('model' => $model));
	}

	public function actionCreate()
	{
		$model = new Passageiro;
		$this->savePassageiro($model);
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$this->savePassageiro($model);
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Passageiro');
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	public function actionAdmin()
	{
		$model = new Passageiro('search');
		$model->unsetAttributes();
		if (isset($_GET['Passageiro']))
			$model->attributes = $_GET['Passageiro'];

		$this->render('admin', array('model' => $model));
	}

	public function loadModel($id)
	{
		$model = Passageiro::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'O passageiro não foi encontrado.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'passageiro-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
