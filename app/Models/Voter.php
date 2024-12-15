<?php

namespace App\Models;

use CodeIgniter\Model;

class Voter extends Model
{
    protected $table            = 'voters';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'email', 'user_id', 'check_digit', 'parent_name', 'status'];

}
