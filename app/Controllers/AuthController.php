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

    // // public function loginForm()
    // // {
    // //     $data = [
    // //         'pageTitle' => 'Login',
    // //         'validation' => null
    // //     ];

    // //     return view('backend/pages/auth/login', $data);
    // // }

    // public function loginHandler()
    // {
    //     $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    //     if ($fieldType == 'email') {
    //         $isValid = $this->validate([
    //             'login_id' => [
    //                 'rules' => 'required|valid_email|is_not_unique[users.email]',
    //                 'errors' => [
    //                     'required' => 'Email is required.',
    //                     'valid_email' => 'Please, check the email field. It does not appear to be valid.',
    //                     'is_not_unique' => 'Email does not exist in our system.'
    //                 ]
    //             ],
    //             'password' => [
    //                 'rules' => 'required|min_length[5]|max_length[45]',
    //                 'errors' => [
    //                     'required' => 'Password is required.',
    //                     'min_length' => 'Password must have at least 5 characters in length.',
    //                     'max_length' => 'Password must no have more than 45 characters in length.'
    //                 ]
    //             ]
    //         ]);
    //     } else {
    //         $isValid = $this->validate([
    //             'login_id' => [
    //                 'rules' => 'required|is_not_unique[users.username]',
    //                 'errors' => [
    //                     'required' => 'Username is required.',
    //                     'is_not_unique' => 'Username does not exist in our system.'
    //                 ]
    //             ],
    //             'password' => [
    //                 'rules' => 'required|min_length[5]|max_length[45]',
    //                 'errors' => [
    //                     'required' => 'Password is required.',
    //                     'min_length' => 'Password must have at least 5 characters in length.',
    //                     'max_length' => 'Password must no have more than 45 characters in length.'
    //                 ]
    //             ]
    //         ]);
    //     }

    //     if (!$isValid) {
    //         return view('backend/pages/auth/login', [
    //             'pageTitle' => 'Login',
    //             'validation' => $this->validator
    //         ]);
    //     } else {
    //         $user = new User();
    //         $userInfo = $user->where($fieldType, $this->request->getVar('login_id'))->first();
    //         $check_password = Hash::check($this->request->getVar('password'), $userInfo['password']);

    //         if (!$check_password) {
    //             return redirect()->route('admin.login.form')->with('fail','Wrong password.')->withInput();
    //         } else {
    //             CIAuth::setCIAuth($userInfo);   // important line
    //             return redirect()->route('admin.home');
    //         }
    //     }
    // }

}
