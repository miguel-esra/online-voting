<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Models\Voter;
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
}
