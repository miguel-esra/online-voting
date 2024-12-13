<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use App\Models\Voter;
use App\Models\LoginAttempt;
use App\Models\PasswordResetToken;
use Carbon\Carbon;
use CodeIgniter\Config\Services as ConfigServices;
use CodeIgniter\Database\Config;
use CodeIgniter\I18n\Time;
use CodeIgniter\I18n\TimeDifference;
use Config\Services;

class AuthController extends BaseController
{
    // protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions'];
    protected $helpers = ['url', 'form', 'CIFunctions'];

    public function loginUser() 
    {
        $data = [
            'pageTitle' => 'Votación Virtual',
            'validation' => null
        ];

        return view('backend/pages/auth/loginUser', $data);
    }

    public function loginHandlerUser()
    {
        $login_attempt = new LoginAttempt();
        $voter = new Voter();

        $isValid = $this->validate([
            'login_id' => [
                'rules' => 'required|min_length[8]|max_length[8]|is_not_unique[voters.user_id]',
                'errors' => [
                    'required' => 'El número de DNI es obligatorio.',
                    'min_length' => 'El número de DNI debe contener al menos 8 caracteres de longitud.',
                    'max_length' => 'El número de DNI no debe exceder 8 caracteres de longitud.',
                    'is_not_unique' => 'El número de DNI no se encuentra en el sistema.'
                ]
            ],
            'parent_name' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'El nombre del padre o madre es obligatorio.',
                    'min_length' => 'El nombre del padre o madre debe contener al menos 3 caracteres de longitud.'
                ]
            ]
        ]);
        
        if (!$isValid) {
            return view('backend/pages/auth/loginUser', [
                'pageTitle' => 'Votación Virtual',
                'validation' => $this->validator
            ]);
        } else {
            $user = new Voter();
            $userInfo = $user->where('user_id', $this->request->getVar('login_id'))->first();
            $userParent = $userInfo['parent_name'];
            $parentName = trim($this->request->getVar('parent_name'));
            
            if ( $this->isBlocked()) {
                return redirect()->route('user.login.form')->with('fail', 'Usuario bloqueado. Intente nuevamente en 30 minutos.')->withInput();
            } else {
                if ( $parentName != $userParent ) {
                    $this->loginAttempt();
                    $record = $login_attempt->where('user_id', $this->request->getVar('login_id'))->first();
                    if ($record['attempts'] == 3) {
                        $voter->where('user_id', $this->request->getVar('login_id'))->set(['status' => 0])->update();
                        return redirect()->route('user.login.form')->with('fail', 'Usuario bloqueado. Intente nuevamente en 30 minutos.')->withInput();
                    } elseif ($record['attempts'] == 2) {
                        return redirect()->route('user.login.form')->with('fail', 'El nombre del padre o madre es incorrecto. Queda ' . (3 - $record['attempts']) . ' intento.')->withInput();
                    } else {
                        return redirect()->route('user.login.form')->with('fail', 'El nombre del padre o madre es incorrecto. Quedan ' . (3 - $record['attempts']) . ' intentos.')->withInput();
                    }
                } else {
                    CIAuth::setCIAuthVoter($userInfo);   // important line
                    $this->loginAttempt( true );
                    return redirect()->route('user.home');
                }
            }
        } 
    }

    private function loginAttempt( $passed = false )
    {
        $login_attempt = new LoginAttempt();
        $voter = new Voter();
        $user_id = $this->request->getVar('login_id');

        // If the user logged in with success
        if ( $passed ) :
            // Clear this user loginAttempts
            $login_attempt->where('user_id', $user_id)->set(['attempts' => 0, 'timestamp' => Time::now('America/Lima')])->update();
            $voter->where('user_id', $user_id)->set(['status' => 1])->update();
 
        // This is a failed login attempt
        else :
            // Check if we have the user record
            $record = $login_attempt->where('user_id', $user_id)->first();

            if ( empty( $record ) ) :
                // Create the user record
                $data = [
                    'user_id' => $user_id,
                    'attempts' => 1,
                    'timestamp' => Time::now('America/Lima')
                ];
                $login_attempt->save($data);

            // Check if the user needs to be blocked
            else :
                // The user exceeded the login attempts
                if ( $record['attempts'] < 3 ):
                    // Update the user record
                    $login_attempt->where('user_id', $user_id)->set(['attempts' => ($record['attempts'] + 1), 'timestamp' => Time::now('America/Lima')])->update();
                endif;
            endif;
        endif;
 
        return true;
    }

    public function isBlocked()
    {
        $login_attempt = new LoginAttempt();
        $voter = new Voter();
        $user_id = $this->request->getVar('login_id');

        // Time that a user gets blocked
        $blockTime = 1800;
 
        // Check if we have the user record
        $record = $login_attempt->where('user_id', $user_id)->first();
        if ( !empty( $record ) ) :
            // Check this user login attempts
            if ( $record['attempts'] >= 3 ) :
                // Check if the user block time has expired
                $record_timestamp = Time::parse($record['timestamp'], 'America/Lima');
                $current_timestamp = Time::now('America/Lima');
                $diff = $record_timestamp->difference($current_timestamp);
                
                if ( $diff->getSeconds() > $blockTime ) :
                    // User is not blocked anymore and clear login attempts
                    $login_attempt->where('user_id', $user_id)->set(['attempts' => 0, 'timestamp' => Time::now('America/Lima')])->update();
                    $voter->where('user_id', $user_id)->set(['status' => 1])->update();
                    return false;
                else :
                    // The user is blocked
                    return true;
                endif;
            endif;
        endif;
 
        // The user is not blocked
        return false;
    }

    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Votación Virtual',
            'validation' => null
        ];

        return view('backend/pages/auth/login', $data);
    }

    public function loginHandler()
    {
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($fieldType == 'email') {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'trim|required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'El correo electrónico es obligatorio.',
                        'valid_email' => 'El correo electrónico no es válido.',
                        'is_not_unique' => 'El correo electrónico no se encuentra en el sistema.'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required' => 'La contraseña es obligatoria.',
                        'min_length' => 'La contraseña debe contener al menos 5 caracteres de longitud.',
                        'max_length' => 'La contraseña no debe exceder 45 caracteres de longitud.'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'trim|required|is_not_unique[users.username]',
                    'errors' => [
                        'required' => 'El usuario es obligatorio.',
                        'is_not_unique' => 'El usuario no se encuentra en el sistema.'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required' => 'La contraseña es obligatoria.',
                        'min_length' => 'La contraseña debe contener al menos 5 caracteres de longitud.',
                        'max_length' => 'La contraseña no debe exceder 45 caracteres de longitud.'
                    ]
                ]
            ]);
        }

        if (!$isValid) {
            return view('backend/pages/auth/login', [
                'pageTitle' => 'Votación Virtual',
                'validation' => $this->validator
            ]);
        } else {
            $user = new User();
            $userInfo = $user->where($fieldType, $this->request->getVar('login_id'))->first();
            $check_password = Hash::check($this->request->getVar('password'), $userInfo['password']);

            if (!$check_password) {
                return redirect()->route('admin.login.form')->with('fail','Contraseña incorrecta.')->withInput();
            } else {
                CIAuth::setCIAuth($userInfo);   // important line
                return redirect()->route('admin.home');
            }
        }
    }

}
