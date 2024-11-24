<?php

namespace App\Models;

use CodeIgniter\Model;

class Results extends Model
{
    protected $table            = 'results';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'candidate_number'];

}
