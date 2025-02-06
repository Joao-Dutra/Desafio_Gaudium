<?php

class Corrida extends CActiveRecord
{
    public function tableName()
    {
        return 'corridas';
    }

    public function rules()
    {
        return [
            ['passageiro_id, motorista_id, origem_endereco, destino_endereco, status', 'required'],
            ['passageiro_id, motorista_id', 'numerical', 'integerOnly' => true],
            ['status', 'in', 'range' => ['Em andamento', 'Não Atendida', 'Finalizada']],
            ['tarifa, previsao_chegada_destino, data_inicio, data_fim', 'safe'],
        ];
    }

    public function relations()
    {
        return [

            'passageiro' => [self::BELONGS_TO, 'Passageiro', 'passageiro_id'],
            'motorista' => [self::BELONGS_TO, 'Motorista', 'motorista_id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'passageiro_id' => 'Passageiro',
            'motorista_id' => 'Motorista',
            'origem_endereco' => 'Endereço de Origem',
            'destino_endereco' => 'Endereço de Destino',
            'data_inicio' => 'Data/Hora de Início',
            'previsao_chegada_destino' => 'Previsão de Chegada',
            'tarifa' => 'Tarifa',
            'status' => 'Status',
            'data_fim' => 'Data/Hora de Finalização',
        ];
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('passageiro_id', $this->passageiro_id);
        $criteria->compare('motorista_id', $this->motorista_id);
        $criteria->compare('origem_endereco', $this->origem_endereco, true);
        $criteria->compare('destino_endereco', $this->destino_endereco, true);
        $criteria->compare('previsao_chegada_destino', $this->previsao_chegada_destino, true);
        $criteria->compare('tarifa', $this->tarifa, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('data_fim', $this->data_fim, true);

        if (!empty($this->data_inicio)) {
            $criteria->addCondition("data_inicio >= :data_inicio");
            $criteria->params[':data_inicio'] = $this->data_inicio;
        }

        if (!empty($this->data_fim)) {
            $criteria->addCondition("data_inicio <= :data_fim");
            $criteria->params[':data_fim'] = $this->data_fim;
        }

        $criteria->order = "FIELD(status, 'Em andamento', 'Não Atendida', 'Finalizada'), data_inicio DESC";

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
