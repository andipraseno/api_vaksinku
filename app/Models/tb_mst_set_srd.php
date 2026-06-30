<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ************************************
// setting - shared
// ************************************
class tb_mst_set_srd extends Model
{
     // variabel
     protected $connection = 'mysql';

     protected $table = 'tb_mst_set_srd';

     // spesification
     protected $primaryKey = 'id';

     public $timestamps = false;

     protected $guarded = [
          'id',
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
