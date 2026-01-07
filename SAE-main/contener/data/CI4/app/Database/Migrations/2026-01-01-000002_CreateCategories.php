<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'   => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>80],
            'slug' => ['type'=>'VARCHAR','constraint'=>100],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addUniqueKey('name');

        $this->forge->createTable('categories', true);
    }

    public function down()
    {
        $this->forge->dropTable('categories', true);
    }
}
