<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Models\Voter;
use App\Models\Results;
use Config\Services;
USE App\Libraries\Hash;
use App\Models\Setting;
use App\Models\SocialMedia;
use SSP;
use SawaStacks\CodeIgniter\Slugify;

class VoterController extends BaseController
{
    // protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions'];
    protected $helpers = ['url', 'form', 'CIFunctions'];
    protected $db;

    public function __construct()
    {
        require_once APPPATH.'ThirdParty/ssp.php';
        $this->db = db_connect();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Votación Virtual',
        ];
        return view('backend/pages/home', $data);
    }

    public function logoutHandler()
    {
        CIAuth::forgetVoter();
        return redirect()->route('user.login.form')->with('fail', 'Sesión finalizada.');
    }

    public function settings()
    {
        $data = [
            'pageTitle' => 'Votación Virtual'
        ];
        return view('backend/pages/settings', $data);
    }

    public function profile()
    {
        $data = array(
            'pageTitle' => 'Votación Virtual'
        );
        return view('backend/pages/profile', $data);
    }

    public function myVote()
    {
        $userIdVoter = CIAuth::userIdVoter();
        $result = new Results();
        $voterChoice = $result->asObject()->where('user_id', $userIdVoter)->first();

        $voterChoice ? $voteSuccess = true : $voteSuccess = false;
        
        $data = [
            'pageTitle' => 'Votación Virtual',
            'voteSuccess' => $voteSuccess
        ];
       
        return view('backend/pages/my-vote', $data);
    }

    public function addVote()
    {
        $request = \Config\Services::request();

        $result = new Results();
        $voter = CIAuth::voter();
        $data = [
            'user_id' => $voter->user_id,
            'candidate_number' => $request->getVar('candidate_number')
        ];
        $save = $result->save($data);

        if ( $save ) {
            return $this->response->setJSON(['status' => 1, 'msg' => 'Voto registrado correctamente.']);
        } else {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Algo salió mal. Intente nuevamente.']);
        }
    }
}
