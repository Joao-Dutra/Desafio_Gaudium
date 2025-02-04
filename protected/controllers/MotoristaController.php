<?php

class MotoristaController extends Controller
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

	private function saveMotorista($model)
	{
		if (isset($_POST['Motorista'])) { 
			$model->attributes = $_POST['Motorista'];
			if ($model->save()) {
				$this->redirect(array('view', 'id' => $model->id));
			}
		}

		$this->render($model->isNewRecord ? 'create' : 'update', array('model' => $model));
	}

	public function actionCreate()
	{
		$model = new Motorista;
		$this->saveMotorista($model);
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$this->saveMotorista($model);
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Motorista');
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	public function actionAdmin()
	{
		$model = new Motorista('search');
		$model->unsetAttributes();
		if (isset($_GET['Motorista']))
			$model->attributes = $_GET['Motorista'];

		$this->render('admin', array('model' => $model));
	}

	public function loadModel($id)
	{
		$model = Motorista::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'O motorista não foi encontrado.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'motorista-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
