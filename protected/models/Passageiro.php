<?php

class Passageiro extends CActiveRecord
{
	public function tableName()
	{
		return 'passageiro';
	}

	public function rules()
	{
		return array(
			array('nome, nascimento, email, telefone, status', 'required'),
			array('obs', 'length', 'max' => 200),

			array('email', 'email'),
			array('telefone', 'match', 'pattern' => '/^\+\d{2} \(\d{2}\) \d{5}-\d{4}$/', 'message' => 'Telefone deve estar no formato +99 (99) 99999-9999.'),
			array('status', 'in', 'range' => array('A', 'I'), 'message' => 'Status deve ser A (Ativo) ou I (Inativo).'),
			array('nascimento', 'date', 'format' => 'yyyy-MM-dd', 'message' => 'A data de nascimento deve estar no formato YYYY-MM-DD.'),

			array('nome', 'match', 'pattern' => '/^([\p{L}]{3,}) ([\p{L}]{3,})$/u', 'message' => 'O nome deve ter pelo menos duas palavras com no mínimo 3 caracteres cada.'),

			// Mantemos `data_hora_status` como seguro para que possa ser usado na busca, mas sem alterá-lo manualmente
			array('data_hora_status', 'safe'),
		);
	}

	public function relations()
	{
		return array();
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nome' => 'Nome',
			'nascimento' => 'Data de Nascimento',
			'email' => 'E-mail',
			'telefone' => 'Telefone',
			'status' => 'Status',
			'data_hora_status' => 'Data/Hora do Status',
			'obs' => 'Observação',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('nome', $this->nome, true);
		$criteria->compare('nascimento', $this->nascimento, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('telefone', $this->telefone, true);
		$criteria->compare('status', $this->status, true);
		$criteria->compare('data_hora_status', $this->data_hora_status, true);
		$criteria->compare('obs', $this->obs, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
