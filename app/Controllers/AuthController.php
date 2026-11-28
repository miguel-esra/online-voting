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
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions'];

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

        $isValid = $this->validate([
            'email' => [
                'rules' => 'trim|required|valid_email|is_not_unique[voters.email]',
                'errors' => [
                    'required' => 'El correo electrónico es obligatorio.',
                    'valid_email' => 'El correo electrónico no es válido.',
                    'is_not_unique' => 'El correo electrónico no se encuentra en el sistema.'
                ]
            ],
            'user_password' => [
                'rules' => 'required|min_length[6]|max_length[45]',
                'errors' => [
                    'required' => 'La contraseña es obligatoria.',
                    'min_length' => 'La contraseña debe contener al menos 6 caracteres de longitud.',
                    'max_length' => 'La contraseña no debe exceder 45 caracteres de longitud.'
                ]
            ]
        ]);

        if (!$isValid) {
            return view('backend/pages/auth/loginUser', [
                'pageTitle' => 'Votación Virtual',
                'validation' => $this->validator
            ]);
        } else {
            $voter = new Voter();
            $userInfo = $voter->where('email', $this->request->getVar('email'))->first();
            $email = $userInfo['email'];
            $check_password = Hash::check($this->request->getVar('user_password'), $userInfo['user_password']);

            if ($this->isBlocked()) {
                return redirect()->route('user.login.form')->with('fail', 'Usuario bloqueado. Intente nuevamente en 5 minutos.')->withInput();
            } else {
                if ($userInfo['user_password'] === "") {
                    return redirect()->route('user.login.form')->with('fail', 'Usuario no encontrado. Debe registrarse primero.')->withInput();
                }

                if (!$check_password) {
                    $this->loginAttempt();
                    $record = $login_attempt->where('email', $email)->first();

                    if ($record['attempts'] == 3) {
                        $voter->where('email', $email)->set(['status' => 0])->update();
                        return redirect()->route('user.login.form')->with('fail', 'Usuario bloqueado. Intente nuevamente en 5 minutos.')->withInput();
                    } elseif ($record['attempts'] == 2) {
                        return redirect()->route('user.login.form')->with('fail', 'La contraseña es incorrecta. Queda ' . (3 - $record['attempts']) . ' intento.')->withInput();
                    } else {
                        return redirect()->route('user.login.form')->with('fail', 'La contraseña es incorrecta. Quedan ' . (3 - $record['attempts']) . ' intentos.')->withInput();
                    }
                } else {
                    CIAuth::setCIAuthVoter($userInfo);   // important line
                    $this->loginAttempt(true);
                    return redirect()->route('user.home');
                }
            }
        }
    }

    private function loginAttempt($passed = false)
    {
        $login_attempt = new LoginAttempt();
        $voter = new Voter();
        $email = trim($this->request->getVar('email'));

        // If the user logged in with success
        if ($passed) :
            // Clear this user loginAttempts
            $login_attempt->where('email', $email)->set(['attempts' => 0, 'timestamp' => Time::now('America/Lima')])->update();
            $voter->where('email', $email)->set(['status' => 1])->update();

        // This is a failed login attempt
        else :
            // Check if we have the user record
            $record = $login_attempt->where('email', $email)->first();

            if (empty($record)) :
                // Create the user record
                $data = [
                    'email' => $email,
                    'attempts' => 1,
                    'timestamp' => Time::now('America/Lima')
                ];
                $login_attempt->save($data);

            // Check if the user needs to be blocked
            else :
                // The user exceeded the login attempts
                if ($record['attempts'] < 3):
                    // Update the user record
                    $login_attempt->where('email', $email)->set(['attempts' => ($record['attempts'] + 1), 'timestamp' => Time::now('America/Lima')])->update();
                endif;
            endif;
        endif;

        return true;
    }

    public function isBlocked()
    {
        $login_attempt = new LoginAttempt();
        $voter = new Voter();
        $email = trim($this->request->getVar('email'));

        // Time that a user gets blocked
        $blockTime = 300;

        // Check if we have the user record
        $record = $login_attempt->where('email', $email)->first();
        if (!empty($record)) :
            // Check this user login attempts
            if ($record['attempts'] >= 3) :
                // Check if the user block time has expired
                $record_timestamp = Time::parse($record['timestamp'], 'America/Lima');
                $current_timestamp = Time::now('America/Lima');
                $diff = $record_timestamp->difference($current_timestamp);

                if ($diff->getSeconds() > $blockTime) :
                    // User is not blocked anymore and clear login attempts
                    $login_attempt->where('email', $email)->set(['attempts' => 0, 'timestamp' => Time::now('America/Lima')])->update();
                    $voter->where('email', $email)->set(['status' => 1])->update();
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
                return redirect()->route('admin.login.form')->with('fail', 'Contraseña incorrecta.')->withInput();
            } else {
                CIAuth::setCIAuth($userInfo);   // important line
                return redirect()->route('admin.home');
            }
        }
    }

    public function registerForm()
    {
        $data = [
            'pageTitle' => 'Votación Virtual',
            'validation' => null
        ];

        return view('backend/pages/auth/register', $data);
    }

    public function sendRegisterLink()
    {
        $isValid = $this->validate([
            'email' => [
                'rules' => 'trim|required|valid_email|is_not_unique[voters.email]',
                'errors' => [
                    'required' => 'El correo electrónico es obligatorio.',
                    'valid_email' => 'El correo electrónico no es válido.',
                    'is_not_unique' => 'El correo electrónico no tiene permitido participar en estas elecciones.'
                ]
            ],

        ]);

        if (!$isValid) {
            return view('backend/pages/auth/register', [
                'pageTitle' => 'Votación Virtual',
                'validation' => $this->validator,
            ]);
        } else {
            // Get voter details
            $voter = new Voter();
            $user_info = $voter->asObject()->where('email', $this->request->getVar('email'))->first();

            if ($user_info->user_password !== "") {
                return redirect()->route('user.register.form')->with('fail', 'Usuario ya registrado. Si no puede iniciar sesión restablezca su contraseña.')->withInput();
            }

            // Generate token
            $token = bin2hex(openssl_random_pseudo_bytes(65));

            // Get reset password token
            $password_reset_token = new PasswordResetToken();
            $isOldTokenExists = $password_reset_token->asObject()->where('email', $user_info->email)->first();

            if ($isOldTokenExists) {
                // Update existing token
                $password_reset_token->where('email', $user_info->email)->set(['token' => $token, 'created_at' => Carbon::now()])->update();
            } else {
                $password_reset_token->insert(['email' => $user_info->email, 'token' => $token, 'created_at' => Carbon::now()]);
            }
        }

        // Create action link
        $actionLink = base_url(route_to('user.create-password', $token));

        $mail_data = [
            'actionLink' => $actionLink,
            'user' => $user_info,
        ];

        $mail_body = view('email-templates/create-password-template', ['mail_data' => $mail_data]);

        $mailConfig = array(
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $user_info->email,
            'mail_recipient_name' => $user_info->name,
            'mail_subject' => 'Activación de Usuario - Sistema de Votación Virtual',
            'mail_body' => $mail_body
        );

        // Send email
        if (sendEmail($mailConfig)) {
            return redirect()->route('user.register.form')->with('success', 'Se ha enviado el enlace de activación a su correo electrónico.');
        } else {
            return redirect()->route('user.register.form')->with('fail', 'Algo salió mal. Vuelve a intentarlo más tarde.');
        }
    }

    public function createPassword(string $token)
    {
        $passwordResetToken = new PasswordResetToken();
        $check_token = $passwordResetToken->asObject()->where('token', $token)->first();

        if (!$check_token) {
            return redirect()->route('user.register.form')->with('fail', 'Token inválido. Solicita otro enlace para activar tu usuario.');
        } else {
            // Check if token has not expired (Not older than 15 minutes)
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $check_token->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins > 15) {
                // If token expired (older than 15 minutes)
                return redirect()->route('user.register.form')->with('fail', 'Token inválido. Solicita otro enlace para activar tu usuario.');
            } else {
                return view('backend/pages/auth/newPassword', [
                    'pageTitle' => 'Votación Virtual',
                    'validation' => null,
                    'token' => $token
                ]);
            }
        }
    }

    public function createPasswordHandler(string $token)
    {
        $isValid = $this->validate([
            'new_password' => [
                'rules' => 'required|min_length[6]|max_length[45]|is_password_strong[new_password]',
                'errors' => [
                    'required' => 'La nueva contraseña es obligatoria.',
                    'min_length' => 'La contraseña debe contener al menos 6 caracteres de longitud.',
                    'max_length' => 'La contraseña no debe exceder 45 caracteres de longitud.',
                    'is_password_strong' => 'La nueva contraseña debe contener al menos 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial.',
                ]
            ],
            'confirm_new_password' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Confirma la nueva contraseña.',
                    'matches' => 'Las contraseñas no coinciden.',
                ]
            ]
        ]);

        if (!$isValid) {
            return view('backend/pages/auth/newPassword', [
                'pageTitle' => 'Votación Virtual',
                'validation' => null,
                'token' => $token,
            ]);
        } else {
            // Get token details
            $passwordResetToken = new PasswordResetToken();
            $get_token = $passwordResetToken->asObject()->where('token', $token)->first();

            // Get voter details
            $voter = new Voter();
            $user_info = $voter->asObject()->where('email', $get_token->email)->first();

            if (!$get_token) {
                return redirect()->back()->with('fail', '¡Token inválido. Vuelve a iniciar el registro.!')->withInput();
            } else {
                // Update admin password in DB
                $voter->where('email', $user_info->email)->set(['user_password' => Hash::make($this->request->getVar('new_password'))])->update();

                //Send notification to user (admin) email address
                $mail_data = array(
                    'user' => $user_info,
                    'new_password' => $this->request->getVar('new_password')
                );

                $view = \Config\Services::renderer();
                $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/password-created-email-template');

                $mailConfig = array(
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_recipient_email' => $user_info->email,
                    'mail_recipient_name' => $user_info->name,
                    'mail_subject' => 'Usuario Activado - Sistema de Votación Virtual',
                    'mail_body' => $mail_body
                );

                // Send email
                if (sendEmail($mailConfig)) {
                    // Delete token
                    $passwordResetToken->where('email', $user_info->email)->delete();

                    // Redirect and display message on login page
                    return redirect()->route('user.login.form')->with('success', '¡Usuario activado! Utiliza tu contraseña para iniciar sesión.');
                } else {
                    return redirect()->back()->with('fail', 'Algo salió mal. Vuelve a intentarlo más tarde.')->withInput();
                }
            }
        }
    }

    public function forgotForm()
    {
        $data = [
            'pageTitle' => 'Votación Virtual',
            'validation' => null
        ];

        return view('backend/pages/auth/forgot', $data);
    }

    public function sendPasswordResetLink()
    {
        $isValid = $this->validate([
            'email' => [
                'rules' => 'trim|required|valid_email|is_not_unique[voters.email]',
                'errors' => [
                    'required' => 'El correo electrónico es obligatorio.',
                    'valid_email' => 'El correo electrónico no es válido.',
                    'is_not_unique' => 'El correo electrónico no tiene permitido participar en estas elecciones.'
                ]
            ],

        ]);

        if (!$isValid) {
            return view('backend/pages/auth/forgot', [
                'pageTitle' => 'Votación Virtual',
                'validation' => $this->validator,
            ]);
        } else {
            // Get voter details
            $voter = new Voter();
            $user_info = $voter->asObject()->where('email', $this->request->getVar('email'))->first();

            if ($user_info->user_password === "") {
                return redirect()->route('user.forgot.form')->with('fail', 'Usuario no encontrado. Debe registrarse primero.')->withInput();
            }

            // Generate token
            $token = bin2hex(openssl_random_pseudo_bytes(65));

            // Get reset password token
            $password_reset_token = new PasswordResetToken();
            $isOldTokenExists = $password_reset_token->asObject()->where('email', $user_info->email)->first();

            if ($isOldTokenExists) {
                // Update existing token
                $password_reset_token->where('email', $user_info->email)->set(['token' => $token, 'created_at' => Carbon::now()])->update();
            } else {
                $password_reset_token->insert(['email' => $user_info->email, 'token' => $token, 'created_at' => Carbon::now()]);
            }
        }

        // Create action link
        $actionLink = base_url(route_to('user.reset-password', $token));

        $mail_data = [
            'actionLink' => $actionLink,
            'user' => $user_info,
        ];

        $mail_body = view('email-templates/forgot-email-template', ['mail_data' => $mail_data]);

        $mailConfig = array(
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $user_info->email,
            'mail_recipient_name' => $user_info->name,
            'mail_subject' => 'Restablecer Contraseña - Sistema de Votación Virtual',
            'mail_body' => $mail_body
        );

        // Send email
        if (sendEmail($mailConfig)) {
            return redirect()->route('user.forgot.form')->with('success', 'Se ha enviado el enlace de recuperación a su correo electrónico.');
        } else {
            return redirect()->route('user.forgot.form')->with('fail', 'Algo salió mal. Vuelve a intentarlo más tarde.');
        }
    }

    public function resetPassword(string $token)
    {
        $passwordResetToken = new PasswordResetToken();
        $check_token = $passwordResetToken->asObject()->where('token', $token)->first();

        if (!$check_token) {
            return redirect()->route('user.forgot.form')->with('fail', 'Token inválido. Solicita otro enlace para restablecer la contraseña.');
        } else {
            // Check if token has not expired (Not older than 15 minutes)
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $check_token->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins > 15) {
                // If token expired (older than 15 minutes)
                return redirect()->route('user.forgot.form')->with('fail', 'Token inválido. Solicita otro enlace para restablecer la contraseña.');
            } else {
                return view('backend/pages/auth/reset', [
                    'pageTitle' => 'Votación Virtual',
                    'validation' => null,
                    'token' => $token
                ]);
            }
        }
    }

    public function resetPasswordHandler(string $token)
    {
        $isValid = $this->validate([
            'new_password' => [
                'rules' => 'required|min_length[6]|max_length[45]|is_password_strong[new_password]',
                'errors' => [
                    'required' => 'La nueva contraseña es obligatoria.',
                    'min_length' => 'La contraseña debe contener al menos 6 caracteres de longitud.',
                    'max_length' => 'La contraseña no debe exceder 45 caracteres de longitud.',
                    'is_password_strong' => 'La nueva contraseña debe contener al menos 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial.',
                ]
            ],
            'confirm_new_password' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Confirma la nueva contraseña.',
                    'matches' => 'Las contraseñas no coinciden.',
                ]
            ]
        ]);

        if (!$isValid) {
            return view('backend/pages/auth/reset', [
                'pageTitle' => 'Votación Virtual',
                'validation' => null,
                'token' => $token,
            ]);
        } else {
            // Get token details
            $passwordResetToken = new PasswordResetToken();
            $get_token = $passwordResetToken->asObject()->where('token', $token)->first();

            // Get voter details
            $voter = new Voter();
            $user_info = $voter->asObject()->where('email', $get_token->email)->first();

            if (!$get_token) {
                return redirect()->back()->with('fail', '¡Token inválido. Vuelve a iniciar el restablecimiento.!')->withInput();
            } else {
                // Update admin password in DB
                $voter->where('email', $user_info->email)->set(['user_password' => Hash::make($this->request->getVar('new_password'))])->update();

                //Send notification to user (admin) email address
                $mail_data = array(
                    'user' => $user_info,
                    'new_password' => $this->request->getVar('new_password')
                );

                $view = \Config\Services::renderer();
                $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/password-changed-email-template');

                $mailConfig = array(
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_recipient_email' => $user_info->email,
                    'mail_recipient_name' => $user_info->name,
                    'mail_subject' => 'Contraseña Actualizada - Sistema de Votación Virtual',
                    'mail_body' => $mail_body
                );

                // Send email
                if (sendEmail($mailConfig)) {
                    // Delete token
                    $passwordResetToken->where('email', $user_info->email)->delete();

                    // Redirect and display message on login page
                    return redirect()->route('user.login.form')->with('success', '¡Tu contraseña ha sido cambiada! Utiliza la nueva contraseña para iniciar sesión.');
                } else {
                    return redirect()->back()->with('fail', 'Algo salió mal. Vuelve a intentarlo más tarde.')->withInput();
                }
            }
        }
    }

    public function maintenance()
    {
        $data = [
            'pageTitle' => 'Votación Virtual',
            'validation' => null
        ];

        return view('maintenance', $data);
    }
}
