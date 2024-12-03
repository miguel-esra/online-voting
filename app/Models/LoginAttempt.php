<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginAttempt extends Model
{
    protected $table            = 'login_attempts';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'attempts', 'timestamp'];

}
