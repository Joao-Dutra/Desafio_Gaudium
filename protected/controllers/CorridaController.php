<?php

class CorridaController extends Controller
{
    public $layout = '//layouts/column2';

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', 'actions' => array('index', 'admin', 'view'), 'users' => array('@')),
            array('deny', 'users' => array('*')),
        );
    }

    public function actionAdmin()
    {
        $model = new Corrida('search');
        $model->unsetAttributes();

        if (isset($_GET['Corrida'])) {
            $model->attributes = $_GET['Corrida'];
        }

        $this->render('admin', array('model' => $model));
    }

    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function loadModel($id)
    {
        $model = Corrida::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'A corrida não foi encontrada.');
        }
        return $model;
    }
}
