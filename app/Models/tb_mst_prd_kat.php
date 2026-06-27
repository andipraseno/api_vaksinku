<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ************************************
// kategori produk
// ************************************
class tb_mst_prd_kat extends Model
{
    // variabel
    protected $connection = 'mysql';

    protected $table = 'tb_mst_prd_kat';

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
