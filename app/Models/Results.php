<?php

namespace App\Models;

use CodeIgniter\Model;

class Results extends Model
{
    protected $table            = 'results';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'candidate_number'];

    public function getResultsData() 
    {
        $candidates = [1, 2];
        $builder = $this->db->table('results');
        $builder->select('candidate_number, COUNT(candidate_number) total')->whereIn('candidate_number', $candidates)->groupBy('candidate_number')->orderBy('candidate_number', 'ASC');

        return $builder->get()->getResult();
    }
}
