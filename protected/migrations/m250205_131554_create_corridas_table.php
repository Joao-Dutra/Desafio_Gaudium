<?php

class m250205_131554_create_corridas_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('corridas', [
			'id' => 'pk',
			'passageiro_id' => 'int NOT NULL',
			'motorista_id' => 'int NOT NULL',
			'origem_endereco' => 'string NOT NULL',
			'destino_endereco' => 'string NOT NULL',
			'data_inicio' => 'datetime NOT NULL',
			'previsao_chegada_destino' => 'datetime',
			'tarifa' => 'decimal(10,2)',
			'status' => "ENUM('Em andamento', 'Não Atendida', 'Finalizada') NOT NULL",
			'data_fim' => 'datetime',
		]);

		// Criando chaves estrangeiras
		$this->addForeignKey('fk_corrida_passageiro', 'corridas', 'passageiro_id', 'passageiro', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_corrida_motorista', 'corridas', 'motorista_id', 'motorista', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		$this->dropTable('corridas');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
