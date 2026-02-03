<?php

namespace App\Models;

use CodeIgniter\Model;

class HotelModel extends Model
{
    protected $table      = 'hotels';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'hotel_name','location','latitude','longitude','website','founded','size','logo','created_at','created_by',
        'updated_at','updated_by','deleted_at','deleted_by'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = false;
}
