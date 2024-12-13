<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVotersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'user_id' => [
                'type' => 'VARCHAR',
                'constraint' => '8'
            ],
            'check_digit' => [
                'type' => 'INT',
                'constraint' => 1
            ],
            'parent_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => 1
            ],
            'created_at timestamp default current_timestamp',
            'updated_at timestamp default current_timestamp on update current_timestamp'
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('voters');
    }

    public function down()
    {
        $this->forge->dropTable('voters');
    }
}
