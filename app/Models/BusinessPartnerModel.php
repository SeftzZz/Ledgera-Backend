<?php

namespace App\Models;

use CodeIgniter\Model;

class BusinessPartnerModel extends Model
{
    protected $table = 'business_partners';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'partner_type',
        'partner_code',
        'partner_name'
    ];
}
