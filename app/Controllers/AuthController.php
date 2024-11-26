<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use App\Models\Voter;
use App\Models\PasswordResetToken;
use Carbon\Carbon;
use CodeIgniter\Config\Services as ConfigServices;
use CodeIgniter\Database\Config;
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
            'check_digit' => [
                'rules' => 'required|max_length[1]',
                'errors' => [
                    'required' => 'El dígito verificador es obligatorio.',
                    'max_length' => 'El dígito verificador no debe exceder 1 carácter de longitud.'
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
            $userDigit = $userInfo['check_digit'];
            $check_digit = $this->request->getVar('check_digit');
            
            if ( $check_digit != $userDigit ) {
                return redirect()->route('user.login.form')->with('fail', 'El dígito verificador es incorrecto.')->withInput();
            } else {
                CIAuth::setCIAuthVoter($userInfo);   // important line
                return redirect()->route('user.home');
            }
        }
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
