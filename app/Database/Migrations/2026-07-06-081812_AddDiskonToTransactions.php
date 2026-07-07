<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiskonToTransactions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transactions', [
            'diskon' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'total_harga' // Menempatkan kolom setelah total_harga
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'diskon');
    }
}