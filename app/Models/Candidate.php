<?php

namespace App\Models;

use CodeIgniter\Model;

class Candidate extends Model
{
    protected $table            = 'candidates';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'candidate_number', 'candidate_id', 'picture', 'bio'];

    public function getCandidatesData() 
    {
        $candidates = [1, 2];
        $builder = $this->db->table('candidates');
        $builder->select('name, candidate_number, 0 total')->whereIn('candidate_number', $candidates)->orderBy('candidate_number', 'ASC');

        return $builder->get()->getResult();
    }
}
