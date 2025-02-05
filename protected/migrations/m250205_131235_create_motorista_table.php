<?php

class m250205_131235_create_motorista_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('motorista', [
            'id' => 'pk',
            'nome' => 'VARCHAR(255) NOT NULL',
            'nascimento' => 'DATE NOT NULL',
            'email' => 'VARCHAR(255) NOT NULL UNIQUE',
            'telefone' => 'VARCHAR(20) NOT NULL',
            'placa' => 'VARCHAR(10) NOT NULL',
            'status' => "ENUM('A', 'I') NOT NULL DEFAULT 'A'",
            'data_hora_status' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'obs' => 'VARCHAR(200) NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('motorista');
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