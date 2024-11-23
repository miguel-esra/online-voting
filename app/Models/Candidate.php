<?php

namespace App\Models;

use CodeIgniter\Model;

class Candidate extends Model
{
    protected $table            = 'candidates';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'candidate_number', 'candidate_id', 'picture', 'bio'];

}
