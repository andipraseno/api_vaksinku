<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ************************************
// otorisasi
// ************************************
class tb_act_acc_oto extends Model
{
    // variabel
    protected $connection = 'mysql';

    protected $table = 'tb_act_acc_oto';

    // spesification
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    // properties
    public function get_connection()
    {
        return $this->connection;
    }

    public function get_table_raw()
    {
        return $this->table;
    }

    public function get_table_conn()
    {
        return $this->connection . '.' . $this->table;
    }

    public function get_table()
    {
        $dbDatabase = config('database.connections.' . $this->connection . '.database');

        return $dbDatabase . '.' . $this->table;
    }
}
